"""Carga docs/ley_lottt_venezuela.json en leyes, articulos_ley y titulo_articulo_ley.

Idempotente: usa ON CONFLICT DO UPDATE, se puede correr mas de una vez sin duplicar filas.
El vinculo articulo->titulo (spec-marco-legal.md §4: curaduria adoptada del dump legado,
revisada a mano) viene incluido en el JSON como titulo_taxonomia_id -- no hace falta un
archivo aparte para la tabla puente. Requiere que schema.sql ya haya sido aplicado.
"""
import json
import os
from pathlib import Path

import psycopg
from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
REPO_ROOT = SERVICE_DIR.parent
LEY_PATH = REPO_ROOT / "docs" / "ley_lottt_venezuela.json"

load_dotenv(SERVICE_DIR / ".env")


def main() -> None:
    database_url = os.environ["DATABASE_URL"]
    data = json.loads(LEY_PATH.read_text(encoding="utf-8"))
    ley = data["ley"]
    articulos = data["articulos"]

    with psycopg.connect(database_url) as conn:
        with conn.cursor() as cur:
            cur.execute("SELECT id FROM paises WHERE codigo = %s", (ley["pais_codigo"],))
            fila = cur.fetchone()
            if fila is None:
                raise RuntimeError(f"No existe el pais con codigo {ley['pais_codigo']!r} -- corre seed_taxonomia.py primero")
            pais_id = fila[0]

            cur.execute(
                """
                INSERT INTO leyes (pais_id, nombre, gaceta, fecha_publicacion)
                VALUES (%(pais_id)s, %(nombre)s, %(gaceta)s, %(fecha_publicacion)s)
                ON CONFLICT (pais_id, nombre) DO UPDATE SET
                    gaceta = EXCLUDED.gaceta,
                    fecha_publicacion = EXCLUDED.fecha_publicacion
                RETURNING id
                """,
                {**ley, "pais_id": pais_id},
            )
            ley_id = cur.fetchone()[0]

            n_vinculos = 0
            for articulo in articulos:
                cur.execute(
                    """
                    INSERT INTO articulos_ley (ley_id, nro_articulo, titulo_articulo, texto_completo)
                    VALUES (%(ley_id)s, %(nro_articulo)s, %(titulo_articulo)s, %(texto_completo)s)
                    ON CONFLICT (ley_id, nro_articulo) DO UPDATE SET
                        titulo_articulo = EXCLUDED.titulo_articulo,
                        texto_completo = EXCLUDED.texto_completo
                    RETURNING id
                    """,
                    {**articulo, "ley_id": ley_id},
                )
                articulo_ley_id = cur.fetchone()[0]

                if articulo["titulo_taxonomia_id"] is not None:
                    cur.execute(
                        """
                        INSERT INTO titulo_articulo_ley (titulo_id, articulo_ley_id)
                        VALUES (%s, %s)
                        ON CONFLICT DO NOTHING
                        """,
                        (articulo["titulo_taxonomia_id"], articulo_ley_id),
                    )
                    n_vinculos += 1
        conn.commit()

        with conn.cursor() as cur:
            cur.execute("SELECT count(*) FROM leyes")
            n_leyes = cur.fetchone()[0]
            cur.execute("SELECT count(*) FROM articulos_ley")
            n_articulos = cur.fetchone()[0]
            cur.execute("SELECT count(DISTINCT titulo_id) FROM titulo_articulo_ley")
            n_titulos_vinculados = cur.fetchone()[0]

    print(f"leyes: {n_leyes} filas")
    print(f"articulos_ley: {n_articulos} filas")
    print(f"titulo_articulo_ley: {n_vinculos} vinculos ({n_titulos_vinculados} titulos distintos)")


if __name__ == "__main__":
    main()
