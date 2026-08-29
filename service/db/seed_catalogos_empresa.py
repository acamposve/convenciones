"""Carga docs/catalogos_empresa_venezuela.json en los catalogos globales de segmentacion
de empresas (Art. II.5 de la constitucion): sectores, tipos_empresa, categorias_sector,
actividades_empresa, estados, localidades.

Extraido del dump legado (presenci_cccol.sql) con un parser de tuplas SQL propio (no un
split ingenuo, porque las descripciones traen HTML con comas adentro de atributos), y
curado a mano: entidades HTML resueltas, doble-encoding UTF-8 reparado con ftfy, y ~23
nombres de localidades con la tilde perdida en el legado corregidos con conocimiento
directo de geografia venezolana (confirmado con Alex) -- ver el commit que agrega este
archivo para el detalle de cada correccion.

Idempotente: usa ON CONFLICT DO UPDATE, se puede correr mas de una vez sin duplicar filas.
Requiere que db/schema.sql (o la migracion 003_catalogos_empresa.sql) ya haya sido aplicado.
"""
import json
import os
from pathlib import Path

import psycopg
from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
REPO_ROOT = SERVICE_DIR.parent
CATALOGOS_PATH = REPO_ROOT / "docs" / "catalogos_empresa_venezuela.json"

load_dotenv(SERVICE_DIR / ".env")


def _seed_catalogo_simple(cur, tabla: str, filas: list[dict]) -> None:
    """sectores / tipos_empresa / categorias_sector / actividades_empresa: mismo shape
    (id via "codigo" en el JSON, nombre, descripcion)."""
    for fila in filas:
        cur.execute(
            f"""
            INSERT INTO {tabla} (id, nombre, descripcion)
            VALUES (%(id)s, %(nombre)s, %(descripcion)s)
            ON CONFLICT (id) DO UPDATE SET
                nombre = EXCLUDED.nombre,
                descripcion = EXCLUDED.descripcion
            """,
            {"id": fila["codigo"], "nombre": fila["nombre"], "descripcion": fila["descripcion"] or None},
        )


def main() -> None:
    database_url = os.environ["DATABASE_URL"]
    data = json.loads(CATALOGOS_PATH.read_text(encoding="utf-8"))

    with psycopg.connect(database_url) as conn:
        with conn.cursor() as cur:
            _seed_catalogo_simple(cur, "sectores", data["sectores"])
            _seed_catalogo_simple(cur, "tipos_empresa", data["tipos_empresa"])
            _seed_catalogo_simple(cur, "categorias_sector", data["categorias_sector"])
            _seed_catalogo_simple(cur, "actividades_empresa", data["actividades_empresa"])

            # Venezuela es el unico pais activo hoy (Art I.2/II.4) -- se resuelve por
            # codigo, nunca se hardcodea el id de la fila en `paises`.
            cur.execute("SELECT id FROM paises WHERE codigo = 'VE'")
            pais_id_ve = cur.fetchone()[0]

            for estado in data["estados"]:
                cur.execute(
                    """
                    INSERT INTO estados (id, pais_id, nombre)
                    VALUES (%(id)s, %(pais_id)s, %(nombre)s)
                    ON CONFLICT (id) DO UPDATE SET
                        pais_id = EXCLUDED.pais_id,
                        nombre = EXCLUDED.nombre
                    """,
                    {"id": estado["id"], "pais_id": pais_id_ve, "nombre": estado["nombre"]},
                )

            for localidad in data["localidades"]:
                cur.execute(
                    """
                    INSERT INTO localidades (id, estado_id, nombre)
                    VALUES (%(id)s, %(estado_id)s, %(nombre)s)
                    ON CONFLICT (id) DO UPDATE SET
                        estado_id = EXCLUDED.estado_id,
                        nombre = EXCLUDED.nombre
                    """,
                    {"id": localidad["id"], "estado_id": localidad["id_estado"], "nombre": localidad["nombre"]},
                )
        conn.commit()

        with conn.cursor() as cur:
            conteos = {}
            for tabla in ["sectores", "tipos_empresa", "categorias_sector", "actividades_empresa", "estados", "localidades"]:
                cur.execute(f"SELECT count(*) FROM {tabla}")
                conteos[tabla] = cur.fetchone()[0]

    print("Catalogos de empresa sembrados:")
    for tabla, n in conteos.items():
        print(f"  {tabla}: {n} filas")


if __name__ == "__main__":
    main()
