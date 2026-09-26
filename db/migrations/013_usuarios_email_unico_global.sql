-- 013_usuarios_email_unico_global.sql
-- Alta de tenant self-service (Fase 5.2, POST /tenants): AuthController.Login busca el
-- usuario solo por email, sin filtrar por tenant_id (docs/spec-plataforma.md §2, decision
-- ya cerrada antes de este cambio). Con UNIQUE(tenant_id, email) mas el indice parcial de
-- Plataforma, dos tenants distintos podian registrar el mismo email de AdminTenant y dejar
-- el login ambiguo: FirstOrDefaultAsync toma cualquiera de las dos cuentas. Se reemplazan
-- ambos indices por uno global sobre email que cubre todas las filas (con o sin tenant_id).
--
-- Antes de aplicar en una base con datos reales: confirmar que no existen ya emails
-- duplicados entre tenants (esta migracion falla si los hay, a proposito, en vez de
-- resolver la colision en silencio).

BEGIN;

ALTER TABLE usuarios DROP CONSTRAINT usuarios_tenant_id_email_key;
DROP INDEX idx_usuarios_email_plataforma;

CREATE UNIQUE INDEX idx_usuarios_email ON usuarios(email);

COMMIT;
