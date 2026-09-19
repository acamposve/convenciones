-- 012_rls_supabase.sql
-- Row Level Security para Supabase (Fase 2.3 de PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md,
-- Art. VI.2 y VI.7 de constitution.md).
--
-- ADVERTENCIA (ver PLAN_MIGRACION..., nota de riesgo abierto bajo Fase 2.3): auth.jwt()
-- depende de que la sesion de Postgres tenga poblado request.jwt.claims -- eso lo hace
-- automaticamente PostgREST/el Data API de Supabase, pero NO una conexion directa de
-- Npgsql/EF Core o psycopg (lo que usan hoy la API en C# y el servicio Python). Mientras
-- eso no este resuelto y probado con un test de aislamiento real, estas politicas quedan
-- declaradas pero no son el mecanismo que efectivamente aisla -- el filtro por tenant_id en
-- codigo (Art VI.2, vigente desde el MVP) sigue siendo el que hace el trabajo real.
--
-- Por que no rompe nada de lo que ya corre: el rol con el que se conectan hoy la API y el
-- servicio Python es el dueno de las tablas (o tiene privilegios equivalentes), y Postgres
-- no aplica RLS al dueno de una tabla por default. Activar esto deja las politicas listas
-- para cuando se resuelva la propagacion del JWT, sin bloquear las conexiones actuales.
-- A proposito NO se usa `FORCE ROW LEVEL SECURITY`: eso si aplicaria RLS incluso al dueno de
-- la tabla, y romperia las conexiones actuales antes de tener la propagacion de claims
-- resuelta -- no es un olvido, es deliberado hasta que ese riesgo este cerrado.
--
-- Alcance: las 4 tablas que lista el plan (empresas, documentos, clausulas, negociaciones).
-- OJO: peticiones/ofertas/reuniones/acuerdos/bitacora_negociacion (hijas de negociaciones,
-- spec-negociacion.md) NO tienen su propia columna tenant_id -- solo negociacion_id -- asi
-- que quedan FUERA de esta migracion. Si se quiere RLS ahi tambien hace falta una politica
-- con subquery contra negociaciones (o agregarles tenant_id directo) -- decision que no se
-- toma en este archivo, requiere su propia revision.

BEGIN;

ALTER TABLE empresas ENABLE ROW LEVEL SECURITY;
ALTER TABLE documentos ENABLE ROW LEVEL SECURITY;
ALTER TABLE clausulas ENABLE ROW LEVEL SECURITY;
ALTER TABLE negociaciones ENABLE ROW LEVEL SECURITY;

CREATE POLICY tenant_isolation_empresas ON empresas
    USING (tenant_id = (auth.jwt() ->> 'tenant_id')::uuid);

CREATE POLICY tenant_isolation_documentos ON documentos
    USING (tenant_id = (auth.jwt() ->> 'tenant_id')::uuid);

CREATE POLICY tenant_isolation_clausulas ON clausulas
    USING (tenant_id = (auth.jwt() ->> 'tenant_id')::uuid);

CREATE POLICY tenant_isolation_negociaciones ON negociaciones
    USING (tenant_id = (auth.jwt() ->> 'tenant_id')::uuid);

-- Biblioteca publica (Art VI.7, spec-biblioteca-publica.md): unica excepcion explicita al
-- aislamiento por tenant, y solo sobre `documentos` -- nunca clausulas ni datos de empresa
-- mas alla del nombre (spec-biblioteca-publica.md: "No se toca clausulas en absoluto").
-- Politica adicional PERMISSIVE (default): se combina con OR junto a
-- tenant_isolation_documentos, asi que un SELECT ve "mis documentos" O "los publicos";
-- INSERT/UPDATE/DELETE siguen regidos solo por tenant_isolation_documentos (FOR ALL),
-- porque esta es FOR SELECT unicamente.
CREATE POLICY biblioteca_publica_documentos ON documentos
    FOR SELECT
    USING (es_publico = true);

COMMIT;
