"""Persistencia de los documentos originales (Art V / VI.3 de la constitucion).

Disco local por ahora (STORAGE_DIR) -- el backend de storage durable en la nube (Art V:
"Blob storage cifrado en reposo") queda pendiente de definir junto con el proveedor de
deploy nuevo (se elimino el soporte a Azure Blob Storage al retirar Terraform/Azure, ver
constitution.md Art V). El pipeline de extraccion (Art IV.3) nunca lee de vuelta a traves
de este modulo: recibe los bytes ya en memoria (ver main.py), asi que este modulo solo
resuelve la persistencia *durable* del original, no el flujo de clasificacion.
"""
import os
import uuid

from app.config import STORAGE_DIR


def guardar(tenant_id, filename: str, contenido: bytes) -> str:
    safe_name = os.path.basename(filename)

    destino_dir = STORAGE_DIR / str(tenant_id)
    destino_dir.mkdir(parents=True, exist_ok=True)
    destino = destino_dir / f"{uuid.uuid4().hex}_{safe_name}"
    destino.write_bytes(contenido)
    return str(destino)
