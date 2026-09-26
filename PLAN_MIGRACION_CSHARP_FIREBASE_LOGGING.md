# Plan Maestro y Análisis Técnico: Migración a C# (.NET 10 LTS), Adopción de Supabase e Implementación de Logging

**Proyecto:** Comparador de Convenciones Colectivas de Trabajo  
**Rol:** Senior Software Developer & Modernization Architect  
**Fecha:** Septiembre 2026  
**Documento:** `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md` (Revisión 3.0: .NET 10 LTS & Cronograma de Desconexión de Python)  

> **⚠️ Se retiró Azure como proveedor de infraestructura (Enmienda 2.3.0 de
> `docs/constitution.md`):** este documento se escribió asumiendo Azure (Container Apps,
> Terraform, ACR) como destino de deploy — eso ya no es así. `infra/terraform/` y los
> workflows que desplegaban ahí se eliminaron del repositorio. Las secciones de abajo que
> mencionan Azure/Terraform (sobre todo la Fase 5.3) describen el plan **tal como se escribió
> originalmente**; el "destino" real de cada paso de infraestructura queda pendiente de
> redefinir cuando se elija el proveedor nuevo. El resto del plan (unificación a .NET 10,
> adopción de Supabase, Fases 1-4) no depende del proveedor de nube y sigue vigente.

---

## 1. Preguntas Frecuentes y Clarificaciones Arquitectónicas Clave

### 1.1. ¿En qué momento exacto se elimina el servicio de Python?

La eliminación de Python sigue el patrón **Parallel Run con Switchover Inmediato (Strangler Fig)** para no romper la demo actual que ya está funcionando en Azure.

```
TIEMPO ───►
───────────────────────────────────────────────────────────────────────────────────────────
Fases 1 a 3:  [ Python ACTIVO ]  ───► Atiende tráfico de la app web actual (CRUDs + IA)
              [ .NET 10 CRECE ] ───► Absorbe Auth, CRUDs, Supabase y EF Core en paralelo
───────────────────────────────────────────────────────────────────────────────────────────
Fase 4:       [ Python ACTIVO ]  ───► Sigue corriendo en producción
              [ .NET 10 COMPLETO]──► Se implementa OCR, PdfPig y Claude en .NET 10 (Staging)
───────────────────────────────────────────────────────────────────────────────────────────
Fase 5.1:     [ VALIDACIÓN ]    ───► Pruebas de paridad con los 403 PDFs de prueba
───────────────────────────────────────────────────────────────────────────────────────────
Fase 5.2:     🔥 EL MOMENTO DEL CORTE (Cutover):
              1. Frontend (`web/`) cambia su URL y apunta 100% a .NET 10.
              2. Python deja de recibir peticiones (tráfico = 0).
───────────────────────────────────────────────────────────────────────────────────────────
Fase 5.3:     🔥 EL APAGADO EN NUBE (Infraestructura):
              `terraform apply` elimina `azurerm_container_app.service`.
              El contenedor Python se destruye en Azure.
───────────────────────────────────────────────────────────────────────────────────────────
Fase 5.4:     🔥 LA ELIMINACIÓN DE CÓDIGO (Repositorio):
              Se elimina la carpeta `service/` del repositorio Git.
              Python queda formalmente extinguido del proyecto.
───────────────────────────────────────────────────────────────────────────────────────────
```

> **Aclaración sobre Fase 1:** El parche de logging a Python en la Fase 1 es un *salvavidas operativo temporal*. Se hace en 15 minutos solo para que, mientras construimos la versión final en C#, los documentos cargados hoy no se congelen silenciosamente si el cliente o el equipo hacen una prueba.

---

### 1.2. ¿Es posible y conveniente migrar a .NET 10 en vez de .NET 8?

**Respuesta corta: SÍ, es totalmente posible y ALTAMENTE RECOMENDADO.**

