## Plan: Migración Progresiva a Laravel/MySQL

Preparar una alternativa autónoma Laravel/MySQL para el cliente, operada en infraestructura del cliente, que alcance paridad funcional con el legacy mediante releases progresivos. La primera capacidad funcional priorizada es la bitácora de negociación. Se migrará el histórico de negocio y documentos; las cuentas legacy se excluyen salvo la creación de un superusuario inicial. El primer release incluye administración y portal público, mostrando únicamente contenido que el legacy identifica como publicado. El legacy está congelado y se retirará después de verificar datos, documentos, permisos, contenido público y redirecciones.

**Decisiones confirmadas**
- Laravel/MySQL será una plataforma alternativa autónoma, no una integración con .NET.
- Objetivo final: paridad funcional completa; entregas por fases aceptadas.
- Tenant: un cliente al inicio, con aislamiento explícito y preparación para varios tenants.
- Fuente: dump de producción `legacy/presenci_cccol (1).sql`, congelado, aproximadamente 51 MB y 35 tablas según extracción de metadatos. Los registros no se inspeccionaron.
- Archivos: se encontraron 407 PDF/DOC; el usuario confirma que representan el conjunto completo. Su migración queda sujeta a confirmar derechos de uso y retención.
- Datos: conservar el histórico completo; no importar las cuentas ni contraseñas de usuarios existentes. Provisionar un superusuario en el primer release; crear otras cuentas bajo demanda.
- Primera capacidad de negocio: bitácora, con sus dependencias de empresas y catálogos.
- Primera entrega incluye portal público y administración; el portal expone solo contratos/noticias que respetan los estados publicados del legacy.
- Hosting del cliente; proponer el entorno y no asumir proveedor/versiones. Usar dominio nuevo con redirecciones.
- Sin fecha objetivo comprometida. El equipo previsto es una persona con apoyo de IA. Hay referente funcional disponible.
- Retirar el legacy después de validar la migración; acordar retención operativa/legal antes del apagado.

**Fases**
1. **Preparación, seguridad y criterios de éxito**
   - Confirmar autorización, retención y derechos de reutilización de documentos y datos; tratar el dump y adjuntos como producción, con acceso mínimo, almacenamiento seguro y sin mostrar datos personales en informes.
   - Verificar estado congelado, origen/cobertura del dump y archivos, codificación, versión del motor MySQL, referencias de documentos, base de URLs actuales y configuración de publicación.
   - Documentar gates de aceptación: cobertura de tablas y filas, relaciones, checksums de archivos, flujos aprobados por referente, permisos, aislamiento tenant, publicación correcta, recuperación y redirects.
   - No trasladar código PHP; caracterizar reglas y reimplementarlas. Resolver la diferencia entre la constitución y `legacy/README.md` antes de reutilizar cualquier dato/documento.
   - **Salida:** alcance firmado, registro de incógnitas, permisos de manejo de datos, criterios de aceptación y decisión de retención.

2. **Descubrimiento funcional y caracterización**
   - Mapear los módulos completos: empresas y catálogos, contratos/artículos/anexos, comparación, bitácora/peticiones/ofertas/reuniones/acuerdos, leyes, seguridad, perfil, noticias, boletín, links y páginas públicas.
   - Con el referente, confirmar reglas, estados, transiciones, reportes, roles y contenido público. Clasificar cada comportamiento como requerido, dependiente de confirmación, defecto, deuda, obsoleto o desconocido.
   - Caracterizar con casos de aceptación la bitácora (crear/editar/cerrar/reabrir y relacionar peticiones, ofertas, reuniones y acuerdos); documentar dependencias de empresas y títulos comparativos.
   - Inventariar rutas antiguas y formatos de URLs necesarios para el mapa de redirecciones.
   - **Salida:** catálogo priorizado de capacidades, flujos y criterios de aceptación; mapa de rutas y decisiones pendientes.

