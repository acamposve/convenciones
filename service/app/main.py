"""Servicio FastAPI — ingesta, extraccion, segmentacion y clasificacion (Art IV, pasos 1-5).

El pipeline se detiene despues de la clasificacion (paso 5): sin score de confianza,
sin cola de revision, sin publicacion (spec-mvp-demo.md). Se ejecuta de forma sincrona
dentro de POST /documentos, sin cola de tareas — simplificacion de demo señalada
explicitamente frente al Art V de la constitucion.
"""
import os
import uuid
from pathlib import Path
from typing import Optional

import httpx
from fastapi import FastAPI, File, Form, HTTPException, UploadFile

from app.classification import build_system_prompt, classify_clause
from app.config import STORAGE_DIR
from app.db import get_conn
from app.extraction import ExtractionError, extract_text
from app.segmentation import segment_clauses

app = FastAPI(title="Comparador de Documentos Legales — demo Venezuela")


# ---------------------------------------------------------------------------
# Tenants (spec-mvp-demo.md #3: alta de tenant = alta de empresa, pais fijo Venezuela)
# ---------------------------------------------------------------------------


@app.post("/tenants", status_code=201)
def crear_tenant(nombre_empresa: str = Form(...)):
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "INSERT INTO tenants (nombre_empresa) VALUES (%s) RETURNING id, nombre_empresa, pais, created_at",
            (nombre_empresa,),
        )
        tenant = cur.fetchone()
        conn.commit()
    return tenant


@app.get("/tenants")
def listar_tenants():
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute("SELECT id, nombre_empresa, pais, created_at FROM tenants ORDER BY id")
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


def _guardar_archivo(tenant_id: int, filename: str, contenido: bytes) -> str:
    safe_name = os.path.basename(filename)
    destino_dir = STORAGE_DIR / str(tenant_id)
    destino_dir.mkdir(parents=True, exist_ok=True)
    destino = destino_dir / f"{uuid.uuid4().hex}_{safe_name}"
    destino.write_bytes(contenido)
    return str(destino)


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
    tenant_id: int = Form(...),
    origen: str = Form(...),
    url_origen: Optional[str] = Form(None),
    es_publico: bool = Form(False),
    archivo: Optional[UploadFile] = File(None),
):
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

    ruta_archivo = None
    if origen == "archivo":
        contenido = archivo.file.read()
        ruta_archivo = _guardar_archivo(tenant_id, archivo.filename, contenido)

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
        ruta_archivo = _guardar_archivo(tenant_id, nombre, contenido)
        with get_conn() as conn, conn.cursor() as cur:
            cur.execute("UPDATE documentos SET ruta_archivo = %s WHERE id = %s", (ruta_archivo, documento_id))
            conn.commit()

    _procesar_pipeline(documento_id, tenant_id, ruta_archivo)

    return _obtener_documento(documento_id, tenant_id)


def _marcar_error(documento_id: int, mensaje: str) -> None:
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "UPDATE documentos SET estado = 'error', estado_detalle = %s WHERE id = %s",
            (mensaje, documento_id),
        )
        conn.commit()


def _procesar_pipeline(documento_id: int, tenant_id: int, ruta_archivo: str) -> None:
    try:
        texto = extract_text(ruta_archivo)
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


def _obtener_documento(documento_id: int, tenant_id: int) -> dict:
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
def obtener_documento(documento_id: int, tenant_id: int):
    return _obtener_documento(documento_id, tenant_id)


@app.get("/documentos")
def listar_documentos(tenant_id: int):
    with get_conn() as conn, conn.cursor() as cur:
        cur.execute(
            "SELECT id, origen, url_origen, es_publico, estado, estado_detalle, created_at "
            "FROM documentos WHERE tenant_id = %s ORDER BY id DESC",
            (tenant_id,),
        )
        return cur.fetchall()


# ---------------------------------------------------------------------------
# UI minima (paso 3): HTML servido por este mismo backend.
# ---------------------------------------------------------------------------
from app.ui import router as ui_router  # noqa: E402  (registrado al final para reusar lo de arriba)

app.include_router(ui_router)
