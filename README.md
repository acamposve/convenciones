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

## Estructura del repo

| Carpeta | Qué es |
|---|---|
| `api/` | API de autenticación y datos, .NET 8 (tenants, usuarios, roles, JWT) — destino final del microservicio Python según el plan de migración |
| `service/` | Microservicio Python (FastAPI) — ingesta, extracción, segmentación y clasificación de cláusulas por IA. **Plan de migración prevé eliminarlo** (Fase 5.4) una vez completado el cutover a `api/` en .NET 10 |
| `web/` | Frontend, React + Vite |
| `infra/terraform/` | Infraestructura como código (Azure: Container Apps, Postgres, ACR, Storage). **Plan de migración prevé eliminar** el Postgres Flexible Server administrado (Fase 5.3) al pasar a Supabase |
| `.github/workflows/` | CI/CD (build+test, plan/apply de Terraform, deploy a Azure) |
| `docs/` | Constitución, specs (auth, MVP demo, plan de publicación), taxonomía de Venezuela |
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

**Desplegado hoy:** .NET 8 · Python/FastAPI · React/Vite · Azure PostgreSQL Flexible Server ·
Terraform · Azure Container Apps.

**Objetivo (migración en curso, Enmienda 2.2.0):** .NET 10 LTS unificado (API + IA) ·
React/Vite · Supabase (PostgreSQL + RLS + Auth + Storage) · Terraform · Azure Container Apps.
Justificación de cada elección, y de la transición, en el Art. V de
[`docs/constitution.md`](docs/constitution.md) y en
[`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
