-- Esquema Postgres — Comparador de Documentos Legales (MVP demo Venezuela)
-- Fuente de verdad: docs/constitution.md · Alcance: docs/spec-mvp-demo.md
--
-- Decisiones de diseño relevantes:
--   * tenant_id presente en toda tabla de datos propiedad de un tenant (documentos, clausulas),
--     por la regla dura del Art. VI.2. Las tablas de taxonomia son catalogo compartido
--     (no pertenecen a un tenant), asi que no llevan tenant_id.
--   * pais queda fijo en 'Venezuela' a nivel de tenant (Art I.3 / spec-mvp-demo.md #3),
--     sin catalogo de paises ni selector: no es necesario para este MVP de un solo pais.
--   * IDs de taxonomia_categorias/taxonomia_titulos son los del dump legado
--     (docs/taxonomia_venezuela.json), no autoincrementales, para trazabilidad con el
--     dataset historico (Art IX.2).

CREATE TABLE tenants (
    id              SERIAL PRIMARY KEY,
    nombre_empresa  TEXT NOT NULL,
    pais            TEXT NOT NULL DEFAULT 'Venezuela' CHECK (pais = 'Venezuela'),
    created_at      TIMESTAMPTZ NOT NULL DEFAULT now()
);

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

CREATE TABLE documentos (
    id            SERIAL PRIMARY KEY,
    tenant_id     INTEGER NOT NULL REFERENCES tenants(id),
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

CREATE TABLE clausulas (
    id             SERIAL PRIMARY KEY,
    documento_id   INTEGER NOT NULL REFERENCES documentos(id),
    tenant_id      INTEGER NOT NULL REFERENCES tenants(id),
    texto          TEXT NOT NULL,
    titulo_id      INTEGER REFERENCES taxonomia_titulos(id),
    categoria_id   INTEGER REFERENCES taxonomia_categorias(id),
    orden          INTEGER NOT NULL,
    created_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_clausulas_documento_id ON clausulas(documento_id);
CREATE INDEX idx_clausulas_tenant_id ON clausulas(tenant_id);
