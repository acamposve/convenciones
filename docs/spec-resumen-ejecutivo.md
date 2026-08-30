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

### A. Pipeline: campo comparativo + resumen ejecutivo ✅ terminado

- [x] Migración: `clausulas.campo_comparativo`, `resumen_ejecutivo`, `estado_revision_resumen`, `revisado_por_resumen`, `revisado_at_resumen` (`service/db/migrations/010_resumen_ejecutivo.sql`)
- [x] `classification.py`: `summarize_clause()` — llamada separada de `classify_clause()` (se necesita saber el título ya asignado antes de pedir el resumen/campo comparativo). `campo_comparativo` se omite del schema de salida cuando la categoría del título no lo requiere (`taxonomia_categorias.requiere_campo_comparacion_economica`), en vez de forzar al modelo a inventarlo
- [x] `_procesar_pipeline()`: integrado después de cumplimiento legal (5 bis); un fallo no bloquea el pipeline, igual que clasificación/cumplimiento legal
- [x] Verificado: schema fresco + migración incremental llegan a la misma estructura contra Postgres real; pipeline en vivo no rompe cuando la clasificación falla (gracia igual que en fases anteriores); `summarize_clause()` verificado con el cliente de Anthropic mockeado (3 tests nuevos en `test_classification.py`: pide campo comparativo cuando la categoría lo requiere, lo omite cuando no, maneja `refusal`) — no se pudo probar el juicio real del modelo en este entorno por no contar con una API key válida (mismo límite que en Fase 4), pendiente antes de producción real
- [ ] Revisión manual de fidelidad con casos reales — sigue pendiente hasta tener una key real

### B. Cola de revisión: aprobar resumen como gesto independiente ✅ terminado

- [x] Backend: `POST /revision/{clausula_id}/aprobar-resumen` y `.../rechazar-resumen` (endpoints separados, no un parámetro en los existentes), gateados igual que hoy (`AdminTenant`/`Revisor`). `aprobar` (clasificación) también gana un `campo_comparativo` opcional para corregirlo en el mismo gesto, igual que ya hacía con `titulo_id`
- [x] `GET /revision`: incluye `estado_revision`, `campo_comparativo`, `resumen_ejecutivo`, `estado_revision_resumen` — y el `WHERE` cambia a mostrar la cláusula mientras **cualquiera** de los dos estados siga `pendiente` (antes solo miraba `estado_revision`, lo que hacía desaparecer la cláusula de la cola en cuanto se aprobaba el título, sin dejar forma de revisar el resumen por separado)
- [x] Frontend (`RevisionPage.jsx`): dos columnas independientes ("Clasificación" y "Resumen ejecutivo"), cada una con sus propios controles editables + Aprobar/Rechazar mientras esté `pendiente`, y un badge de solo lectura una vez resuelta
- [x] Verificado en vivo contra el stack completo: aprobé la clasificación primero → la cláusula siguió en la cola (resumen todavía pendiente), con la columna de clasificación mostrando el badge "Aprobado" y el resumen todavía editable → aprobé el resumen → la cláusula recién ahí desapareció de la cola. Confirmado en Postgres: ambos estados quedaron `aprobado`, cada uno con su propia auditoría (`revisado_at`/`revisado_at_resumen`)

### C. Comparador: resumen colapsado + texto completo desplegable ✅ terminado

- [x] Backend (`GET /comparador`): la consulta incluye `campo_comparativo`, `resumen_ejecutivo`, `estado_revision_resumen`; el `resumen_ejecutivo` se nulifica en el servidor si `estado_revision_resumen != 'aprobado'` (nunca viaja un resumen sin validar) — el gate de publicación sigue siendo solo `estado_revision = 'aprobado'` (Art IV.9, decisión cerrada en §6)
- [x] Frontend (`ComparadorPage.jsx`): si el resumen viene presente (i.e. aprobado), se muestra colapsado junto al `campo_comparativo` como badge, con botón "Ver texto completo"/"Ver resumen" para alternar; si no hay resumen (pendiente o rechazado), se muestra el texto completo directo, sin controles de resumen
- [x] Verificado de punta a punta contra el stack completo (docker compose + uvicorn local en :8010 por el conflicto de puerto con Laragon, ver memoria): se insertaron dos cláusulas de prueba vía SQL directo (título "Vacaciones" aprobado en ambas) — una con `estado_revision_resumen='aprobado'` (se vio colapsada con badge de campo comparativo y botón "Ver texto completo", que al hacer clic despliega el texto original y cambia a "Ver resumen") y otra con `estado_revision_resumen='pendiente'` (se vio con el texto completo directo, sin botón, resumen ausente en la respuesta JSON tal como lo nulifica el backend). Datos de prueba eliminados al terminar
- [x] `npm run build` limpio y `pytest` 25/25 verdes, sin regresiones

## 9. Checklist resumido

- [x] A. Pipeline: campo comparativo + resumen ejecutivo
- [x] B. Cola de revisión: aprobar resumen como gesto independiente
- [x] C. Comparador: resumen colapsado + texto completo desplegable

**Fase 6 completa.** Sigue pendiente, transversal a los tres bloques: la revisión manual de fidelidad del resumen/campo comparativo con casos reales, bloqueada por no contar con una API key de Anthropic válida en este entorno (mismo límite ya señalado en Fase 4) — verificar antes de producción real.

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fases 2-5.)*
