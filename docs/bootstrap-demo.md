# Bootstrap del demo (Venezuela)

> Depende de: `constitution.md` (Art. V), `auth-spec.md` §4. El backend ya es un único
> servicio .NET 10 (Enmienda 2.5.0 — el microservicio Python que existía en `service/` se
> eliminó del repositorio); lo único que sigue pendiente del stack objetivo es la base de
> datos (Supabase, todavía PostgreSQL local aquí — ver
> [`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)).
> Objetivo de este documento: dejar documentado y automatizado el orden real que hoy hace
> falta para poder loguearse por primera vez. Antes era tribal knowledge (tres comandos
> manuales, sin documentar, en un orden que si se rompe da `Unauthorized` sin explicación).

## Por qué existe este documento

El login (`POST /api/auth/login`) solo funciona si ya existe un usuario `AdminTenant`
sembrado para un tenant — sin eso, la API responde `Unauthorized` sin más contexto. Ese
seed, a su vez, necesita que exista un tenant. Ninguno de los dos pasos ocurre solo.

## Qué automatiza `docker compose up --build`

```bash
docker compose up --build
```

1. **`db`** — Postgres 16. En un volumen nuevo, aplica automáticamente `db/schema.sql`
   (montado en `docker-entrypoint-initdb.d`) — crea todas las tablas y siembra el catálogo
   de `paises` (solo VE activo, Art. I.3). En un volumen ya existente esto **no se reaplica**
   (comportamiento estándar de la imagen de Postgres), tal como ya documentaba `schema.sql`.
2. **`seed`** — job de un solo uso (`restart: "no"`). Corre
   [`db/seed_admin_user.py`](../db/seed_admin_user.py), que:
   - crea el tenant demo si todavía no hay ninguno (nombre configurable vía
     `TENANT_DEMO_NOMBRE` en `.env`, default `Empresa Demo`),
   - siembra el usuario `AdminTenant` (`admin@empresademo.local` / `CambiarAhora123!`,
     **DEV ONLY**) con `requiere_reset_password=true` — el primer login no da sesión
     completa, solo el token de un solo uso para `/api/auth/reset-password` (Art. VI.4;
     lo maneja `web/` en `LoginPage → ResetPasswordPage`), y
   - **loguea** (no envía) el email que le avisaría a `director@presenciavirtual.net` que
     se creó el admin — no hay proveedor SMTP configurado todavía. Se ve en
     `docker compose logs seed`.
   Idempotente: correrlo de nuevo (o reiniciar el compose) no duplica nada ni vuelve a
   loguear el email.
3. **`api`** (.NET, puerto `API_PORT`, default `5080`) y **`web`** (React, puerto
  `WEB_PORT`, default `5173`).

Con eso ya podés ir a `http://localhost:${WEB_PORT}/login`, loguearte con las credenciales
de arriba, definir la contraseña nueva cuando te lo pida, y llegar a la pantalla de carga
de documentos — sin ningún curl manual a `POST /tenants` ni correr el script aparte.
La UI es enteramente React (`web/`) — no hay UI server-rendered.

**Fase 5 (spec-plataforma.md):** el camino real para un operador nuevo ya no es
`seed_admin_user.py` — es el registro self-service en `http://localhost:${WEB_PORT}/registro`
(`POST /tenants` con `nombre_empresa`/`email`/`password`), que crea el Tenant y su primer
Usuario AdminTenant en un solo paso y loguea de una (sin reset obligatorio, porque el
usuario elige su propia contraseña ahí mismo). `seed_admin_user.py` queda como atajo de
desarrollo/demo — sigue siendo útil para tener un tenant con credenciales fijas y
predecibles sin pasar por el formulario cada vez que se levanta el compose desde cero.

La siembra de la taxonomía (`db/seed_taxonomia.py`, ~60 títulos reales de Venezuela), el
marco legal (`db/seed_marco_legal.py`) y los catálogos de empresa
(`db/seed_catalogos_empresa.py`) no se ejecutan desde este compose local. Para una base
local ya creada, ejecutalos manualmente:

```bash
python db/seed_taxonomia.py
python db/seed_marco_legal.py
python db/seed_catalogos_empresa.py
```

(Necesitan `psycopg[binary]`, `python-dotenv` y `DATABASE_URL` apuntando al Postgres
publicado por el compose — mismo patrón que usa el servicio `seed`.)
