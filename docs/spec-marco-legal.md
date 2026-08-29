# Spec — Marco Legal y Verificación de Cumplimiento (Fase 4)

> **Depende de:** `constitution.md` v2.0.0 (Art. II.6, Art. IV.5 bis)
> **Estado:** borrador
> **Objetivo:** corpus de leyes por país, y una señal de cumplimiento sobre cada cláusula
> clasificada — no un panel de consulta pasivo, sino algo que el clasificador usa
> activamente (confirmado explícitamente por Alex).

## 1. Alcance de esta fase

| Incluido | Excluido (fase posterior) |
|---|---|
| Catálogo de leyes por país (ej. Ley Orgánica del Trabajo para Venezuela) y sus artículos | Otras leyes más allá de la ley laboral principal — el legado tenía "otras_leyes" genérico; se deja para cuando haya demanda real de un cliente/país específico |
| Vínculo artículo de ley ↔ título de taxonomía (M:N) | Alertas proactivas si una ley cambia y una cláusula publicada queda desactualizada |
| Verificación de cumplimiento en el paso 5 bis del Art. IV: LLM señala por_debajo / iguala / supera el mínimo legal, con justificación breve | |
| La señal se muestra en la cola de revisión (Fase 2) como dato adicional — **nunca** como aprobación automática | |

## 2. Modelo de datos nuevo

- `leyes` (`pais_id`, `nombre`)
- `articulos_ley` (`ley_id`, `nro_articulo`, `titulo_articulo`, `texto_completo`)
- `titulo_articulo_ley` (`titulo_id`, `articulo_ley_id`) — tabla puente, M:N
- `clausulas.cumplimiento_legal` (enum: `por_debajo` / `iguala` / `supera` / `no_aplica`)
- `clausulas.cumplimiento_justificacion` (texto breve del modelo)

## 3. Aviso legal (Art. XI.6 de la constitución)

Todo lugar de la UI donde se muestre la señal de cumplimiento debe dejar explícito que es
una **asistencia de IA, no asesoría legal** — pendiente confirmar el texto exacto con
asesoría legal real antes de activar esta función comercialmente. Esto no es un detalle de
UX, es una condición para poder vender la función sin exposición legal para Presencia
Virtual.

## 4. Decisiones cerradas

- **Carga del corpus:** ETL desde el legado (`ley_trabajo`/`articulos_ley_trabajo`),
  marcado explícitamente como no validado legalmente (mismo patrón que
  `paises.activo=false` para los países aún no comercialmente activos) — no bloquear esta
  fase con una transcripción manual verificada contra el texto oficial vigente, esa
  validación queda pendiente antes de activar la función comercialmente (ya es el Riesgo
  #6 del Art. XI).
- **Alcance por país:** el esquema es genérico por país (`leyes.pais_id`), pero solo se
  siembra y activa el corpus de Venezuela (LOTTT) por ahora — mismo criterio que
  `taxonomia_titulos`, versionado por país pero con un único país activo en Fase 1
  (Art. I.3).
- **Curaduría del vínculo artículo↔título:** manual — **pero con un hallazgo que cambia el
  punto de partida.** El dump legado (`articulos_ley_trabajo.codigo_titulo_comparativo`)
  ya trae un vínculo artículo→título curado hace más de 20 años: 357 de 555 artículos de
  la LOTTT (28 de los 64 títulos de la taxonomía) tienen un `codigo_titulo_comparativo`
  asignado. Se confirmó por trazabilidad de IDs (`taxonomia_titulos.id` ya son los IDs del
  legado, documentado en `schema.sql`) que ese código apunta directo a
  `taxonomia_titulos.id` sin traducción. Revisé una muestra a mano antes de confiar en
  esto (no es una sugerencia de IA sin validar): título 9 "Aumento de Salario" → Art. 111
  LOTTT "Aumentos salariales"; título 10 "Vacaciones" → Art. 121 "Salario para
  vacaciones" y Art. 190 "Vacaciones"; título 80 "Ambito de Aplicación" → Art. 3 "Ámbito
  de aplicación". Los tres corresponden exactamente. Se adopta ese vínculo legado como
  curaduría inicial (revisada por un humano, no aceptada a ciegas) en vez de construir
  uno desde cero para un subconjunto — los 28 títulos sin vínculo en el legado quedan
  `no_aplica` hasta que se cure el resto a mano.

**Nota de calidad de datos:** el HTML del legado envolvía el texto letra por letra en
`<strong>`/`<font>` (probablemente pegado desde Word hace años), lo que partía palabras a
la mitad si se limpiaba con una sustitución ingenua de tags — corregido en el parser.
Aparte de eso, de ~382.000 caracteres solo 12 (en un único artículo, el 1, que ni siquiera
tiene vínculo a un título) son irrecuperables — un carácter ya reemplazado por "�" en el
propio legado antes de este ETL, no algo que se pueda arreglar reprocesando. Aceptable
para "ETL sin validar".

## 5. Plan de implementación

Tres bloques — A necesita estar cerrado para tener datos que consultar en B, B expone la
señal que C muestra.

### A. Modelo de datos + ETL de la LOTTT

- [ ] Agregar a `schema.sql`: `leyes`, `articulos_ley`, `titulo_articulo_ley`,
  `clausulas.cumplimiento_legal`, `clausulas.cumplimiento_justificacion`
- [ ] Migración incremental `service/db/migrations/008_marco_legal.sql`
- [ ] `docs/ley_lottt_venezuela.json` — corpus curado (parser propio de tuplas SQL, mismo
  enfoque que Fase 2 para el HTML del legado), con el vínculo artículo→título ya incluido
- [ ] `service/db/seed_marco_legal.py` — idempotente, mismo estilo que `seed_taxonomia.py`
- [ ] Bloque condicional en `.github/workflows/deploy-apps.yml`

### B. Verificación de cumplimiento en el pipeline (Art IV.5 bis)

- [ ] `classification.py`: `check_legal_compliance()` — cruza el texto de la cláusula
  contra los artículos vinculados al título ya asignado, devuelve
  `por_debajo`/`iguala`/`supera` + justificación breve (salida estructurada, mismo patrón
  que `classify_clause()`)
- [ ] `_procesar_pipeline()`: después de clasificar, si el título tiene artículos
  vinculados (para el país del tenant) llama a `check_legal_compliance()`; si no,
  `cumplimiento_legal='no_aplica'` sin llamar al modelo (ahorra costo cuando no hay base
  legal para comparar)
- [ ] Nunca bloquea ni reemplaza la aprobación humana (Art IV.8) — es una columna más que
  ve el Revisor, no una puerta

### C. Mostrar la señal (con el aviso legal no negociable)

- [ ] `RevisionPage.jsx` y `DocumentDetail.jsx`: columna/badge de cumplimiento con la
  justificación breve
- [ ] Aviso explícito en la UI (Art. XI.6): "asistencia de IA, no asesoría legal" en cada
  lugar donde se muestre la señal
- [ ] Verificar de punta a punta contra Postgres real: cláusula clasificada con un título
  vinculado → señal de cumplimiento calculada y visible; cláusula con un título sin
  vínculo → `no_aplica` sin llamada extra al modelo

## 6. Checklist resumido

- [ ] A. Modelo de datos + ETL de la LOTTT
- [ ] B. Verificación de cumplimiento en el pipeline
- [ ] C. Mostrar la señal

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fase 2 y 3.)*