En el calendario tecnológico actual (2026), .NET 8 se aproxima al fin de su soporte estándar (LTS de 3 años finaliza en noviembre de 2026). Iniciar una migración hacia .NET 8 significaría nacer con obsolescencia programada inmediata. **.NET 10 es la versión LTS vigente** (lanzada a finales de 2025 con soporte extendido hasta finales de 2028).

#### Ventajas Cruciales de .NET 10 para este Proyecto:
1. **Librería Estándar de IA (`Microsoft.Extensions.AI`):**
   * .NET 10 incorpora el paquete oficial de abstracciones unificadas de IA de Microsoft (`IChatClient`), con soporte nativo para middleware de observabilidad, tracking de costos de tokens, caching y salidas estructuradas JSON. Permite conectarse a Claude (Anthropic) con un estándar empresarial mucho más robusto que en .NET 8.
2. **Arranque en Frío y Memoria en Azure Container Apps:**
   * .NET 10 optimiza drásticamente el *runtime* para contenedores Linux y arquitecturas *scale-to-zero*, reduciendo el uso de memoria base en más de un 25% frente a .NET 8.
3. **Mejoras en EF Core 10 y Npgsql:**
   * Soporte optimizado para Postgres 16/17, mejor generación de consultas LINQ compiladas y mapeos nativos de JSONB.
4. **C# 14:**
   * Mayor expresividad en *primary constructors*, *pattern matching* y *source generators* para las expresiones regulares de segmentación de cláusulas.

---

## 2. Resumen Ejecutivo y Matriz de Tecnologías

| Criterio de Evaluación | Azure PostgreSQL Flexible (Actual) | Firebase Cloud Firestore (NoSQL) | Supabase (PostgreSQL BaaS) |
|---|:---:|:---:|:---:|
| **Motor de Base de Datos** | PostgreSQL 16 relacional nativo | NoSQL documental | **PostgreSQL 16 relacional nativo** |
| **Compatibilidad con Schema Actual** | 100% (27 tablas relacionales) | 0% (Requiere reescritura total) | **100% (Importación directa de `schema.sql`)** |
| **Consultas de Comparación Laboral** | 🟢 Excelente (JOINs, índices) | 🔴 Pésima (Sin JOINs, costo por lectura) | 🟢 **Excelente (Mismo motor y optimizador)** |
| **Aislamiento Multi-Tenant (Art. VI.2)** | En código / queries manuales | Reglas NoSQL complejas | 🟢 **Nativo vía Postgres Row Level Security (RLS)** |
| **Autenticación y Usuarios** | Código propio JWT en .NET | Firebase Auth | 🟢 **Supabase Auth (GoTrue, JWT, SSO/SAML)** |
| **Almacenamiento de Archivos (PDFs)** | Azure Blob Storage | Firebase Storage (GCS) | 🟢 **Supabase Storage (S3-compatible + RLS)** |
| **Modelo de Costos** | ~$30 - $120 USD/mes fijo | Pago por millón de lecturas | **Tier Gratuito / $25 USD/mes (Flat Rate)** |
| **Integración con C# (.NET 10)** | EF Core / Npgsql nativo | SDK Firestore C# limitado | 🟢 **EF Core + Npgsql estándar o `supabase-csharp`** |
| **Veredicto Arquitectónico** | Estable pero más costoso | 🔴 **Desaconsejado (Anti-patrón)** | 🟢 **Altamente Viable y Recomendado** |

---

## 3. Arquitectura Objetivo Unificada (.NET 10 LTS + Supabase)

```
 +-------------------------------------------------------------------------+
 |                         Frontend React 18 (Vite)                        |
 +--------------------+-------------------------------+--------------------+
                      |                               |
       [Llamadas de Negocio / IA]          [Auth / Realtime / Catálogos]
                      |                               |
                      v                               v
 +----------------------------------------+   +----------------------------+
 |          API C# (.NET 10 LTS)          |   |       Supabase Cloud       |
 |       (Azure Container Apps)           |   |       (BaaS Postgres)      |
 |                                        |   |                            |
 |  - Ingesta, OCR y Extracción (PdfPig)  |   | - PostgreSQL 16 (27 tablas)|
 |  - Segmentación Regex ([GeneratedRegex])|  | - Row Level Security (RLS) |
 |  - Claude vía Microsoft.Extensions.AI  |   | - Supabase Auth (GoTrue)   |
 |  - Comparador Laboral y Negociación    |   | - Supabase Storage (PDFs)  |
 |  - Serilog + OpenTelemetry             |   | - Supavisor Pooler (6543)  |
 +--------------------+-------------------+   +-------------+--------------+
                      |                                     ^
                      +------------------ Npgsql / EF Core -+
```

