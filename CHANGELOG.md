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

### Changed
- Constitution.md v2.0.0: redefinición de modelo tenant (ahora operador, no empresa única)
- Incorporación de negociación colectiva (Art. IV bis, pendiente Fase 1)

### Removed
- No hay versión anterior en git — este repo comienza con MVP

## Fases futuras

Ver [`docs/spec-mvp-demo.md`](docs/spec-mvp-demo.md) y roadmap en `constitution.md` Art. X.
