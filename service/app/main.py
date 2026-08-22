"""Servicio FastAPI — ingesta, extraccion, segmentacion y clasificacion (Art IV, pasos 1-5).

El pipeline se detiene despues de la clasificacion (paso 5): sin score de confianza,
sin cola de revision, sin publicacion (spec-mvp-demo.md).

POST /documentos responde 201 apenas persiste el documento y corre el pipeline en una
tarea en segundo plano (BackgroundTasks), NO dentro de la request: la clasificacion hace
una llamada al modelo por clausula, y en un documento real eso excede el timeout del
ingress de Container Apps — la request moria y el navegador lo reportaba como un error de
CORS (la respuesta de error del proxy no lleva cabeceras CORS). El avance se sigue por la
columna `estado` (pendiente -> extraido -> segmentado -> clasificado | error), que ya
existia para eso. Sigue sin haber cola de tareas (Azure Service Bus, Art V) — sigue siendo
la simplificacion de demo señalada explicitamente.
"""
import uuid
from pathlib import Path
from typing import Optional

import httpx
from fastapi import BackgroundTasks, FastAPI, File, Form, HTTPException, Request, UploadFile
from fastapi.middleware.cors import CORSMiddleware

from app import storage
from app.auth import require_role
from app.classification import build_system_prompt, classify_clause
from app.config import WEB_ORIGIN
from app.db import get_conn
from app.extraction import ExtractionError, extract_text
from app.segmentation import segment_clauses

app = FastAPI(title="Comparador de Documentos Legales — demo Venezuela")

# La app de React (web/) llama a este servicio directo desde el navegador — sin esto el
# navegador bloquea la carga/lista de documentos con "No 'Access-Control-Allow-Origin'".
app.add_middleware(
    CORSMiddleware,
    allow_origins=[WEB_ORIGIN],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# ---------------------------------------------------------------------------
# Tenants (spec-mvp-demo.md #3: alta de tenant = alta de empresa, pais fijo Venezuela)
# ---------------------------------------------------------------------------


@app.post("/tenants", status_code=201)
def crear_tenant(nombre_empresa: str = Form(...)):
    with get_conn() as conn, conn.cursor() as cur:
        # Pais fijo Venezuela para este MVP (Art I.3): sin selector, se resuelve el unico
        # pais activo de la tabla paises en vez de pedirlo en el form.
        cur.execute(
            """
            INSERT INTO tenants (nombre_empresa, pais_id)
            SELECT %s, id FROM paises WHERE codigo = 'VE'
            RETURNING id, nombre_empresa, pais_id, plan_licencia, created_at
            """,
            (nombre_empresa,),
        )
        tenant = cur.fetchone()
        conn.commit()
    return tenant


@app.get("/tenants")
def listar_tenants():
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            """
            SELECT t.id, t.nombre_empresa, t.plan_licencia, t.created_at,
                   p.codigo AS pais_codigo, p.nombre AS pais_nombre
            FROM tenants t
            JOIN paises p ON p.id = t.pais_id
            ORDER BY t.created_at
            """
        )
        return cur.fetchall()


# ---------------------------------------------------------------------------
# Documentos: ingesta + pipeline (Art IV, pasos 1-5)
# ---------------------------------------------------------------------------


def _validar_url_publica(url: str) -> None:
    """Art IV.2 / VI.1: si se declara publico via URL, debe validarse que responda
    sin autenticacion antes de aceptarla como publica."""
    try:
        resp = httpx.get(url, timeout=15.0, follow_redirects=True)
    except httpx.HTTPError as exc:
        raise HTTPException(
            422,
            f"No se pudo marcar el documento como publico: la URL no respondio ({exc}). "
            "Corrige la URL o vuelve a intentar sin marcarlo como publico.",
        ) from exc
    if resp.status_code != 200:
        raise HTTPException(
            422,
            f"No se pudo marcar el documento como publico: la URL respondio {resp.status_code} "
            "(se esperaba 200 sin autenticacion). Corrige la URL o vuelve a intentar sin marcarlo como publico.",
        )


