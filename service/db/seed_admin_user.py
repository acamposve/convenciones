"""Bootstrap del demo: crea el tenant inicial (si no existe) y el usuario AdminTenant
para poder loguearse por primera vez.

Este es el paso que debe correr ANTES del primer login (docs/auth-spec.md §4): sin un
usuario sembrado, POST /api/auth/login siempre devuelve Unauthorized. Antes, crear el
tenant requeria un curl a mano contra POST /tenants (servicio Python) antes de correr
este script; se fusiona aca para que el bootstrap completo sea un solo comando, sin
depender de que el servicio Python este corriendo.

DEV ONLY: password de arranque en texto plano solo para poder loguearse la primera vez.
El usuario se crea con requiere_reset_password=true (Art VI.4): el primer login solo
devuelve un token de un solo uso para /api/auth/reset-password, no una sesion completa —
mismo flujo que ya soporta la app de React (LoginPage -> ResetPasswordPage).

Todavia no hay proveedor de email configurado (SMTP/SES/etc); en vez de bloquear el
bootstrap por eso, se loguea el contenido del correo que se le mandaria al director en
vez de enviarlo de verdad.

Idempotente: si ya hay un tenant, se reusa el mas antiguo en vez de crear uno nuevo; si el
email ya existe para ese tenant, no hace nada (tampoco se re-loguea el email).
"""
import os
from pathlib import Path

import bcrypt
import psycopg
from dotenv import load_dotenv

SERVICE_DIR = Path(__file__).resolve().parent.parent
load_dotenv(SERVICE_DIR / ".env")

EMAIL = "admin@empresademo.local"
PASSWORD_DEV_ONLY = "CambiarAhora123!"
TENANT_DEMO_NOMBRE = os.environ.get("TENANT_DEMO_NOMBRE", "Empresa Demo")
DIRECTOR_EMAIL = "director@presenciavirtual.net"


def _log_email_admin_creado(nombre_empresa: str, email: str, password: str) -> None:
    """No hay proveedor de email configurado todavia (SMTP/SES/etc) — se deja constancia
    en el log de lo que se le mandaria a director@presenciavirtual.net, para no bloquear
    el bootstrap del demo en una decision de infraestructura de correo pendiente."""
    print("[seed] --- EMAIL (no enviado, solo log) ---")
    print(f"  Para:    {DIRECTOR_EMAIL}")
    print(f"  Asunto:  Usuario administrador creado — {nombre_empresa}")
    print("  Cuerpo:")
    print(f"    Se creo el usuario AdminTenant inicial para '{nombre_empresa}'.")
    print(f"    Email:    {email}")
    print(f"    Password: {password}  (temporal — se exige cambiarla en el primer login)")
    print("[seed] --- fin EMAIL ---")


def _obtener_o_crear_tenant(cur) -> tuple:
    cur.execute("SELECT id, nombre_empresa FROM tenants ORDER BY created_at LIMIT 1")
    tenant = cur.fetchone()
    if tenant is not None:
        return tenant

    # Pais fijo Venezuela para este MVP (Art I.3) — mismo criterio que POST /tenants
    # en service/app/main.py: se resuelve el unico pais activo, sin selector.
    cur.execute(
        """
        INSERT INTO tenants (nombre_empresa, pais_id)
        SELECT %s, id FROM paises WHERE codigo = 'VE'
        RETURNING id, nombre_empresa
        """,
        (TENANT_DEMO_NOMBRE,),
    )
    tenant = cur.fetchone()
    print(f"[seed] Tenant demo creado: '{tenant[1]}' ({tenant[0]}).")
    return tenant


def main() -> None:
    database_url = os.environ["DATABASE_URL"]

    with psycopg.connect(database_url) as conn:
        with conn.cursor() as cur:
            tenant_id, nombre_empresa = _obtener_o_crear_tenant(cur)
            conn.commit()

            cur.execute(
                "SELECT id FROM usuarios WHERE tenant_id = %s AND email = %s",
                (tenant_id, EMAIL),
            )
            if cur.fetchone() is not None:
                print(f"Ya existe {EMAIL} para el tenant '{nombre_empresa}' — nada que hacer.")
                return

            password_hash = bcrypt.hashpw(PASSWORD_DEV_ONLY.encode(), bcrypt.gensalt()).decode()

            cur.execute(
                """
                INSERT INTO usuarios (tenant_id, email, password_hash, rol, requiere_reset_password)
                VALUES (%s, %s, %s, 'AdminTenant', true)
                """,
                (tenant_id, EMAIL, password_hash),
            )
            conn.commit()

    print(f"Usuario AdminTenant creado para el tenant '{nombre_empresa}' ({tenant_id}).")
    print(f"  email:    {EMAIL}")
    print(f"  password: {PASSWORD_DEV_ONLY}  (DEV ONLY — debe resetearse en el primer login)")
    _log_email_admin_creado(nombre_empresa, EMAIL, PASSWORD_DEV_ONLY)


if __name__ == "__main__":
    main()
