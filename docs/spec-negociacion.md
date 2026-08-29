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
| Reapertura de una negociación cerrada (addendum), con versionado del Documento | |

## 2. Modelo de datos nuevo

- `negociaciones` (`tenant_id`, `empresa_id`, `estado` [abierta/cerrada], `fecha_inicio`, `fecha_cierre`)
- `peticiones` (`negociacion_id`, `titulo_id` nullable, `nro_peticion`, `texto`)
- `ofertas` (`peticion_id`, `texto`)
- `reuniones` (`negociacion_id`, `fecha`, `asistentes`, `resumen`)
- `acuerdos` (`negociacion_id`, `titulo_id`, `texto_acordado`, `peticion_id` nullable, `oferta_id` nullable)
- `bitacora_negociacion` (`negociacion_id`, `evento`, `usuario_id`, `detalle`, `created_at`) —
  **distinta** de `bitacora_accesos` (Art. III de la constitución)
- `documentos.negociacion_id` (FK nueva, nullable), `documentos.version_negociacion`
  (nullable) — solo se llenan cuando el Documento se generó desde un cierre; `origen` gana
  el valor `'negociacion'` junto a los existentes `'archivo'`/`'url'`

## 3. Flujo de cierre → Documento

Al cerrar (o re-cerrar tras una reapertura), el sistema toma el acuerdo **más reciente**
por título dentro de la negociación, arma un `.docx` sintético (un párrafo por título) y lo
persiste con `storage.guardar` (Art. V) exactamente igual que un archivo cargado —
`origen='negociacion'`, `ruta_archivo` apuntando al `.docx` generado. Dispara el pipeline
completo del Art. IV desde el principio (extracción → segmentación → clasificación → ...),
reusando `_procesar_pipeline()` sin rama especial (ver decisiones cerradas, §5). El
Documento queda enlazado a la negociación (`negociacion_id`) y numerado
(`version_negociacion`), sin tocar los Documentos de cierres anteriores.

## 4. Permisos (extiende `auth-spec.md` §5)

| Acción | Admin Tenant | Revisor | Editor | Visualizador |
|---|---|---|---|---|
| Ver negociación (peticiones/ofertas/reuniones/acuerdos) | ✅ | ✅ | ✅ | ❌ |
| Registrar petición / oferta / reunión / acuerdo | ✅ | ❌ | ✅ | ❌ |
| Cerrar negociación (genera Documento) | ✅ | ❌ | ❌ | ❌ |
| Reabrir negociación cerrada | ✅ | ❌ | ❌ | ❌ |

Es un proceso pre-firma interno del tenant, no un reporte publicado (Art. IV.9) — por eso
Visualizador no tiene acceso, a diferencia del comparador. Cerrar/reabrir queda reservado a
Admin Tenant porque genera (o regenera) un Documento oficial que entra al pipeline del
Art. IV.

## 5. Decisiones cerradas

- **Redacción del texto acordado:** humano lo redacta manualmente al registrar el acuerdo,
  sin sugerencia de LLM en este flujo. Evita que un texto generado quede mal atribuido en
  un instrumento con peso legal.
- **Reapertura:** sí, con versionado. `negociaciones.estado` vuelve a `abierta`; los
  acuerdos previos no se borran. Al volver a cerrar, se genera un **nuevo** Documento (no se
  edita el anterior) tomando, para cada título, el acuerdo **más reciente** dentro de esa
  negociación — así un addendum puede renegociar un solo título sin tocar los demás. Cada
  Documento generado queda enlazado a su negociación (`documentos.negociacion_id`) con un
  número de versión (`documentos.version_negociacion`), de modo que el historial completo
  de convenciones de una Empresa queda trazable (versión 1, versión 2 tras el addendum,
  etc.) sin necesidad de una tabla de versiones separada. Los Documentos/cláusulas de
  versiones anteriores no se tocan ni se invalidan — quedan como registro histórico, igual
  que el comparador ya agrupa por empresa y no por documento.
