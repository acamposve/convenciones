# Informe de Evaluación de Madurez y Diagnóstico de Modernización
**Proyecto:** Comparador de Convenciones Colectivas de Trabajo  
**Rol:** Senior Software Developer & Modernization Architect  
**Fecha:** Septiembre 2026  
**Versión de Análisis:** 1.0.0

> **⚠️ Snapshot pre-Fase 1:** este informe es el diagnóstico que motivó
> `PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md` y su Fase 1. La sección 4.2 (y el puntaje de
> "Observabilidad y Resiliencia Operacional: 1.5/5") describe el estado **antes** de que esa
> Fase 1 se implementara en este mismo repositorio: hoy ya existe logging estructurado JSON
> en Python y en la API .NET (Serilog), `try/except` global en `_procesar_pipeline()`, y
> `ErrorBoundary` en el frontend. No se actualizó el puntaje retroactivamente porque este
> documento es un diagnóstico fechado, no una spec viva — ver `docs/CHANGELOG.md` para el
> estado actual. **Además, se escribió asumiendo Azure como proveedor de nube** (varias
> secciones lo dan por hecho) — eso también cambió: se retiró Azure del proyecto (Enmienda
> 2.3.0 de `constitution.md`), sin proveedor nuevo decidido todavía.
> **Además, desde la enmienda 2.4.0 el MVP interno excluye IA:** la fila "Pipeline de
> Ingesta e IA" y las referencias a Claude describen el estado histórico del servicio Python,
> no el alcance vigente del MVP .NET, que termina en extracción/OCR y segmentación.

---

## 1. Resumen Ejecutivo (Executive Summary)

El proyecto **Comparador de Convenciones Colectivas de Trabajo** se encuentra en una etapa crítica y fascinante de **transición y modernización activa**. Se trata del reemplazo integral de un sistema SaaS monolítico en PHP desarrollado hace más de 20 años (datado formalmente entre los años 2000 y 2009), migrando hacia una arquitectura contemporánea en la nube basada en **.NET 8, Python (FastAPI), React 18, PostgreSQL y Azure Container Apps**.

### Diagnóstico Global de Madurez

| Dimensión | Nivel de Madurez (1 a 5) | Estado | Veredicto |
|---|:---:|:---:|---|
| **Gobierno y Constitución Arquitectónica** | **4.8 / 5** | 🟢 Ejemplar | Claridad absoluta de reglas de negocio, límites del sistema y decisiones no negociables (`docs/constitution.md`). |
| **Arquitectura de Dominio y Datos (Nuevo)** | **4.2 / 5** | 🟢 Maduro | Modelo relacional PostgreSQL sólido, normalizado, con multi-tenancy estricto (`tenant_id`) y auditoría. |
| **Pipeline de Ingesta (Nuevo)** | **3.0 / 5** | 🟡 En migración | Ingesta PDF/Word, extracción y segmentación en .NET; OCR nativo y validación de paridad aún pendientes. |
| **Infraestructura como Código y CI/CD** | **3.7 / 5** | 🟡 Bien encaminado | Terraform completo para Azure (Container Apps, PostgreSQL, ACR, Storage) y workflows en GitHub Actions. |
| **Seguridad de la Plataforma Nueva** | **3.5 / 5** | 🟡 Aceptable | BCrypt, JWT/Refresh tokens, aislamiento por tenant. Pendiente gestión granular de usuarios y SSO. |
| **Higiene y Coherencia Arquitectónica** | **2.5 / 5** | 🟠 En Riesgo | Desviación del diseño original: `service/app/main.py` se convirtió en un monolito de ~1.500 líneas absorbiendo CRUDs de .NET. |
| **Observabilidad y Resiliencia Operacional** | **1.5 / 5** | 🔴 Crítico | Sin logging estructurado, ausencia de `try/catch` en background tasks, colas de ejecución en memoria (`BackgroundTasks`). |
| **Estrategia de Pruebas y Aseguramiento (QA)** | **2.0 / 5** | 🟠 Insuficiente | Tests unitarios básicos en C# y Python; 0 pruebas automatizadas en Frontend (`web/`); sin tests end-to-end. |
| **Estado del Código Legado (`legacy/`)** | **1.0 / 5** | 💀 Legacy Crítico | PHP 3/4/5 no mantenible, sin framework, contraseñas en texto plano, inyección SQL masiva. Aislado como referencia. |

