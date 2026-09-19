# Plan Maestro y Análisis Técnico: Migración a C# (.NET 10 LTS), Adopción de Supabase e Implementación de Logging

**Proyecto:** Comparador de Convenciones Colectivas de Trabajo  
**Rol:** Senior Software Developer & Modernization Architect  
**Fecha:** Septiembre 2026  
**Documento:** `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md` (Revisión 3.0: .NET 10 LTS & Cronograma de Desconexión de Python)  

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

### FASE 2: Aprovisionamiento y Configuración de Supabase
*Objetivo: Establecer la base de datos definitiva con Row Level Security y Auth.*

- [ ] **2.1. Aprovisionar Supabase:**
  - [ ] Crear proyecto en Supabase Cloud en la región más cercana a Azure (ej. `us-east-1`).
  - [ ] Obtener cadena de conexión con connection pooler (**Supavisor**, puerto 6543).
- [ ] **2.2. Migración del Esquema:**
  - [ ] Ejecutar `service/db/schema.sql` en Supabase SQL Editor.
  - [ ] Verificar creación de las 27 tablas, índices, secuencias y el enum `rol_usuario`.
  - [ ] Ejecutar semillas: países, taxonomía Venezuela, catálogos y marco legal LOTTT.
- [ ] **2.3. Habilitar Row Level Security (RLS):**
  - [ ] Activar RLS en tablas de tenant (`empresas`, `documentos`, `clausulas`, `negociaciones`).
  - [ ] Crear política: `tenant_id = (auth.jwt() ->> 'tenant_id')::uuid`.
  - [ ] Crear política pública de lectura para la Biblioteca Pública (`es_publico = true`).

---

### FASE 3: Upgrade a .NET 10 LTS y Consolidación de CRUDs de Negocio
*Objetivo: Actualizar la API a .NET 10 y absorber todos los endpoints que hoy maneja Python.*

- [ ] **3.1. Upgrade del Proyecto C# a .NET 10:**
  - [ ] En `api/Comparador.Api.csproj`, actualizar `<TargetFramework>net10.0</TargetFramework>`.
  - [ ] Actualizar paquetes NuGet a versiones 10.x:
    - `Microsoft.AspNetCore.Authentication.JwtBearer` (10.0)
    - `Npgsql.EntityFrameworkCore.PostgreSQL` (10.0)
    - `Microsoft.EntityFrameworkCore.Design` (10.0)
  - [ ] Instalar paquete `Microsoft.Extensions.AI` (10.0).
  - [ ] Actualizar el `Dockerfile` de la API para usar las imágenes base `mcr.microsoft.com/dotnet/aspnet:10.0` y `mcr.microsoft.com/dotnet/sdk:10.0`.
- [ ] **3.2. Conexión de .NET 10 con Supabase:**
  - [ ] Actualizar cadena de conexión en `appsettings.json` apuntando al pooler de Supabase (puerto 6543).
  - [ ] Mapear las 27 tablas en `ComparadorDbContext`.
- [ ] **3.3. Portar Endpoints de Negocio desde Python a C#:**
  - [ ] `TenantsController.cs`: Registro y gestión de operadores.
  - [ ] `EmpresasController.cs`: CRUD completo de empresas con filtro de tenant.
  - [ ] `NegociacionController.cs`: Peticiones, ofertas, reuniones, acuerdos y bitácora.
  - [ ] `RevisionController.cs`: Aprobación de cláusulas y resúmenes ejecutivos.
  - [ ] `ComparadorController.cs`: Consultas relacionales analíticas.
  - [ ] `BibliotecaPublicaController.cs`: Catálogo cross-tenant.

---

### FASE 4: Portabilidad del Pipeline de Ingesta, OCR e IA a .NET 10
*Objetivo: Reemplazar el procesamiento de documentos de Python con C# nativo.*

- [ ] **4.1. Canal de Background Processing:**
  - [ ] Crear cola en memoria con `System.Threading.Channels.Channel<int>`.
  - [ ] Implementar worker desacoplado `DocumentProcessingWorker` (`BackgroundService`).
- [ ] **4.2. Extracción de Texto y OCR:**
  - [ ] Integrar `UglyToad.PdfPig` para PDFs digitales.
  - [ ] Integrar `DocumentFormat.OpenXml` para archivos Word (.docx).
  - [ ] Configurar OCR (Azure AI Document Intelligence o Tesseract nativo en Docker).
- [ ] **4.3. Segmentación de Cláusulas:**
  - [ ] Portar regex de segmentación a C# utilizando `[GeneratedRegex]` de C# 14.
- [ ] **4.4. Clasificación de IA con Claude vía `Microsoft.Extensions.AI`:**
  - [ ] Configurar cliente de Anthropic bajo la abstracción `IChatClient`.
  - [ ] Portar generación de prompts estructurados (categorías, títulos, confianza).
  - [ ] Implementar resumen ejecutivo y cálculo de cumplimiento legal.

---

### FASE 5: Pruebas de Paridad, Cutover y Eliminación de Python
*Objetivo: El momento definitivo donde Python se apaga y se elimina del repositorio.*

- [ ] **5.1. Validación de Paridad Funcional:**
  - [ ] Procesar lote de PDFs de prueba en el nuevo pipeline C# .NET 10.
  - [ ] Verificar coincidencia exacta de clasificación vs. los datos históricos.
- [ ] **5.2. Corte de Tráfico (Cutover):**
  - [ ] En `web/.env`, actualizar `VITE_API_URL` para que apunte exclusivamente a la API .NET 10.
  - [ ] Eliminar `VITE_SERVICE_URL`. En este momento, **Python deja de recibir peticiones**.
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
5. **Costos Optimizados:** Reducción sustancial en la factura mensual de Azure al eliminar la base de datos administrada y el segundo contenedor.
