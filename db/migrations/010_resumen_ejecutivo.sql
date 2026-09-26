-- 010_resumen_ejecutivo.sql
-- Resumen ejecutivo y campo comparativo (Fase 6, spec-resumen-ejecutivo.md, Art IV.6/6 bis).
-- campo_comparativo comparte estado_revision (la clasificacion); resumen_ejecutivo tiene su
-- propio estado, independiente -- puede aprobarse en un momento distinto al de la
-- clasificacion, sin bloquearse mutuamente.

BEGIN;

ALTER TABLE clausulas ADD COLUMN campo_comparativo TEXT;
ALTER TABLE clausulas ADD COLUMN resumen_ejecutivo TEXT;
ALTER TABLE clausulas ADD COLUMN estado_revision_resumen TEXT NOT NULL DEFAULT 'pendiente'
    CHECK (estado_revision_resumen IN ('pendiente', 'aprobado', 'rechazado'));
ALTER TABLE clausulas ADD COLUMN revisado_por_resumen UUID REFERENCES usuarios(id);
ALTER TABLE clausulas ADD COLUMN revisado_at_resumen TIMESTAMPTZ;

CREATE INDEX idx_clausulas_estado_revision_resumen ON clausulas(tenant_id, estado_revision_resumen);

COMMIT;
