# Spec — Empresa, Revisión y Comparación (Fase 2)

> **Depende de:** `constitution.md` v2.0.0 (Art. I.3, II.5, III, IV.7-9)
> **Estado:** borrador — primera fase después del MVP técnico ya desplegado (`spec-mvp-demo.md`)
> **Objetivo:** dar cuerpo al catálogo de Empresas, la cola de revisión humana (Art. IV.8 —
> ya era no negociable en la constitución original, pero nunca se construyó) y el
> comparador cross-empresa dentro del catálogo de un tenant — el núcleo del producto
> original (`legacy/admin/modulos/contratos/comparacion/comparador.php`).

## 1. Alcance de esta fase

| Incluido | Excluido (fase posterior) |
|---|---|
| Entidad Empresa: CRUD dentro de un tenant | Negociación (Fase 3) |
| Catálogos globales de segmentación (sector, tipo, categoría, actividad, geografía) | Marco legal / verificación de cumplimiento (Fase 4) |
| Vincular Documento a una Empresa (hoy `documentos` solo tiene `tenant_id`) | Rol Plataforma con UI propia (Fase 5 — sigue siendo `seed_admin_user.py` manual) |
| Cola de revisión: listar cláusulas pendientes, priorizadas por confianza, aprobar/corregir/rechazar | Expansión a Uruguay/Argentina/Chile (Fase 6) |
| Publicación real (Art. IV.9): solo cláusulas aprobadas quedan disponibles para comparar | Facturación real |
| Comparador: título comparativo + filtros (sector/tipo/actividad/geografía) + selección de empresas del catálogo propio, vista lado a lado | |

## 2. Modelo de datos nuevo/modificado

- `empresas` (`tenant_id` FK, `nombre`, `rif`, `sector_id`, `tipo_id`, `categoria_id`, `actividad_id`, `pais_id`, `estado_id`, `localidad_id`, contacto)
- `sectores`, `tipos_empresa`, `categorias_sector`, `actividades_empresa` — catálogos **globales**, sin `tenant_id` (Art. II.5)
- `estados`, `localidades` — jerarquía geográfica bajo `paises`, también globales
- `documentos.empresa_id` (FK nueva) — ver pregunta abierta sobre migración de los documentos ya existentes
- `clausulas.estado_revision` (pendiente/aprobado/rechazado), `clausulas.revisado_por`, `clausulas.revisado_at`

## 3. Permisos (extiende `auth-spec.md` §5)

| Acción | Admin Tenant | Revisor | Editor | Visualizador |
|---|---|---|---|---|
| Gestionar empresas del catálogo | ✅ | ❌ | ✅ | ❌ |
| Ver cola de revisión / aprobar-corregir | ✅ | ✅ | ❌ | ❌ |
| Ver comparador / reportes publicados | ✅ | ✅ | ✅ | ✅ |

Los catálogos globales (sector/tipo/actividad/geografía) **no son editables por ningún rol de
tenant** en esta fase — su administración queda para el rol de Plataforma (Fase 5).

## 4. Criterio de éxito

Un Admin Tenant puede: dar de alta 2+ empresas de su catálogo con distinto sector, tener un
documento clasificado para cada una, pasar sus cláusulas por la cola de revisión, aprobarlas,
y comparar el mismo título de taxonomía entre esas dos empresas filtrando por sector.

## 5. Decisiones cerradas

- **Migración de documentos sin `empresa_id`:** al agregar la columna, cada tenant recibe
  una empresa por defecto ("Empresa Demo", o el `nombre_empresa` que ya tenga el tenant) y
  todos sus documentos existentes se vinculan a ella. Nadie queda huérfano.
- **Score de confianza (Art. IV.7):** auto-reporte del modelo — se agrega un campo de
  confianza (alto/medio/bajo) al mismo `output_config.schema` de `classify_clause()`
  (`service/app/classification.py`), junto a `titulo_id`, en la misma llamada que ya se
  hace. Sin costo ni latencia adicional. Es una señal blanda (los LLM tienden a
  sobreestimar su propia confianza) pero alcanza para el único uso que exige el Art. IV.7:
  ordenar la cola de revisión, no certificar nada. Si en producción se ve que el modelo
  reporta "alto" casi siempre y la señal deja de ser útil para ordenar, la alternativa a
  evaluar es comparar por similitud contra el dataset histórico de ~6.400 cláusulas
  (Art. IV, Art. IX) — pero no se construye ahora, se deja señalado.

## 6. Preguntas abiertas

Ninguna pendiente por ahora — las dos de la sección anterior quedaron resueltas.

## 7. Plan de implementación

Cinco bloques, en este orden — cada uno depende de que el anterior esté cerrado (B necesita
A para tener qué referenciar, C necesita B para tener a qué empresa vincular el documento,
etc.). No tiene sentido adelantar un bloque sin el previo.

### A. Catálogos globales de segmentación ✅ terminado

- [x] Diseñar el schema: `sectores`, `tipos_empresa`, `categorias_sector`, `actividades_empresa`, `estados`, `localidades` — agregado a `schema.sql` (instalación nueva) y a `service/db/migrations/003_catalogos_empresa.sql` (base ya desplegada)
- [x] Conseguir/curar el dataset real venezolano — extraído de `presenci_cccol.sql` (dump legado) con un parser de tuplas SQL propio; HTML limpiado, doble-encoding reparado con `ftfy`, y 23 nombres de localidades con la tilde perdida corregidos a mano (confirmados con Alex) + 1 duplicado del legado excluido (`Morón` bajo Lara). Datos curados en `docs/catalogos_empresa_venezuela.json`
- [x] Script de seed (`service/db/seed_catalogos_empresa.py`) — idempotente, verificado corriéndolo dos veces seguidas contra Postgres real (mismos conteos)
- [x] Endpoint de solo lectura (`GET /catalogos`, `GET /catalogos/localidades?estado_id=`) — verificado con requests HTTP reales, no solo tests