**Veredicto General:**  
El proyecto demuestra una **madurez conceptual y estratégica sobresaliente**, muy por encima de la media de proyectos de migración. La decisión fundacional de no portar código PHP y utilizar el sistema legado exclusivamente como especificación funcional y dataset de prueba (Art. IX de la constitución) fue acertadísima. Sin embargo, en el plano de la **madurez operativa y de ingeniería de software**, el proyecto exhibe los síntomas típicos de un MVP acelerado: concentración de lógica en un único archivo, tareas en background frágiles y una ausencia total de observabilidad técnica.

---

## 2. Radiografía del Sistema Legado (`legacy/`)

Una inspección profunda de la carpeta `legacy/` (compuesta por **444 archivos PHP** y aproximadamente **59.800 líneas de código**) permite fechar y categorizar arqueológicamente el sistema:

### 2.1. Arqueología Tecnológica y Cronología
* **Periodo de desarrollo:** 2000 – 2009. Se observan archivos fundacionales fechados el **21 de abril de 2000** (`legacy/lib/bd/base.php`, autor: Sebastián Delmont) y modificaciones sucesivas hasta mayo de 2009 (`comparador-30-04-2009.php`) y 2010.
* **Versión de PHP:** Diseñado originalmente para **PHP 3 / PHP 4**, con parches cosméticos para PHP 5.2.
* **Incompatibilidad absoluta con PHP moderno:** El código utiliza construcciones eliminadas en PHP 7 y 8:
  * Funciones de base de datos nativas `mysql_connect()` y `mysql_select_db()` (eliminadas en PHP 7.0).
  * Iteradores obsoletos: `while(list($key, $value) = each($array))` (deprecado en PHP 7.2, eliminado en PHP 8.0).
  * Sintaxis de clases PHP 4 con variables declaradas con `var $propiedad;` y constructores homónimos.
  * Indices de arreglos asociativos sin entrecomillar: `$row[campo_columna]`, que genera errores fatales en PHP 8.
  * Etiquetas cortas (`<?` y `<?=`).
* **Frontend y Herramientas:**
  * Uso intensivo de **Macromedia Dreamweaver** (presencia de archivos de bloqueo `.LCK` y directorios `_notes/`).
  * Framework **Adobe Spry** para paneles colapsables (`SpryCollapsiblePanel.js`).
  * Dependencia de **Macromedia Shockwave Flash** (`AC_RunActiveContent.js`, `.swf` para avisos y botones).
  * Maquetación en tablas HTML 4/XHTML 1.0 con atributos en línea (`bgcolor`, `<font>`, `border="1"`).

### 2.2. Hallazgos de Seguridad Críticos en el Legado
Si este sistema estuviera expuesto a Internet hoy, su compromiso sería inmediato:
1. **Almacenamiento de contraseñas en texto plano:** En `legacy/lib/objetos/user.php`, las claves se insertan y consultan directamente como texto sin cifrar (`Clave_usuario = %s`).
2. **Inyección SQL ubicua y trivial:**
   * En el login:
     ```php
     $qQuery = "Select * from usuarios WHERE Login_usuario LIKE '".$user."' AND Clave_usuario LIKE '".$password."'";
     ```
     Una entrada de usuario simple como `' OR '1'='1` permite eludir la autenticación sin conocer usuario ni contraseña.
   * En consultas de negocio: concatenación directa de parámetros de URL sin sanitizar (`$_GET['dato']`, `$_GET['sector_empresa']`).
3. **Credenciales hardcodeadas en repositorio:** `legacy/local/configuracion.php` expone credenciales de bases de datos locales (`root`, sin contraseña; usuarios con passwords de servidores WAMP de la época como `"vertrigo"` y `"T3ue9+b3"`).
4. **Falta de controles de sesión y CSRF:** Variables de sesión asignadas manualmente sin protección contra fijación de sesión ni tokens de verificación.

### 2.3. Higiene y Deuda del Repositorio Legado
* **Archivos residuales y duplicados:** `user.php`, `2user.php`, `usuarios.php`, `settings_old.php`, `comparacion.1php`.
* **Archivos de depuración y binarios en control de versiones:** Archivos `error_log` de hasta 120 KB con rutas absolutas de servidores y presentaciones de PowerPoint (`demo.pps` de 5.8 MB) versionadas en git.
* **Carpetas vacías o anómalas:** `xyiznwsk/`.

### 2.4. El "Tesoro" Rescatable del Legado
A pesar de su obsolescencia técnica, el sistema legado resolvía con éxito un problema de negocio complejo de alto valor B2B:
* **Taxonomía laboral estructurada:** 5 categorías y ~60 títulos comparativos.
* **Modelo de discusión y negociación colectiva:** Peticiones de sindicatos, ofertas de empresas, actas de reuniones y convergencia de acuerdos (`discusion.php`).
* **Dataset histórico invaluable:** ~6.400 cláusulas clasificadas manualmente y más de 400 documentos reales que sirven hoy como benchmark de entrenamiento y validación para los modelos de IA.

