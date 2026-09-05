-- Esquema Postgres — Comparador de Documentos Legales (MVP demo Venezuela + Auth Fase 1)
-- Fuente de verdad: docs/constitution.md · Alcance: docs/spec-mvp-demo.md, docs/auth-spec.md
--
-- Instalacion nueva: aplicar este archivo completo.
-- Base ya existente (con datos del MVP demo previo a auth): NO reaplicar este archivo tal cual
-- (tenants ya no es SERIAL). Usar db/migrations/002_auth.sql, que transforma una base que
-- todavia tiene el esquema viejo (tenants.id INTEGER, sin paises/usuarios) a este esquema.
--
-- Decisiones de diseño relevantes:
--   * tenant_id presente en toda tabla de datos propiedad de un tenant (documentos, clausulas,
--     usuarios, refresh_tokens, bitacora_accesos via usuario), por la regla dura del Art. VI.2.
--     Las tablas de taxonomia son catalogo compartido (no pertenecen a un tenant), asi que no
--     llevan tenant_id.
--   * tenants.id es UUID (no SERIAL): es el mismo tenant_id que viaja en el claim del JWT
--     (auth-spec.md §2/§4) y que usan documentos/clausulas — un solo tenant_id compartido por
--     todo el sistema, no dos modelos de tenant paralelos.
--   * pais fijo Venezuela como UNICO pais activo en esta fase (Art I.3 / spec-mvp-demo.md #3):
--     la tabla paises ya existe (la necesita auth-spec.md para Tenant.pais_id), pero solo VE
--     tiene activo=true; no hay selector de pais en la UI del MVP.
--   * IDs de taxonomia_categorias/taxonomia_titulos son los del dump legado
--     (docs/taxonomia_venezuela.json), no autoincrementales, para trazabilidad con el
--     dataset historico (Art IX.2).
--   * Nombres de tabla en plural, snake_case, consistente en todo el esquema (paises, tenants,
--     usuarios, refresh_tokens, bitacora_accesos) — el scaffold de auth traia singular
--     (tenant, usuario, refresh_token); se renombraron acá para no tener dos convenciones.

-- PlataformaAdmin/Soporte/Auditor (Fase 5, spec-plataforma.md): usuarios sin tenant
-- (usuarios.tenant_id NULL) que administran operadores despues de creados.
CREATE TYPE rol_usuario AS ENUM (
    'AdminTenant', 'Revisor', 'Editor', 'Visualizador',
    'PlataformaAdmin', 'PlataformaSoporte', 'PlataformaAuditor'
);

CREATE TABLE paises (
    id      SERIAL PRIMARY KEY,
    codigo  VARCHAR(2) NOT NULL UNIQUE,  -- VE, UY, AR, CL
    nombre  VARCHAR(80) NOT NULL,
    activo  BOOLEAN NOT NULL DEFAULT false  -- true solo tras validacion legal, Art. II.4
);

CREATE TABLE tenants (
    id                  UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre_empresa      TEXT NOT NULL,
    pais_id             INTEGER NOT NULL REFERENCES paises(id),
    -- plan_licencia/fecha_vencimiento: columnas presentes porque auth-spec.md las necesita
    -- (Art VII.3), pero los tiers concretos NO estan definidos (Art XI.4 pendiente) — el MVP
    -- no lee ni hace cumplir estos valores todavia.
    plan_licencia       TEXT NOT NULL DEFAULT 'trial',
    fecha_vencimiento   DATE,
    -- Fase 5 (spec-plataforma.md): Plataforma puede suspender un operador (licencia vencida,
    -- incumplimiento) sin borrar sus datos -- el login sigue funcionando, pero el JWT no
    -- habilita ninguna accion. No reemplaza el aislamiento del Art VI.2, es un check aparte.
    suspendido          BOOLEAN NOT NULL DEFAULT false,
    created_at          TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Fase 5: que paises tiene habilitados cada tenant segun su licencia (independiente del
-- flip global paises.activo, que es el gate legal de Art II.4). Hoy cada tenant nuevo
-- arranca con exactamente una fila (su propio pais_id) via el registro self-service --
-- modelado M:N pensando en Fase 6 (expansion), no porque un tenant ya opere multi-pais.
CREATE TABLE tenant_paises_habilitados (
    tenant_id  UUID NOT NULL REFERENCES tenants(id),
    pais_id    INTEGER NOT NULL REFERENCES paises(id),
    PRIMARY KEY (tenant_id, pais_id)
);

CREATE TABLE usuarios (
    id                          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    -- Fase 5: nullable -- NULL identifica a un usuario de Plataforma (PlataformaAdmin/
    -- Soporte/Auditor), que por definicion no pertenece a ningun tenant (Art VII.4).
    tenant_id                   UUID REFERENCES tenants(id),
    email                       VARCHAR(320) NOT NULL,
    password_hash               TEXT NOT NULL,
    rol                         rol_usuario NOT NULL,
    activo                      BOOLEAN NOT NULL DEFAULT true,
    -- Art VI.4: nunca se migran contraseñas heredadas; todo usuario cargado por ETL arranca
    -- en true hasta que resetea su propia contraseña.
    requiere_reset_password     BOOLEAN NOT NULL DEFAULT true,
    ultimo_login_at             TIMESTAMPTZ,
    created_at                  TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (tenant_id, email)
);

-- Postgres trata cada NULL como distinto en un UNIQUE compuesto, asi que
-- UNIQUE(tenant_id, email) de arriba NO evita emails duplicados entre usuarios de
-- Plataforma (tenant_id NULL) -- este indice parcial cubre exactamente ese caso.
CREATE UNIQUE INDEX idx_usuarios_email_plataforma ON usuarios(email) WHERE tenant_id IS NULL;

CREATE TABLE refresh_tokens (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    usuario_id  UUID NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    token_hash  TEXT NOT NULL,
    expira_at   TIMESTAMPTZ NOT NULL,
    revocado    BOOLEAN NOT NULL DEFAULT false,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Tokens de un solo uso para /api/auth/reset-password (placeholder resuelto del scaffold).
CREATE TABLE reset_password_tokens (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    usuario_id  UUID NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    token_hash  TEXT NOT NULL,
    expira_at   TIMESTAMPTZ NOT NULL,
    usado       BOOLEAN NOT NULL DEFAULT false,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE bitacora_accesos (
    id          BIGSERIAL PRIMARY KEY,
    usuario_id  UUID REFERENCES usuarios(id),
    tenant_id   UUID REFERENCES tenants(id),
    evento      VARCHAR(30) NOT NULL,  -- login_ok | login_ok_reset_pending | login_fail | logout | reset_password
    ip          VARCHAR(64),
    user_agent  TEXT,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Aislamiento por tenant (Art. VI.2): indices para que el filtro tenant_id sea siempre barato,
-- nunca una excusa para omitirlo en una query "por performance".
CREATE INDEX idx_usuarios_tenant ON usuarios(tenant_id);
CREATE INDEX idx_bitacora_tenant ON bitacora_accesos(tenant_id);
CREATE INDEX idx_refresh_usuario ON refresh_tokens(usuario_id);
CREATE INDEX idx_reset_tokens_usuario ON reset_password_tokens(usuario_id);

CREATE TABLE taxonomia_categorias (
    id                                      INTEGER PRIMARY KEY,
    nombre                                  TEXT NOT NULL,
    descripcion                             TEXT,
    requiere_campo_comparacion_economica    BOOLEAN NOT NULL
);

-- Fase 8 (spec-taxonomia-por-pais.md, Art II.3): el nucleo de categorias de arriba es
-- comun a los 4 paises -- lo que se versiona por pais es la capa de titulos. pais_id no
-- compone la PK: un titulo clonado recibe un id nuevo (taxonomia_titulos_clon_seq), asi
-- que clausulas.titulo_id / titulo_articulo_ley.titulo_id siguen apuntando sin ambiguedad
-- a un titulo de un pais especifico sin ningun cambio en esas FKs.
CREATE TABLE taxonomia_titulos (
    id            INTEGER PRIMARY KEY,
    nombre        TEXT NOT NULL,
    descripcion   TEXT,
    categoria_id  INTEGER NOT NULL REFERENCES taxonomia_categorias(id),
    pais_id       INTEGER NOT NULL REFERENCES paises(id),
    -- Nunca se borra un titulo (integridad referencial con clausulas ya clasificadas) --
    -- se desactiva. El pipeline (Art IV.5) y GET /taxonomia solo ofrecen activo=true.
    activo        BOOLEAN NOT NULL DEFAULT true
);

CREATE INDEX idx_taxonomia_titulos_categoria_id ON taxonomia_titulos(categoria_id);
CREATE INDEX idx_taxonomia_titulos_pais_id ON taxonomia_titulos(pais_id);

-- IDs nuevos para titulos clonados a un pais nuevo (Bloque B) -- arranca por encima de los
-- ~64 ids legado de Venezuela, con holgura de sobra.
CREATE SEQUENCE taxonomia_titulos_clon_seq START WITH 1000;

-- Marco legal (Art. II.6 / IV.5 bis de la constitucion, Fase 4 / spec-marco-legal.md):
-- catalogo de leyes por pais, igual criterio que la taxonomia -- global, sin tenant_id.
-- No es solo referencia de consulta: titulo_articulo_ley alimenta la verificacion de
-- cumplimiento legal del pipeline (paso 5 bis), una señal de asistencia para el Revisor,
-- nunca una determinacion legal vinculante (Art XI.6 -- disclaimer obligatorio en la UI).
CREATE TABLE leyes (
    id                 SERIAL PRIMARY KEY,
    pais_id            INTEGER NOT NULL REFERENCES paises(id),
    nombre             TEXT NOT NULL,
    gaceta             TEXT,
    fecha_publicacion  DATE,
    UNIQUE (pais_id, nombre)
);

CREATE INDEX idx_leyes_pais_id ON leyes(pais_id);

-- IDs no autoincrementales a proposito de "nro_articulo" en si (ese es de la ley, no de la
-- tabla): la PK es un id propio porque un mismo nro_articulo puede repetirse entre leyes
-- distintas (por eso el UNIQUE es compuesto, no nro_articulo solo).
CREATE TABLE articulos_ley (
    id               SERIAL PRIMARY KEY,
    ley_id           INTEGER NOT NULL REFERENCES leyes(id),
    nro_articulo     INTEGER NOT NULL,
    titulo_articulo  TEXT,
    texto_completo   TEXT NOT NULL,
    UNIQUE (ley_id, nro_articulo)
);

CREATE INDEX idx_articulos_ley_ley_id ON articulos_ley(ley_id);

-- Tabla puente M:N (spec-marco-legal.md §4): la curaduria inicial se adopta del vinculo
-- que ya traia el dump legado (articulos_ley_trabajo.codigo_titulo_comparativo == este
-- mismo taxonomia_titulos.id, confirmado por trazabilidad de IDs), revisada a mano antes
-- de confiar en ella -- no es una sugerencia de IA sin validar.
CREATE TABLE titulo_articulo_ley (
    titulo_id       INTEGER NOT NULL REFERENCES taxonomia_titulos(id),
    articulo_ley_id INTEGER NOT NULL REFERENCES articulos_ley(id),
    PRIMARY KEY (titulo_id, articulo_ley_id)
);

CREATE INDEX idx_titulo_articulo_ley_articulo_ley_id ON titulo_articulo_ley(articulo_ley_id);

-- Catalogos globales de segmentacion de empresas (Art. II.5 de la constitucion, Fase 2 /
-- spec-empresas-comparacion.md Bloque A): compartidos por TODOS los tenants, sin tenant_id
-- -- son datos de referencia objetivos (ej. "Sector: Manufactura" es el mismo dato para
-- cualquier operador). Mismo criterio que taxonomia_categorias/titulos: IDs son los del
-- dump legado (docs/catalogos_empresa_venezuela.json, extraido de presenci_cccol.sql), no
-- autoincrementales, para trazabilidad con el dataset historico.
-- Van ANTES de documentos/empresas a proposito: empresas.sector_id (etc.) y
-- documentos.empresa_id necesitan que estas tablas ya existan.

CREATE TABLE sectores (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE tipos_empresa (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE categorias_sector (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE actividades_empresa (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

-- Geografia: estados y localidades de Venezuela (unico pais activo, Art I.2/II.4). pais_id
-- referencia la tabla paises ya existente arriba -- se resuelve por codigo='VE' al sembrar,
-- nunca se hardcodea el id.
CREATE TABLE estados (
    id       INTEGER PRIMARY KEY,
    pais_id  INTEGER NOT NULL REFERENCES paises(id),
    nombre   TEXT NOT NULL
);

CREATE INDEX idx_estados_pais_id ON estados(pais_id);

CREATE TABLE localidades (
    id         INTEGER PRIMARY KEY,
    estado_id  INTEGER NOT NULL REFERENCES estados(id),
    nombre     TEXT NOT NULL
);

CREATE INDEX idx_localidades_estado_id ON localidades(estado_id);

-- Empresa (Art. III de la constitucion, Fase 2 / spec-empresas-comparacion.md Bloque B):
-- la empresa-cliente cuya(s) convencion(es) el tenant (operador) analiza/compara. Pertenece
-- a un tenant (Art. VI.2 -- aislamiento obligatorio); los catalogos de segmentacion son
-- opcionales (nullable) porque cargar una empresa no deberia bloquearse si todavia no se
-- sabe su sector/tipo/categoria/actividad exactos.
CREATE TABLE empresas (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tenant_id     UUID NOT NULL REFERENCES tenants(id),
    -- Fase 8 (spec-taxonomia-por-pais.md §3.2): pais explicito, elegido al crear la
    -- empresa -- NO se deriva de estado_id (opcional hoy; estados/localidades de paises
    -- distintos a Venezuela todavia no estan sembradas). Determina que capa de
    -- taxonomia_titulos usa el pipeline de clasificacion (Art IV.5) para sus documentos.
    pais_id       INTEGER NOT NULL REFERENCES paises(id),
    nombre        TEXT NOT NULL,
    rif           TEXT,
    sector_id     INTEGER REFERENCES sectores(id),
    tipo_id       INTEGER REFERENCES tipos_empresa(id),
    categoria_id  INTEGER REFERENCES categorias_sector(id),
    actividad_id  INTEGER REFERENCES actividades_empresa(id),
    estado_id     INTEGER REFERENCES estados(id),
    localidad_id  INTEGER REFERENCES localidades(id),
    contacto_nombre  TEXT,
    contacto_email   TEXT,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_empresas_tenant_id ON empresas(tenant_id);

-- Negociacion colectiva pre-firma (Art IV bis, Fase 3 / spec-negociacion.md): peticion del
-- sindicato, oferta de la empresa, reuniones y acuerdo por titulo. Al cerrar, genera un
-- Documento (definido mas abajo, de ahi que este bloque vaya antes). Definido antes de
-- documentos porque documentos.negociacion_id referencia esta tabla.
CREATE TABLE negociaciones (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tenant_id     UUID NOT NULL REFERENCES tenants(id),
    empresa_id    UUID NOT NULL REFERENCES empresas(id),
    estado        TEXT NOT NULL DEFAULT 'abierta' CHECK (estado IN ('abierta', 'cerrada')),
    fecha_inicio  TIMESTAMPTZ NOT NULL DEFAULT now(),
    fecha_cierre  TIMESTAMPTZ,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_negociaciones_tenant_id ON negociaciones(tenant_id);
CREATE INDEX idx_negociaciones_empresa_id ON negociaciones(empresa_id);

CREATE TABLE peticiones (
    id              SERIAL PRIMARY KEY,
    negociacion_id  UUID NOT NULL REFERENCES negociaciones(id),
    titulo_id       INTEGER REFERENCES taxonomia_titulos(id),
    nro_peticion    INTEGER NOT NULL,
    texto           TEXT NOT NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_peticiones_negociacion_id ON peticiones(negociacion_id);

CREATE TABLE ofertas (
    id           SERIAL PRIMARY KEY,
    peticion_id  INTEGER NOT NULL REFERENCES peticiones(id),
    texto        TEXT NOT NULL,
    created_at   TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_ofertas_peticion_id ON ofertas(peticion_id);

CREATE TABLE reuniones (
    id              SERIAL PRIMARY KEY,
    negociacion_id  UUID NOT NULL REFERENCES negociaciones(id),
    fecha           DATE NOT NULL,
    asistentes      TEXT,
    resumen         TEXT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_reuniones_negociacion_id ON reuniones(negociacion_id);

-- El acuerdo mas reciente por titulo_id dentro de una negociacion es el que se toma al
-- cerrar (spec-negociacion.md §5) -- no hay un flag "vigente", se resuelve por created_at
-- para no tener que mantener ese estado en sync en cada insercion.
CREATE TABLE acuerdos (
    id              SERIAL PRIMARY KEY,
    negociacion_id  UUID NOT NULL REFERENCES negociaciones(id),
    titulo_id       INTEGER NOT NULL REFERENCES taxonomia_titulos(id),
    texto_acordado  TEXT NOT NULL,
    peticion_id     INTEGER REFERENCES peticiones(id),
    oferta_id       INTEGER REFERENCES ofertas(id),
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_acuerdos_negociacion_id ON acuerdos(negociacion_id, titulo_id, created_at);

-- Distinta de bitacora_accesos (login/logout/aprobaciones) -- esta es la bitacora propia
-- del proceso de negociacion (Art III de la constitucion).
CREATE TABLE bitacora_negociacion (
    id              SERIAL PRIMARY KEY,
    negociacion_id  UUID NOT NULL REFERENCES negociaciones(id),
    evento          TEXT NOT NULL CHECK (
                        evento IN ('creacion', 'peticion', 'oferta', 'reunion', 'acuerdo', 'cierre', 'reapertura')
                    ),
    usuario_id      UUID REFERENCES usuarios(id),
    detalle         TEXT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_bitacora_negociacion_negociacion_id ON bitacora_negociacion(negociacion_id);

CREATE TABLE documentos (
    id            SERIAL PRIMARY KEY,
    tenant_id     UUID NOT NULL REFERENCES tenants(id),
    -- Bloque C (spec-empresas-comparacion.md): un documento pertenece a una Empresa del
    -- catalogo del tenant, no solo al tenant. En instalacion nueva no hace falta backfill
    -- (no hay documentos previos); en una base existente lo resuelve
    -- db/migrations/005_documentos_empresa.sql.
    empresa_id    UUID NOT NULL REFERENCES empresas(id),
    origen        TEXT NOT NULL CHECK (origen IN ('archivo', 'url', 'negociacion')),
    url_origen    TEXT,
    ruta_archivo  TEXT,
    es_publico    BOOLEAN NOT NULL DEFAULT false,
    estado        TEXT NOT NULL DEFAULT 'pendiente',
    estado_detalle TEXT,
    -- Fase 3 (spec-negociacion.md, Art IV bis.5): solo se llenan cuando el documento se
    -- genero al cerrar una negociacion. version_negociacion permite reapertura + addendum
    -- sin tabla de versiones separada (cada cierre agrega un Documento, no reemplaza el anterior).
    negociacion_id       UUID REFERENCES negociaciones(id),
    version_negociacion  INTEGER,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
    CONSTRAINT chk_documentos_origen_datos CHECK (
        (origen = 'url' AND url_origen IS NOT NULL)
        OR (origen IN ('archivo', 'negociacion') AND ruta_archivo IS NOT NULL)
    ),
    -- Solo un documento ingresado por URL puede declararse publico (Art VI.1 / IV.2):
    -- un archivo cargado no tiene una URL de origen que validar como accesible sin autenticacion.
    CONSTRAINT chk_documentos_publico_requiere_url CHECK (
        es_publico = false OR origen = 'url'
    )
);

CREATE INDEX idx_documentos_tenant_id ON documentos(tenant_id);
CREATE INDEX idx_documentos_empresa_id ON documentos(empresa_id);
CREATE INDEX idx_documentos_negociacion_id ON documentos(negociacion_id);

CREATE TABLE clausulas (
    id             SERIAL PRIMARY KEY,
    documento_id   INTEGER NOT NULL REFERENCES documentos(id),
    tenant_id      UUID NOT NULL REFERENCES tenants(id),
    texto          TEXT NOT NULL,
    titulo_id      INTEGER REFERENCES taxonomia_titulos(id),
    categoria_id   INTEGER REFERENCES taxonomia_categorias(id),
    orden          INTEGER NOT NULL,
    -- Bloque D (spec-empresas-comparacion.md, Art. IV.7-8): cola de revision humana.
    -- confianza: auto-reporte del modelo (decision cerrada, spec §5) -- señal blanda para
    -- ordenar la cola, no una certificacion.
    confianza          TEXT CHECK (confianza IN ('alto', 'medio', 'bajo')),
    estado_revision    TEXT NOT NULL DEFAULT 'pendiente'
                       CHECK (estado_revision IN ('pendiente', 'aprobado', 'rechazado')),
    revisado_por       UUID REFERENCES usuarios(id),
    revisado_at        TIMESTAMPTZ,
    -- Fase 4 (spec-marco-legal.md, Art IV.5 bis): señal de asistencia, nunca una
    -- determinacion legal vinculante -- 'no_aplica' cuando el titulo asignado no tiene
    -- articulos de ley vinculados (titulo_articulo_ley), sin necesidad de llamar al modelo.
    cumplimiento_legal          TEXT CHECK (cumplimiento_legal IN ('por_debajo', 'iguala', 'supera', 'no_aplica')),
    cumplimiento_justificacion  TEXT,
    -- Fase 6 (spec-resumen-ejecutivo.md, Art IV.6/6 bis): campo_comparativo comparte el
    -- estado_revision de arriba (decision cerrada, spec §6) -- resumen_ejecutivo tiene su
    -- PROPIO estado, independiente, porque puede aprobarse en un momento distinto al de la
    -- clasificacion (uno no bloquea al otro).
    campo_comparativo   TEXT,
    resumen_ejecutivo   TEXT,
    estado_revision_resumen  TEXT NOT NULL DEFAULT 'pendiente'
                             CHECK (estado_revision_resumen IN ('pendiente', 'aprobado', 'rechazado')),
    revisado_por_resumen     UUID REFERENCES usuarios(id),
    revisado_at_resumen      TIMESTAMPTZ,
    created_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_clausulas_documento_id ON clausulas(documento_id);
CREATE INDEX idx_clausulas_tenant_id ON clausulas(tenant_id);
CREATE INDEX idx_clausulas_estado_revision ON clausulas(tenant_id, estado_revision);
CREATE INDEX idx_clausulas_estado_revision_resumen ON clausulas(tenant_id, estado_revision_resumen);

-- Seed minimo de paises (activo=false hasta validacion legal, ver Art. II.4 y XI.1)
INSERT INTO paises (codigo, nombre, activo) VALUES
    ('VE', 'Venezuela', true),  -- unico activo en Fase 1 (Art. X)
    ('UY', 'Uruguay', false),
    ('AR', 'Argentina', false),
    ('CL', 'Chile', false);
