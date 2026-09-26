# Comparador de Convenciones Colectivas de Trabajo

Sistema que automatiza la lectura, clasificación y extracción de cláusulas de convenciones
colectivas de trabajo mediante IA — reemplaza un SaaS PHP legado que hacía este trabajo
100% manual. **Fase actual: MVP demo Venezuela** (ver alcance exacto en
[`docs/spec-mvp-demo.md`](docs/spec-mvp-demo.md)).

> **Toda decisión de arquitectura, alcance y reglas duras del proyecto vive en
> [`docs/constitution.md`](docs/constitution.md) — es la fuente de verdad.** Si algo en este
> README contradice la constitución, la constitución gana.

> **Migración de stack (Enmienda 2.2.0 de la constitución):** el backend ya es
> **C#/.NET 10 LTS unificado** — el microservicio Python (`service/`) se eliminó del
> repositorio (Fase 5.2 cutover + Fase 5.4 limpieza de
> [`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md);
> el nombre del archivo es heredado — el destino es Supabase, no Firebase). El proyecto
> Supabase real ya tiene el esquema aplicado y RLS validado (Fase 2) y la conexión es
> configurable (Fase 3.2), pero no se opera así por default: el entorno local sigue
> apuntando a Postgres local a propósito, y el storage de documentos sigue en disco local
> — ver nota de abajo.

> **Se eliminó la infraestructura de Azure (Terraform, deploy a Container Apps):** el demo
> dejó de estar desplegado en la nube — el equipo va a redesplegar a otro proveedor, todavía
> **sin decidir** (Enmienda 2.3.0). Hasta que eso se defina, el proyecto solo corre
> localmente ([`docs/bootstrap-demo.md`](docs/bootstrap-demo.md)); `infra/` ya no existe en
> el repo, y la Fase 5.3 original del plan de migración (`terraform apply` para destruir el
> contenedor Python en Azure) no aplica más — no hay Terraform ni Azure de qué apagar.

## Estructura del repo

| Carpeta | Qué es |
|---|---|
| `api/` | API única en .NET 10 LTS — auth, tenants/usuarios/roles, negocio, documentos, ingesta/OCR/segmentación deterministas. Único backend del proyecto (el microservicio Python que existía en `service/` se eliminó en la Fase 5.4 del plan de migración) |
| `web/` | Frontend, React + Vite |
| `db/` | Schema (`schema.sql`), migraciones versionadas y seeds de desarrollo — compartido por `docker-compose.yml`, no específico de ningún componente |
| `.github/workflows/` | CI: build + test de API y frontend (`ci.yml`). Sin deploy — ver nota de arriba |
| `docs/` | Constitución, specs (auth, MVP demo), taxonomía de Venezuela |
| `legacy/` | SaaS PHP original — solo como referencia funcional (Art. IX de la constitución), no se porta código de acá |

## Cómo correr el pipeline localmente

Guía completa en [`docs/bootstrap-demo.md`](docs/bootstrap-demo.md). Resumen:

```bash
docker compose up --build
```

Con eso: `docker compose up` crea el schema, siembra un tenant + usuario `AdminTenant` de
demo (ver credenciales impresas en `docker compose logs seed`), y dejás listo el login en
`http://localhost:5173/login`.

## Stack

**Desplegado hoy:** ningún ambiente en la nube activo — el proyecto corre local (Docker
Compose). Componentes: .NET 10 LTS (API + procesamiento determinista unificados) ·
React/Vite · PostgreSQL local.

**Objetivo (migración en curso, Enmienda 2.2.0):** .NET 10 LTS unificado (ya aplicado) ·
React/Vite · Supabase (PostgreSQL + RLS + Auth + Storage — todavía pendiente, sigue siendo
PostgreSQL local hoy). Infraestructura/proveedor de deploy todavía sin decidir — se eliminó
Azure (Terraform, Container Apps); contenedores Docker siguen siendo el empaquetado, pero el
destino se define aparte.
Justificación de cada elección, y de la transición, en el Art. V de
[`docs/constitution.md`](docs/constitution.md) y en
[`PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
