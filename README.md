# Comparador de Convenciones Colectivas de Trabajo

Sistema que automatiza la lectura, clasificación y extracción de cláusulas de convenciones
colectivas de trabajo mediante IA — reemplaza un SaaS PHP legado que hacía este trabajo
100% manual. **Fase actual: MVP demo Venezuela** (ver alcance exacto en
[`docs/spec-mvp-demo.md`](docs/spec-mvp-demo.md)).

> **Toda decisión de arquitectura, alcance y reglas duras del proyecto vive en
> [`docs/constitution.md`](docs/constitution.md) — es la fuente de verdad.** Si algo en este
> README contradice la constitución, la constitución gana.

> **Migración de stack en curso (Enmienda 2.2.0 de la constitución):** el proyecto está
> migrando a **C#/.NET 10 LTS unificado + Supabase**, según
> [`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)
> (el nombre del archivo es heredado — el destino es Supabase, no Firebase). La tabla de
> abajo describe el stack **desplegado hoy** (Python sigue atendiendo tráfico real); el
> Art. V de la constitución describe el stack **objetivo**. Nada de esto se ha portado a
> código todavía.

> **Se eliminó la infraestructura de Azure (Terraform, deploy a Container Apps):** el demo
> dejó de estar desplegado en la nube — el equipo va a redesplegar a otro proveedor, todavía
> sin decidir. Hasta que eso se defina, el proyecto solo corre localmente
> ([`docs/bootstrap-demo.md`](docs/bootstrap-demo.md)); `infra/` ya no existe en el repo.

## Estructura del repo

| Carpeta | Qué es |
|---|---|
| `api/` | API de autenticación y datos, .NET 10 LTS (tenants, usuarios, roles, JWT) — destino final del microservicio Python según el plan de migración (Fase 3.1 ya hecha; el resto de la lógica de negocio sigue en Python hasta Fase 3.3) |
| `service/` | Microservicio Python (FastAPI) — ingesta, extracción, segmentación y clasificación de cláusulas por IA. **Plan de migración prevé eliminarlo** (Fase 5.4) una vez completado el cutover a `api/` en .NET 10 |
| `web/` | Frontend, React + Vite |
| `.github/workflows/` | CI: build + test de los tres componentes (`ci.yml`). Sin deploy — ver nota de arriba |
| `docs/` | Constitución, specs (auth, MVP demo), taxonomía de Venezuela |
| `legacy/` | SaaS PHP original — solo como referencia funcional (Art. IX de la constitución), no se porta código de acá |

## Cómo correr el pipeline localmente

Guía completa en [`docs/bootstrap-demo.md`](docs/bootstrap-demo.md). Resumen:

```bash
# 1. Base de datos + API de auth + frontend
cd service
docker compose up --build

# 2. Microservicio de ingesta/IA (no está containerizado todavía, corre aparte)
pip install -r requirements.txt
uvicorn app.main:app --reload --port 8000
```

Con eso: `docker compose up` crea el schema, siembra un tenant + usuario `AdminTenant` de
demo (ver credenciales impresas en `docker compose logs seed`), y dejás listo el login en
`http://localhost:5173/login`.

## Stack

**Desplegado hoy:** ningún ambiente en la nube activo — el proyecto corre local (Docker
Compose). Componentes: .NET 10 LTS (Fase 3.1 del plan de migración ya aplicada) ·
Python/FastAPI (sigue con la mayoría de la lógica de negocio, Fase 3.3 pendiente) ·
React/Vite · PostgreSQL.

**Objetivo (migración en curso, Enmienda 2.2.0):** .NET 10 LTS unificado (API + IA) ·
React/Vite · Supabase (PostgreSQL + RLS + Auth + Storage). Infraestructura/proveedor de
deploy todavía sin decidir — se eliminó Azure (Terraform, Container Apps); contenedores
Docker siguen siendo el empaquetado, pero el destino se define aparte.
Justificación de cada elección, y de la transición, en el Art. V de
[`docs/constitution.md`](docs/constitution.md) y en
[`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
