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

-- Hijas de negociaciones (spec-negociacion.md): peticiones/reuniones/acuerdos/
-- bitacora_negociacion no tienen tenant_id propio, solo negociacion_id -- sin politica
-- propia quedarian sin RLS (legibles/escribibles por cualquier rol no-bypass con acceso a
-- la tabla, filtrando solo por FK, no por tenant). Se resuelven con una subquery contra
-- negociaciones.tenant_id. ofertas es un nivel mas profundo (peticion_id -> negociacion_id),
-- asi que su subquery encadena un JOIN mas.
CREATE POLICY tenant_isolation_peticiones ON peticiones
    USING (negociacion_id IN (
        SELECT id FROM negociaciones WHERE tenant_id = (auth.jwt() ->> 'tenant_id')::uuid
    ));

CREATE POLICY tenant_isolation_ofertas ON ofertas
    USING (peticion_id IN (
        SELECT p.id
        FROM peticiones p
        JOIN negociaciones n ON n.id = p.negociacion_id
        WHERE n.tenant_id = (auth.jwt() ->> 'tenant_id')::uuid
    ));

CREATE POLICY tenant_isolation_reuniones ON reuniones
    USING (negociacion_id IN (
        SELECT id FROM negociaciones WHERE tenant_id = (auth.jwt() ->> 'tenant_id')::uuid
    ));

CREATE POLICY tenant_isolation_acuerdos ON acuerdos
    USING (negociacion_id IN (
        SELECT id FROM negociaciones WHERE tenant_id = (auth.jwt() ->> 'tenant_id')::uuid
    ));

CREATE POLICY tenant_isolation_bitacora_negociacion ON bitacora_negociacion
    USING (negociacion_id IN (
        SELECT id FROM negociaciones WHERE tenant_id = (auth.jwt() ->> 'tenant_id')::uuid
    ));

ALTER TABLE peticiones ENABLE ROW LEVEL SECURITY;
ALTER TABLE ofertas ENABLE ROW LEVEL SECURITY;
ALTER TABLE reuniones ENABLE ROW LEVEL SECURITY;
ALTER TABLE acuerdos ENABLE ROW LEVEL SECURITY;
ALTER TABLE bitacora_negociacion ENABLE ROW LEVEL SECURITY;

-- Biblioteca publica (Art VI.7, spec-biblioteca-publica.md): la vista `biblioteca_publica`
-- ya vive en schema.sql (es SQL portable, sin funciones de Supabase -- desarrollo local
-- tambien la necesita, y GET /biblioteca en main.py la consulta directamente en vez de
-- documentos/empresas). Acá solo va lo que SI es especifico de Supabase: los grants para
-- que un rol RLS no-bypass (anon/authenticated) pueda leerla. No hace falta una politica
-- publica sobre `documentos`/`empresas` para que el JOIN interno de la vista funcione: una
-- vista corre con los privilegios de su dueno por default en Postgres (no se declara
-- `security_invoker`), y esta se crea con un rol BYPASSRLS -- el JOIN ignora las politicas
-- tenant_isolation sin importar que rol externo consulte la vista. El filtro real (que
-- documento es publico) lo hace el WHERE de la vista, no RLS.
--
-- anon/authenticated son los roles estandar que Supabase crea en todo proyecto nuevo, y por
-- default les otorga privilegios amplios (INSERT/UPDATE/DELETE/...) sobre objetos nuevos del
-- schema public via ALTER DEFAULT PRIVILEGES -- el REVOKE explicito no es defensivo de mas:
-- esta vista no es "auto-updatable" para Postgres (tiene un JOIN, no una sola tabla en el
-- FROM), asi que un INSERT/UPDATE/DELETE fallaria igual hoy, pero no hay que depender de esa
-- casualidad de forma -- se deja solo el SELECT que efectivamente se necesita.
REVOKE ALL ON biblioteca_publica FROM anon, authenticated;
GRANT SELECT ON biblioteca_publica TO anon, authenticated;

COMMIT;
