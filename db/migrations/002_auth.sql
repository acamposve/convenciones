-- 002_auth.sql
-- Migra una base ya existente del MVP demo (tenants.id INTEGER, sin tablas de auth) al
-- esquema objetivo en db/schema.sql: tenants.id UUID + paises/usuarios/refresh_tokens/
-- reset_password_tokens/bitacora_accesos.
--
-- Preserva los datos ya cargados: el tenant existente conserva su nombre_empresa y todos
-- sus documentos/clausulas quedan re-vinculados al mismo tenant bajo su nuevo id UUID.
-- Idempotente-friendly: falla claro (no silencioso) si se corre dos veces, porque la
-- columna/tabla ya existiria.
--
-- Uso (base ya corriendo, ver README de instalacion): aplicar una sola vez contra una base
-- creada con el schema.sql anterior a este cambio.

BEGIN;

CREATE TYPE rol_usuario AS ENUM ('AdminTenant', 'Revisor', 'Editor', 'Visualizador');

CREATE TABLE paises (
    id      SERIAL PRIMARY KEY,
    codigo  VARCHAR(2) NOT NULL UNIQUE,
    nombre  VARCHAR(80) NOT NULL,
    activo  BOOLEAN NOT NULL DEFAULT false
);

INSERT INTO paises (codigo, nombre, activo) VALUES
    ('VE', 'Venezuela', true),
    ('UY', 'Uruguay', false),
    ('AR', 'Argentina', false),
    ('CL', 'Chile', false);

-- 1. tenants: agregar id UUID en paralelo, mapear pais TEXT -> pais_id, agregar columnas
--    de licencia, y solo despues reemplazar la PK entera.
ALTER TABLE tenants ADD COLUMN id_uuid UUID NOT NULL DEFAULT gen_random_uuid();
ALTER TABLE tenants ADD COLUMN pais_id INTEGER REFERENCES paises(id);
UPDATE tenants SET pais_id = (SELECT id FROM paises WHERE codigo = 'VE');
ALTER TABLE tenants ALTER COLUMN pais_id SET NOT NULL;
ALTER TABLE tenants ADD COLUMN plan_licencia TEXT NOT NULL DEFAULT 'trial';
ALTER TABLE tenants ADD COLUMN fecha_vencimiento DATE;

-- 2. documentos/clausulas: agregar tenant_id_uuid en paralelo y backfillear desde el
--    id_uuid del tenant correspondiente, antes de tocar ninguna FK.
ALTER TABLE documentos ADD COLUMN tenant_id_uuid UUID;
UPDATE documentos d SET tenant_id_uuid = t.id_uuid FROM tenants t WHERE t.id = d.tenant_id;
ALTER TABLE clausulas ADD COLUMN tenant_id_uuid UUID;
UPDATE clausulas c SET tenant_id_uuid = t.id_uuid FROM tenants t WHERE t.id = c.tenant_id;

-- 3. Soltar las FKs/PK viejas antes de eliminar las columnas enteras que reemplazan.
ALTER TABLE documentos DROP CONSTRAINT documentos_tenant_id_fkey;
ALTER TABLE clausulas DROP CONSTRAINT clausulas_tenant_id_fkey;
ALTER TABLE tenants DROP CONSTRAINT tenants_pkey;
ALTER TABLE tenants DROP CONSTRAINT tenants_pais_check;

-- 4. tenants: reemplazar id entero por el nuevo UUID como PK real.
ALTER TABLE tenants DROP COLUMN id;
ALTER TABLE tenants DROP COLUMN pais;
ALTER TABLE tenants RENAME COLUMN id_uuid TO id;
ALTER TABLE tenants ALTER COLUMN id SET DEFAULT gen_random_uuid();
ALTER TABLE tenants ADD PRIMARY KEY (id);

-- 5. documentos/clausulas: reemplazar tenant_id entero por el UUID, re-crear la FK.
ALTER TABLE documentos DROP COLUMN tenant_id;
ALTER TABLE documentos RENAME COLUMN tenant_id_uuid TO tenant_id;
ALTER TABLE documentos ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE documentos ADD CONSTRAINT documentos_tenant_id_fkey FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE clausulas DROP COLUMN tenant_id;
ALTER TABLE clausulas RENAME COLUMN tenant_id_uuid TO tenant_id;
ALTER TABLE clausulas ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE clausulas ADD CONSTRAINT clausulas_tenant_id_fkey FOREIGN KEY (tenant_id) REFERENCES tenants(id);

CREATE INDEX idx_documentos_tenant_id ON documentos(tenant_id);
CREATE INDEX idx_clausulas_tenant_id ON clausulas(tenant_id);

-- 6. Tablas nuevas de auth (identicas a schema.sql).
CREATE TABLE usuarios (
    id                          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tenant_id                   UUID NOT NULL REFERENCES tenants(id),
    email                       VARCHAR(320) NOT NULL,
    password_hash               TEXT NOT NULL,
    rol                         rol_usuario NOT NULL,
    activo                      BOOLEAN NOT NULL DEFAULT true,
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
    evento      VARCHAR(30) NOT NULL,
    ip          VARCHAR(64),
    user_agent  TEXT,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX idx_usuarios_tenant ON usuarios(tenant_id);
CREATE INDEX idx_bitacora_tenant ON bitacora_accesos(tenant_id);
CREATE INDEX idx_refresh_usuario ON refresh_tokens(usuario_id);
CREATE INDEX idx_reset_tokens_usuario ON reset_password_tokens(usuario_id);

COMMIT;
