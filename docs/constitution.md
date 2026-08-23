# Constitución del Proyecto — Comparador de Documentos Legales

> **Versión:** 2.0.0 · **Ratificada:** 2026-08-08 · **Última enmienda:** 2026-08-23
> **Origen:** `documento_arquitectura_comparador_convenciones.docx` (preparado para Alex Campos, 8 de agosto de 2026)
> **Enmienda 2.0.0:** redefine el modelo de tenant (Art. I.3) tras revisar el código legado
> completo (`legacy/`). El valor original del producto era la comparación cross-empresa
> dentro del catálogo propio de un operador (consultora, firma, cámara de comercio) — no
> el autoservicio de una sola empresa. Se incorporan las entidades Empresa, Negociación y
> Marco Legal, y se replantea el roadmap (Art. X). Registrado según la regla de enmienda
> del Art. XI (cambio a Art. I).

Este documento fija los principios y decisiones de arquitectura que gobiernan el diseño e implementación del nuevo Comparador de Documentos Legales. Cualquier decisión técnica o de producto que lo contradiga debe justificarse explícitamente y, si se acepta, disparar una enmienda a esta constitución.

## Preámbulo

El sistema reemplaza un SaaS PHP de ~20 años ("convenciones") que comparaba convenciones colectivas de trabajo mediante clasificación 100% manual. **El legado no era una herramienta de autoservicio para una sola empresa: era el catálogo propio de un operador (consultora de RRHH, cámara de comercio) que comparaba entre muchas empresas de su cartera.** El sistema nuevo preserva esa esencia — comparación dentro del catálogo de un operador — y automatiza la lectura, clasificación y extracción de cláusulas mediante IA, preservando un paso de revisión humana obligatorio antes de publicar cualquier contenido. Además cubre el proceso de negociación colectiva previo a la firma, que el legado también tenía (módulo de discusión) y que la versión 1.0 de esta constitución no contemplaba.

## Artículo I — Alcance y no-alcance

1. El sistema trata **exclusivamente convenciones colectivas de trabajo**, no documentos legales genéricos. No se diseña clasificación genérica para otros tipos de documento en esta fase.
2. Países soportados: **Venezuela, Uruguay, Argentina y Chile**. Cada país tiene su propia taxonomía de cláusulas, validada legalmente antes de activarse.
3. **Modelo tenant–operador: 1 tenant = 1 operador (consultora, firma de abogados, cámara de comercio) que gestiona un catálogo propio de empresas-cliente, en un país.** Una Empresa (Art. III) pertenece a exactamente un tenant; el mismo operador puede tener muchas empresas en su catálogo. No hay agregación cross-tenant: dos operadores nunca comparten catálogo de empresas ni comparación entre sí (Art. VI.2 sigue aplicando al nivel de tenant, ahora sobre el catálogo completo de un operador, no sobre una sola empresa). **[Enmienda 2.0.0 — reemplaza "1 tenant = 1 empresa/firma en 1 país"]**
4. Todo el contenido generado por IA pasa por **revisión interna obligatoria** antes de publicarse; no hay publicación automática sin intervención humana.
5. El entregable al usuario final es dual pero de fuente única: **reporte (PDF/export) y vista web navegable**, generados desde el mismo dato estructurado.
6. **El sistema cubre dos momentos de una convención: antes de la firma (negociación — peticiones, ofertas, acuerdos, reuniones, Art. IV bis) y después de la firma (documento final — ingesta, clasificación, comparación, Art. IV). El cierre de una negociación con acuerdo genera el Documento que entra al segundo flujo; ambos comparten el mismo modelo de Empresa y Tenant. [Enmienda 2.0.0 — nuevo]**

## Artículo II — Modelo de dominio y taxonomía

