import os
from pathlib import Path

from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
load_dotenv(SERVICE_DIR / ".env")

DATABASE_URL = os.environ["DATABASE_URL"]

STORAGE_DIR = SERVICE_DIR / "storage"
STORAGE_DIR.mkdir(exist_ok=True)

# Modelo por defecto para clasificacion (Art IV.5): claude-opus-5, salvo que se pida otro explicitamente.
CLASSIFICATION_MODEL = os.environ.get("CLASSIFICATION_MODEL", "claude-opus-5")
