# Plan — Completar CRUD de tablas (post-auditoría)

> **Estado:** borrador, no iniciado — documento de planificación interna, no una spec cerrada
> con decisiones del cliente (a diferencia de los demás `docs/spec-*.md`).
> **Origen:** auditoría de cobertura CRUD (Create/Read/Update/Delete) de las 27 tablas del
> esquema, hecha en sesión del 2026-09-05, justo después de cerrar Fase 8 (taxonomía por país).
> **Alcance:** cerrar los huecos reales encontrados. No incluye las tablas que la auditoría
> confirmó como "sin CRUD de usuario por diseño" (catálogos Art II.5, logs de auditoría).

## Contexto — qué encontró la auditoría

- Solo existe **un** `DELETE` real en todo el proyecto (`tenant_paises_habilitados`); el resto
  usa soft-delete (columnas `activo`/`suspendido`/`estado_revision`) o no tiene delete en
  absoluto — patrón consistente y deliberado por integridad referencial con datos ya
  clasificados/aprobados, no algo que este plan busque cambiar.
- 7 tablas son infraestructura interna o catálogos globales (Art II.5) sin CRUD de usuario
  esperado, confirmado por diseño: `refresh_tokens`, `reset_password_tokens`,
  `bitacora_accesos`, `bitacora_negociacion`, `sectores`, `tipos_empresa`,
  `categorias_sector`, `actividades_empresa`, `estados`, `localidades`.
- `taxonomia_titulos` tiene el CRUD más completo del proyecto (Create/Read/Update +
  soft-delete explícito, con UI completa) — es el modelo a seguir para los bloques B y D.

## Bloque A — Gestión de usuarios (el hueco más urgente)

**Problema:** un AdminTenant no tiene forma de ver, invitar, cambiar el rol, ni desactivar
usuarios de su propio tenant — solo existe el alta del primer AdminTenant vía registro
self-service (`POST /tenants`). Plataforma tampoco puede listar/editar/desactivar usuarios
de Plataforma ya creados (solo puede crearlos, `POST /api/plataforma/usuarios`).

**Preguntas a cerrar con el usuario antes de implementar:**
- ¿Invitación por email (token de un solo uso, como el reset de password) o alta directa
  con password temporal (como ya hace `seed_admin_user.py`)?
- ¿Quién puede cambiar el rol de otro usuario — solo AdminTenant sobre su propio tenant?
- ¿"Desactivar" es soft (columna `activo`) o hay que revocar también sus `refresh_tokens`
  activos en el mismo gesto?

**Trabajo estimado:**
- Backend .NET (`api/Controllers/`): `GET /api/usuarios` (scoped al tenant del JWT),
  `POST /api/usuarios` (invitar/crear), `PUT /api/usuarios/{id}/rol`,
  `PUT /api/usuarios/{id}/activo`.
- Backend .NET (`PlataformaController.cs`): endpoints análogos para usuarios de Plataforma
  (`GET/PUT /api/plataforma/usuarios/...`).
- Frontend: nueva página `web/src/usuarios/UsuariosPage.jsx` (tabla + alta); sección análoga
  en `PlataformaPage.jsx` para usuarios de Plataforma.
- Verificación: crear un tenant, invitar un Editor, loguearse como ese Editor y confirmar
  permisos; desactivarlo y confirmar que no puede loguearse más.

## Bloque B — Marco legal (`leyes`/`articulos_ley`/`titulo_articulo_ley`) sin API

**Problema:** cero superficie de API, ni de lectura. Se carga una sola vez con
`seed_marco_legal.py` y solo se usa internamente para el semáforo de cumplimiento legal
(Art IV.5 bis). A diferencia de `taxonomia_titulos` — que nace del mismo tipo de dataset
legado y sí tiene panel admin completo — acá no hay forma de ver ni editar el corpus legal
desde la UI.

**Por qué importa ahora:** es un prerrequisito técnico para activar Uruguay comercialmente
(`spec-taxonomia-por-pais.md` §3.5, Art II.4) — alguien va a tener que cargar
`leyes`/`articulos_ley` de Uruguay y curar `titulo_articulo_ley`, y hoy la única forma es un
script corrido a mano contra la base.

**Trabajo estimado:**
- Backend Python, mismo patrón que taxonomía por país (Plataforma-only,
  `require_plataforma_role`): `GET /plataforma/marco-legal/leyes?pais_id=`,
  `POST`/`PUT` para `leyes` y `articulos_ley`, `POST`/`DELETE` para el vínculo
  `titulo_articulo_ley`.
- Frontend: sección "Marco legal" en `PlataformaPage.jsx`, análoga a "Taxonomía por país" —
  elegir país, ver leyes/artículos, vincular artículos a títulos.
