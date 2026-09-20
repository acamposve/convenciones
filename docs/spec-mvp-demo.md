# Spec — MVP Demo (Venezuela, sin IA)

> **Depende de:** `constitution.md` v2.4.0
> **Estado:** borrador de demo interna, no reemplaza los specs de fase completa (ingesta, análisis, categorización)
> **Objetivo:** mostrar el pipeline determinista de ingesta → extracción/OCR → segmentación funcionando end-to-end sobre datos reales, sin llamadas a modelos ni servicios de IA.

## 1. Alcance de esta demo

Recorte deliberado de la Fase 1 (Art. X) para poder mostrar algo funcional hoy. Todo lo que no se menciona aquí **no está incluido** y no debe asumirse implementado.

| Incluido | Excluido (fase posterior) |
|---|---|
| Alta de tenant = alta de empresa, país fijo Venezuela | Selector de país / multi-país por tenant |
| Ingesta por carga de archivo **y** por URL | — |
| Extracción de texto (PDF nativo, Word; OCR si escaneo) | — |
| Segmentación en cláusulas/artículos | — |
| Persistencia de cláusulas segmentadas sin clasificación automática | Clasificación automática, score de confianza y cola de revisión |
| — | Extracción de campo comparativo (Art. IV.6) → Fase 2 |
| — | Publicación / vista web de reporte (Art. IV.9) |
| — | SSO/SAML, licenciamiento, multi-tenant real más allá del filtro por `tenant_id` |

**El pipeline se detiene explícitamente después de la segmentación (Art. IV, paso 4).** La excepción temporal está registrada en la enmienda 2.4.0 de `constitution.md`: no hay clasificación automática, score, revisión ni publicación en este MVP.

## 2. Regla de negocio confirmada (no tocar sin enmienda)

Por decisión explícita, esta demo **mantiene el Art. VI.1 sin cambios**: documentos privados por defecto, públicos solo si el usuario lo declara explícitamente. No se propuso enmienda; cualquier futura decisión de invertir este default requiere actualizar `constitution.md` con la razón registrada.

## 3. Modelo de tenant para esta demo

Confirmado contra Art. I.3 (1 tenant = 1 empresa/firma en 1 país): la activación es por **empresa**, no por país. El país queda fijo en Venezuela como atributo del tenant, sin UI de selección — no porque el modelo lo prohíba, sino porque activar más países es Fase 2. Cada país sigue siendo independiente entre sí (una misma empresa con convenciones en dos países son tenants distintos), tal como confirmaste.

## 4. Pipeline (referencia a Art. IV)

1. **Ingesta** (Art. IV.1) — carga de archivo (PDF/Word) o URL.
2. **Check público/privado** (Art. IV.2, Art. VI.1) — privado por defecto; si se declara público vía URL, validar accesibilidad sin autenticación antes de tratarlo como tal.
3. **Extracción de texto** (Art. IV.3) — parseo nativo o OCR según corresponda.
4. **Segmentación** (Art. IV.4) — división en artículos/cláusulas individuales. Debe soportar fallback para documentos sin estructura clara (heurística mínima: numeración, encabezados en mayúsculas, o similar — a definir en implementación).
5. **Clasificación** — fuera del MVP. Las cláusulas se persisten con `titulo_id` nulo para revisión o clasificación en una fase posterior.

**Salida esperada de la demo:** por cada documento cargado, una lista de cláusulas con su texto, orden y estado pendiente. Sin llamadas a IA, sin título automático, sin score, sin revisión y sin publicación.

## 5. Corpus de prueba

Se usa el dataset del sistema legado según Art. IV (nota) y Art. IX.2 (403 PDFs venezolanos + ~6.400 artículos clasificados). El derecho de reutilización sigue pendiente de confirmación (Art. XI.2) — no bloqueante para esta demo interna, pero si esta demo se muestra fuera del equipo o a un cliente, ese punto debe resolverse antes.

## 6. Criterio de éxito

La demo es exitosa si extrae y segmenta correctamente un subconjunto de documentos del corpus legado, conserva el texto original de cada cláusula y deja el documento en un estado observable. La validación de clasificación queda fuera del MVP.

## 7. Fuera de alcance explícito (recordatorio)

Clasificación automática, score de confianza, cola de revisión, campo comparativo, resumen, cumplimiento legal, publicación/reporte web, multi-país, licenciamiento, SSO y todo lo demás de Fase 2/3 según Art. X.