def _descargar_url(url: str) -> tuple[bytes, str]:
    try:
        resp = httpx.get(url, timeout=30.0, follow_redirects=True)
        resp.raise_for_status()
    except httpx.HTTPError as exc:
        raise HTTPException(422, f"No se pudo descargar el documento desde la URL: {exc}") from exc

    nombre = Path(url.split("?")[0]).name or "documento"
    if "." not in nombre:
        content_type = resp.headers.get("content-type", "")
        if "pdf" in content_type:
            nombre += ".pdf"
        elif "word" in content_type or "officedocument.wordprocessingml" in content_type:
            nombre += ".docx"
    return resp.content, nombre


@app.post("/documentos", status_code=201)
def crear_documento(
    request: Request,
    background: BackgroundTasks,
    origen: str = Form(...),
    url_origen: Optional[str] = Form(None),
    es_publico: bool = Form(False),
    archivo: Optional[UploadFile] = File(None),
):
    # auth-spec.md §5: "Cargar documento (ingesta)" = AdminTenant/Editor. tenant_id sale
    # del claim del JWT (Art VI.2), nunca de un form field — evita cross-tenant manipulando
    # el payload.
    claims = require_role(request, "AdminTenant", "Editor")
    tenant_id = claims.tenant_id

    if origen not in ("archivo", "url"):
        raise HTTPException(422, "origen debe ser 'archivo' o 'url'")
    if origen == "url" and not url_origen:
        raise HTTPException(422, "url_origen es requerido cuando origen='url'")
    if origen == "archivo" and archivo is None:
        raise HTTPException(422, "archivo es requerido cuando origen='archivo'")
    if es_publico and origen != "url":
        raise HTTPException(422, "es_publico solo aplica a documentos ingresados por URL")

    with get_conn() as conn, conn.cursor() as cur:
        cur.execute("SELECT id FROM tenants WHERE id = %s", (tenant_id,))
        if cur.fetchone() is None:
            raise HTTPException(404, f"tenant_id {tenant_id} no existe")

    if es_publico:
        _validar_url_publica(url_origen)

    # contenido/extension se mantienen en memoria para el pipeline (extraction.py) — nunca
    # se vuelve a leer el original desde su ubicacion persistida (storage.guardar) para
    # procesarlo, asi que scale-to-zero o una replica distinta entre requests no rompe nada.
    ruta_archivo = None
    contenido = None
    extension = None
    if origen == "archivo":
        contenido = archivo.file.read()
        extension = Path(archivo.filename).suffix
        ruta_archivo = storage.guardar(tenant_id, archivo.filename, contenido)

    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            """
            INSERT INTO documentos (tenant_id, origen, url_origen, ruta_archivo, es_publico, estado)
            VALUES (%s, %s, %s, %s, %s, 'pendiente')
            RETURNING id
            """,
            (tenant_id, origen, url_origen, ruta_archivo, es_publico),
        )
        documento_id = cur.fetchone()["id"]
        conn.commit()

    if origen == "url":
        contenido, nombre = _descargar_url(url_origen)
        extension = Path(nombre).suffix
        ruta_archivo = storage.guardar(tenant_id, nombre, contenido)
        with get_conn() as conn, conn.cursor() as cur:
            cur.execute("UPDATE documentos SET ruta_archivo = %s WHERE id = %s", (ruta_archivo, documento_id))
            conn.commit()

    # En segundo plano, despues de responder: el documento queda en 'pendiente' y el
    # cliente sigue el avance por la columna `estado` (la lista se refresca sola).
    background.add_task(_procesar_pipeline, documento_id, tenant_id, contenido, extension)

    return _obtener_documento(documento_id, tenant_id)


def _marcar_error(documento_id: int, mensaje: str) -> None:
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "UPDATE documentos SET estado = 'error', estado_detalle = %s WHERE id = %s",
            (mensaje, documento_id),
        )
        conn.commit()


