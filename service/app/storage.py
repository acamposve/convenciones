"""Persistencia de los documentos originales (Art V / VI.3 de la constitucion).

Azure Blob Storage cuando STORAGE_CONNECTION_STRING esta configurada (Azure Container
Apps la inyecta via Terraform); disco local como fallback solo quien no tiene Azure a mano
(docker-compose / uvicorn local no levantan Azurite). El pipeline de extraccion (Art IV.3)
nunca lee de vuelta a traves de este modulo: recibe los bytes ya en memoria (ver main.py),
asi que scale-to-zero o una replica distinta nunca le impiden clasificar el documento que
acaba de subir. Este modulo solo resuelve la persistencia *durable* del original.
"""
import os
import uuid

from app.config import STORAGE_CONNECTION_STRING, STORAGE_CONTAINER, STORAGE_DIR


def guardar(tenant_id, filename: str, contenido: bytes) -> str:
    safe_name = os.path.basename(filename)
    blob_name = f"{tenant_id}/{uuid.uuid4().hex}_{safe_name}"

    if STORAGE_CONNECTION_STRING:
        from azure.storage.blob import BlobServiceClient

        client = BlobServiceClient.from_connection_string(STORAGE_CONNECTION_STRING)
        container = client.get_container_client(STORAGE_CONTAINER)
        container.upload_blob(name=blob_name, data=contenido, overwrite=True)
        return f"blob:{STORAGE_CONTAINER}/{blob_name}"

    destino_dir = STORAGE_DIR / str(tenant_id)
    destino_dir.mkdir(parents=True, exist_ok=True)
    destino = destino_dir / f"{uuid.uuid4().hex}_{safe_name}"
    destino.write_bytes(contenido)
    return str(destino)