- **Segmentación:** el Documento generado pasa por el pipeline completo del Art. IV,
  incluyendo segmentación, igual que uno cargado directo. En la práctica: se arma un
  `.docx` sintético (un párrafo por título, con el acuerdo más reciente de cada uno,
  generado con `python-docx` — misma librería que ya usa `extraction.py`), se persiste con
  `storage.guardar` (Art. V) igual que un archivo cargado, y se reusa exactamente
  `_procesar_pipeline()` sin ninguna rama especial. Es más código que un atajo "un acuerdo
  = una cláusula ya clasificada", pero evita mantener dos caminos de clasificación
  distintos — el extractor/segmentador ya maneja texto simple sin problema.

## 6. Preguntas abiertas

Ninguna pendiente por ahora — las tres de la sección anterior quedaron resueltas.

## 7. Plan de implementación

Cuatro bloques, en este orden — cada uno depende del anterior (B necesita las tablas de A,
C necesita poder registrar acuerdos antes de cerrar, D es la superficie visible de todo lo
anterior).

### A. Modelo de datos

- [ ] Diseñar y agregar a `schema.sql`: `negociaciones`, `peticiones`, `ofertas`,
  `reuniones`, `acuerdos`, `bitacora_negociacion`
- [ ] `documentos.negociacion_id` + `documentos.version_negociacion` (nullable), extender
  el CHECK de `origen` para admitir `'negociacion'`
- [ ] Migración incremental para la base ya desplegada:
  `service/db/migrations/007_negociacion.sql`
- [ ] Bloque conditional correspondiente en `.github/workflows/deploy-apps.yml`

### B. CRUD de negociación (sin cierre todavía)

- [ ] Backend: `POST/GET /negociaciones` (por empresa), `GET /negociaciones/{id}` (detalle
  con peticiones/ofertas/reuniones/acuerdos anidados)
- [ ] Backend: `POST /negociaciones/{id}/peticiones`, `POST /peticiones/{id}/ofertas`,
  `POST /negociaciones/{id}/reuniones`, `POST /negociaciones/{id}/acuerdos`
- [ ] Cada escritura agrega su evento a `bitacora_negociacion`
- [ ] Matriz de permisos de §4 aplicada (`require_role`)

### C. Cierre y reapertura → Documento

- [ ] Función que arma el `.docx` sintético a partir del acuerdo más reciente por título
- [ ] `POST /negociaciones/{id}/cerrar`: valida ≥1 acuerdo, genera Documento
  (`origen='negociacion'`, `version_negociacion` incremental), dispara
  `_procesar_pipeline()`, marca `negociaciones.estado='cerrada'`
- [ ] `POST /negociaciones/{id}/reabrir` (solo si `estado='cerrada'`)
- [ ] Verificar con Postgres real: cerrar → Documento aparece en `/documentos` con su
  `empresa_id` → pipeline corre → reabrir → agregar/corregir un acuerdo → volver a cerrar →
  aparece un **segundo** Documento (`version_negociacion=2`), el primero intacto

### D. Frontend

- [ ] Pantalla de negociación por Empresa: listar negociaciones, crear una nueva
- [ ] Detalle de negociación: registrar petición → oferta, reunión, acuerdo; botón
  cerrar/reabrir; ver Documentos generados por versión
- [ ] Enlace desde `EmpresasPage.jsx` (o `DocumentList.jsx`) hacia la negociación de una
  empresa
- [ ] Actualizar `auth-spec.md` §5 con la matriz de §4

## 8. Checklist resumido

- [ ] A. Modelo de datos
- [ ] B. CRUD de negociación
- [ ] C. Cierre y reapertura → Documento
- [ ] D. Frontend

*(Se actualiza a medida que avanzamos, mismo criterio que `spec-empresas-comparacion.md`.)*