- Verificación: cargar una ley de prueba para Uruguay, vincularla a un título clonado,
  confirmar que aparece en el cálculo de cumplimiento legal de una cláusula clasificada
  contra ese título.

## Bloque C — Exponer el `PUT` de empresas ya existente

**Problema:** `PUT /empresas/{id}` funciona en el backend pero `EmpresasPage.jsx` no tiene
botón de editar. Es el hueco más barato de cerrar — no hace falta backend nuevo.

**Trabajo estimado:**
- Frontend únicamente: botón "Editar" por fila en la tabla de `EmpresasPage.jsx`,
  reutilizando el formulario de alta en modo edición (mismo patrón ya usado en
  `PlataformaPage.jsx` para editar títulos de taxonomía).
- Regla de negocio a respetar: `pais_id` no debería ser editable después de creada la
  empresa (mismo criterio que ya se aplicó a `taxonomia_titulos.pais_id`, que tampoco es
  editable tras el clonado) — evita que una empresa con documentos ya clasificados contra
  la taxonomía de un país "salte" a otro país.
- Verificación: editar una empresa existente, confirmar que persiste y que el campo país
  queda de solo lectura en el formulario de edición.

## Bloque D — Edición de tenants (`nombre_empresa`)

**Problema:** no se puede corregir el nombre de un operador después de creado.

**Pregunta a cerrar con el usuario:** ¿tiene sentido permitir editar `pais_id` de un tenant
ya existente? Probablemente no — mismo criterio que Bloque C, rompería la relación con
empresas/documentos ya cargados bajo ese país.

**Trabajo estimado:**
- Backend .NET (`PlataformaController.cs`): `PUT /api/plataforma/tenants/{id}` (solo
  `nombre_empresa`).
- Frontend: input editable en la fila de `PlataformaPage.jsx` (mismo patrón que ya existe
  para `plan_licencia`/`fecha_vencimiento`).
- Limpieza aparte, no relacionada al CRUD pero encontrada en la misma auditoría: el
  endpoint `GET /tenants` (Python, `service/app/main.py`) quedó huérfano — sin
  autenticación, sin uso en el frontend, expone la lista completa de operadores sin login.
  Evaluar eliminarlo o protegerlo con auth.

## Bloque E — Editar/eliminar peticiones, ofertas, reuniones

**Problema:** Create+Read existen para las tres tablas, cero Update/Delete. Un error de
tipeo en una petición/oferta/reunión queda para siempre.

**Pregunta a cerrar con el usuario:**
- ¿Edición libre, o solo mientras la negociación sigue "abierta" (mismo candado que ya usan
  sus `POST` respectivos)?
- ¿Hace falta delete (soft o duro), o alcanza con poder corregir el texto/fecha?

**Trabajo estimado:**
- Backend Python: `PUT /peticiones/{id}`, `PUT /ofertas/{id}`, `PUT /reuniones/{id}` (todos
  con el mismo candado "negociación abierta" que ya validan sus `POST`).
- Frontend: edición inline en `NegociacionDetailPage.jsx`.
- Verificación: editar una petición en una negociación abierta; confirmar que falla
  (422/403) si la negociación ya está cerrada.

## Checklist resumido

- [ ] A. Gestión de usuarios (invitar, listar, rol, activo) — tenant y Plataforma
- [ ] B. Marco legal: API + UI de Plataforma para `leyes`/`articulos_ley`/`titulo_articulo_ley`
- [ ] C. Exponer edición de empresas en la UI (backend ya existe)
- [ ] D. Editar `nombre_empresa` de tenants + limpiar `GET /tenants` huérfano
- [ ] E. Editar/eliminar peticiones, ofertas, reuniones

## Fuera de alcance (evaluado y descartado en la auditoría)

- **`acuerdos` sin edición:** parece intencional — el patrón es "último acuerdo vigente por
  `titulo_id`" (`schema.sql`), corregir es crear uno nuevo (addendum), no editar el
  existente. Si en la práctica resulta ser un hueco real, agregar como Bloque F.
- **Delete duro en general:** el proyecto usa consistentemente soft-delete o ningún delete.
  Este plan no propone agregar `DELETE` real salvo que un caso concreto lo justifique
  explícitamente.
- **Tablas "por diseño"** de la auditoría (catálogos Art II.5, logs de auditoría): no aplica
  CRUD de usuario, confirmado, no se tocan.

---

*Actualizar este documento o promoverlo a un `spec-*.md` formal cuando se cierre alcance con
el usuario para cada bloque — mismo criterio que las demás specs de fase.*
