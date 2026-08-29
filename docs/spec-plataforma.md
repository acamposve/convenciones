# Spec — Rol de Plataforma / Onboarding SaaS (Fase 5)

> **Depende de:** `constitution.md` v2.0.0 (Art. VI.6, Art. VII.4, Art. XI.5)
> **Estado:** borrador
> **Objetivo:** reemplazar `service/db/seed_admin_user.py` (bootstrap manual por consola)
> por (a) un registro público self-service para operadores nuevos y (b) un panel de
> Plataforma donde Presencia Virtual gestiona esos tenants después de creados (licencia,
> países habilitados).

## 1. Alcance de esta fase

| Incluido | Excluido |
|---|---|
| Registro público self-service: crea el Tenant + su primer Usuario Admin Tenant, sin intervención de Plataforma | Facturación real (cobro, pasarela de pago) — se asume manual/fuera de banda; el tenant nuevo arranca sin plan pago hasta que se facture aparte |
| Login de Plataforma, con roles internos granulares (no un único "GOD") | Panel de soporte/analytics cross-tenant más allá de licencia/países |
| Ver/editar licencia y fecha de vencimiento por tenant | Activación de Uruguay/Argentina/Chile en sí (siguen sin validación legal, Art II.4/XI.1) — esta fase deja la mecánica lista, no las activa |
| Activación comercial de país **por tenant** (Art. II.4) — qué países tiene habilitados cada operador según su licencia | |
| Flip global de `paises.activo` (el gate legal en sí: ¿existe ya validación de abogado laboral para este país?) — reservado al rol más alto | |

## 2. Arquitectura: `usuarios.tenant_id` nullable (decisión cerrada)

Se investigó el código real de `api/` (.NET) antes de decidir, no solo en abstracto:

- El API tiene un solo controller de auth (`AuthController.cs`) y una sola línea de
  middleware que lee el claim `tenant_id` (`Program.cs`) — superficie chica.
- El login (`AuthController.cs`) ya busca el usuario **solo por email**, sin filtrar por
  tenant — un usuario de Plataforma con `tenant_id = NULL` cae en el mismo camino sin
  cambiar el login en sí.
- La única tabla real es `service/db/schema.sql` (no hay EF Core migrations) — sacar el
  `NOT NULL` de `usuarios.tenant_id` es una migración chica.
