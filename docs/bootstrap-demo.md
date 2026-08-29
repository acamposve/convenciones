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
  `WEB_PORT`, default `5173`) — como antes. Este compose local todavía no declara el
  contenedor `service`.

Con eso ya podés ir a `http://localhost:${WEB_PORT}/login`, loguearte con las credenciales
de arriba, definir la contraseña nueva cuando te lo pida, y llegar a la pantalla de carga
de documentos — sin ningún curl manual a `POST /tenants` ni correr el script aparte.
La UI ahora es enteramente React: la vieja UI server-rendered en Jinja2
(`service/app/ui.py` + `templates/`) se eliminó — React (`web/`) es el único frontend.

**Fase 5 (spec-plataforma.md):** el camino real para un operador nuevo ya no es
`seed_admin_user.py` — es el registro self-service en `http://localhost:${WEB_PORT}/registro`
(`POST /tenants` con `nombre_empresa`/`email`/`password`), que crea el Tenant y su primer
Usuario AdminTenant en un solo paso y loguea de una (sin reset obligatorio, porque el
usuario elige su propia contraseña ahí mismo). `seed_admin_user.py` queda como atajo de
desarrollo/demo — sigue siendo útil para tener un tenant con credenciales fijas y
predecibles sin pasar por el formulario cada vez que se levanta el compose desde cero.

## Servicio Python

El microservicio Python (`service/app/main.py` — ingesta, extracción, segmentación,
clasificación, Art. IV pasos 1-5) **sí está containerizado** mediante
[`service/Dockerfile`](../service/Dockerfile). El workflow de Azure construye esa imagen y
la publica como `comparador-ai-service`.

Para el demo local, `service/docker-compose.yml` todavía no lo levanta automáticamente.
Puedes iniciarlo en otro terminal con Python:

```bash
cd service
pip install -r requirements.txt
uvicorn app.main:app --reload --port 8000
```

Necesita `service/.env` con `DATABASE_URL` apuntando al Postgres publicado. Usa el valor de
`POSTGRES_PORT` definido en `service/.env` (en tu configuración actual es `5433`; si no lo
defines, el compose usa `5432`). No uses `db:5432` desde un proceso local: ese hostname
solo existe dentro de la red de Docker. También necesita `ANTHROPIC_API_KEY` para que la
clasificación (Art. IV.5) funcione.

Para ejecutarlo como contenedor de forma independiente:

```bash
cd service
docker build -t comparador-ai-service .
docker run --rm --env-file .env -p 8000:8000 comparador-ai-service
```

En ese caso, `DATABASE_URL` debe usar `host.docker.internal` y el puerto publicado por el
compose (`5433` en tu configuración actual), por ejemplo:

`postgresql://convenciones:convenciones@host.docker.internal:5433/convenciones`

Es el servicio que sirve `/tenants` y `/documentos` — **la pantalla de carga y lista de
documentos en `web/` no funciona sin este proceso corriendo**, aparte de `api/`. Tiene su
propio CORS (`WEB_ORIGIN` en `.env`, default `http://localhost:5173`) para aceptar
llamadas directas desde el navegador en ese origen.

La siembra de la taxonomía (`db/seed_taxonomia.py`, ~60 títulos reales de Venezuela) no se
ejecuta desde este compose local; el workflow de Azure sí la ejecuta antes de sembrar el
usuario AdminTenant. Para una base local ya creada, ejecútala manualmente desde `service/`.

## Decisión que vale la pena señalar

El `Dockerfile` del servicio Python empaqueta `pymupdf`, `pytesseract` y Tesseract OCR para
Azure. La decisión pendiente para desarrollo local es agregarlo al mismo compose, porque
eso requiere definir el uso de `db:5432` dentro de la red Docker y cómo se inyectará
`ANTHROPIC_API_KEY`; por ahora se ejecuta como proceso local o como contenedor independiente.