1. El sistema legado organiza cláusulas en dos niveles: **5 categorías** (GENERALES, ECONÓMICO, SOCIOECONÓMICAS, SINDICALES, SEGURIDAD OCUPACIONAL) y **~60 títulos comparativos** dentro de ellas, cada uno con un indicador de si requiere campo de comparación económica.
2. Esta estructura conceptual es transversal a los cuatro países (remuneración, beneficios/condiciones de trabajo, aportes sindicales), confirmado contra los marcos legales de Argentina (Ley 14.250), Uruguay (Ley 18.566) y Chile (Código del Trabajo, Libro IV).
3. Diseño obligatorio: **núcleo de categorías común a los cuatro países**, con una **capa de títulos versionada por país** que permite agregar, renombrar o desactivar títulos sin tocar el núcleo.
4. Ningún país nuevo se activa comercialmente sin que su taxonomía haya sido **validada por un abogado laboral local**. La investigación web no sustituye esta validación.
5. **Los catálogos de segmentación de empresas (sector, tipo de empresa, categoría de sector, actividad económica) y de geografía (país, estado, localidad) son globales, compartidos por todos los tenants — no se duplican por operador.** Son datos de referencia objetivos (ej. "Sector: Manufactura" es el mismo dato para cualquier consultora). **[Enmienda 2.0.0 — nuevo]**
6. **El marco legal (leyes y artículos de ley) es un catálogo por país, igual que la taxonomía. No es solo referencia de consulta: alimenta la verificación de cumplimiento legal (Art. IV.5 bis) — cruza cada cláusula clasificada contra los artículos de ley relacionados a su título, y señala si está por debajo, iguala, o supera el mínimo legal. Esta señal es asistencia para la revisión humana, nunca una determinación legal vinculante ni asesoría legal automatizada — el Revisor (Art. VII) es quien decide, igual que ningún resultado se publica sin su aprobación (Art. IV.8, no negociable). [Enmienda 2.0.0 — nuevo]**

## Artículo III — Modelo de datos

Entidades fundamentales (nombres conceptuales, no nombres de tabla definitivos):

| Entidad | Descripción | Origen |
|---|---|---|
| Tenant | **Operador (consultora/firma/cámara) cliente del SaaS**; asociado a país, plan de licencia y fecha de vencimiento. Gestiona un catálogo propio de Empresas. | Nuevo — el legado no tenía multi-tenancy; era un solo operador global |
| **Empresa** *(nuevo)* | Empresa-cliente cuya(s) convención(es) el tenant analiza/compara: nombre, RIF, sector, tipo, categoría, actividad, país/estado/localidad, contacto. Pertenece a un tenant. | Adaptado de `empresas.php` |
| Usuario / Rol | Pertenece a un tenant (excepto el rol de Plataforma, Art. VII.4); rol entre Admin Tenant, Revisor, Editor, Visualizador, definido en instalación | Adaptado de `tipos_de_usuarios` y `seguridades` |
| País | Catálogo de países soportados con su marco legal de referencia | Adaptado de `paises` |
| **Catálogos de segmentación** *(nuevo)* | Sector, tipo de empresa, categoría de sector, actividad económica, geografía (país/estado/localidad) — globales (Art. II.5) | Adaptado de `sectores`, `tipos_empresas`, `categoria_sector`, `actividad_empresa`, `estados`/`localidad` |
| Categoría / Título | Árbol de clasificación de cláusulas, versionado por país | Adaptado de `categorias_titulos` y `titulos` |
| **Ley / Artículo de ley** *(nuevo)* | Corpus legal por país (ej. Ley Orgánica del Trabajo, otras leyes), vinculado a títulos de taxonomía; alimenta la verificación de cumplimiento (Art. IV.5 bis) | Adaptado de `ley_trabajo`, `otras_leyes`, `articulos_ley_trabajo` |
| **Negociación** *(nuevo)* | Proceso de negociación colectiva de una Empresa antes de firmar: peticiones (sindicato), ofertas (empresa), reuniones, acuerdos. Al cerrar con acuerdo, genera el Documento (Art. IV). Bitácora propia (`bitacora_negociacion`), **distinta** de la bitácora de accesos | Adaptado del módulo `discusion` |
| Documento / Contrato | Convención capturada por URL, carga, **o generada al cerrar una Negociación**; guarda tenant, **empresa**, país, vigencia, estado público/privado | Adaptado de `contratos`, ahora vinculado a Empresa además de Tenant |
| Cláusula / Artículo | Unidad extraída: número, texto, resumen, título asignado, campo comparativo, estado de revisión, confianza del modelo, **señal de cumplimiento legal** *(nuevo)* | Adaptado de `articulos_contratos` |
| Bitácora de accesos | Registro de login/logout/fallos y aprobaciones de cláusulas — **distinta de la bitácora de negociación** (arriba) | Adaptado de `bitacoras` |
| Licencia | Plan del tenant: fechas, límites de usuarios/documentos, países habilitados | Nuevo |
| Reporte de comparación | Selección de empresas/documentos/cláusulas del catálogo del tenant, filtros por sector/tipo/actividad/geografía; alimenta vista web y export | Adaptado del módulo `comparador.php` |

## Artículo IV bis — Negociación colectiva (pre-firma) *(nuevo)*