---

## 3. Evaluación de la Plataforma Nueva (Arquitectura y Estado Actual)

La modernización se compone de:
* **`api/`**: API en C# / .NET 8 (Autenticación, JWT, roles, EF Core, Npgsql).
* **`service/`**: Microservicio Python / FastAPI (Ingesta de documentos, OCR, segmentación, clasificación LLM, marco legal, resúmenes ejecutivos).
* **`web/`**: Single Page Application en React 18 con Vite.
* **`infra/terraform/`**: Infraestructura como código en Microsoft Azure.
* **`docs/`**: Documentación de gobernanza (Constitución v2.1.0, especificaciones funcionales por módulo).

```
                      +------------------------------------------+
                      |         Frontend React 18 + Vite         |
                      |          (web/ - Azure Static / CA)      |
                      +--------------------+---------------------+
                                           |
                    +----------------------+----------------------+
                    |                                             |
           [Auth / Tokens / Admin]                      [Negocio / Ingesta / CRUD]
                    |                                             |
                    v                                             v
         +--------------------+                        +--------------------+
         |   API .NET 8 LTS   |                        |  FastAPI (Python)  |
         |       (api/)       |                        |     (service/)     |
         +---------+----------+                        +----------+---------+
                   |                                              |
                   |               +------------------+           |
                   +-------------->|    PostgreSQL    |<----------+
                                   |  (Flexible Srv)  |
                                   +------------------+
                                              ^
                                              |
                                   +----------+---------+
                                   | Azure Blob Storage |
                                   | (PDFs & Contratos) |
                                   +--------------------+
```

### 3.1. Puntos Fuertes Destacados (High Maturity Indicators)

1. **Gobernanza mediante "Constitución Arquitectónica":**
   La existencia de `docs/constitution.md` (versión 2.1.0, ratificada y enmendada) es un diferenciador de clase mundial. Establece reglas inmutables ("reglas duras") como:
   * Documentos privados por defecto.
   * Aislamiento estricto por tenant (`tenant_id`).
   * Revisión humana obligatoria como compuerta antes de cualquier publicación de IA.
   * Regla de enmienda formal para evitar deriva técnica o de producto.

2. **Modelo de Datos Relacional Excelente:**
   El esquema PostgreSQL (`service/db/schema.sql`, 434 líneas) es ejemplar:
   * Claves primarias UUID para entidades de tenant, evitando ataques de enumeración.
   * Enumeraciones nativas tipadas (`rol_usuario`).
   * Índices compuestos optimizados para multi-tenancy (`(tenant_id, estado_revision)`).
   * Separación rigurosa entre catálogos globales (taxonomía base, sectores, estados) y entidades privadas de cada operador.

3. **Pipeline de Inteligencia Artificial Pragmático:**
   * Uso de modelos de frontera (Anthropic Claude vía API) con salida estructurada en formato JSON estricto.
   * Enfoque human-in-the-loop: el modelo asiste y pre-clasifica, asignando un nivel de confianza (`alto`, `medio`, `bajo`) y señales de cumplimiento legal, pero nunca publica automáticamente sin aprobación explícita de un Revisor humano.
   * Separación de revisiones: la clasificación de la cláusula y el resumen ejecutivo tienen estados de aprobación independientes.

4. **Infraestructura como Código Declarativa:**
   * La infraestructura en Azure está 100% parametrizada con Terraform: Azure Container Apps con configuración *scale-to-zero* para optimización de costes, Azure Database for PostgreSQL Flexible Server, Azure Container Registry y Azure Blob Storage.

---

## 4. Deuda Técnica, Inconsistencias y Riesgos de la Plataforma Nueva

A pesar de sus grandes aciertos, la modernización presenta áreas donde la madurez técnica flaquea y requiere atención inmediata:

### 4.1. Desviación Arquitectónica: Erosión de Límites entre .NET y Python
* **El plan original (Art. V de la Constitución):**
  * `.NET 8`: API principal para lógica de negocio, multi-tenancy, CRUDs, usuarios, licenciamiento y autenticación.
  * `Python (FastAPI)`: Microservicio satélite especializado exclusivamente en OCR, procesamiento de texto y llamadas al LLM.
* **La realidad encontrada:**
  * `.NET 8` ha quedado relegado casi exclusivamente a `AuthController.cs` y un incipiente `PlataformaController.cs` (un total de ~950 LOC).
  * `service/app/main.py` en Python absorbió el 90% de la lógica de negocio y se convirtió en un **monolito de 1.478 líneas de código**, manejando altas de empresas, negociaciones, reuniones, revisión de cláusulas, biblioteca pública y consultas directas SQL con `psycopg`.