- Puntos que sí hay que tocar, todos acotados: `TokenService.cs` (emitir el claim
  `tenant_id` de forma null-safe, no como string vacío), `AuthController.cs` (la
  resolución de país por tenant explota con `InvalidOperationException` si el tenant es
  null — hay que ramificar para el caso Plataforma), el enum `rol_usuario` (agregar los
  roles nuevos, en Postgres y en el enum C# `RolUsuario`), y el índice único
  `(tenant_id, email)` (Postgres no lo hace global entre filas con `tenant_id NULL` — hace
  falta un índice único parcial aparte para el email de usuarios de Plataforma).

Se descarta la tabla separada `usuarios_plataforma`: dado lo chico que es hoy el código de
auth, esa alternativa duplicaría `TokenService`, `AuthController`, refresh/reset-tokens y
la validación de JWT en `Program.cs` — más superficie de mantenimiento que la que se evita.

## 3. Decisiones cerradas (preguntas de §3 anterior)

- **Roles granulares desde ya**, no un único "GOD" — mismo espíritu que Master/Operador/
  Transcriptor del legado, adaptado:
  - **PlataformaAdmin**: control total — licencia/países por tenant, flip global de
    `paises.activo` (el gate legal), y alta de otros usuarios de Plataforma.
  - **PlataformaSoporte**: gestión del día a día — editar licencia/fecha de vencimiento y
    países habilitados por tenant. No puede tocar el flip global de país ni crear otros
    usuarios de Plataforma.
  - **PlataformaAuditor**: solo lectura — ver tenants, licencias y países habilitados,
    para auditoría/reporting. Ninguna escritura.
- **Alta de tenant: self-service**, sin aprobación de Plataforma. Un formulario público
  crea el Tenant y su primer Usuario Admin Tenant (mismo flujo de "reset de contraseña
  obligatorio en el primer login" que ya usa `seed_admin_user.py`) — arranca sin países
  habilitados más allá de Venezuela (el único con `paises.activo=true` hoy) y sin plan
  pago hasta que se facture aparte (fuera de alcance, tabla de §1). Plataforma interviene
  **después**, no en la creación.
- **Activación de país: por tenant.** `paises.activo` (global) sigue siendo el gate legal
  de Art. II.4 — ¿existe ya la validación de un abogado laboral para este país, en
  absoluto? Nueva tabla `tenant_paises_habilitados` (M:N) decide, dado que un país ya está
  globalmente activo, a cuáles tenants se les vendió ese país. Los dos flags son
  independientes: un país puede estar `activo=true` globalmente y aun así no estar
  habilitado para un tenant específico que no lo pagó.

  **Nota de coherencia con el esquema actual:** `tenants.pais_id` sigue siendo un único
  valor NOT NULL (el país "de origen" del operador, el que ya usa el claim `pais` del JWT,
  auth-spec.md §2) — hoy ningún tenant opera multi-país de verdad. `tenant_paises_habilitados`
  se modela igual como M:N pensando en Fase 6 (expansión), pero en esta fase cada tenant
  nuevo arranca con exactamente una fila ahí (su propio `pais_id`, poblada automáticamente
  al registrarse) — Plataforma puede agregar países adicionales a futuro sin rediseñar el
  modelo de licenciamiento cuando la expansión sea real.

## 4. Modelo de datos nuevo/modificado

- `usuarios.tenant_id` pasa a nullable (antes `NOT NULL`) — NULL identifica a un usuario de
  Plataforma
- `rol_usuario` (enum Postgres) y `RolUsuario` (enum C#) ganan tres valores:
  `PlataformaAdmin`, `PlataformaSoporte`, `PlataformaAuditor`
- Índice único parcial `usuarios(email) WHERE tenant_id IS NULL` — el `UNIQUE(tenant_id, email)`
  existente no alcanza para filas con `tenant_id NULL` (Postgres trata cada NULL como
  distinto en un índice único)
- `tenant_paises_habilitados` (`tenant_id`, `pais_id`) — tabla puente, ver nota de §3
- `tenants.suspendido` (bool, default false) — para que Plataforma pueda desactivar un
  operador sin borrar sus datos (ej. licencia vencida, incumplimiento) — el login sigue
  funcionando, pero el JWT no habilita ninguna acción (Art VI.2, el aislamiento no cambia,
  solo se agrega este check adicional)

## 5. Permisos

| Acción | PlataformaAdmin | PlataformaSoporte | PlataformaAuditor |
|---|---|---|---|
| Ver tenants / licencias / países habilitados | ✅ | ✅ | ✅ |
| Editar licencia / fecha de vencimiento | ✅ | ✅ | ❌ |
| Habilitar/deshabilitar país por tenant | ✅ | ✅ | ❌ |
| Suspender/reactivar un tenant | ✅ | ✅ | ❌ |
| Flip global de `paises.activo` (gate legal, Art II.4) | ✅ | ❌ | ❌ |
| Crear otro usuario de Plataforma | ✅ | ❌ | ❌ |

El registro público self-service (crear un tenant nuevo) no requiere ningún rol de
Plataforma — es un endpoint sin autenticación, como ya lo es hoy `POST /tenants`.

## 6. Plan de implementación

Cuatro bloques. A toca el modelo de auth compartido (riesgoso, se verifica con especial
cuidado antes de seguir); B es la superficie pública; C es el panel de Plataforma; D es el
rol en sí en el login existente.

### A. Modelo de datos + auth nullable ✅ terminado

- [x] Migración: `usuarios.tenant_id` nullable, índice único parcial de email,
  `tenant_paises_habilitados`, `tenants.suspendido`, valores nuevos de `rol_usuario`
  (`service/db/migrations/009_plataforma.sql`, con backfill de tenants existentes)
- [x] `api/Models/Usuario.cs`: `TenantId` → `Guid?`; enum `RolUsuario` con los 3 roles nuevos
- [x] `api/Services/TokenService.cs`: emitir el claim `tenant_id` de forma null-safe (omitirlo
  si es null, no emitir un string vacío)
- [x] `api/Controllers/AuthController.cs`: la resolución de país por tenant no debe explotar
  cuando el usuario no tiene tenant (usuario de Plataforma) — el claim `pais` queda vacío/omitido
- [x] `api/Services/AuthorizationPolicies.cs`: 4 políticas nuevas para los 3 roles de Plataforma
- [x] Verificado con Postgres real y el stack completo (`docker compose`): schema fresco y
  migración incremental (con un tenant preexistente, confirmando el backfill) llegan a la
  misma estructura. Contra la API .NET real: un usuario con `tenant_id=NULL` se loguea y
  recibe un JWT con `PlataformaAdmin` y **sin** `tenant_id` ni `pais`; el flujo completo de
  un usuario normal (login → reset de contraseña obligatorio → login → refresh) sigue
  funcionando exactamente igual que antes, confirmado decodificando ambos JWT. `dotnet test`
  7/7 (agregado `GenerarAccessToken_UsuarioDePlataformaSinTenantNiPais_OmiteAmbosClaims`).

### B. Registro público self-service ✅ terminado

- [x] Endpoint público (sin auth) que crea Tenant + primer Usuario Admin Tenant en una sola
  transacción (`POST /tenants` extendido) — a diferencia de `seed_admin_user.py`, acá el
  usuario elige su propia contraseña en el momento, así que `requiere_reset_password=false`
  (no aplica el flujo de reset obligatorio, que es para contraseñas que generó otra
  persona)
- [x] Puebla `tenant_paises_habilitados` automáticamente con el `pais_id` del tenant nuevo
- [x] Frontend: `web/src/auth/RegisterPage.jsx`, ruta pública `/registro` (fuera de
  `ProtectedRoute`), enlazada desde `LoginPage.jsx`
- [x] `seed_admin_user.py` y `docs/bootstrap-demo.md` documentan el registro self-service
  como el camino real; el script queda como atajo de desarrollo

Verificado de punta a punta contra el stack completo (Postgres real + API .NET + servicio
Python + Vite): registré "Consultora Laboral del Este" desde `/registro` → quedé logueado
de una (sin pasar por reset) → confirmé en Postgres el tenant, el usuario
(`requiere_reset_password=false`) y `tenant_paises_habilitados` con Venezuela. Probé
también las validaciones del lado del servidor: contraseña corta (422) y email sin "@"
(422).

### C. Panel de Plataforma ✅ terminado

- [x] Backend (`api/Controllers/PlataformaController.cs`, nuevo): listar tenants (con
  licencia, países habilitados, suspendido), editar licencia/fecha de vencimiento,
  habilitar/deshabilitar país por tenant, suspender/reactivar, flip global de
  `paises.activo`, crear otro usuario de Plataforma — matriz de permisos de §5 via
  `[Authorize(Policy = ...)]` por endpoint
- [x] `AuthController.cs`: login y refresh rechazan con 401 si el tenant está suspendido
- [x] Frontend: `web/src/plataforma/PlataformaPage.jsx`, ruta `/plataforma` — el login es
  el mismo formulario compartido (`LoginPage.jsx`), que redirige según el rol del JWT
- [x] Verificado de punta a punta contra el stack completo (primera vez que un
  `[Authorize(Policy=...)]` real se ejercita en este API — hasta ahora solo existían las
  políticas, sin controller que las usara): PlataformaAdmin ve/edita todo e incluso crea
  otro usuario de Plataforma (201); PlataformaSoporte ve y edita pero el flip global de
  país le da 403; PlataformaAuditor solo ve (403 en cualquier escritura); un AdminTenant
  normal recibe 403 en cualquier endpoint de Plataforma; suspender un tenant bloquea el
  login de su AdminTenant con 401 y el mensaje correcto; reactivar lo desbloquea.
  `dotnet test` 7/7, `npm run build` limpio.

### D. Housekeeping ✅ terminado

- [x] `auth-spec.md` §5 bis: modelo de Plataforma documentado aparte (no encajaba en la
  matriz de roles de tenant existente)
- [x] `docs/bootstrap-demo.md` y `seed_admin_user.py`: documentan el registro self-service
  como el camino real; el script queda como atajo de desarrollo (y ahora también puebla
  `tenant_paises_habilitados`, consistente con el registro self-service)

## 7. Checklist resumido

- [x] A. Modelo de datos + auth nullable
- [x] B. Registro público self-service
- [x] C. Panel de Plataforma
- [x] D. Housekeeping

**Fase 5 completa.**

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fases 2-4.)*
