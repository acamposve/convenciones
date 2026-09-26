-- 007_negociacion.sql
-- Negociacion colectiva pre-firma (Art IV bis de la constitucion, Fase 3 /
-- spec-negociacion.md). Peticion (sindicato), oferta (empresa), reunion y acuerdo por
-- titulo; al cerrar genera un Documento que entra al pipeline del Art. IV igual que uno
-- cargado directo.
--
-- documentos.origen gana el valor 'negociacion' -- hay que recrear el CHECK inline (nombre
-- por defecto de Postgres: documentos_origen_check) y el CHECK con nombre
-- chk_documentos_origen_datos para que 'negociacion' tambien exija ruta_archivo, igual que
-- 'archivo'.

BEGIN;

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

ALTER TABLE documentos ADD COLUMN negociacion_id UUID REFERENCES negociaciones(id);
ALTER TABLE documentos ADD COLUMN version_negociacion INTEGER;
CREATE INDEX idx_documentos_negociacion_id ON documentos(negociacion_id);

ALTER TABLE documentos DROP CONSTRAINT documentos_origen_check;
ALTER TABLE documentos ADD CONSTRAINT documentos_origen_check
    CHECK (origen IN ('archivo', 'url', 'negociacion'));

ALTER TABLE documentos DROP CONSTRAINT chk_documentos_origen_datos;
ALTER TABLE documentos ADD CONSTRAINT chk_documentos_origen_datos CHECK (
    (origen = 'url' AND url_origen IS NOT NULL)
    OR (origen IN ('archivo', 'negociacion') AND ruta_archivo IS NOT NULL)
);

COMMIT;
