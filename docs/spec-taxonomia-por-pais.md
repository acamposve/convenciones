# Spec — Taxonomía por país y activación de Uruguay (Fase 8, alcance acotado)

> **Depende de:** `constitution.md` v2.1.0 (Art. II.3, Art. VII.4)
> **Estado:** borrador
> **Alcance:** de las cinco cosas que el roadmap agrupa bajo "Fase 8", esta spec cubre
> únicamente **taxonomía por país + activación de Uruguay** — la única parte con decisiones
> ya cerradas con el cliente. SSO/SAML, aislamiento de datos dedicado y mejora continua del
> extractor quedan como ítems de roadmap sueltos, sin spec ni fecha (decisión con el usuario).

## 1. Qué existe hoy vs qué falta

Ya construido, no se toca:

- `paises` con las 4 filas (Venezuela activo=true, Uruguay/Argentina/Chile activo=false) y el flip `PUT /api/plataforma/paises/{id}/activo` (Art II.4 — puerta legal, gateado a PlataformaAdmin).
- `tenant_paises_habilitados` y su gestión por tenant (Fase 5) — licenciamiento comercial, capa distinta de la activación global.
- `leyes`/`articulos_ley` ya son catálogo **por país** desde Fase 4 — el patrón a seguir ya existe en el sistema, no se inventa.
- `estados`/`localidades` ya tienen `pais_id` (geografía preparada para expansión, hoy solo Venezuela sembrada).

Falta (esto es lo que construye esta fase):

- `taxonomia_titulos` es un catálogo **global único** (el de Venezuela) — Art II.3 exige núcleo de categorías compartido + capa de títulos versionada por país, y hoy no hay versión por país en absoluto.
- `empresas` no sabe en qué país opera — el pipeline de clasificación usa el único set de títulos que existe, sin noción de país.
- No hay mecanismo de clonado ni de edición de títulos por país (Art II.3: "editable por Plataforma... sin afectar al país de origen").

## 2. Decisiones cerradas con el usuario

- **Alcance**: solo taxonomía por país + Uruguay en este ciclo (no SSO/SAML, no aislamiento dedicado, no mejora del extractor).
- **País de una Empresa**: campo `pais_id` explícito en `empresas`, elegido al crearla — no se deriva de `estado_id` (opcional hoy, y `estados`/`localidades` de Uruguay no están sembradas).

## 3. Diseño

### 3.1 Núcleo de categorías compartido, títulos versionados (Art II.3)

Releyendo Art II.3 con cuidado: el **núcleo de categorías es común a los 4 países** (las mismas 5 categorías GENERALES/ECONÓMICO/SOCIOECONÓMICAS/SINDICALES/SEGURIDAD OCUPACIONAL, con el mismo `requiere_campo_comparacion_economica`) — lo que se versiona por país es la **capa de títulos**. Esto simplifica bastante el modelo de datos:

- `taxonomia_categorias`: **sin cambios**. Sigue siendo un catálogo global único, igual que hoy.
- `taxonomia_titulos`: se le agrega `pais_id INTEGER NOT NULL REFERENCES paises(id)` (backfill a Venezuela para las filas existentes) y `activo BOOLEAN NOT NULL DEFAULT true` (para poder "desactivar" un título sin borrarlo — Art II.3 dice explícitamente agregar/renombrar/desactivar, nunca borrar). `categoria_id` sigue apuntando al núcleo compartido, sin cambios en esa FK.
- **`id` sigue siendo la PK única globalmente, sin componer con `pais_id`** — un título clonado recibe un id nuevo (no reutiliza los ids legado 1-64 de Venezuela), así que `clausulas.titulo_id` y `titulo_articulo_ley.titulo_id` **no necesitan ningún cambio**: siguen apuntando a un id que identifica sin ambigüedad un título de un país específico. Se agrega `CREATE SEQUENCE taxonomia_titulos_clon_seq START WITH 1000` (holgura de sobra sobre los ~64 ids legado) para los ids nuevos generados al clonar.

### 3.2 País de la Empresa

- `empresas.pais_id INTEGER NOT NULL REFERENCES paises(id)` (backfill al `pais_id` del tenant para las filas existentes).
- Al crear una empresa, el selector de país solo ofrece los países en `tenant_paises_habilitados` del tenant — no tiene sentido dar de alta una empresa en un país que el tenant no tiene licenciado.

### 3.3 Clonado (Plataforma, Art II.3/VII.4)

**Excepción deliberada al patrón "Plataforma vive en `api/` (.NET)"**: las tablas de taxonomía son propiedad exclusiva del servicio Python (`schema.sql`, seeds, toda la lógica de clasificación) — el `.NET` ni siquiera las mapea en EF Core. Duplicar esa capa de acceso en `.NET` solo para esta acción sería peor que una excepción bien documentada. El endpoint de clonado/edición vive en `service/app/main.py`, gateado por un nuevo `require_plataforma_role()` en `app/auth.py` (el `require_role()` actual asume `tenant_id` siempre es un UUID válido, y un usuario de Plataforma tiene `tenant_id = null` — hoy el servicio Python no puede ni decodificar ese token sin reventar).

