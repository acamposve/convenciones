# Changelog

Todos los cambios notables a este proyecto serán documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- MVP Demo: pipeline ingesta → extracción → segmentación → clasificación (Venezuela)
- API .NET: autenticación JWT, modelo de tenants, usuario demo
- Microservicio Python: OCR, extracción de texto, clasificación por IA (Claude)
- Frontend React: login, carga de documentos (en construcción)
- Taxonomía real de Venezuela: 5 categorías, ~60 títulos
- Estructura multi-tenant: 1 tenant = 1 operador
- `service/db/migrations/012_rls_supabase.sql`: políticas de Row Level Security para Supabase
  (Fase 2.3 de `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`) — escritas y listas, **no
  ejecutadas todavía** contra ningún proyecto real (Fase 2.1, aprovisionar el proyecto, sigue
  pendiente de una acción manual/credenciales que no están disponibles en este entorno)

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
