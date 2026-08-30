# Spec — Resumen Ejecutivo y Campo Comparativo (Fase 6)

> **Depende de:** `constitution.md` v2.1.0 (Art. II.6, Art. IV.6/6 bis/8/9)
> **Estado:** borrador
> **Origen:** reunión con Luis Villegas (especialista de dominio), 2026-08-29 —
> `docs/transcripcion_reunion_convenciones_ia.md` (ver también el resumen de la reunión
> más abajo en §7).
> **Objetivo:** que un ejecutivo pueda comparar cláusulas de un vistazo ("Banco de
> Venezuela: 15 días hábiles de vacaciones + 1 bono anual") sin tener que leer el texto
> legal completo de cada una, con la opción de desplegar el original cuando lo necesite.

## 1. Alcance de esta fase

| Incluido | Excluido |
|---|---|
| Resumen ejecutivo por cláusula (IA), fiel al texto original, sin interpretar | Biblioteca pública (Fase 7 — depende de esto solo en que comparte el pipeline, no hay acoplamiento real) |
| Campo comparativo (Art. IV.6, ya diseñado desde el MVP original, nunca implementado) | Taxonomía por país (Fase 8) |
| Revisión humana del resumen, **independiente** de la revisión de la clasificación | Módulo de costeo económico (pospuesto explícitamente por el cliente) |
| Comparador: resumen colapsado + texto completo desplegable | |

## 2. Modelo de datos nuevo/modificado

- `clausulas.campo_comparativo` (`TEXT`, nullable) — valor normalizado cuando el título lo requiere (ej. "15 días hábiles", "30% del salario"). Comparte el estado de revisión de la clasificación (`estado_revision`) — no tiene aprobación propia (a diferencia del resumen, ver más abajo); si se necesita separarlo más adelante, es un cambio incremental.
- `clausulas.resumen_ejecutivo` (`TEXT`, nullable) — síntesis breve generada por IA.
- `clausulas.estado_revision_resumen` (`TEXT NOT NULL DEFAULT 'pendiente'`, mismo enum que `estado_revision`: `pendiente`/`aprobado`/`rechazado`) — **estado independiente**, decisión cerrada en §6.
- `clausulas.revisado_por_resumen` (`UUID REFERENCES usuarios(id)`), `clausulas.revisado_at_resumen` (`TIMESTAMPTZ`) — auditoría propia, espejo de `revisado_por`/`revisado_at` pero para el gesto de aprobar el resumen.

## 3. Pipeline (Art. IV, extiende lo existente)

Se integra en `_procesar_pipeline()` (`service/app/main.py`) después del paso 5 bis (cumplimiento legal) y junto al paso 6:

1. Clasificación (5) → título asignado.
2. Cumplimiento legal (5 bis) → sin cambios.
3. **Campo comparativo (6)** — si el título lo requiere (ver `taxonomia_titulos` — hoy no existe un flag para esto, hay que agregarlo o inferirlo por categoría `requiere_campo_comparacion_economica` ya existente en `taxonomia_categorias`), un llamado a Claude extrae el valor normalizado.
4. **Resumen ejecutivo (6 bis)** — llamado a Claude separado (o combinado con el paso 3 en la misma llamada de salida estructurada, a decidir en implementación — ver Bloque A) que redacta la síntesis. Restricción dura de prompt: **no interpretar, no agregar contenido ausente del texto original** — esto es un requisito de producto explícito del cliente, no un detalle de estilo.
5. Ambos quedan en estado `pendiente` hasta revisión humana — **por separado** (ver §6).

## 4. Permisos (extiende `auth-spec.md` §5)

Reusa exactamente la matriz existente de la cola de revisión (`AdminTenant`/`Revisor` pueden aprobar-corregir, Art. IV.8) — no se agregan roles nuevos. Lo que cambia es la **cantidad de gestos**: hoy `POST /revision/{id}/aprobar` aprueba título+campo comparativo; se agrega `POST /revision/{id}/aprobar-resumen` (o similar) como acción independiente, con el mismo candado de rol.

## 5. Comparador (Art. IV.9)

- Se sigue publicando solo por `estado_revision='aprobado'` (clasificación) — **sin cambios en el gate de publicación** (decisión cerrada, §6).
- Si además `estado_revision_resumen='aprobado'`: la vista muestra el resumen colapsado, con un control para desplegar el texto completo original.
- Si el resumen todavía no está aprobado (o fue rechazado): se muestra directamente el texto completo, nunca un resumen sin validar.

## 6. Decisiones cerradas

- **Campo comparativo y resumen ejecutivo son dos campos separados** — no se fusionan en uno solo, aunque conceptualmente los dos "resumen la cláusula para lectura rápida". El campo comparativo es el valor normalizado (Art. IV.6, ya diseñado desde el origen); el resumen ejecutivo es una síntesis en lenguaje natural más amplia (nueva, de esta reunión).
- **El resumen ejecutivo tiene su propio estado de revisión**, independiente del de la clasificación — puede pasar que el título esté bien pero el resumen necesite ajuste, o viceversa; un Revisor puede aprobar uno sin el otro, en cualquier orden.
- **La publicación en el comparador depende solo de que la clasificación esté aprobada** (sin cambios respecto al Art. IV.9 actual) — la aprobación del resumen no bloquea que la cláusula se pueda comparar, solo decide si se muestra el resumen o el texto completo directamente.
- **Restricción de fidelidad del resumen** (la puso el cliente explícitamente, no es un detalle de implementación menor): el resumen no puede interpretar, opinar, ni agregar contenido que no esté literalmente en el texto original de la cláusula. Esto condiciona el prompt del paso 6 bis y debe verificarse con casos reales antes de dar la fase por cerrada, igual de riguroso que la verificación de fidelidad que ya se hizo para el marco legal (Fase 4).

## 7. Preguntas abiertas

Ninguna pendiente — las de la reunión (§6 de la respuesta original) quedaron resueltas con Alex.

## 8. Plan de implementación

Tres bloques — A es el pipeline (necesita estar antes de B y C), B es la cola de revisión extendida, C es el comparador.

### A. Pipeline: campo comparativo + resumen ejecutivo

- [ ] Migración: `clausulas.campo_comparativo`, `resumen_ejecutivo`, `estado_revision_resumen`, `revisado_por_resumen`, `revisado_at_resumen`
- [ ] `classification.py`: función(es) para campo comparativo y resumen ejecutivo — decidir en implementación si van en la misma llamada estructurada que la clasificación (menos costo/latencia, mismo patrón que `confianza`) o en llamadas separadas (más control, más costo)
- [ ] `_procesar_pipeline()`: integrar el/los paso(s) nuevo(s), guardando ambos campos con `estado_revision_resumen='pendiente'` por defecto
- [ ] Verificar con Postgres real + casos de cláusulas reales: el resumen no debe desviarse del texto original (revisión manual de una muestra antes de dar por buena la fase, mismo criterio que se usó para el marco legal)

### B. Cola de revisión: aprobar resumen como gesto independiente

- [ ] Backend: `POST /revision/{clausula_id}/aprobar-resumen` y `.../rechazar-resumen` (o extender los existentes con un parámetro — a decidir en implementación), gateados igual que hoy (`AdminTenant`/`Revisor`)
- [ ] `GET /revision`: incluir `campo_comparativo`, `resumen_ejecutivo`, `estado_revision_resumen` en la respuesta
- [ ] Frontend (`RevisionPage.jsx`): mostrar resumen propuesto + campo comparativo, con su propio botón de aprobar/rechazar separado del de clasificación
- [ ] Verificar: aprobar clasificación sin aprobar resumen queda visible en la cláusula sin bloquear nada; aprobar resumen sin clasificación aprobada no publica igual (Art IV.9 sigue dependiendo solo de clasificación)

### C. Comparador: resumen colapsado + texto completo desplegable

- [ ] Backend (`GET /comparador`): incluir `resumen_ejecutivo`/`estado_revision_resumen`/`campo_comparativo` en la respuesta
- [ ] Frontend (`ComparadorPage.jsx`): si `estado_revision_resumen='aprobado'`, mostrar resumen colapsado con control para desplegar texto completo; si no, mostrar texto completo directo
- [ ] Verificar de punta a punta: una cláusula con clasificación aprobada y resumen aprobado se ve colapsada; una con clasificación aprobada pero resumen pendiente se ve con el texto completo, sin romper nada

## 9. Checklist resumido

- [ ] A. Pipeline: campo comparativo + resumen ejecutivo
- [ ] B. Cola de revisión: aprobar resumen como gesto independiente
- [ ] C. Comparador: resumen colapsado + texto completo desplegable

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fases 2-5.)*