Verificado de punta a punta contra un Postgres 16 real en Docker: `schema.sql` fresco, la migración `003` sola (simulando la base ya desplegada en Azure), los dos seeds corridos dos veces (idempotencia), y los dos endpoints respondiendo 200 con acentos correctos.

### B. Entidad Empresa ✅ terminado

- [x] Migración: tabla `empresas` — agregada a `schema.sql` y a `service/db/migrations/004_empresas.sql`
- [x] Backend: `POST/GET/PUT /empresas` (+ `GET /empresas/{id}`), mismo patrón que `/tenants`
- [x] Frontend: `web/src/empresas/EmpresasPage.jsx` — alta con selects encadenados (sector/tipo/categoría/actividad/estado→localidad) + listado
- [x] Matriz de permisos de la sección 3 aplicada (`require_role(request, "AdminTenant", "Editor")` en los 4 endpoints)

Verificado en el navegador contra el stack completo (Postgres real + API .NET vía
`docker compose` + servicio Python + Vite dev server): login → cambio de contraseña
obligatorio → creación de una empresa (Bolívar → Puerto Ordaz, cascada estado→localidad en
vivo) → confirmado `POST /empresas → 201` por red y la fila apareciendo en la tabla del
catálogo. No solo tests — la interacción real.

### C. Vincular Documento a Empresa ✅ terminado

- [x] Migración: `documentos.empresa_id` (nullable primero, luego `NOT NULL`) — `db/migrations/005_documentos_empresa.sql`
- [x] Migración de datos: cada tenant sin empresas recibe una por defecto (su propio `nombre_empresa`, no "Empresa Demo" a secas para no duplicar el nombre del tenant) y se le asignan sus documentos existentes
- [x] Backend: `POST /documentos` exige `empresa_id`, valida que pertenezca al tenant del JWT
- [x] Frontend: selector de empresa en `DocumentUploadForm.jsx`, con aviso si el catálogo está vacío

### D. Cola de revisión (Art. IV.8) ✅ terminado

- [x] Migración: `clausulas.confianza`, `estado_revision`, `revisado_por`, `revisado_at` — `db/migrations/006_cola_revision.sql`
- [x] `classification.py`: campo de confianza agregado al schema de salida de `classify_clause()`, sin costo/latencia extra
- [x] Backend: `GET /revision` (priorizada: confianza baja o sin clasificar primero), `POST /revision/{id}/aprobar` (admite corrección de título en el mismo gesto), `POST /revision/{id}/rechazar`
- [x] Frontend: `web/src/revision/RevisionPage.jsx`
- [ ] Actualizar `auth-spec.md` §5 con las filas nuevas de la sección 3 — pendiente, no bloquea nada

### E. Comparador ✅ terminado

- [x] Backend: `GET /comparador/titulos` (solo títulos con al menos una cláusula aprobada) y `GET /comparador` (filtro por título + sector/tipo/categoría/actividad/estado; **solo** cláusulas aprobadas, Art. IV.9), agrupado por empresa
- [x] Frontend: `web/src/comparador/ComparadorPage.jsx`
- [x] Criterio de éxito de la sección 4 validado de punta a punta (ver abajo)

**Verificación de punta a punta de C+D+E**, en el navegador contra el stack completo
(Postgres real + API .NET + servicio Python + Vite, todo vía `docker compose` + uvicorn
manual): creé una empresa → subí un documento real (PDF con texto nativo generado para la
prueba, no el dummy escaneado de W3C que probé primero y confirmó el manejo de errores) →
la cláusula quedó `pendiente` sin clasificar (API key de prueba, sin llamar a Claude de
verdad) → en la cola de revisión elegí el título correcto y aprobé en un solo gesto → la
cláusula salió de la cola → en el Comparador, el selector de títulos mostró **solo**
"Ambito de Aplicación" (el único aprobado) → al comparar, apareció "Textiles del Sur C.A."
con su cláusula. Confirmado también por red: `POST /empresas → 201`, `POST /documentos →
201`, `POST /revision/1/aprobar → 200`, `GET /comparador?titulo_id=80 → 200`.

De paso se encontraron y corrigieron dos cosas durante la prueba:
- Ninguna de las pantallas nuevas (Empresas/Revisión/Comparador) tenía un link de vuelta a
  "Documentos" — se agregó a las tres.
- El contenedor `web` de `docker compose` no detectaba cambios de archivo hechos desde el
  host (problema de file-watching en volúmenes bind-mounted en Windows) — hace falta
  `docker compose restart web` después de editar código si el navegador sigue sirviendo la
  versión vieja pese al refresh.

## 8. Checklist resumido — para ver de un vistazo dónde estamos

- [x] A. Catálogos globales de segmentación
- [x] B. Entidad Empresa
- [x] C. Documento ↔ Empresa
- [x] D. Cola de revisión (Art. IV.8)
- [x] E. Comparador

**Fase 2 completa.** Queda pendiente, sin bloquear nada: actualizar `auth-spec.md` §5 con
la matriz de permisos nueva (hoy vive redactada en la sección 3 de este spec, no en el doc
de auth real).

*(Se actualiza a medida que avanzamos — marcar acá, no en otro lado, para que este spec sea la única fuente de verdad de "cuánto falta" de la Fase 2.)*
