# Constitución del Proyecto — Comparador de Documentos Legales

> **Versión:** 1.0.0 · **Ratificada:** 2026-08-08 · **Última enmienda:** 2026-08-08
> **Origen:** `documento_arquitectura_comparador_convenciones.docx` (preparado para Alex Campos, 8 de agosto de 2026)

Este documento fija los principios y decisiones de arquitectura que gobiernan el diseño e implementación del nuevo Comparador de Documentos Legales. Cualquier decisión técnica o de producto que lo contradiga debe justificarse explícitamente y, si se acepta, disparar una enmienda a esta constitución.

## Preámbulo

El sistema reemplaza un SaaS PHP de ~20 años ("convenciones") que comparaba convenciones colectivas de trabajo mediante clasificación 100% manual. El nuevo producto automatiza la lectura, clasificación y extracción de cláusulas mediante IA, preservando un paso de revisión humana obligatorio antes de publicar cualquier contenido.

## Artículo I — Alcance y no-alcance

1. El sistema trata **exclusivamente convenciones colectivas de trabajo**, no documentos legales genéricos. No se diseña clasificación genérica para otros tipos de documento en esta fase.
2. Países soportados: **Venezuela, Uruguay, Argentina y Chile**. Cada país tiene su propia taxonomía de cláusulas, validada legalmente antes de activarse.
3. Modelo tenant–país: **1 tenant = 1 empresa/firma en 1 país**. No existe lógica de acceso cruzado entre países dentro de un tenant.
4. Todo el contenido generado por IA pasa por **revisión interna obligatoria** antes de publicarse; no hay publicación automática sin intervención humana.
5. El entregable al usuario final es dual pero de fuente única: **reporte (PDF/export) y vista web navegable**, generados desde el mismo dato estructurado.

## Artículo II — Modelo de dominio y taxonomía

1. El sistema legado organiza cláusulas en dos niveles: **5 categorías** (GENERALES, ECONÓMICO, SOCIOECONÓMICAS, SINDICALES, SEGURIDAD OCUPACIONAL) y **~60 títulos comparativos** dentro de ellas, cada uno con un indicador de si requiere campo de comparación económica.
2. Esta estructura conceptual es transversal a los cuatro países (remuneración, beneficios/condiciones de trabajo, aportes sindicales), confirmado contra los marcos legales de Argentina (Ley 14.250), Uruguay (Ley 18.566) y Chile (Código del Trabajo, Libro IV).
3. Diseño obligatorio: **núcleo de categorías común a los cuatro países**, con una **capa de títulos versionada por país** que permite agregar, renombrar o desactivar títulos sin tocar el núcleo.
4. Ningún país nuevo se activa comercialmente sin que su taxonomía haya sido **validada por un abogado laboral local**. La investigación web no sustituye esta validación.

## Artículo III — Modelo de datos

Entidades fundamentales (nombres conceptuales, no nombres de tabla definitivos):

| Entidad | Descripción | Origen |
|---|---|---|
| Tenant | Empresa/firma cliente; asociada a país, plan de licencia y fecha de vencimiento | Nuevo — el legado no tenía multi-tenancy real |
| Usuario / Rol | Pertenece a un tenant; rol entre Admin Tenant, Revisor, Editor, Visualizador, definido en instalación | Adaptado de `tipos_de_usuarios` y `seguridades` |
| País | Catálogo de países soportados con su marco legal de referencia | Adaptado de `paises` |
| Categoría / Título | Árbol de clasificación de cláusulas, versionado por país | Adaptado de `categorias_titulos` y `titulos` |
| Documento / Contrato | Convención capturada por URL o carga; guarda tenant, país, empresa, vigencia, estado público/privado | Adaptado de `contratos` |
| Cláusula / Artículo | Unidad extraída: número, texto, resumen, título asignado, campo comparativo, estado de revisión, confianza del modelo | Adaptado de `articulos_contratos`, con campos de IA nuevos |
| Revisión / Bitácora | Registro de cambios y aprobaciones (quién, cuándo, qué corrigió) | Adaptado de `bitacoras` |
| Licencia | Plan del tenant: fechas, límites de usuarios/documentos, países habilitados | Nuevo — el legado solo tenía `fech_venc` por usuario |
| Reporte de comparación | Selección de documentos/cláusulas, filtros, fecha de generación; alimenta vista web y export | Adaptado del módulo `comparador.php` |