Flujo para una Empresa que está negociando una convención nueva, antes de que exista un
documento firmado. No reemplaza el Art. IV — lo antecede. Una Empresa puede tener
documentos que nunca pasaron por este flujo (convenciones históricas cargadas directo).

1. **Petición** — el sindicato registra una petición: número, título, texto completo, título de taxonomía sugerido (opcional, Art. II).
2. **Oferta** — la empresa registra su respuesta a una petición.
3. **Reunión** — se registra una sesión de negociación (fecha, asistentes, resumen), vinculada a la bitácora de negociación de la Empresa.
4. **Acuerdo** — cuando petición y oferta convergen, se registra el acuerdo alcanzado para esa cláusula.
5. **Cierre** — cuando los títulos relevantes tienen acuerdo, se cierra la negociación y se genera el Documento a partir de los acuerdos, listo para el pipeline del Art. IV.

## Artículo IV — Pipeline de extracción y clasificación con IA

Flujo obligatorio, en este orden, con revisión humana como puerta de publicación (no opcional):

1. **Ingesta** — el tenant aporta el documento por URL pública, carga de archivo (PDF/Word), **o cierre de una Negociación (Art. IV bis.5)**. **[Enmienda 2.0.0]**
2. **Check público/privado** — el documento es privado por defecto; si se marca público vía URL, debe validarse que sea accesible sin autenticación antes de tratarlo como tal.
3. **Extracción de texto** — parseo de PDF/Word; OCR automático si es un escaneo.
4. **Segmentación** — división del texto en artículos/cláusulas individuales.
5. **Clasificación** — un LLM (vía API, salida estructurada) asigna cada cláusula a un título de la taxonomía del país del tenant, usando las descripciones de cada título como contexto.
5 bis. **Verificación de cumplimiento legal** *(nuevo)* — cruza cada cláusula clasificada contra los artículos de ley (Art. II.6) relacionados a su título, y marca una señal: por debajo / iguala / supera el mínimo legal. Es asistencia para el paso 8, nunca una determinación automática vinculante.
6. **Extracción del campo comparativo** — normaliza el valor comparable (ej. "15 días", "30% del salario") cuando el título lo requiere.
7. **Score de confianza** — cada cláusula clasificada recibe un nivel de confianza que prioriza la cola de revisión.
8. **Revisión humana** — el rol Revisor valida o corrige clasificación, resumen, campo comparativo **y la señal de cumplimiento legal** antes de publicar. **No negociable.**
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
6. **El rol de Plataforma (Art. VII.4) es la única excepción al aislamiento estricto por tenant de este artículo: opera fuera del modelo de tenant para poder dar de alta operadores nuevos. Sus acciones quedan en una bitácora separada, con el mismo nivel de auditoría que el punto 5. [Enmienda 2.0.0 — nuevo]**

## Artículo VII — Roles, autenticación empresarial y licenciamiento

1. Los roles se configuran en instalación (como en el legado: Master, Operador, Transcriptor, perfiles por módulo). Set base propuesto: **Admin Tenant, Revisor, Editor, Visualizador**, extensible por el cliente.
2. SSO/SAML vía proveedor de identidad (WorkOS o Auth0) debe estar previsto en la arquitectura desde el inicio, aunque no se active para la demo — retrofitear SSO sobre multi-tenancy ya construido es considerablemente más caro.
3. Licenciamiento: **anual por tenant**, con tier según número de usuarios, volumen de documentos/mes y países habilitados, facturable anual o trimestral. Vencimiento y límites se modelan a **nivel de tenant** (no de usuario individual) y se hacen cumplir mediante feature flags.
4. **Rol de Plataforma ("GOD")** *(nuevo)*: pertenece al operador del SaaS (Presencia Virtual), no a un tenant. Da de alta operadores nuevos (tenants) y su primer Usuario Admin Tenant; gestiona licenciamiento y activación comercial de países (Art. II.4). Es la única excepción explícita al aislamiento por tenant (Art. VI.2/VI.6). Queda pendiente de decisión técnica **cómo** modelarlo — ver Art. XI.5.

## Artículo VIII — Escalabilidad

1. Punto de partida: multi-tenancy por columna (`tenant_id`) sobre base de datos gestionada, con el servicio de IA como worker asíncrono separado — suficiente para demo y primeros clientes.
2. Ruta de crecimiento: aislamiento de datos por tenant (schema o BD dedicada) para clientes grandes que lo exijan por compliance; escalado horizontal del worker de IA independiente de la API; caché/reuso de extracciones para documentos públicos compartidos entre tenants.

## Artículo IX — Migración desde el sistema legado

