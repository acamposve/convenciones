# Fases del proyecto

## Resumen ejecutivo

| Fase | Nombre | Alcance | Estado |
|---|---|---|---|
| MVP | Demo Venezuela | Ingesta → Clasificación (Art. IV, pasos 1-5) | ✅ En curso |
| 1 | Revisión + Publicación | Cola de revisión, score, campo comparativo, reporte web (Art. IV.6-9) | 📋 Planeada |
| 2 | Multi-país + Autenticación | Selector de país, SSO/SAML, roles, licenciamiento | 📋 Planeada |
| 3 | Negociación colectiva | Pre-firma: peticiones, ofertas, reuniones, acuerdos (Art. IV bis) | 📋 Planeada |
| 4+ | Marketplace y análisis | Integración con terceros, reportes avanzados, benchmarking | 🔮 Backlog |

**Tiempo estimado:** MVP (4-8 semanas) → Fase 1 (8-12 semanas) → Fase 2+ (12+ semanas).

## MVP Demo (Fase actual)

**Objetivo:** Mostrar el pipeline de IA funcionando end-to-end sobre datos reales.

**Incluido:**
- ✅ Alta de tenant (empresa) — Venezuela fijo
- ✅ Ingesta: carga de archivo (PDF/Word) + URL
- ✅ Extracción de texto (nativa + OCR)
- ✅ Segmentación en cláusulas
- ✅ Clasificación por IA (Claude) contra taxonomía real de Venezuela

**Excluido (Fase 1):**
- Score de confianza
- Cola de revisión
- Campo comparativo (normalización numérica)
- Publicación / reporte web
- Verificación de cumplimiento legal (Art. IV.5 bis)

**Documentación:** [`docs/spec-mvp-demo.md`](spec-mvp-demo.md)

---

## Fase 1: Revisión humana y publicación

**Objetivo:** Cerrar el pipeline obligatorio: revisión interna + publicación + reporte web.

**Incluido:**
- Cola de revisión con cláusulas ordenadas por score de confianza
- Interfaz de revisión: validar/corregir clasificación, resumen, campo comparativo
- **[Nuevo]** Verificación de cumplimiento legal (Art. IV.5 bis): señal de si la cláusula está por debajo, iguala, o supera el mínimo legal
- Publicación: cláusula aprobada → visible en reporte web
- Reporte web navegable: filtros por categoría, título, empresa, sector, rango de valor

**Documentación:** `spec-fase1.md` (a crear)

---

## Fase 2: Multi-país + SSO

**Objetivo:** Activar más países y autenticación empresarial.

**Incluido:**
- Selector de país: tenant puede operar en múltiples países
- Taxonomía por país (versionada, Art. II.3)
- Marco legal por país: validación de cumplimiento contra leyes locales
- SSO/SAML: integración con proveedores (WorkOS, Auth0)
- Licenciamiento: planes, límites de usuarios/documentos/países
- Roles avanzados: Admin Tenant, Revisor, Editor, Visualizador

**Excluido:**
- Negociación colectiva (Fase 3)

**Documentación:** `spec-fase2.md` + `auth-spec.md` (parcialmente)

---

## Fase 3: Negociación colectiva (pre-firma)

**Objetivo:** Agregar el flujo de negociación que el legado tenía (módulo "discusión").

**Incluido:**
- Registro de peticiones (sindicato) y ofertas (empresa)
- Sesiones de negociación (reuniones)
- Acuerdos sobre cláusulas
- Generación automática de Documento al cerrar negociación
- Integración con el pipeline de publicación (Art. IV bis)

**Documentación:** [`docs/spec-negociacion.md`](spec-negociacion.md)

---

## Fase 4+: Marketplace y análisis

**Objetivo:** Insights, benchmarking, integración con terceros.

**Posibilidades:**
- Comparador multi-empresa: filtrar por sector, tipo, actividad, geografía
- Reportes de tendencias (cláusulas cada vez más frecuentes)
- API pública para integraciones
- Integración con plataformas de negociación colectiva

**Estado:** Backlog — a priorizar con producto y clientes.

---

## Criterios de salida por fase

### MVP
- [ ] Pipeline completo: ingesta → clasificación, sin errores bloqueantes
- [ ] Validación de precisión: LLM coincide razonablemente con clasificación legada (subconjunto de datos)
- [ ] Documentación de arquitectura (constitution.md v2.0.0)
- [ ] Demo interna funcional

### Fase 1
- [ ] Interfaz de revisión: 100% funcional
- [ ] Verificación de cumplimiento legal: correcta según marcos
- [ ] Reporte web: navegable, filtros completos
- [ ] Tests: cobertura >80% en lógica de publicación

### Fase 2
- [ ] Selector de país funcionando con ≥2 taxonomías
- [ ] SSO integrado (≥1 proveedor)
- [ ] Licenciamiento: límites aplicados, facturación lista

### Fase 3
- [ ] Flujo de negociación completo (petición → acuerdo → documento)
- [ ] Integración con pipeline de Fase 1
- [ ] Tests de negociación: cobertura >80%

---

**Roadmap completo en [`constitution.md`](constitution.md) Art. X.**