## Artículo IV — Pipeline de extracción y clasificación con IA

Flujo obligatorio, en este orden, con revisión humana como puerta de publicación (no opcional):

1. **Ingesta** — el tenant aporta el documento por URL pública o carga de archivo (PDF/Word).
2. **Check público/privado** — el documento es privado por defecto; si se marca público vía URL, debe validarse que sea accesible sin autenticación antes de tratarlo como tal.
3. **Extracción de texto** — parseo de PDF/Word; OCR automático si es un escaneo.
4. **Segmentación** — división del texto en artículos/cláusulas individuales.
5. **Clasificación** — un LLM (vía API, salida estructurada) asigna cada cláusula a un título de la taxonomía del país del tenant, usando las descripciones de cada título como contexto.
6. **Extracción del campo comparativo** — normaliza el valor comparable (ej. "15 días", "30% del salario") cuando el título lo requiere.
7. **Score de confianza** — cada cláusula clasificada recibe un nivel de confianza que prioriza la cola de revisión.
8. **Revisión humana** — el rol Revisor valida o corrige clasificación, resumen y campo comparativo antes de publicar. **No negociable.**
9. **Publicación** — la cláusula aprobada queda visible en el reporte web y disponible para comparación.

**Dataset de partida:** ~6.400 artículos ya clasificados (dump legado) y 403 PDFs reales de convenciones venezolanas vinculados a su clasificación. Uso condicionado a confirmar el derecho de reutilización de esos documentos como dataset de prueba (ver Artículo XI).

## Artículo V — Stack técnico

| Componente | Decisión | Motivo |
|---|---|---|
| API principal | C# / .NET (última LTS) | Preferencia confirmada; adecuado para lógica de negocio, multi-tenancy, auth, licenciamiento |
| Servicio de IA | Python (FastAPI), microservicio separado, consumido async vía cola | Ecosistema natural para LLM/OCR/texto; desacopla de la API |
| Motor de IA | LLM vía API (Claude), salida estructurada | Evita entrenar/mantener modelos propios; usa las ~6.400 cláusulas como referencia |
| Base de datos | PostgreSQL o Azure SQL, multi-tenant por columna `tenant_id` | Esquema compartido simple para arrancar; migrable a aislamiento por tenant |
| Almacenamiento de documentos | Blob storage cifrado en reposo (Azure Blob / S3) | Los PDFs no deben vivir en el filesystem de la app, a diferencia del legado |
| Cola de tareas | Azure Service Bus / RabbitMQ | Desacopla ingesta/extracción IA del resto de la app |
| Frontend | SPA (React) | Portal de tenant, cola de revisión, reportes web |
| Autenticación | OIDC estándar + SSO/SAML vía proveedor (WorkOS o Auth0) | SAML propio es costoso y frágil de mantener |
| Infraestructura | Contenedores (Docker) en Azure Container Apps, con ruta a Kubernetes | Simple para demo, escala sin reescritura |

## Artículo VI — Seguridad y privacidad (no negociables)

1. Documentos **privados por defecto**; solo públicos si el usuario lo declara explícitamente y, cuando aplica, se valida accesibilidad sin autenticación de la URL de origen.
2. **Aislamiento de datos por tenant obligatorio** en cada consulta (filtro por `tenant_id` / row-level security).
3. Cifrado en tránsito y en reposo para documentos y base de datos.
4. Contraseñas con hashing moderno (bcrypt/argon2). **No se migran** la tabla de usuarios ni las contraseñas del sistema legado — sus valores no corresponden a un hash seguro. Se fuerza restablecimiento para todo usuario heredado.
5. Bitácora de auditoría sobre cambios y aprobaciones de cláusulas, continuando el concepto de `bitacoras` del legado.

