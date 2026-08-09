"""UI minima (paso 3): HTML servido por el mismo backend FastAPI, sin frontend aparte."""
from pathlib import Path
from typing import Optional

from fastapi import APIRouter, File, Form, HTTPException, Request, UploadFile
from fastapi.responses import HTMLResponse, RedirectResponse
from fastapi.templating import Jinja2Templates

from app.main import _obtener_documento, crear_documento, crear_tenant, listar_documentos, listar_tenants

router = APIRouter()

templates = Jinja2Templates(directory=str(Path(__file__).resolve().parent / "templates"))


@router.get("/", response_class=HTMLResponse)
def index(request: Request, tenant_id: Optional[int] = None, error: Optional[str] = None):
    tenants = listar_tenants()
    documentos = []
    tenant = None
    if tenant_id is not None:
        tenant = next((t for t in tenants if t["id"] == tenant_id), None)
        if tenant is not None:
            documentos = listar_documentos(tenant_id)
    return templates.TemplateResponse(
        request,
        "index.html",
        {
            "tenants": tenants,
            "tenant_id": tenant_id,
            "tenant": tenant,
            "documentos": documentos,
            "error": error,
        },
    )


@router.post("/ui/tenants")
def crear_tenant_ui(nombre_empresa: str = Form(...)):
    tenant = crear_tenant(nombre_empresa=nombre_empresa)
    return RedirectResponse(url=f"/?tenant_id={tenant['id']}", status_code=303)


@router.post("/ui/documentos")
def crear_documento_ui(
    tenant_id: int = Form(...),
    origen: str = Form(...),
    url_origen: Optional[str] = Form(None),
    es_publico: bool = Form(False),
    archivo: Optional[UploadFile] = File(None),
):
    try:
        documento = crear_documento(
            tenant_id=tenant_id,
            origen=origen,
            url_origen=url_origen,
            es_publico=es_publico,
            archivo=archivo,
        )
    except HTTPException as exc:
        return RedirectResponse(url=f"/?tenant_id={tenant_id}&error={exc.detail}", status_code=303)
    return RedirectResponse(url=f"/ui/documentos/{documento['id']}?tenant_id={tenant_id}", status_code=303)


@router.get("/ui/documentos/{documento_id}", response_class=HTMLResponse)
def ver_documento(request: Request, documento_id: int, tenant_id: int):
    documento = _obtener_documento(documento_id, tenant_id)
    return templates.TemplateResponse(
        request,
        "documento.html",
        {"documento": documento, "tenant_id": tenant_id},
    )
