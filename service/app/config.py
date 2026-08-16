import os
from pathlib import Path

from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
load_dotenv(SERVICE_DIR / ".env")

DATABASE_URL = os.environ["DATABASE_URL"]

STORAGE_DIR = SERVICE_DIR / "storage"
STORAGE_DIR.mkdir(exist_ok=True)

# Art V / VI.3: los documentos no deben vivir en el filesystem de la app. Si esta seteada
# (Azure Container Apps la inyecta via Terraform), app/storage.py sube a Blob Storage en vez
# de escribir a STORAGE_DIR. Sin ella (docker-compose / uvicorn local, sin Azurite), cae al
# disco local de siempre — ningun cambio para el flujo de desarrollo existente.
STORAGE_CONNECTION_STRING = os.environ.get("STORAGE_CONNECTION_STRING")
STORAGE_CONTAINER = os.environ.get("STORAGE_CONTAINER", "documentos")

# Modelo por defecto para clasificacion (Art IV.5): claude-opus-5, salvo que se pida otro explicitamente.
CLASSIFICATION_MODEL = os.environ.get("CLASSIFICATION_MODEL", "claude-opus-5")

# Auth (docs/auth-spec.md): misma signing key que api/ (Program.cs) para poder validar
# localmente, sin llamar a la API, los JWT que ella emite.
JWT_SIGNING_KEY = os.environ["JWT_SIGNING_KEY"]
JWT_ISSUER = "comparador-api"
JWT_AUDIENCE = "comparador-web"

# CORS: la app de React (web/) llama a este servicio directo desde el navegador para
# /tenants y /documentos, en un origen distinto (5173 vs 8000) — mismo motivo que
# api/Program.cs necesita su propio Cors:WebOrigin.
WEB_ORIGIN = os.environ.get("WEB_ORIGIN", "http://localhost:5173")