---

## 4. Plan Maestro de Ejecución y Checklist Paso a Paso

> Estado verificado del repositorio actual (sin `legacy/`) al 2026-09-19: la evidencia presente en el código confirma principalmente la observabilidad de la API y del frontend, y la base de autenticación/tenant. La migración a .NET 10 y la consolidación completa del stack objetivo siguen pendientes.

---

### FASE 1: Observabilidad y Blindaje Inmediato (Quick-Win Operativo) — ✅ **Completada**
*Objetivo: Evitar pérdida de datos o documentos congelados en la demo que hoy está viva.*

- [x] **1.1. Blindar temporalmente el Pipeline en Python (`service/app/main.py`):**
  - [x] Configurar el módulo estándar `logging` con formato estructurado hacia stdout (JSON, vía `_JsonLogFormatter`).
  - [x] Envolver `_procesar_pipeline()` en un bloque `try/except Exception` de alto nivel.
  - [x] Garantizar que cualquier fallo ejecute `_marcar_error(doc_id, ...)` y registre `logger.exception(...)` con traceback. El mensaje persistido en `estado_detalle` (que el frontend muestra al usuario) queda sanitizado — el texto crudo de la excepción solo va al log.
  - [x] Reemplazar los 3 `print()` (`main.py:705,724,741` original) por `logger.warning(...)`/`logger.exception(...)`.
- [x] **1.2. Configurar Serilog en `api/`:**
  - [x] Instalar paquetes: `Serilog.AspNetCore` (incluye `Serilog.Sinks.Console`).
  - [x] Inyectar contexto (`TenantId`, `UserId`, `CorrelationId`) vía `LogContext`, en un scope que envuelve al exception handler y a `UseSerilogRequestLogging` (si no, esos dos emiten sus logs fuera del scope y quedan sin contexto).
  - [x] Habilitar middleware global de excepciones (`ProblemDetails` RFC 7807).
- [x] **1.3. Error Boundary en React (`web/`):**
  - [x] Agregar `ErrorBoundary` global para evitar pantallas en blanco. El detalle crudo del error solo se muestra en desarrollo (`import.meta.env.DEV`); en producción queda en consola/logs, nunca en la UI.

---

### FASE 2: Aprovisionamiento y Configuración de Supabase — ✅ **Completada**
*Objetivo: Establecer la base de datos definitiva con Row Level Security y Auth.*

> Ejecutada de punta a punta contra un proyecto Supabase real (creado por el usuario, connection
> string del pooler compartida para esta migración puntual — no queda guardada en ningún
> archivo del repo ni en texto plano en ningún lado después de esta sesión). Región elegida
> por el usuario al crear el proyecto (`aws-0-us-west-2`); la recomendación de `sa-east-1` de
> más abajo queda como sugerencia para el futuro, no como lo que se usó esta vez.

- [x] **2.1. Aprovisionar Supabase** — hecho por el usuario (no automatizable sin credenciales que Claude no tenía):
  - [x] Proyecto creado en Supabase Cloud.
  - [x] Cadena de conexión del connection pooler (**Supavisor**, puerto 6543) compartida y usada.
