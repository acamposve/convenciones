-- 005_documentos_empresa.sql
-- Vincula documentos a Empresa (Art. III, Fase 2 / spec-empresas-comparacion.md Bloque C).
-- Requiere que 004_empresas.sql ya este aplicado.
--
-- Backfill (decision cerrada en spec-empresas-comparacion.md §5): cada tenant que todavia
-- no tiene ninguna empresa en su catalogo recibe una por defecto (mismo nombre que el
-- tenant, no "Empresa Demo" a secas -- el tenant demo YA se llama asi, para no duplicar el
-- nombre), y todos sus documentos existentes se le asignan. Nadie queda huerfano.

BEGIN;

ALTER TABLE documentos ADD COLUMN empresa_id UUID REFERENCES empresas(id);

INSERT INTO empresas (tenant_id, nombre)
SELECT t.id, t.nombre_empresa
FROM tenants t
WHERE NOT EXISTS (SELECT 1 FROM empresas e WHERE e.tenant_id = t.id);

UPDATE documentos d
SET empresa_id = (
    SELECT e.id FROM empresas e WHERE e.tenant_id = d.tenant_id ORDER BY e.created_at LIMIT 1
)
WHERE d.empresa_id IS NULL;

ALTER TABLE documentos ALTER COLUMN empresa_id SET NOT NULL;

CREATE INDEX idx_documentos_empresa_id ON documentos(empresa_id);

COMMIT;
