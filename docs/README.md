# Documentación

## Fuente de verdad

**[`constitution.md`](constitution.md)** — Lee esto primero. Define los principios, modelo de datos, seguridad, stack, roadmap y reglas no negociables del proyecto.

## Por tema

### Arquitectura y decisiones
- [`constitution.md`](constitution.md) — Fuente única de verdad (Art. I–XI)
- [`ARCHITECTURE.md`](ARCHITECTURE.md) — Síntesis ejecutiva de arquitectura (tech stack, principios)
- [`spec-plataforma.md`](spec-plataforma.md) — Visión general de características por fase

### Gobernanza del repo
- [`CONTRIBUTING.md`](CONTRIBUTING.md) — Cómo contribuir código
- [`SECURITY.md`](SECURITY.md) — Reporte de vulnerabilidades
- [`CHANGELOG.md`](CHANGELOG.md) — Historial de cambios de alcance/arquitectura

### MVP Demo actual (Venezuela)
- [`spec-mvp-demo.md`](spec-mvp-demo.md) — Alcance exacto: ingesta → clasificación
- [`bootstrap-demo.md`](bootstrap-demo.md) — Cómo correr la demo localmente

### Especificaciones de dominio
- [`spec-marco-legal.md`](spec-marco-legal.md) — Marcos legales de Venezuela, Argentina, Uruguay, Chile
- [`taxonomia_venezuela.json`](taxonomia_venezuela.json) — Taxonomía real: 5 categorías, ~60 títulos
- [`catalogos_empresa_venezuela.json`](catalogos_empresa_venezuela.json) — Catálogos de segmentación (sector, tipo, actividad, geografía)

### Especificaciones de funcionalidad (futuras fases)
- [`spec-negociacion.md`](spec-negociacion.md) — Negociación colectiva (Fase 2, Art. IV bis)
- [`spec-empresas-comparacion.md`](spec-empresas-comparacion.md) — Comparador multi-empresa (Fase 2+)
- [`auth-spec.md`](auth-spec.md) — Autenticación, SSO/SAML, roles (Fase 2)
- [`plan-publish-azure.md`](plan-publish-azure.md) — Despliegue a Azure (cuando sea necesario)

## Cómo navegar

1. **Eres nuevo en el proyecto:** Lee `constitution.md` (30 min) → `spec-mvp-demo.md` (10 min)
2. **Vas a contribuir código:** Lee [`CONTRIBUTING.md`](CONTRIBUTING.md) → constitution.md Art. V (stack) → la spec correspondiente a tu área
3. **Vas a revisar una PR:** Verifica que cumpla constitution.md y la phase spec relevante
4. **Vas a agregar un país:** constitution.md Art. II + `spec-marco-legal.md`, validar con abogado local

## Cambios a la documentación

Cualquier cambio a `constitution.md` require una enmienda formal (ver Art. XI).

Otros documentos (specs de fase, guías) pueden evolucionar, pero:
- Comenta en el PR si contradice constitution.md
- Actualiza [`CHANGELOG.md`](CHANGELOG.md) si es un cambio de alcance/arquitectura

---

**Toda decisión técnica registrada en git debería trazarse a un artículo de esta documentación.**
