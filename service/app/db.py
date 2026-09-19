from contextlib import contextmanager

import psycopg
from psycopg.rows import dict_row

from app.config import DATABASE_URL


@contextmanager
def get_conn():
    # prepare_threshold=None: desactiva los prepared statements server-side de psycopg3.
    # Necesario para Supabase (Fase 2, PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md): el pooler
    # Supavisor en modo transaction (puerto 6543) reparte cada query entre conexiones fisicas
    # distintas por detras -- un prepared statement con nombre fijo ("_pg3_0", que psycopg3
    # reutiliza) puede chocar con el de otra sesion en la misma conexion fisica y tira
    # "DuplicatePreparedStatement". Con conexion directa a Postgres (dev local) esto no hace
    # falta pero tampoco rompe nada, solo evita el cacheo server-side de statements.
    with psycopg.connect(DATABASE_URL, row_factory=dict_row, prepare_threshold=None) as conn:
        yield conn
