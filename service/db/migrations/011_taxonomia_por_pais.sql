-- 011_taxonomia_por_pais.sql
-- Taxonomia versionada por pais (Fase 8, spec-taxonomia-por-pais.md Bloque A, Art II.3).
-- taxonomia_titulos era un catalogo global unico (el de Venezuela) -- pasa a llevar
-- pais_id (capa de titulos por pais; taxonomia_categorias sigue siendo el nucleo comun,
-- sin cambios) y activo (para desactivar sin borrar, nunca DELETE). empresas pasa a saber
-- en que pais opera. Backfill: todo lo que ya existe hoy es de Venezuela.
--
-- Uso: aplicar una sola vez contra una base que todavia no tiene estas columnas.

BEGIN;

ALTER TABLE taxonomia_titulos ADD COLUMN pais_id INTEGER REFERENCES paises(id);
UPDATE taxonomia_titulos SET pais_id = (SELECT id FROM paises WHERE codigo = 'VE');
ALTER TABLE taxonomia_titulos ALTER COLUMN pais_id SET NOT NULL;

ALTER TABLE taxonomia_titulos ADD COLUMN activo BOOLEAN NOT NULL DEFAULT true;

CREATE INDEX idx_taxonomia_titulos_pais_id ON taxonomia_titulos(pais_id);

-- IDs nuevos para titulos clonados a un pais nuevo (Bloque B) -- arranca por encima de los
-- ~64 ids legado de Venezuela, con holgura de sobra.
CREATE SEQUENCE taxonomia_titulos_clon_seq START WITH 1000;

ALTER TABLE empresas ADD COLUMN pais_id INTEGER REFERENCES paises(id);
UPDATE empresas e SET pais_id = t.pais_id FROM tenants t WHERE t.id = e.tenant_id;
ALTER TABLE empresas ALTER COLUMN pais_id SET NOT NULL;

COMMIT;
