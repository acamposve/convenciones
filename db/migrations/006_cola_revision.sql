-- 006_cola_revision.sql
-- Cola de revision humana (Art. IV.7-8 de la constitucion, no negociable desde v1.0.0 pero
-- nunca construida hasta ahora / spec-empresas-comparacion.md Bloque D).
--
-- confianza: auto-reporte del modelo (decision cerrada en spec §5) -- señal blanda para
-- ordenar la cola, nunca una certificacion. Las clausulas ya clasificadas antes de este
-- cambio quedan con confianza=NULL (nunca se les pidio) y estado_revision='pendiente'
-- (default), asi que entran a la cola igual que las nuevas.

BEGIN;

ALTER TABLE clausulas ADD COLUMN confianza TEXT CHECK (confianza IN ('alto', 'medio', 'bajo'));
ALTER TABLE clausulas ADD COLUMN estado_revision TEXT NOT NULL DEFAULT 'pendiente'
    CHECK (estado_revision IN ('pendiente', 'aprobado', 'rechazado'));
ALTER TABLE clausulas ADD COLUMN revisado_por UUID REFERENCES usuarios(id);
ALTER TABLE clausulas ADD COLUMN revisado_at TIMESTAMPTZ;

CREATE INDEX idx_clausulas_estado_revision ON clausulas(tenant_id, estado_revision);

COMMIT;