3. **Modelo de datos, calidad y migración documental**
   - Elaborar diccionario de datos de las 35 tablas, relaciones lógicas, claves, dominios de estado, fechas, identificadores y dependencias. Verificar constraints reales; no inferir su ausencia solo desde consultas PHP.
   - Analizar el dump en un entorno controlado sin exportar datos sensibles a logs/respuestas. Medir registros por tabla, encoding, duplicados, valores inválidos, huérfanos y referencias rotas.
   - Relacionar cada referencia almacenada con los archivos de contratos, anexos, negociación, leyes, noticias y empresas. Detectar faltantes, duplicados, nombres ambiguos y archivos sin registro; calcular checksums para validar transferencias.
   - Diseñar importación repetible y no destructiva con mapa de IDs legacy, transformaciones explícitas para fechas/estados, reporte de excepciones y rollback/restauración desde backups probados.
   - Excluir credenciales/cuentas legacy de autenticación; conservar solo datos históricos necesarios y aprobados, sin habilitar su uso como login.
   - **Salida:** diccionario validado, informe de calidad, reglas de transformación, manifiesto de archivos y plan de importación/rollback aprobado.

4. **Fundación de la aplicación y entorno del cliente**
   - Crear la aplicación Laravel/MySQL según versiones y requisitos aprobados; separar configuración/secrets de código, formalizar ambientes y procedimientos de despliegue y respaldo.
   - Establecer arquitectura pragmática por límites Presentation/Application/Domain/Infrastructure y módulos, evitando microservicios y capas innecesarias.
   - Implementar autenticación segura, provisioning del superusuario, autorización server-side, auditoría de operaciones sensibles, validación/CSRF, manejo de errores y logging sin secretos/PII.
   - Definir y hacer cumplir el tenant boundary desde el servidor. Inicializar un tenant, pero probar aislamiento como si existieran varios; definir cómo se resuelve tenant en UI, API, consultas y archivos.
   - Almacenar documentos fuera de rutas públicas directas; entregar acceso mediante autorización o URLs públicas controladas por estado de publicación. Validar contenido/tamaño, nombres seguros, permisos, integridad y errores de procesamiento.
   - Preparar despliegue en infraestructura administrada por el cliente con health checks, backups/restore, logging, monitoreo, HTTPS y guía operativa; seleccionar proveedor/servicios solo tras requisitos del cliente.
   - **Salida:** entorno no productivo operable, modelo de seguridad/tenancy revisado, estrategia de backup/restore probada y esqueleto de portal/admin.

5. **Release inicial: portal público y bitácora**
   - Cargar empresas y los catálogos/títulos mínimos requeridos como prerequisitos de la bitácora.
   - Importar el histórico de negocio/documentos de esta capacidad con reconciliación de relaciones y checksums.
   - Entregar gestión de bitácora y relaciones de peticiones, ofertas, reuniones y acuerdos; preservar estados confirmados y permitir auditoría de cambios según aprobación funcional.
   - Entregar el portal público inicial con navegación y contenido aprobado, respetando exactamente el filtro de estados publicados; todo lo no publicado permanece privado. Administración autenticada solo con el superusuario inicialmente.
   - Probar autorización de acciones, archivos, tenant, búsquedas públicas, errores y comportamiento de los estados con datos representativos anonimizados donde sea posible.
   - **Gate:** aprobación funcional del referente, reconciliación de datos/documentos, pruebas de seguridad y aceptación operativa del cliente.

6. **Releases de paridad funcional**
   - Migrar gestión de contratos, cláusulas/artículos, anexos, publicación y operación documental; preservar versiones/originales y las referencias heredadas.
   - Migrar leyes y artículos; integrar comparación de contratos únicamente después de validar títulos, categorías, reglas y resultados con ejemplos acordados.
   - Migrar administración de empresas/catálogos restantes, usuarios y permisos bajo el modelo nuevo; cuentas legacy siguen excluidas y las nuevas se crean bajo demanda.
   - Incorporar noticias, boletín, links, perfil y demás funciones incluidas en paridad, según inventario y aceptación funcional; no retirar automáticamente duplicados/variantes hasta confirmar uso.
   - En cada release: pruebas unitarias/feature/integración, permisos y tenant, regresión de portal, importación/reconciliación, backup/rollback, despliegue independiente y métricas/logs suficientes.
   - **Gate por módulo:** comportamiento aprobado, datos verificados, seguridad/tenant aprobados, documentación de operación y dependencias legacy identificadas.