## Artículo VII — Roles, autenticación empresarial y licenciamiento

1. Los roles se configuran en instalación (como en el legado: Master, Operador, Transcriptor, perfiles por módulo). Set base propuesto: **Admin Tenant, Revisor, Editor, Visualizador**, extensible por el cliente.
2. SSO/SAML vía proveedor de identidad (WorkOS o Auth0) debe estar previsto en la arquitectura desde el inicio, aunque no se active para la demo — retrofitear SSO sobre multi-tenancy ya construido es considerablemente más caro.
3. Licenciamiento: **anual por tenant**, con tier según número de usuarios, volumen de documentos/mes y países habilitados, facturable anual o trimestral. Vencimiento y límites se modelan a **nivel de tenant** (no de usuario individual) y se hacen cumplir mediante feature flags.

## Artículo VIII — Escalabilidad

1. Punto de partida: multi-tenancy por columna (`tenant_id`) sobre base de datos gestionada, con el servicio de IA como worker asíncrono separado — suficiente para demo y primeros clientes.
2. Ruta de crecimiento: aislamiento de datos por tenant (schema o BD dedicada) para clientes grandes que lo exijan por compliance; escalado horizontal del worker de IA independiente de la API; caché/reuso de extracciones para documentos públicos compartidos entre tenants.

## Artículo IX — Migración desde el sistema legado

1. El sistema legado se trata como **especificación funcional, no como base de código a portar**.
2. **Se reutiliza:** taxonomía de categorías y títulos (adaptada y versionada por país); ~6.400 artículos clasificados como dataset de referencia; 403 PDFs como set de prueba del extractor; la lógica de negocio del flujo categoría → título → campo comparativo → reporte.
3. **No se reutiliza:** código PHP4/5 (incompatible con PHP moderno, sin framework); credenciales en texto plano; consultas sin sanitizar (`$_GET` directo en SQL); tabla de usuarios y contraseñas; flujo de captura 100% manual.
4. Se construyen **scripts ETL puntuales** para migrar tablas de referencia (países, categorías, títulos, empresas, contratos, artículos) del dump MySQL hacia el nuevo modelo, como datos semilla e históricos.

## Artículo X — Roadmap

| Fase | Contenido |
|---|---|
| Fase 0 — Validación | Validación legal de la taxonomía por país con abogados locales; confirmación de derechos de uso del dataset histórico |
| Fase 1 — MVP Venezuela | Carga de documentos (URL o archivo), clasificación asistida por IA, cola de revisión interna, reporte web básico, roles base, un solo país |
| Fase 2 — Expansión de países | Activación de Uruguay, Argentina y Chile con taxonomía propia; licenciamiento anual con enforcement; roles configurables en instalación |
| Fase 3 — Escala empresarial | SSO/SAML vía proveedor de identidad; aislamiento de datos para clientes grandes; ciclo de mejora del extractor con correcciones acumuladas de revisión humana |

## Artículo XI — Riesgos abiertos y gobernanza de cambios

Estos puntos están **pendientes de resolución** y bloquean decisiones downstream hasta cerrarse:

1. Validación legal por país pendiente: la taxonomía de Uruguay, Argentina y Chile necesita revisión de abogado laboral local antes de activarse comercialmente.
2. Derecho de uso de los 403 PDFs y ~6.400 artículos del legado como dataset de prueba — confirmar si son de registro público en cada caso.
3. SLA de revisión humana no definido: tiempo esperado para aprobar un documento cargado y criterio de priorización de la cola por confianza del modelo.
4. Definición fina de los tiers de licencia (números concretos de usuarios/documentos por plan) pendiente de conversación comercial.

**Regla de enmienda:** cualquier cambio a los artículos I, IV o VI (alcance, revisión humana obligatoria, seguridad) requiere actualizar explícitamente este documento y registrar la razón del cambio. Los demás artículos pueden evolucionar durante el diseño detallado sin romper la constitución, siempre que no contradigan estos tres.
