"""Carga docs/taxonomia_venezuela.json en taxonomia_categorias y taxonomia_titulos.

Idempotente: usa ON CONFLICT DO UPDATE, se puede correr mas de una vez sin duplicar filas.
Requiere que schema.sql ya haya sido aplicado.
"""
import json
import os
from pathlib import Path

import psycopg
from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
REPO_ROOT = SERVICE_DIR.parent
TAXONOMIA_PATH = REPO_ROOT / "docs" / "taxonomia_venezuela.json"

load_dotenv(SERVICE_DIR / ".env")


def main() -> None:
    database_url = os.environ["DATABASE_URL"]
    data = json.loads(TAXONOMIA_PATH.read_text(encoding="utf-8"))

    with psycopg.connect(database_url) as conn:
        with conn.cursor() as cur:
            cur.execute("SELECT id FROM paises WHERE codigo = 'VE'")
            fila_venezuela = cur.fetchone()
            if fila_venezuela is None:
                raise RuntimeError(
                    "No existe el pais Venezuela (codigo 'VE') en la tabla paises -- "
                    "aplica schema.sql (o la migracion 002_auth.sql) antes de correr este seed."
                )
            pais_id_venezuela = fila_venezuela[0]

            for categoria in data["categorias"]:
                cur.execute(
                    """
                    INSERT INTO taxonomia_categorias
                        (id, nombre, descripcion, requiere_campo_comparacion_economica)
                    VALUES (%(id)s, %(nombre)s, %(descripcion)s,
                            %(requiere_campo_comparacion_economica)s)
                    ON CONFLICT (id) DO UPDATE SET
                        nombre = EXCLUDED.nombre,
                        descripcion = EXCLUDED.descripcion,
                        requiere_campo_comparacion_economica =
                            EXCLUDED.requiere_campo_comparacion_economica
                    """,
                    categoria,
                )

            for titulo in data["titulos"]:
                cur.execute(
                    """
                    INSERT INTO taxonomia_titulos
                        (id, nombre, descripcion, categoria_id, pais_id)
                    VALUES (%(id)s, %(nombre)s, %(descripcion)s, %(categoria_id)s,
                            %(pais_id)s)
                    ON CONFLICT (id) DO UPDATE SET
                        nombre = EXCLUDED.nombre,
                        descripcion = EXCLUDED.descripcion,
                        categoria_id = EXCLUDED.categoria_id
                    """,
                    {**titulo, "pais_id": pais_id_venezuela},
                )
        conn.commit()

        with conn.cursor() as cur:
            cur.execute("SELECT count(*) FROM taxonomia_categorias")
            n_categorias = cur.fetchone()[0]
            cur.execute("SELECT count(*) FROM taxonomia_titulos")
            n_titulos = cur.fetchone()[0]
            cur.execute(
                """
                SELECT c.nombre, count(t.id)
                FROM taxonomia_categorias c
                LEFT JOIN taxonomia_titulos t ON t.categoria_id = c.id
                GROUP BY c.nombre
                ORDER BY c.nombre
                """
            )
            por_categoria = cur.fetchall()

    print(f"taxonomia_categorias: {n_categorias} filas")
    print(f"taxonomia_titulos: {n_titulos} filas")
    print("Titulos por categoria:")
    for nombre, n in por_categoria:
        print(f"  {nombre}: {n}")


if __name__ == "__main__":
    main()
