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

## 4. Preguntas abiertas

- ¿Quién carga el corpus de leyes inicialmente? ¿ETL desde el legado (`ley_trabajo`,
  `otras_leyes`), o transcripción/carga manual nueva verificada contra el texto oficial?
- La verificación de cumplimiento, ¿corre solo para Venezuela mientras las otras 3
  jurisdicciones no estén activas comercialmente (Art. II.4), o se prepara desde ya para
  las 4?
- ¿El vínculo artículo-de-ley ↔ título lo arma un humano una vez por país (curación
  manual), o se sugiere con IA y se valida?
