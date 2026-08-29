-- 004_empresas.sql
-- Agrega la entidad Empresa (Art. III de la constitucion, Fase 2 /
-- spec-empresas-comparacion.md Bloque B) a una base que ya tiene 003_catalogos_empresa.sql
-- aplicado (necesita sectores/tipos_empresa/categorias_sector/actividades_empresa/
-- estados/localidades para las FKs, aunque todas sean nullable).
--
-- Uso: aplicar una sola vez contra una base que todavia no tiene la tabla `empresas`.

BEGIN;

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

COMMIT;
