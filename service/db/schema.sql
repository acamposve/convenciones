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

CREATE TYPE rol_usuario AS ENUM ('AdminTenant', 'Revisor', 'Editor', 'Visualizador');

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
    created_at          TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE usuarios (
    id                          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tenant_id                   UUID NOT NULL REFERENCES tenants(id),
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

CREATE TABLE taxonomia_titulos (
    id            INTEGER PRIMARY KEY,
    nombre        TEXT NOT NULL,
    descripcion   TEXT,
    categoria_id  INTEGER NOT NULL REFERENCES taxonomia_categorias(id)
);

CREATE INDEX idx_taxonomia_titulos_categoria_id ON taxonomia_titulos(categoria_id);

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

CREATE TABLE documentos (
    id            SERIAL PRIMARY KEY,
    tenant_id     UUID NOT NULL REFERENCES tenants(id),
    -- Bloque C (spec-empresas-comparacion.md): un documento pertenece a una Empresa del
    -- catalogo del tenant, no solo al tenant. En instalacion nueva no hace falta backfill
    -- (no hay documentos previos); en una base existente lo resuelve
    -- db/migrations/005_documentos_empresa.sql.
    empresa_id    UUID NOT NULL REFERENCES empresas(id),
    origen        TEXT NOT NULL CHECK (origen IN ('archivo', 'url')),
    url_origen    TEXT,
    ruta_archivo  TEXT,
    es_publico    BOOLEAN NOT NULL DEFAULT false,
    estado        TEXT NOT NULL DEFAULT 'pendiente',
    estado_detalle TEXT,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now(),
    CONSTRAINT chk_documentos_origen_datos CHECK (
        (origen = 'url' AND url_origen IS NOT NULL)
        OR (origen = 'archivo' AND ruta_archivo IS NOT NULL)
    ),
    -- Solo un documento ingresado por URL puede declararse publico (Art VI.1 / IV.2):
    -- un archivo cargado no tiene una URL de origen que validar como accesible sin autenticacion.
    CONSTRAINT chk_documentos_publico_requiere_url CHECK (
        es_publico = false OR origen = 'url'
    )
);

CREATE INDEX idx_documentos_tenant_id ON documentos(tenant_id);
CREATE INDEX idx_documentos_empresa_id ON documentos(empresa_id);

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
    created_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_clausulas_documento_id ON clausulas(documento_id);
CREATE INDEX idx_clausulas_tenant_id ON clausulas(tenant_id);
CREATE INDEX idx_clausulas_estado_revision ON clausulas(tenant_id, estado_revision);

-- Seed minimo de paises (activo=false hasta validacion legal, ver Art. II.4 y XI.1)
INSERT INTO paises (codigo, nombre, activo) VALUES
    ('VE', 'Venezuela', true),  -- unico activo en Fase 1 (Art. X)
    ('UY', 'Uruguay', false),
    ('AR', 'Argentina', false),
    ('CL', 'Chile', false);