* **Consecuencia:** Duplicación conceptual del acceso a datos (Entity Framework Core en C# con mapeos específicos de Npgsql vs. SQL crudo en Python).

### 4.2. Brecha Crítica en Observabilidad y Logging (Riesgo Operacional P0) — **[RESUELTO por Fase 1]**
Confirmado por la auditoría interna del equipo (`docs/plan-logging.md`) **al momento de este
diagnóstico**. La Fase 1 del plan de migración ya cerró los tres puntos de abajo en este
mismo repositorio (logging JSON en Python y .NET vía Serilog, `try/except` global en
`_procesar_pipeline()` con `_marcar_error` garantizado) — se deja el texto original como
registro histórico del hallazgo, no como estado actual:
* **Cero logging estructurado:** No hay uso del módulo `logging` en Python (cero `import logging` en el código de producción), y solo existían 3 `print()` sin traceback.
* **Cero logging en .NET:** No hay integración de `ILogger<T>`, Serilog o Application Insights; no existe un solo bloque `try/catch` en los controladores C#.
* **Fallas silenciosas en Background Tasks:** El procesamiento del pipeline en `service/app/main.py` se ejecuta mediante `BackgroundTasks` de FastAPI sin un `try/except` global. Si la base de datos o el servicio de almacenamiento fallan a mitad del proceso, el documento queda atascado para siempre en estado intermedio (`extraido`/`segmentado`), no transiciona a `error`, y los operadores no tienen forma de saber qué ocurrió.

### 4.3. Procesamiento Asíncrono en Memoria vs. Message Broker
* La Constitución (Art. V) exige desacoplar la ingesta mediante colas de mensajes durables (**Azure Service Bus** o **RabbitMQ**).
* La implementación actual corre las tareas en segundo plano en la memoria del propio contenedor FastAPI (`BackgroundTasks`).
* **Riesgo:** Si el contenedor se reinicia por uso de memoria, despliegue o escalado dinámico de Container Apps, todos los documentos encolados en ese momento se pierden irreversiblemente.

### 4.4. Madurez de Testing y Aseguramiento de Calidad
* **Frontend (`web/`):** 0 pruebas automatizadas. No hay configuración de Vitest, Jest, Cypress ni Playwright. El frontend depende enteramente de pruebas manuales.
* **Backend .NET (`api/`):** Cobertura limitada a `TokenServiceTests.cs`. No hay pruebas de integración para los controladores ni para las políticas de autorización.
* **Backend Python (`service/`):** Dispone de 6 suites de pruebas unitarias (`test_auth.py`, `test_classification.py`, etc.) con mocks del cliente de IA. Es la capa mejor probada, aunque carece de pruebas de integración contra una base de datos real.

### 4.5. Gestión de Migraciones de Base de Datos
* No se utiliza una herramienta de migración versionada como **Alembic**, **Flyway**, **Liquibase** o **EF Core Migrations**.
* El esquema se mantiene mediante un script manual monolítico (`service/db/schema.sql`) y parches manuales en `service/db/migrations/`. Esto genera riesgo de deriva entre los entornos de desarrollo, staging y producción.

---

## 5. Matriz de Madurez Tecnológica (Scorecard)

Evaluación bajo el estándar de ingeniería para modernización de software:

```
[1] Inicial / Caótico  -->  [2] Repetible / MVP  -->  [3] Definido / Funcional  -->  [4] Gestionado / Maduro  -->  [5] Optimizado
```

```
┌────────────────────────────────────────┬───────┬────────────────────────────────────────────────────────┐
│ Dimensión                              │ Nivel │ Evidencia Principal                                    │
├────────────────────────────────────────┼───────┼────────────────────────────────────────────────────────┤
│ 1. Arquitectura & Gobierno Estratégico │  4.5  │ Constitución viva, specs claras, límites respetados    │
│ 2. Modelo de Dominio y Datos           │  4.2  │ Esquema Postgres multi-tenant, normalizado, RLS ready   │
│ 3. Calidad y Modularidad de Código     │  2.8  │ Separación de stack limpia, pero main.py monolítico    │
│ 4. Seguridad y Gestión de Acceso       │  3.5  │ BCrypt, JWT/Refresh, pendiente SSO empresarial y RBAC  │
│ 5. Automatización de Pruebas (QA)      │  2.0  │ Pruebas unitarias parciales en Python; 0 en Web        │
│ 6. Observabilidad & Monitoreo          │  1.5  │ Ausencia de logs estructurados, métricas y tracing     │
│ 7. DevOps, IaC y Despliegue            │  3.7  │ Terraform completo para Azure, CI/CD en GitHub Actions │
│ 8. Madurez del Proceso de Migración    │  4.0  │ Criterio estricto de no portar código vulnerable       │
└────────────────────────────────────────┴───────┴────────────────────────────────────────────────────────┘
```

---

## 6. Recomendaciones del Senior Architect para los Próximos Pasos

Para consolidar la modernización y llevar el sistema de un **MVP Avanzado** a una **Plataforma de Producción Empresarial Robusta**, se recomienda priorizar las siguientes acciones organizadas por urgencia:

### Prioridad 0: Inmediata (Estabilización Operativa y Resiliencia)
1. **Implementar Logging Estructurado y Blindaje de Tareas:**
   * Ejecutar de inmediato el **Bloque A del `docs/plan-logging.md`**: envolver el pipeline de `service/app/main.py` en un bloque `try/except Exception` de nivel superior para garantizar que ningún documento quede congelado sin estado de error.
   * Incorporar el módulo estándar `logging` de Python y configurar salida JSON hacia stdout para su ingesta directa por Azure Container Apps / Log Analytics.
   * Incorporar `ILogger<T>` en los controladores .NET y habilitar middleware global de manejo de excepciones.
2. **Alertas y Error Boundaries en Frontend:**
   * Agregar Error Boundaries en React para evitar pantallas en blanco ante excepciones no controladas.

### Prioridad 1: Corto Plazo (Consolidación Arquitectónica)
1. **Resolver la Dualidad Backend (.NET vs. FastAPI):**
   * El equipo debe tomar una decisión arquitectónica formal:
     * **Opción A (Re-alineación con la Constitución):** Migrar los endpoints CRUD de negocio (Empresas, Negociaciones, Taxonomía) a la API de .NET 8, dejando a Python únicamente como microservicio worker de IA y extracción.
     * **Opción B (Re-enmienda Constitucional):** Aceptar pragmáticamente que Python es el backend principal de negocio y datos, reduciendo .NET exclusivamente a un Identity Provider / Auth Server, o bien consolidando la autenticación en FastAPI para eliminar la sobrecarga de mantener dos runtimes de backend.
2. **Modularizar `service/app/main.py`:**
   * Descomponer el archivo de 1.500 líneas en routers de FastAPI (`app/routers/tenants.py`, `app/routers/empresas.py`, `app/routers/negociacion.py`, `app/routers/clausulas.py`).
3. **Migrar a una Cola de Mensajería Durable:**
   * Reemplazar `BackgroundTasks` en memoria por **Azure Service Bus** o **Redis/Celery** para asegurar que el procesamiento de documentos soporte caídas o escalado de contenedores sin pérdida de datos.

### Prioridad 2: Mediano Plazo (Calidad, Migraciones y Features)
1. **Adopción de un Gestor de Migraciones SQL:**
   * Introducir **Alembic** (si se prioriza Python) o **Flyway / DbUp** para versionar incrementalmente el esquema de base de datos.
2. **Estrategia de Testing Integral:**
   * Incorporar **Vitest y React Testing Library** en `web/` para los componentes críticos (especialmente la cola de revisión de cláusulas y el comparador).
   * Incorporar pruebas de integración en el pipeline de GitHub Actions utilizando un contenedor de PostgreSQL efímero.
3. **Cierre de CRUDs Pendientes:**
   * Ejecutar el plan `docs/plan-crud-tablas.md`: gestión de usuarios dentro del tenant (invitación y cambio de rol por AdminTenant) y administración del catálogo del marco legal.

---

## 7. Conclusión

El proyecto se encuentra en una **posición inusualmente favorable para tener éxito**. A diferencia de la mayoría de las modernizaciones legacy —que suelen fracasar al intentar refactorizar código espagueti antiguo línea por línea— este equipo tuvo la sabiduría técnica y el rigor de:
1. Aislar el código de hace 20 años como un "arquetipo funcional" sin permitir que contamine el nuevo stack.
2. Formular una gobernanza arquitectónica de alto nivel que protege el valor del negocio.
3. Construir una base moderna sobre tecnologías sólidas y escalables (.NET 8, FastAPI, PostgreSQL, Azure).

Los desafíos actuales no son de concepto ni de viabilidad del negocio, sino de **maduración de ingeniería de software estándar (logging, modularización, colas durables y testing)**. Resolviendo estos puntos, la plataforma estará completamente lista para operar comercialmente a nivel regional.