1. El sistema legado se trata como **especificación funcional, no como base de código a portar**.
2. **Se reutiliza:** taxonomía de categorías y títulos (adaptada y versionada por país); ~6.400 artículos clasificados como dataset de referencia; 403 PDFs como set de prueba del extractor; la lógica de negocio del flujo categoría → título → campo comparativo → reporte; **el modelo de catálogo de empresas y comparación cross-empresa (`empresas.php`, `comparador.php`) — es el valor central del producto original; el módulo de negociación (`discusion`: peticiones, ofertas, acuerdos, reuniones); el corpus de leyes (`ley_trabajo`, `otras_leyes`) como base para la verificación de cumplimiento. [Enmienda 2.0.0]**
3. **No se reutiliza:** código PHP4/5 (incompatible con PHP moderno, sin framework); credenciales en texto plano; consultas sin sanitizar (`$_GET` directo en SQL); tabla de usuarios y contraseñas; flujo de captura 100% manual.
4. Se construyen **scripts ETL puntuales** para migrar tablas de referencia (países, categorías, títulos, empresas, contratos, artículos) del dump MySQL hacia el nuevo modelo, como datos semilla e históricos.

## Artículo X — Roadmap *(replanteado, Enmienda 2.0.0)*

| Fase | Contenido |
|---|---|
| Fase 0 — Validación | Validación legal de la taxonomía por país con abogados locales; confirmación de derechos de uso del dataset histórico |
| Fase 1 — Fundaciones técnicas *(ya desplegado, demo interno)* | Carga de un documento por tenant, clasificación asistida por IA, auth básica con roles fijos. **Sin** catálogo de empresas, sin comparación, sin negociación, sin cola de revisión — es la base técnica, no el producto completo (`spec-mvp-demo.md`) |
| Fase 2 — Empresa + Revisión + Comparación | Entidad Empresa y catálogos globales de segmentación (Art. II.5); cola de revisión humana (Art. IV.8 — ya era no negociable, nunca se construyó); comparador intra-tenant filtrado por sector/tipo/actividad/geografía — el núcleo del producto original |
| Fase 3 — Negociación colectiva | Módulo de discusión completo (Art. IV bis): peticiones, ofertas, reuniones, acuerdos |
| Fase 4 — Marco legal y cumplimiento | Corpus de leyes por país (Art. II.6); verificación de cumplimiento activa en clasificación (Art. IV.5 bis) |
| Fase 5 — Plataforma SaaS real | Rol de Plataforma (Art. VII.4) con UI propia; alta de operadores sin script manual; licenciamiento/facturación real |
| Fase 6 — Expansión y escala | Activación de Uruguay, Argentina y Chile (cada uno con validación legal propia); SSO/SAML; aislamiento de datos dedicado para clientes grandes; mejora continua del extractor |

## Artículo XI — Riesgos abiertos y gobernanza de cambios

Estos puntos están **pendientes de resolución** y bloquean decisiones downstream hasta cerrarse:

1. Validación legal por país pendiente: la taxonomía de Uruguay, Argentina y Chile necesita revisión de abogado laboral local antes de activarse comercialmente.
2. Derecho de uso de los 403 PDFs y ~6.400 artículos del legado como dataset de prueba — confirmar si son de registro público en cada caso.
3. SLA de revisión humana no definido: tiempo esperado para aprobar un documento cargado y criterio de priorización de la cola por confianza del modelo.
4. Definición fina de los tiers de licencia (números concretos de usuarios/documentos por plan) pendiente de conversación comercial.
5. **Cómo modelar el rol de Plataforma sin romper el aislamiento por tenant** (tabla separada `usuarios_plataforma` vs. `tenant_id` nullable en `usuarios`) — pendiente de decisión técnica antes de Fase 5. *[nuevo]*
6. **La verificación de cumplimiento legal (Art. IV.5 bis) es asistencia, no asesoría legal** — confirmar con asesoría legal real si hace falta un disclaimer/términos de servicio explícito antes de activarla comercialmente. *[nuevo]*
7. **Los catálogos globales de segmentación (Art. II.5) no deben filtrar datos de un tenant a otro** — hay que confirmar en el diseño que listar/usar estos catálogos nunca permite inferir cuántas empresas de tal sector tiene un competidor. *[nuevo]*

**Regla de enmienda:** cualquier cambio a los artículos I, IV o VI (alcance, revisión humana obligatoria, seguridad) requiere actualizar explícitamente este documento y registrar la razón del cambio. Los demás artículos pueden evolucionar durante el diseño detallado sin romper la constitución, siempre que no contradigan estos tres.
