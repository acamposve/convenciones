# Plan — Cobertura de logging (post-auditoría)

> **Estado:** borrador, no iniciado — documento de planificación interna, no una spec cerrada
> con decisiones del cliente (a diferencia de los demás `docs/spec-*.md`).
> **Origen:** auditoría de cobertura de logging en los tres componentes (`api/`, `service/`,
> `web/`), hecha en sesión del 2026-09-05, a pedido explícito de validar que "todo el código
> tiene logs".
> **Alcance:** cerrar los huecos encontrados — con foco en excepciones que hoy se tragan sin
> dejar ningún rastro, no en agregar logging cosmético donde ya hay manejo de errores visible
> al usuario.

## Contexto — qué encontró la auditoría

**Veredicto general: no hay logging técnico real en ningún componente del proyecto.**

- **API .NET (`api/`):** cero. No hay un solo `ILogger<T>` inyectado en ningún Controller ni
  Service, no hay Serilog ni Application Insights configurado, y **no existe un solo bloque
  `try/catch` en todo el proyecto** (verificado por grep sobre todos los `.cs`). Lo único que
  queda registrado es el logging default de Kestrel (método HTTP, ruta, status code), sin
  ningún dato de negocio ni de la causa real de un fallo.
- **Servicio Python (`service/app/`):** no se usa el módulo `logging` de la stdlib en ningún
  archivo (0 coincidencias de `import logging` en ~2000 líneas). Solo existen **3 `print()`**
  en todo el servicio, los tres en el pipeline de clasificación (`main.py:705,724,741`), y
  capturan solo `str(exc)` — sin traceback, sin nivel de severidad, sin logging estructurado.
- **Frontend (`web/src/`):** cero `console.error`/`console.warn` en todo el código (0
  coincidencias) y cero Error Boundaries. Los 26 `.catch(` existentes o vacían el estado en
  silencio, o muestran un banner al usuario — en ningún caso el detalle real del error llega
  a la consola del navegador.

El hueco más grave es puntual y concreto: **`_procesar_pipeline()` en
`service/app/main.py:651-766` (el pipeline de clasificación, que corre en background vía
`BackgroundTasks`) no tiene ningún `try/except` que envuelva la función completa.** Si algo
falla ahí fuera de los 4 puntos ya cubiertos por `print()` — por ejemplo, si Postgres se cae a
mitad de camino, o `segment_clauses()` lanza algo no previsto — el documento queda atascado
para siempre en estado `extraido`/`segmentado`, nunca pasa a `error`, y no queda ningún
rastro server-side de qué pasó ni con qué `documento_id`. Nadie se entera.

## Bloque A — Blindar `_procesar_pipeline()` y adoptar `logging` en el servicio Python

**Problema:** el pipeline completo corre en background sin un `try/except` global; un fallo
de infraestructura deja el documento atascado sin pasar a `estado='error'` y sin ningún log.
Los 3 `print()` existentes tampoco loguean traceback, así que incluso los casos que sí se
capturan pierden el punto exacto de la falla dentro del SDK de Anthropic.

**Trabajo estimado:**
- Configurar `logging` de la stdlib en `service/app/` (un `logger = logging.getLogger(__name__)`
  por módulo, nivel configurable vía env var, formato con timestamp + `documento_id` cuando
  aplique — no hace falta un framework externo, Azure Container Apps ya captura stdout).
- Envolver `_procesar_pipeline()` completo en un `try/except Exception` de más alto nivel que
  garantice que CUALQUIER fallo no previsto termine marcando el documento en `error` (vía
  `_marcar_error`) y logueando `logger.exception(...)` (con traceback completo, no solo
  `str(exc)`).
- Reemplazar los 3 `print()` existentes (`main.py:705,724,741`) por `logger.exception(...)`
  con el mismo contexto (`documento_id`, `orden`) que ya tienen, pero con traceback real.
- Verificación: forzar un fallo artificial dentro del pipeline (ej. apagar Postgres a mitad
  de un `_procesar_pipeline` de prueba) y confirmar que el documento pasa a `error` con un
  `estado_detalle` útil, y que el log muestra el traceback completo, no solo un mensaje.

## Bloque B — Logging de fallos en integraciones externas (Blob Storage, Anthropic, Postgres)

**Problema:** `service/app/storage.py:25` (subida a Azure Blob Storage),
`service/app/classification.py` (las 3 llamadas a la API de Anthropic) y
`service/app/db.py:9-12` (`get_conn`) no tienen ningún manejo de error propio — cualquier
fallo de estos tres servicios externos es indistinguible de cualquier otro error en los logs,
y no se puede diferenciar, por ejemplo, un `RateLimitError` de Anthropic de un timeout de red
o de una respuesta malformada del SDK.

**Trabajo estimado:**
- `storage.py`: envolver `container.upload_blob(...)` en `try/except`, loguear con
  `logger.exception` incluyendo el nombre del blob y el tenant, re-lanzar para que el
  llamador siga devolviendo el error al usuario (no cambiar el comportamiento visible, solo
  agregar el rastro).
- `classification.py`: capturar por separado errores conocidos del SDK de Anthropic (rate
  limit, timeout, conexión) vs. errores de parseo de la respuesta, y loguear cada tipo
  distinto para poder diferenciarlos después en los logs de Azure.
- `db.py`: envolver `psycopg.connect` para loguear explícitamente un fallo de conexión a la
  base antes de que se propague.