- `POST /plataforma/taxonomia/clonar` — body `{pais_origen_id, pais_destino_id}`. Copia todos los títulos `activo=true` de origen hacia destino con ids nuevos (misma `categoria_id`, mismo `nombre`/`descripcion`). Falla si destino ya tiene algún título (evita duplicar por clonar dos veces — para volver a intentar, el país destino debe estar vacío).
- `POST /plataforma/taxonomia/titulos` — agrega un título nuevo a un país (no necesita venir de un clon).
- `PUT /plataforma/taxonomia/titulos/{id}` — renombra / cambia descripción / cambia categoría.
- `PUT /plataforma/taxonomia/titulos/{id}/activo` — activa/desactiva (nunca DELETE — preserva integridad referencial con cláusulas ya clasificadas).
- Las cuatro gateadas a rol `PlataformaAdmin` (mismo actor que `PuedeActivarPaisGlobal` en `.NET` — es la misma puerta conceptual: cambios que afectan la oferta comercial de un país).

### 3.4 Pipeline de clasificación (Art IV.5, spec-mvp-demo.md)

- `_procesar_pipeline()` hoy arma el prompt con **todos** los títulos existentes. Pasa a resolver primero el `pais_id` de la empresa dueña del documento (`documentos.empresa_id → empresas.pais_id`) y filtrar `taxonomia_titulos` por `pais_id = ese` **y** `activo = true`.
- `GET /taxonomia` (usado por el dropdown de corrección en `RevisionPage`) pasa a requerir `pais_id` como query param — hoy no filtra nada y no tiene auth; se le agrega el filtro (sigue sin requerir rol, es de solo lectura y ya se consume así desde el frontend autenticado).
- `GET /comparador/titulos` y `GET /comparador`: **sin cambios** — ya derivan los títulos disponibles a partir de cláusulas reales (`JOIN taxonomia_titulos`), así que automáticamente sólo ofrecen títulos del país correspondiente (dos países nunca comparten id de título, por diseño de la §3.1).

### 3.5 Marco legal por país — explícitamente fuera de esta fase

`titulo_articulo_ley` mapea títulos a artículos de ley curados a mano (Art IV.5 bis). Un título clonado para Uruguay **no trae mapeo legal** — eso requiere que alguien cargue el corpus de leyes de Uruguay (`leyes`/`articulos_ley`, ya soporta `pais_id`) y cure la relación título↔artículo, con validación de un abogado laboral local (Art II.4). Es tarea de contenido legal, no de ingeniería — `check_legal_compliance()` ya maneja sin romper el caso de un título sin artículos mapeados (se comporta igual que hoy con títulos venezolanos sin mapeo). Activar Uruguay "comercialmente" (`paises.activo=true`) no debería hacerse hasta que ese contenido exista, pero el flip en sí es una acción de Plataforma ya construida (Fase 5) — no se toca en esta fase.

## 4. Frontend

- **Alta de empresa**: selector de país (solo los habilitados para el tenant), pre-seleccionado al país del tenant si tiene uno solo habilitado.
- **`PlataformaPage.jsx`**: nueva sección "Taxonomía por país" — elegir país, ver sus títulos (nombre, categoría, activo/inactivo), clonar desde otro país si está vacío, agregar/renombrar/desactivar título.

## 5. Preguntas abiertas

Ninguna — quedaron resueltas con el usuario (§2) y por relectura cuidadosa de Art II.3 (núcleo de categorías compartido vs capa de títulos versionada, §3.1).

## 6. Plan de implementación

- [ ] **Bloque A — Schema**: `taxonomia_titulos.pais_id`/`activo`, `empresas.pais_id`, secuencia de clonado, backfill a Venezuela, migración incremental + verificación contra Postgres real (mismo patrón que `010_resumen_ejecutivo.sql`)
- [ ] **Bloque B — Backend Plataforma**: `require_plataforma_role()` en `app/auth.py`; endpoints de clonado/alta/edición/activación de títulos; verificado con un JWT de Plataforma real (rol `PlataformaAdmin`, `tenant_id=null`) contra el stack completo
- [ ] **Bloque C — Pipeline + revisión**: `_procesar_pipeline()` filtra títulos por país de la empresa; `GET /taxonomia` filtra por `pais_id`; alta de empresa pide país (limitado a `tenant_paises_habilitados`); verificado clasificando un documento de una empresa uruguaya de prueba contra el set clonado, confirmando que nunca aparece un título venezolano
- [ ] **Bloque D — Demo de Uruguay de punta a punta**: clonar Venezuela → Uruguay vía Plataforma, crear empresa uruguaya, subir un documento, confirmar que se clasifica solo contra títulos uruguayos y que el comparador no mezcla países

## 7. Checklist resumido

- [ ] A. Schema
- [ ] B. Backend Plataforma (clonado + edición)
- [ ] C. Pipeline + revisión filtran por país
- [ ] D. Demo de punta a punta (Uruguay activado)

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fases 2-7.)*
