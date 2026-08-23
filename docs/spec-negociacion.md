# Spec — Negociación Colectiva (Fase 3)

> **Depende de:** `constitution.md` v2.0.0 (Art. IV bis), `spec-empresas-comparacion.md`
> (Fase 2 — requiere que exista la entidad Empresa)
> **Estado:** borrador
> **Objetivo:** llevar al sistema nuevo el módulo `discusion` del legado (peticiones,
> ofertas, reuniones, acuerdos), que documenta una negociación colectiva **antes** de que
> exista un documento firmado.

## 1. Alcance de esta fase

| Incluido | Excluido (fase posterior o fuera de alcance) |
|---|---|
| Negociación por Empresa (estado abierta/cerrada) | Notificaciones/calendario de reuniones — por ahora es solo registro |
| Petición (sindicato) por título de taxonomía | Firma digital del documento generado al cerrar |
| Oferta (empresa) respondiendo una petición | Reapertura de una negociación cerrada (addendum) — ver pregunta abierta |
| Reunión (fecha, asistentes, resumen) | |
| Acuerdo (cuando petición + oferta convergen para un título) | |
| Cierre de negociación → genera Documento y entra al pipeline del Art. IV | |

## 2. Modelo de datos nuevo

- `negociaciones` (`empresa_id`, `estado`, `fecha_inicio`, `fecha_cierre`)
- `peticiones` (`negociacion_id`, `titulo_id`, `texto`, `nro_peticion`)
- `ofertas` (`peticion_id`, `texto`)
- `reuniones` (`negociacion_id`, `fecha`, `asistentes`, `resumen`)
- `acuerdos` (`negociacion_id`, `titulo_id`, `texto_acordado`, `peticion_id`, `oferta_id`)
- `bitacora_negociacion` (`negociacion_id`, `evento`, `usuario_id`, `created_at`) —
  **distinta** de `bitacora_accesos` (Art. III de la constitución)

## 3. Flujo de cierre → Documento

Al cerrar una negociación, el sistema arma un documento sintético a partir de los acuerdos
(uno por título), lo persiste igual que un Documento cargado (mismo `storage.guardar`,
Art. V), y dispara el pipeline desde clasificación en adelante — ya viene segmentado por
título, así que el paso de segmentación (Art. IV.4) probablemente no aplica en este flujo
(ver pregunta abierta).

## 4. Permisos

A confirmar junto con `auth-spec.md` §5. Propuesta inicial: Editor/AdminTenant pueden
cargar peticiones/ofertas/reuniones; el **cierre** queda restringido a AdminTenant, porque
es una acción irreversible que genera un documento oficial.

## 5. Preguntas abiertas

- ¿El texto acordado de cada título lo redacta un humano en el momento del acuerdo, o el
  sistema sugiere una redacción a partir de la oferta final (con LLM, revisable antes de
  aceptar)?
- ¿Puede reabrirse una negociación cerrada (ej. addendum posterior)? Si sí, ¿genera un
  nuevo Documento o modifica el existente?
- ¿La segmentación (Art. IV.4) se salta cuando el documento viene de un cierre de
  negociación (ya está segmentado por título), o igual pasa por el extractor para
  consistencia con documentos cargados directamente?
