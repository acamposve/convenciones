# Guía de Contribución

## Antes de contribuir

**Lee primero:** [`constitution.md`](constitution.md) — es la fuente única de verdad del proyecto.

Cualquier cambio técnico o de producto que contradiga la constitución requiere:
1. Comentar explícitamente la contradicción
2. Proponer una enmienda a la constitución
3. Registrar la razón de la enmienda en el documento

> **⚠️ Migración de stack (Enmienda 2.2.0/2.5.0 de la constitución):** el backend ya es
> C#/.NET 10 unificado, según
> [`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)
> — el microservicio Python (`service/`) se eliminó del repositorio. Falta migrar la base
> de datos a Supabase (sigue siendo PostgreSQL local). Ver Art. V de `constitution.md`.

> **⚠️ Se eliminó la infraestructura de Azure** (`infra/terraform/`, los workflows de deploy):
> el proyecto va a redesplegar a otro proveedor, todavía sin decidir. Hoy no hay ningún
> ambiente en la nube activo — solo desarrollo local.

## Estructura del repo

| Carpeta | Qué es |
|---|---|
| `api/` | API única en .NET 10 LTS — autenticación, datos, tenants, negocio, ingesta/OCR/segmentación deterministas |
| `web/` | Frontend React + Vite |
| `docs/` | Documentación: constitution, specs, taxonomías, marcos legales |
| `db/` | Scripts SQL: schema, seeds, fixtures de prueba |
| `legacy/` | SaaS PHP legado — referencia arquitectónica, **no se porta código de aquí** |
| `.github/workflows/` | CI: build + test de API y frontend (`ci.yml`). Sin deploy |

## Fases del proyecto

| Fase | Alcance | Estado |
|---|---|---|
| **MVP Demo** | Ingesta → Extracción → Segmentación → Clasificación (Venezuela) | En curso |
| **Fase 1** | Revisión humana, cola de revisión, score de confianza, campo comparativo | Planeada |
| **Fase 2+** | Multi-país, licenciamiento, SSO, publicación, reportes web | Planeada |

Ver [`spec-mvp-demo.md`](spec-mvp-demo.md) para el alcance exacto de hoy.

## Desarrollo local

```bash
docker compose up --build
```

Credenciales de demo en `docker compose logs seed`. Guía completa en
[`bootstrap-demo.md`](bootstrap-demo.md).

## Código

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
