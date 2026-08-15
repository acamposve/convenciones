"""Conftest a nivel de servicio (no de tests/): pytest inserta este directorio en
sys.path por tener este archivo, lo que permite "import app.xxx" sin importar desde
donde se invoque pytest. Tambien fija las env vars que app/config.py exige con
os.environ[...] ANTES de que cualquier test importe modulos de app/ — sin esto, solo
importar app.auth (que importa app.config) revienta con KeyError en collection.
"""
import os

os.environ.setdefault("DATABASE_URL", "postgresql://test:test@localhost/test_no_usado")
os.environ.setdefault("JWT_SIGNING_KEY", "clave-de-pruebas-no-usar-en-otro-entorno")
os.environ.setdefault("ANTHROPIC_API_KEY", "sk-test-no-se-llama-a-la-api-en-unit-tests")