- [x] **2.2. Migración del Esquema** — ejecutado y verificado contra la base real:
  - [x] `service/db/schema.sql` aplicado — confirmado fresh-install-ready tal cual (no hizo falta aplicar las migraciones 002-011 aparte).
  - [x] Verificado: **27 tablas**, **58 índices**, **12 secuencias**, enum `rol_usuario` con sus 7 valores (`AdminTenant`, `Revisor`, `Editor`, `Visualizador`, `PlataformaAdmin`, `PlataformaSoporte`, `PlataformaAuditor`).
  - [x] Semillas ejecutadas — `seed_taxonomia.py`: 5 categorías, 64 títulos (Venezuela); `seed_catalogos_empresa.py`: 3 sectores, 11 tipos de empresa, 18 categorías de sector, 21 actividades, 23 estados, 409 localidades; `seed_marco_legal.py`: 1 ley (LOTTT), 555 artículos, 357 vínculos título↔artículo. Texto con tildes verificado correcto en la base (ej. "SOCIOECONÓMICAS") — un mojibake en la salida de la terminal local hizo dudar en el momento, pero era solo de la consola, no de los datos (confirmado leyendo un archivo UTF-8 aparte).
- [x] **2.3. Habilitar Row Level Security (RLS)** — [`service/db/migrations/012_rls_supabase.sql`](service/db/migrations/012_rls_supabase.sql) aplicado y verificado, **corregido tras revisión de Copilot en el PR** (ver detalle abajo):
  - [x] RLS activo (`rowsecurity = true`) en las 4 tablas que pide el plan (`empresas`, `documentos`, `clausulas`, `negociaciones`) **y también** en sus 5 hijas (`peticiones`, `ofertas`, `reuniones`, `acuerdos`, `bitacora_negociacion`) — ver hallazgo corregido abajo.
  - [x] Políticas `tenant_isolation_*` confirmadas contra `pg_policies` en las 9 tablas — las 4 originales comparan `tenant_id` directo; las 5 hijas usan una subquery contra `negociaciones.tenant_id` (`ofertas` encadena un JOIN más, vía `peticiones`), porque no tienen columna `tenant_id` propia.
  - [x] Biblioteca pública (Art VI.7): **ya no es una política sobre `documentos`** (RLS filtra filas, no columnas — una política así regalaba `tenant_id`/id interno/`ruta_archivo`/estado a cualquier rol con SELECT). Ahora es la vista `biblioteca_publica` (mismos 3 campos que ya devuelve `GET /biblioteca` en `main.py`: `empresa_nombre`, `url_origen`, `created_at`), con `GRANT SELECT` explícito a `anon`/`authenticated` — sin depender de una política pública sobre `empresas` que hubiera dejado el JOIN interno bloqueado para un rol sin JWT.
  - [x] Confirmado que esto **no rompe nada de lo que ya corre**: el rol de conexión (`postgres`) tiene `rolbypassrls = true`, así que las políticas están activas pero no bloquean al rol actual — quedan listas para cuando haya un rol de aplicación separado (Fase 3+).
  - [x] **Probado con un test de aislamiento real** (no solo declarado): `SET ROLE anon` + `SELECT` directo contra `documentos`/`empresas` → 0 filas (RLS bloquea correctamente, sin JWT no hay `tenant_id`); `SELECT` contra la vista `biblioteca_publica` → ejecuta sin error; `INSERT` contra la vista → falla con `cannot insert into view` (no es auto-updatable, tiene un JOIN) — confirmado que ni siquiera hacía falta el `REVOKE ALL` de abajo para bloquear escrituras, pero se dejó explícito igual, no por casualidad de forma.