7. **Cutover, redirects y retiro del legacy**
   - Ejecutar ensayo completo de importación y cutover en entorno del cliente; comparar tablas/relaciones, estados visibles y checksums de documentos con criterios acordados.
   - Cambiar dominio/rutas mediante mapa de redirects; probar enlaces principales y documentos públicos. Confirmar explícitamente que borradores y registros privados no se exponen.
   - Mantener ventana de observación y rollback acordada. Aunque el legacy esté congelado, conservar backups y evidencias verificables antes de apagarlo.
   - Retirar legacy solo tras aprobación del referente/cliente, recuperación probada y confirmación de retención legal/operativa. Desactivar credenciales legacy y documentar componentes retirados.
   - **Salida:** servicio Laravel aceptado, histórico verificable, legacy retirado de forma controlada y registros de decisiones/retención.

**Archivos y superficies relevantes**
- `docs/constitution.md` — reglas obligatorias de seguridad, datos, tenants, incrementalidad y Definition of Done.
- `docs/inititial_prompt.md` — inventario y alcance de análisis solicitado.
- `legacy/README.md` — advertencia de no copiar lógica y derechos pendientes; resolver su diferencia documental antes de uso de datos.
- `legacy/presenci_cccol (1).sql` — fuente del esquema/datos de producción; manejar como información sensible.
- `legacy/admin/modulos/contratos/settings.php` y `legacy/lib/objetos/discusion.php` — mapas funcionales a caracterizar para bitácora y contratos.
- `legacy/lib/objetos/contratos.php` y `legacy/lib/objetos/empresas.php` — consultas, relaciones y campos usados por contratos/empresas.
- `legacy/lib/objetos/user.php`, `legacy/lib/objetos/seguridad.php`, `legacy/admin/index.php` y `legacy/admin/modulos/contratos/index.php` — riesgos de autenticación, permisos y rutas que no deben reproducirse.
- Árboles documentales en `legacy/admin/modulos/contratos/`, `legacy/admin/modulos/leyes/` y `legacy/admin/modulos/tablas/` — manifiesto, referencias, clasificación y checksums.

**Verificación**
1. Ensayo de importación en base aislada con conteos por tabla y reporte firmado de exclusiones/transformaciones; segunda ejecución sin duplicados ni pérdida.
2. Validación de relaciones empresariales, contratos, bitácoras, peticiones/ofertas/acuerdos y leyes frente a consultas de origen aprobadas.
3. Comparación de archivos por referencia, tamaño y checksum; faltantes/huérfanos resueltos o aceptados explícitamente.
4. Pruebas de escenarios de negocio del referente, incluidas transiciones de estado y comparación de resultados cuando aplique.
5. Pruebas de acceso: visitante solo ve publicados; superusuario gestiona; archivos privados bloqueados; separación entre tenants; CSRF y autorización server-side.
6. Pruebas de redirects, HTTPS, despliegue, logs sin datos sensibles, backup y restauración real en infraestructura del cliente.
7. Criterios de Definition of Done de la constitución por módulo antes de retirar la parte equivalente del legacy.

**Supuestos y fuera de alcance por ahora**
- No se decide proveedor cloud ni versiones PHP/Laravel/MySQL hasta levantar restricciones del cliente.
- No se asume que todos los estados/rutas visibles están vigentes; se confirman con referente y datos.
- No se incorporan funcionalidades de IA/document intelligence nuevas en la primera planificación; cualquier derivado futuro debe tener trazabilidad al documento fuente y no sobrescribirlo.
- El plan no promete fechas ni esfuerzo calendario: equipo de una persona, entorno objetivo y reglas de negocio todavía impiden estimar con rigor.
- El legacy no recibirá nuevas escrituras durante el proyecto; si esta premisa cambia, se debe replanificar el mecanismo de sincronización/corte.
