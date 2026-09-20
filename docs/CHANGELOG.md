# Changelog

Todos los cambios notables a este proyecto serán documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Enmienda 2.4.0: el MVP interno no usa LLMs, Claude, `Microsoft.Extensions.AI` ni servicios
  externos de IA. El pipeline termina en extracción/OCR y segmentación; las cláusulas quedan
  sin clasificación automática. La clasificación, resumen y cumplimiento legal se difieren
  a una fase posterior.

### Added
- MVP Demo: pipeline ingesta → extracción → segmentación → clasificación (Venezuela)
- API .NET: autenticación JWT, modelo de tenants, usuario demo
- Microservicio Python: OCR, extracción de texto, clasificación por IA (Claude)
- Frontend React: login, carga de documentos (en construcción)
- Taxonomía real de Venezuela: 5 categorías, ~60 títulos
- Estructura multi-tenant: 1 tenant = 1 operador
- Fase 2 completa del plan de migración: proyecto de Supabase aprovisionado (por el usuario),
  `schema.sql` aplicado y verificado (27 tablas, 58 índices, 12 secuencias, enum `rol_usuario`),
  semillas cargadas (taxonomía de Venezuela: 5 categorías/64 títulos; catálogos de empresa;
  marco legal LOTTT: 1 ley/555 artículos/357 vínculos), y
  [`service/db/migrations/012_rls_supabase.sql`](../service/db/migrations/012_rls_supabase.sql)
  (RLS) aplicado y verificado contra la base real — RLS activo en las 9 tablas de tenant
  (incluidas las 5 hijas de `negociaciones`, sin `tenant_id` propio, vía subquery), biblioteca
  pública expuesta como vista de solo lectura en `schema.sql` (`biblioteca_publica`, no como
  política sobre `documentos`; `GET /biblioteca` en `main.py` ya la consulta a ella en vez de
  las tablas directo), confirmado con un test de aislamiento real (`SET ROLE anon`) y que no
  rompe las conexiones actuales (el rol de conexión tiene `rolbypassrls`)
- Fase 3.1 del plan de migración: `api/` corre sobre **.NET 10 LTS** — `TargetFramework`,
  paquetes NuGet (`Microsoft.AspNetCore.Authentication.JwtBearer`, `Npgsql.EntityFrameworkCore.PostgreSQL`,
  `Microsoft.EntityFrameworkCore.Design`, `EFCore.NamingConventions`) a la última versión 10.x
  publicada, `Microsoft.Extensions.AI` 10.10.0 instalado (sin conectar, Fase 4), `Dockerfile`
  actualizado a las imágenes base `:10.0`. Verificado con `dotnet build`/`dotnet test` (7/7)
  y con un smoke test en vivo (`dotnet run` apuntando a la base Supabase real vía variables de
  entorno, sin tocar `appsettings.json`) — encontró un hallazgo real para Fase 3.2 (diagnosticado
  y con fix probado, pero no aplicado a código todavía — ver el checklist de Fase 3.2 en
  `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`): doble pooling (Npgsql + Supavisor) cuelga el
  segundo comando de una misma request contra Supabase; `Pooling=false` en la cadena de
  conexión lo resuelve.

### Fixed
- `service/app/db.py` y los 4 scripts de `service/db/seed_*.py`: conectar contra Supabase vía
  el pooler Supavisor (modo *transaction*, puerto 6543) tiraba
  `psycopg.errors.DuplicatePreparedStatement` — psycopg3 usa prepared statements server-side
  por default, y el pooler reparte cada query entre conexiones físicas distintas por detrás.
  Se agregó `prepare_threshold=None` a las 5 llamadas a `psycopg.connect(...)`. Sin este fix
  el servicio no puede operar contra Supabase en absoluto, no solo los seeds.

### Changed
- Constitution.md v2.0.0: redefinición de modelo tenant (ahora operador, no empresa única)
- Incorporación de negociación colectiva (Art. IV bis, pendiente Fase 1)
- Constitution.md v2.2.0: se adopta el plan de migración de stack descrito en
  `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md` — Art. V pasa de (API .NET + microservicio
  Python/FastAPI + Azure PostgreSQL Flexible Server) a (C#/.NET 10 LTS unificado + Supabase
  con Row Level Security). Decisión de documentación únicamente por ahora — el código y la
  infraestructura desplegada siguen siendo los anteriores hasta el cutover (Fase 5 del plan).

### Deprecated
- Microservicio Python/FastAPI (`service/`) y Azure PostgreSQL Flexible Server autoadministrado:
  reemplazados en el stack objetivo por .NET 10 unificado y Supabase respectivamente (Enmienda
  2.2.0). Siguen en producción hasta el cutover; su eliminación real está planeada para la
  Fase 5.3-5.4 del plan de migración.

### Removed
- No hay versión anterior en git — este repo comienza con MVP
- Constitution.md v2.3.0: se retira Azure como proveedor de infraestructura. Se elimina
  `infra/terraform/` completo, los workflows de GitHub Actions que desplegaban ahí
  (`terraform.yml`, `deploy-apps.yml`) y el soporte a Azure Blob Storage en
  `service/app/storage.py` (queda solo el fallback a disco local). Motivo: el ACR del demo
  quedó en estado `REGISTRY_NOT_READY` no atribuible a permisos ni configuración. Efecto
  inmediato: no hay ningún ambiente desplegado en la nube — el proyecto corre solo local
  hasta que se elija un proveedor nuevo.

## Fases futuras

Ver [`spec-mvp-demo.md`](spec-mvp-demo.md) y roadmap en `constitution.md` Art. X.
