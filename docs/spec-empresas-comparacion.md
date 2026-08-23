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

### A. Catálogos globales de segmentación

- [ ] Diseñar el schema: `sectores`, `tipos_empresa`, `categorias_sector`, `actividades_empresa`, `estados`, `localidades` (migración nueva en `service/db/migrations/`)
- [ ] Conseguir/curar el dataset real venezolano para estas tablas (revisar si `legacy/` tiene datos ya cargados en estas tablas, no solo el CRUD — si están, es ETL; si no, hay que armarlos a mano)
- [ ] Script de seed (`service/db/seed_catalogos_empresa.py`, mismo patrón que `seed_taxonomia.py`)
- [ ] Endpoint de solo lectura para listarlos (en el servicio Python, junto a `/tenants` y `/documentos`)

### B. Entidad Empresa

- [ ] Migración: tabla `empresas` (FK a tenant + a los catálogos de A)
- [ ] Backend: `POST/GET/PUT /empresas` (mismo patrón que `/tenants` en `service/app/main.py`)
- [ ] Frontend: pantalla de alta/listado/edición de empresas
- [ ] Aplicar la matriz de permisos de la sección 3 (`require_role` en cada endpoint)

### C. Vincular Documento a Empresa

- [ ] Migración: `documentos.empresa_id` (nullable primero)
- [ ] Migración de datos: crear "Empresa Demo" por tenant existente y asignarla a sus documentos (decisión ya cerrada, sección 5)
- [ ] Una vez poblado en todos los tenants, `empresa_id` pasa a `NOT NULL`
- [ ] Backend: `POST /documentos` exige `empresa_id`
- [ ] Frontend: selector de empresa en `DocumentUploadForm.jsx`

### D. Cola de revisión (Art. IV.8)

- [ ] Migración: `clausulas.estado_revision`, `revisado_por`, `revisado_at`
- [ ] `classification.py`: agregar el campo de confianza (alto/medio/bajo) al schema de salida de `classify_clause()` (decisión cerrada, sección 5)
- [ ] Backend: `GET /revision` (lista priorizada por confianza), `POST /revision/{clausula_id}/aprobar|corregir|rechazar`
- [ ] Frontend: pantalla de cola de revisión
- [ ] Actualizar `auth-spec.md` §5 con las filas nuevas de la sección 3 de este spec — hoy solo están redactadas acá, falta llevarlas al doc de auth real

### E. Comparador

- [ ] Backend: endpoint de comparación (filtro por título + sector/tipo/actividad/geografía + empresas seleccionadas del catálogo del tenant; **solo** cláusulas aprobadas, Art. IV.9)
- [ ] Frontend: pantalla de comparación (equivalente moderno de `comparador.php`)
- [ ] Validar el criterio de éxito de la sección 4 de punta a punta

## 8. Checklist resumido — para ver de un vistazo dónde estamos

- [ ] A. Catálogos globales de segmentación
- [ ] B. Entidad Empresa
- [ ] C. Documento ↔ Empresa
- [ ] D. Cola de revisión (Art. IV.8)
- [ ] E. Comparador

*(Se actualiza a medida que avanzamos — marcar acá, no en otro lado, para que este spec sea la única fuente de verdad de "cuánto falta" de la Fase 2.)*