- Verificación: no requiere una prueba end-to-end nueva — alcanza con revisar que los logs
  generados durante la demo en vivo con el cliente (ya planeada) muestren mensajes
  diferenciados si algo falla, en vez de un traceback genérico sin contexto.

## Bloque C — Logging básico en la API .NET

**Problema:** no existe ningún `ILogger<T>` ni framework de logging configurado; tampoco hay
un solo `try/catch` en todo `api/`. Cualquier excepción no controlada (JWT mal configurado,
fallo de conexión a la base, un `NullReferenceException` como el de
`TokenService.cs:44` si falta `Jwt:SigningKey`) solo deja el stack trace genérico que ASP.NET
Core imprime por default, sin ningún dato de negocio (qué tenant, qué usuario, qué acción).

**Trabajo estimado:**
- Configurar logging estructurado básico en `Program.cs` (el builtin de ASP.NET Core alcanza
  para empezar — no hace falta Serilog/App Insights todavía, eso puede ser un paso posterior
  si Azure Log Analytics no alcanza).
- Inyectar `ILogger<T>` en `AuthController`, `PlataformaController` y `TokenService`, y
  loguear al menos: intentos de login fallidos (con email, sin loguear la password),
  excepciones no controladas antes de que el middleware las convierta en 500, y las acciones
  administrativas de Plataforma (suspender/activar tenant, activar país) — esto último es
  logging técnico complementario a `bitacora_negociacion`, no un reemplazo.
- Verificación: provocar un login fallido y una excepción no controlada (ej. token JWT mal
  formado) contra un ambiente local, confirmar que aparecen en los logs con contexto útil.

## Bloque D — `console.error` y manejo de errores visible en el frontend

**Problema:** cero `console.error` en las 26 rutas `.catch(` de `web/src/**/*.jsx`. Cuando un
usuario reporta un bug, no hay ningún rastro en la consola del navegador para diagnosticarlo
sin reproducirlo a mano — ni siquiera el error real queda expuesto, solo un mensaje genérico
en el banner.

**Trabajo estimado:**
- Agregar `console.error(err)` (o el objeto de error completo) en los 26 `.catch(` existentes,
  antes de actualizar el estado/mostrar el banner — cambio mecánico, bajo riesgo, sin cambiar
  el comportamiento visible al usuario.
- Los `res.json().catch(() => null)` usados para parsear el body de error (7 ocurrencias)
  también deberían loguear cuando el parseo falla — hoy ese caso (ej. un 502 de un proxy que
  devuelve HTML en vez de JSON) se pierde silenciosamente.
- Evaluar agregar un Error Boundary a nivel de `web/src/main.jsx` para capturar errores de
  render no manejados (hoy no existe ninguno) — esto es un nice-to-have, no bloqueante.
- Verificación: forzar un fetch fallido (ej. apagar el servicio Python) y confirmar que el
  error real aparece en la consola del navegador, no solo el banner genérico.

## Bloque E — Logging de intentos de autenticación fallidos (seguridad)

**Problema:** ni `service/app/auth.py` ni `api/Controllers/AuthController.cs` dejan ningún
rastro operacional de fallos de autenticación repetidos (token inválido/expirado, claims
faltantes, login fallido). Esto es relevante más allá de debugging — intentos repetidos
podrían indicar fuerza bruta o un token robado, y hoy no hay forma de detectarlo fuera de la
tabla `bitacora_accesos` (que registra el evento pero no está pensada como alerta operacional).

**Trabajo estimado:** cubierto en gran parte por los Bloques A/C de arriba (agregar logging a
`auth.py` y a `AuthController`) — este bloque es más una decisión de qué nivel de severidad
usar (ej. `WARNING` para tokens inválidos, para poder filtrarlos/alertar sobre ellos
después) que trabajo de implementación nuevo. Evaluar si vale la pena en esta ronda o
diferirlo hasta que haya un consumidor real de esas alertas (Azure Monitor, etc.).

## Checklist resumido

- [ ] A. Blindar `_procesar_pipeline()` completo + adoptar `logging` en el servicio Python
- [ ] B. Logging de fallos en Blob Storage, Anthropic API y conexión a Postgres
- [ ] C. Logging básico (`ILogger`) en la API .NET — Controllers y `TokenService`
- [ ] D. `console.error` en los 26 `.catch(` del frontend + evaluar Error Boundary
- [ ] E. Nivel de severidad para fallos de autenticación (depende de A/C, decisión más que
      trabajo)

## Fuera de alcance (por ahora)

- **Framework de logging externo** (Serilog, Application Insights, structured logging a un
  sink centralizado): el logging default de Azure Container Apps (captura de stdout) alcanza
  para este primer paso. Evaluar como fase posterior si el volumen de logs lo justifica.
- **Alertas automáticas** sobre los eventos de seguridad del Bloque E: requiere un consumidor
  (Azure Monitor u otro) que hoy no existe — este plan solo deja el rastro, no configura
  alertas.
- **Reemplazar `bitacora_accesos`/`bitacora_negociacion`**: son auditoría de negocio en la
  base de datos, ya existen y funcionan — este plan es sobre logging técnico/operacional
  complementario, no un reemplazo.

---

*Actualizar este documento o promoverlo a un `spec-*.md` formal si el trabajo crece más allá
de una limpieza técnica — mismo criterio que las demás specs de fase.*
