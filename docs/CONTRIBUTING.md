# Guía de Contribución

## Antes de contribuir

**Lee primero:** [`constitution.md`](constitution.md) — es la fuente única de verdad del proyecto.

Cualquier cambio técnico o de producto que contradiga la constitución requiere:
1. Comentar explícitamente la contradicción
2. Proponer una enmienda a la constitución
3. Registrar la razón de la enmienda en el documento

> **⚠️ Migración de stack en curso (Enmienda 2.2.0 de la constitución):** el proyecto migra
> a C#/.NET 10 unificado + Supabase, según
> [`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
> La estructura y comandos de abajo describen el stack **desplegado hoy** (`service/`
> sigue activo); no se ha portado código todavía. Ver Art. V de `constitution.md` para el
> stack objetivo.

## Estructura del repo

| Carpeta | Qué es |
|---|---|
| `api/` | API .NET 8 — autenticación, datos, tenants. Destino final del microservicio Python tras el cutover (plan de migración, Fase 3-5) |
| `service/` | Microservicio Python/FastAPI — IA, extracción, segmentación, clasificación. El plan de migración prevé eliminarlo (Fase 5.4) |
| `web/` | Frontend React + Vite |
| `infra/terraform/` | IaC — Azure Container Apps, PostgreSQL, ACR, Storage. El Postgres administrado se elimina en Fase 5.3 del plan de migración (reemplazado por Supabase) |
| `docs/` | Documentación: constitution, specs, taxonomías, marcos legales |
| `db/` | Scripts SQL: schema, seeds, fixtures de prueba |
| `legacy/` | SaaS PHP legado — referencia arquitectónica, **no se porta código de aquí** |
| `.github/workflows/` | CI/CD: build, test, plan/apply Terraform, deploy |

## Fases del proyecto

| Fase | Alcance | Estado |
|---|---|---|
| **MVP Demo** | Ingesta → Extracción → Segmentación → Clasificación (Venezuela) | En curso |
| **Fase 1** | Revisión humana, cola de revisión, score de confianza, campo comparativo | Planeada |
| **Fase 2+** | Multi-país, licenciamiento, SSO, publicación, reportes web | Planeada |

Ver [`spec-mvp-demo.md`](spec-mvp-demo.md) para el alcance exacto de hoy.

## Desarrollo local

```bash
# Base de datos + API + frontend
cd service
docker compose up --build

# Microservicio de IA (en otra terminal)
cd service
pip install -r requirements.txt
uvicorn app.main:app --reload --port 8000
```

Credenciales de demo en `docker compose logs seed`.

## Código

### Python (service/)
- Style: Black (100 chars), Ruff
- Tests: pytest
- Tipos: mypy

### C# (api/)
- Style: Roslyn analyzers (StyleCop Analyzers)
- Tests: xUnit
- Nullable reference types obligatorio

### TypeScript/React (web/)
- Style: Prettier (80 chars)
- Linting: ESLint
- Testing: Vitest (cuando haya tests)

## Commits y PRs

**Commits:** sigue Conventional Commits.
- `feat:` nueva funcionalidad
- `fix:` corrección de bug
- `docs:` cambios de documentación
- `refactor:` sin cambios funcionales
- `test:` tests
- `chore:` build, dependencies, config

**PRs:**
- Referencia el issue que resuelve o el artículo de constitution.md que implementa
- Incluye cambios de documentación si corresponde
- CI/CD debe pasar

## Contacto y escaladas

Si un cambio que necesitas implementar contradice la constitución y no sabes cómo proceder, abre un issue explicando:
1. Qué necesitas implementar
2. Qué artículo de constitution.md contradice
3. Por qué crees que ese artículo debe cambiar

No hagas el cambio hasta tener acuerdo.