- [x] **Hallazgo corregido (Copilot, PR #29):** `peticiones`/`ofertas`/`reuniones`/`acuerdos`/`bitacora_negociacion` quedaban sin RLS en absoluto — legibles/escribibles por cualquier rol no-bypass, sin ningún filtro de tenant (Art VI.2). Resuelto con las 5 políticas de subquery de arriba.
- [x] **Hallazgo corregido (Copilot, PR #29):** la política pública sobre `documentos` exponía la fila completa (RLS es por fila, no por columna) — cualquier rol con SELECT veía `tenant_id`, id interno, `ruta_archivo`, estado y metadata de negociación de cada documento público, violando VI.7. Resuelto reemplazándola por la vista `biblioteca_publica` con la proyección exacta permitida.
- [x] **Hallazgo corregido (Copilot, PR #29):** con la política pública viviendo solo en `documentos`, el JOIN contra `empresas` para un rol anónimo (`auth.jwt()->>'tenant_id'` NULL) filtraba todas las filas de `empresas` — `/biblioteca` habría devuelto siempre vacío pese a admitir documentos públicos. Resuelto: la vista corre con los privilegios de su dueño (`postgres`, bypassa RLS), así que el JOIN interno no depende del JWT de quien la consulta.
- [x] **Hallazgo corregido (Copilot, segunda ronda, PR #29):** la vista existía pero `GET /biblioteca` en `service/app/main.py` seguía consultando `documentos`/`empresas` directo — el día que el servicio deje de usar el rol `postgres`/`BYPASSRLS`, esa consulta habría quedado filtrada a cero filas (mismo problema que el punto anterior, pero en el código de la app, no en la base). Resuelto: el endpoint ahora consulta la vista `biblioteca_publica` — que además se movió de `012_rls_supabase.sql` a `schema.sql`, porque es SQL portable (sin funciones de Supabase) y desarrollo local la necesita igual. `012_rls_supabase.sql` ahora solo tiene los `GRANT`/`REVOKE` de la vista para `anon`/`authenticated`, que sí son específicos de Supabase. Probado contra la base real: la nueva query (con y sin filtro `empresa`) ejecuta sin error tanto con el rol privilegiado como con `SET ROLE anon`.
- [x] **Hallazgo adicional, encontrado al verificar (no estaba en ningún comentario):** Supabase otorga privilegios amplios (`INSERT`/`UPDATE`/`DELETE`/...) a `anon`/`authenticated` por default sobre objetos nuevos del schema `public` — el `GRANT SELECT` de la vista quedó apilado sobre eso. Se agregó un `REVOKE ALL` explícito antes del `GRANT SELECT`, para no depender de que la vista "no sea auto-updatable" como única barrera.
- [x] **Hallazgo nuevo y arreglado (no estaba en el checklist original):** conectar vía el pooler Supavisor en modo *transaction* rompía con `psycopg.errors.DuplicatePreparedStatement` — psycopg3 usa prepared statements server-side por default, y el pooler reparte cada query entre conexiones físicas distintas por detrás, así que un statement preparado con nombre fijo choca entre sesiones. Se agregó `prepare_threshold=None` a las 5 llamadas a `psycopg.connect(...)` del proyecto (`app/db.py` — el que usa toda la API en producción — y los 4 scripts de seed). Sin este fix, **el servicio Python no puede operar contra Supabase en absoluto**, no solo los seeds.

> **⚠️ Riesgo abierto, sigue sin resolver pese a que 2.3 ya está aplicada:** activar RLS
> (arriba) no cierra este riesgo, solo lo deja declarado en la base — falta la mitad que lo
> hace real. `auth.jwt()` en una política RLS de Supabase depende de que la sesión de Postgres tenga poblado
> `request.jwt.claims` — eso lo hace automáticamente PostgREST/el Data API de Supabase, pero
> **no** una conexión directa de Npgsql/EF Core como la que usa la API en C# (Art. V). Dos
> problemas concretos a resolver, no solo declarar:
> 1. Si la API se conecta con un rol privilegiado (el que suele usarse con un connection
>    pooler para EF Core), ese rol puede **saltarse RLS por completo** — la política
>    quedaría configurada pero sin efecto real, dando una falsa sensación de aislamiento.
> 2. Si se usa un rol no privilegiado, hay que definir explícitamente **cómo** cada
>    conexión/transacción propaga el `tenant_id` del JWT ya validado por la API hacia la
>    sesión de Postgres (ej. `SET LOCAL request.jwt.claims = '...'` por transacción, o un rol
>    de Postgres separado que reciba el tenant como parámetro) — EF Core no lo hace solo.
>
> Mientras esto no esté definido y probado con un test de aislamiento real (un tenant
> autenticado no puede leer filas de otro), el filtro por `tenant_id` en código (Art. VI.2,
> ya vigente) sigue siendo el mecanismo de aislamiento real — RLS es un refuerzo, no algo que
> se pueda asumir como respaldo automático solo por estar "activado".

---

### FASE 3: Upgrade a .NET 10 LTS y Consolidación de CRUDs de Negocio — ✅ **Completada**
*Objetivo: Actualizar la API a .NET 10 y absorber todos los endpoints que hoy maneja Python.*

> La Fase 3 quedó implementada y validada en el repositorio: la API apunta a .NET 10,
> el contexto EF Core refleja el schema real, la conexión a Supabase es configurable y
> los endpoints de negocio usados por el frontend están implementados con aislamiento por tenant.

- [x] **3.1. Upgrade del Proyecto C# a .NET 10:**
  - [x] En `api/Comparador.Api.csproj` y `api/Comparador.Api.Tests/Comparador.Api.Tests.csproj`, `<TargetFramework>net10.0</TargetFramework>`.
  - [x] Paquetes NuGet actualizados a la última versión 10.x publicada (verificado contra nuget.org, no asumido): `Microsoft.AspNetCore.Authentication.JwtBearer` 10.0.12, `Npgsql.EntityFrameworkCore.PostgreSQL` 10.0.3, `Microsoft.EntityFrameworkCore.Design` 10.0.12, `EFCore.NamingConventions` 10.0.1 (no estaba en el checklist original pero es una dependencia directa que también fija major version con EF Core).
  - [x] No se mantiene dependencia de `Microsoft.Extensions.AI` en el MVP; la integración de IA queda diferida.
  - [x] `Dockerfile` actualizado a `mcr.microsoft.com/dotnet/sdk:10.0` / `aspnet:10.0` — **no verificado con un build real** (Docker Desktop no estaba corriendo en este entorno); son tags oficiales publicados, pero falta confirmarlo con `docker build` cuando haya Docker a mano.
  - [x] **Build y tests:** `dotnet build`/`dotnet test` — 0 errores, 7/7 tests OK sobre `net10.0`.
  - [x] **Smoke test real contra Supabase** (no solo compilar): se levantó la API completa (`dotnet run`) apuntando a la base de Supabase real (vía variables de entorno, sin tocar `appsettings.json` — eso es 3.2) y se probó `POST /api/auth/login` de punta a punta.
- [x] **3.2. Conexión de .NET 10 con Supabase:**
  - [x] Cadena de conexión configurable para Supavisor en `api/Program.cs` y `appsettings.json`.
  - [x] Mapeo de las tablas del schema real en `ComparadorDbContext`.
  - [x] Build validado después de la configuración y del mapeo.
- [x] **3.3. Portar Endpoints de Negocio desde Python a C#:**
  - [x] `EmpresasController.cs`: catálogos, países habilitados, taxonomía y empresas scoped por tenant.
  - [x] `DocumentosController.cs`: listado, detalle y alta de documentos.
  - [x] `NegociacionController.cs`: negociaciones, peticiones, reuniones, acuerdos, cierre y reapertura.
  - [x] `RevisionController.cs`: aprobación/rechazo de cláusulas y resúmenes ejecutivos.
  - [x] `ComparadorController.cs`: títulos disponibles y comparación relacional por filtros.
  - [x] `BibliotecaPublicaController.cs`: consulta pública de documentos publicados.
  - [x] `dotnet build` y `dotnet test`: 7/7 pruebas correctas.

---

### FASE 4: Portabilidad del Pipeline Determinista a .NET 10
*Objetivo: Reemplazar la ingesta, extracción, OCR y segmentación de documentos de Python con C# nativo, sin modelos ni servicios de IA en el MVP.*

- [x] **4.1. Canal de Background Processing:**
  - [x] Crear cola en memoria con `System.Threading.Channels.Channel<int>`.
  - [x] Implementar worker desacoplado `DocumentProcessingWorker` (`BackgroundService`).
- [x] **4.2. Extracción de Texto y OCR:**
  - [x] Integrar `UglyToad.PdfPig` para PDFs digitales.
  - [x] Integrar `DocumentFormat.OpenXml` para archivos Word (.docx).
  - [x] Configurar Tesseract nativo en Docker con `tesseract-ocr-spa` y `poppler-utils`. No se usa Azure AI Document Intelligence en el MVP.
  - [x] Validar `docker build` y la presencia de Tesseract, `pdftoppm` e idioma `spa` en la imagen runtime.
- [x] **4.3. Segmentación de Cláusulas:**
  - [x] Portar regex de segmentación a C# utilizando `[GeneratedRegex]` de C# 14.
- [x] **4.4. IA, clasificación, resumen y cumplimiento legal — fuera del MVP:**
  - [x] No configurar cliente de Anthropic ni `IChatClient`.
  - [x] No portar prompts ni clasificación automática; las cláusulas quedan con `titulo_id` nulo.
  - [x] Diferir resumen ejecutivo, campo comparativo y cumplimiento legal a una fase posterior con alcance y proveedor aprobados.

---

### FASE 5: Pruebas de Paridad, Cutover y Eliminación de Python
*Objetivo: El momento definitivo donde Python se apaga y se elimina del repositorio.*

- [ ] **5.1. Validación de Paridad Funcional:**
  - [x] Registrar un lote reproducible de 5 PDFs contractuales del repositorio `legacy/`, identificado por manifiesto y SHA-256.
  - [x] Procesar el lote en el pipeline C#/.NET 10 y dejar estado observable por documento.
  - [x] Documentar tasas, discrepancias y casos que requieren ajuste en `docs/phase5-parity-report.md`.
  - [x] Excluir clasificación automática e IA de la comparación.
  - [x] Verificar paridad de extracción y segmentación contra los datos históricos; la diferencia textual localizada de PDVSA se acepta como cosmética por tratarse de un PDF histórico con glifos superpuestos.
- [x] **5.2. Corte de Tráfico (Cutover):**
  - [x] En `web/.env`, actualizar `VITE_API_BASE_URL` para que apunte exclusivamente a la API .NET 10.
  - [x] Eliminar `VITE_DOCUMENT_API_BASE_URL`; el frontend usa una única base URL para auth, negocio y procesamiento documental.
  - [x] Migrar el registro público y la biblioteca pública a la API .NET 10; Python deja de recibir peticiones del frontend.
- [ ] **5.3. Apagado y Destrucción en Infraestructura (Terraform):**
  - [ ] En `infra/terraform/container_apps.tf`:
    - Eliminar el recurso `azurerm_container_app.service` (Python).
    - Ajustar `azurerm_container_app.api` (1.0 CPU, 2.0 GiB RAM).
  - [ ] En `infra/terraform/database.tf`:
    - Eliminar `azurerm_postgresql_flexible_server` (ahorro de costos, migrado a Supabase).
  - [ ] En `.github/workflows/deploy-apps.yml`:
    - Eliminar los steps de build y deploy de Python.
  - [ ] Ejecutar `terraform apply`: **El contenedor Python y Postgres Azure se destruyen en la nube.**
- [ ] **5.4. Limpieza del Repositorio:**
  - [ ] Eliminar la carpeta `service/` del código fuente.
  - [ ] Actualizar `README.md`, `ARCHITECTURE.md` y `docs/constitution.md` registrando a .NET 10 y Supabase como stack oficial único.

---

## 5. Criterios de Éxito Final

1. **Stack Unificado:** 100% de la lógica de backend reside en una única solución C# (.NET 10 LTS).
2. **Cero Código Python:** El directorio `service/` y sus contenedores ya no existen en ningún entorno.
3. **Cero Pérdida de Datos:** Base de datos PostgreSQL alojada en Supabase con RLS protegiendo el multi-tenancy.
4. **Observabilidad Completa:** Logs estructurados JSON contextualizados en cada petición y tarea en segundo plano.
5. **MVP sin IA:** ingesta, extracción/OCR y segmentación funcionan sin llamadas a LLM ni servicios externos de IA; la clasificación automática queda fuera del MVP.
