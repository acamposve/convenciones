# Bootstrap del demo (Venezuela)

> Depende de: `constitution.md` (Art. V — dos servicios separados), `auth-spec.md` §4.
> Objetivo: dejar documentado y automatizado el orden real que hoy hace falta para poder
> loguearse por primera vez. Antes era tribal knowledge (tres comandos manuales, sin
> documentar, en un orden que si se rompe da `Unauthorized` sin explicación).

## Por qué existe este documento

El sistema son dos servicios separados (Art. V): la API de auth en **.NET** (`api/`) y el
microservicio de ingesta/IA en **Python** (`service/`). El login (`POST /api/auth/login`)
solo funciona si ya existe un usuario `AdminTenant` sembrado para un tenant — sin eso,
la API responde `Unauthorized` sin más contexto. Ese seed, a su vez, necesita que exista
un tenant. Ninguno de los dos pasos ocurre solo.

## Qué automatiza `docker compose up --build` (desde `service/`)

```bash
cd service
docker compose up --build
```

1. **`db`** — Postgres 16. En un volumen nuevo, aplica automáticamente `db/schema.sql`
   (montado en `docker-entrypoint-initdb.d`) — crea todas las tablas y siembra el catálogo
   de `paises` (solo VE activo, Art. I.3). En un volumen ya existente esto **no se reaplica**
   (comportamiento estándar de la imagen de Postgres), tal como ya documentaba `schema.sql`.
2. **`seed`** — job de un solo uso (`restart: "no"`). Corre
   [`db/seed_admin_user.py`](../service/db/seed_admin_user.py), que:
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
   `WEB_PORT`, default `5173`) — como antes.

Con eso ya podés ir a `http://localhost:${WEB_PORT}/login`, loguearte con las credenciales
de arriba, definir la contraseña nueva cuando te lo pida, y llegar a la pantalla de carga
de documentos — sin ningún curl manual a `POST /tenants` ni correr el script aparte.
La UI ahora es enteramente React: la vieja UI server-rendered en Jinja2
(`service/app/ui.py` + `templates/`) se eliminó — React (`web/`) es el único frontend.

## Lo que sigue siendo manual (fuera de alcance de este cambio)

El microservicio Python (`service/app/main.py` — ingesta, extracción, segmentación,
clasificación, Art. IV pasos 1-5) **no está containerizado** — no tiene `Dockerfile` y no
corre dentro de este `docker-compose.yml`. Sigue arrancando local:

```bash
cd service
pip install -r requirements.txt
uvicorn app.main:app --reload --port 8000
```

Necesita `service/.env` con `DATABASE_URL` apuntando al Postgres publicado
(`localhost:${POSTGRES_PORT:-5433}`, no `db:5432` — ese hostname solo existe dentro de la
red de Docker) y `ANTHROPIC_API_KEY` seteada para que la clasificación (Art. IV.5) funcione.

Es el servicio que sirve `/tenants` y `/documentos` — **la pantalla de carga y lista de
documentos en `web/` no funciona sin este proceso corriendo**, aparte de `api/`. Tiene su
propio CORS (`WEB_ORIGIN` en `.env`, default `http://localhost:5173`) para aceptar
llamadas directas desde el navegador en ese origen.

Tampoco se automatizó la siembra de la taxonomía (`db/seed_taxonomia.py`, ~60 títulos reales
de Venezuela) — no formaba parte de lo pedido (bootstrap de tenant/login) y tocarla implica
decidir si se ejecuta contra el mismo volumen nuevo o no; se deja fuera a propósito.

## Decisión que vale la pena señalar

Containerizar el servicio Python (agregarle `Dockerfile` + servicio en este compose) no se
hizo acá porque no fue parte de lo pedido y es un cambio de alcance mayor (empaqueta
`pymupdf`/`pytesseract`/OCR, credenciales de `ANTHROPIC_API_KEY`, red interna vs. puerto de
Postgres publicado). Si se quiere, es un paso natural siguiente pero separado de este.
