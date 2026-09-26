-- 009_plataforma.sql
-- Rol de Plataforma / onboarding SaaS (Fase 5, spec-plataforma.md, Art VII.4/XI.5).
-- usuarios.tenant_id pasa a nullable (NULL = usuario de Plataforma), se agregan los 3
-- roles nuevos al enum, y las tablas de suspension/licenciamiento por pais.

BEGIN;

ALTER TYPE rol_usuario ADD VALUE 'PlataformaAdmin';
ALTER TYPE rol_usuario ADD VALUE 'PlataformaSoporte';
ALTER TYPE rol_usuario ADD VALUE 'PlataformaAuditor';

ALTER TABLE tenants ADD COLUMN suspendido BOOLEAN NOT NULL DEFAULT false;

CREATE TABLE tenant_paises_habilitados (
    tenant_id  UUID NOT NULL REFERENCES tenants(id),
    pais_id    INTEGER NOT NULL REFERENCES paises(id),
    PRIMARY KEY (tenant_id, pais_id)
);

-- Backfill: cada tenant ya existente queda habilitado para su propio pais_id (el que ya
-- tenia), asi ningun operador actual pierde acceso al aplicar esta migracion.
INSERT INTO tenant_paises_habilitados (tenant_id, pais_id)
SELECT id, pais_id FROM tenants
ON CONFLICT DO NOTHING;

ALTER TABLE usuarios ALTER COLUMN tenant_id DROP NOT NULL;

CREATE UNIQUE INDEX idx_usuarios_email_plataforma ON usuarios(email) WHERE tenant_id IS NULL;

COMMIT;