def _procesar_pipeline(documento_id: int, tenant_id: uuid.UUID, contenido: bytes, extension: str) -> None:
    try:
        texto = extract_text(contenido, extension)
    except ExtractionError as exc:
        _marcar_error(documento_id, str(exc))
        return

    with get_conn() as conn, conn.cursor() as cur:
        cur.execute("UPDATE documentos SET estado = 'extraido' WHERE id = %s", (documento_id,))
        conn.commit()

    clausulas_texto = segment_clauses(texto)
    if not clausulas_texto:
        _marcar_error(documento_id, "No se pudo segmentar ninguna clausula del texto extraido.")
        return

    with get_conn() as conn, conn.cursor() as cur:
        cur.execute("UPDATE documentos SET estado = 'segmentado' WHERE id = %s", (documento_id,))
        conn.commit()

        cur.execute(
            """
            SELECT t.id, t.nombre, t.descripcion, c.id AS categoria_id, c.nombre AS categoria_nombre
            FROM taxonomia_titulos t
            JOIN taxonomia_categorias c ON c.id = t.categoria_id
            ORDER BY c.id, t.id
            """
        )
        titulos = cur.fetchall()

    system_prompt = build_system_prompt(titulos)
    fallos = 0

    with get_conn() as conn, conn.cursor() as cur:
        for orden, texto_clausula in enumerate(clausulas_texto, start=1):
            titulo_id = None
            categoria_id = None
            try:
                resultado = classify_clause(texto_clausula, titulos, system_prompt)
                titulo_id = resultado["titulo_id"]
                categoria_id = resultado["categoria_id"]
            except Exception as exc:  # nunca abortar todo el documento por una clausula
                print(f"[clasificacion] documento {documento_id} orden {orden}: {exc}")
                fallos += 1

            cur.execute(
                """
                INSERT INTO clausulas (documento_id, tenant_id, texto, titulo_id, categoria_id, orden)
                VALUES (%s, %s, %s, %s, %s, %s)
                """,
                (documento_id, tenant_id, texto_clausula, titulo_id, categoria_id, orden),
            )
        conn.commit()

    detalle = None
    if fallos:
        detalle = f"{fallos} de {len(clausulas_texto)} clausulas no pudieron clasificarse automaticamente."

    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "UPDATE documentos SET estado = 'clasificado', estado_detalle = %s WHERE id = %s",
            (detalle, documento_id),
        )
        conn.commit()


def _obtener_documento(documento_id: int, tenant_id: uuid.UUID) -> dict:
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "SELECT * FROM documentos WHERE id = %s AND tenant_id = %s",
            (documento_id, tenant_id),
        )
        documento = cur.fetchone()
        if documento is None:
            raise HTTPException(404, "documento no encontrado")

        cur.execute(
            """
            SELECT cl.id, cl.texto, cl.orden, t.nombre AS titulo_nombre, c.nombre AS categoria_nombre
            FROM clausulas cl
            LEFT JOIN taxonomia_titulos t ON t.id = cl.titulo_id
            LEFT JOIN taxonomia_categorias c ON c.id = cl.categoria_id
            WHERE cl.documento_id = %s AND cl.tenant_id = %s
            ORDER BY cl.orden
            """,
            (documento_id, tenant_id),
        )
        documento["clausulas"] = cur.fetchall()
    return documento


@app.get("/documentos/{documento_id}")
def obtener_documento(request: Request, documento_id: int):
    # Art VI.2: tenant_id sale SIEMPRE del claim del JWT, nunca de un query param — antes
    # este endpoint tomaba tenant_id directo de la URL, sin ninguna autenticacion.
    claims = require_role(request, "AdminTenant", "Revisor", "Editor", "Visualizador")
    return _obtener_documento(documento_id, claims.tenant_id)


def _listar_documentos(tenant_id: uuid.UUID) -> list[dict]:
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "SELECT id, origen, url_origen, es_publico, estado, estado_detalle, created_at "
            "FROM documentos WHERE tenant_id = %s ORDER BY id DESC",
            (tenant_id,),
        )
        return cur.fetchall()


@app.get("/documentos")
def listar_documentos(request: Request):
    claims = require_role(request, "AdminTenant", "Revisor", "Editor", "Visualizador")
    return _listar_documentos(claims.tenant_id)
