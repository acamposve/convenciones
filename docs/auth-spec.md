# Spec — Autenticación y Autorización

> **Estado:** Draft v0.1 · **Depende de:** `constitution.md` (Artículos III, VI, VII)
> **Tipo:** Transversal — no forma parte de la cadena ingesta → análisis → categorización; es consumido por las tres etapas y por el portal web.

## 1. Propósito

Definir cómo se identifica a un usuario, a qué tenant pertenece, qué rol tiene, y cómo se hace cumplir el aislamiento por tenant (Art. III, VI.2) en cada request de la API. Esta spec cubre solo lo necesario para **Fase 1 — MVP Venezuela** (Art. X); deja el punto de extensión para SSO/SAML de Fase 3 sin activarlo, según Art. VII.2 ("previsto desde el inicio, aunque no se active para la demo").

## 2. Alcance

**Incluido en esta versión:**
- Login con email/password (hashing bcrypt/argon2, Art. VI.4).
- Emisión de JWT de sesión + refresh token.
- Claims mínimos: `user_id`, `tenant_id`, `role`, `pais`.
- RBAC con los 4 roles base del Art. VII.1: Admin Tenant, Revisor, Editor, Visualizador.
- Middleware de aislamiento por tenant: toda query filtra por `tenant_id` extraído del JWT, nunca de un parámetro de la request (evita cross-tenant por manipulación de payload).
- Flujo de reseteo forzado de contraseña para usuarios migrados del legado (Art. VI.4 — no se migran contraseñas heredadas).
- Bitácora de eventos de login/logout/fallos (Art. VI.5).

**Fuera de alcance en esta versión (Fase 3, Art. X):**
- Federación SSO/SAML real vía WorkOS/Auth0 — se deja una interfaz `IIdentityProvider` con implementación local única, para no tener que retrofitear el modelo de tenant/rol después.
- MFA.
- Políticas de expiración de licencia bloqueando login (depende de la entidad Licencia, Art. III — pendiente de tiers concretos, Art. XI.4).

## 3. Modelo de datos (extiende Art. III)

```
Tenant
  id, nombre, pais_id, plan_licencia, fecha_vencimiento

Usuario
  id, tenant_id, email, password_hash, rol, activo,
  requiere_reset_password (bool, default true para usuarios migrados),
  ultimo_login_at

Rol (enum por ahora, no tabla — extensible por instalación según Art. VII.1)
  AdminTenant | Revisor | Editor | Visualizador

BitacoraAcceso
  id, usuario_id, tenant_id, evento (login_ok|login_fail|logout|reset_password),
  ip, user_agent, timestamp
```

Nota: `Usuario` ya existía como entidad conceptual en Art. III ("Usuario / Rol"); esta spec la concreta a nivel de columnas para la Fase 1.

## 4. Flujo de autenticación

1. `POST /api/auth/login` con email + password.
2. Se valida contra `password_hash` (argon2). Si el usuario tiene `requiere_reset_password = true`, la respuesta indica `reset_required` y NO emite token de sesión completo — solo un token de un solo uso para `/api/auth/reset-password`.
3. Si es válido y no requiere reset: se emite `access_token` (JWT, TTL corto — pendiente definir exacto, ver §6) y `refresh_token` (almacenado hasheado en DB, TTL largo).
4. Cada request autenticada pasa por middleware que:
   - valida el JWT,
   - inyecta `tenant_id` del claim como filtro obligatorio de row-level security en cada acceso a datos (continuación directa del Art. VI.2 — el aislamiento es en cascada: tenant → documento → cláusula, y acá es donde arranca la cascada),
   - verifica que el rol tenga permiso para la acción (ver matriz §5).
5. Todo intento (éxito o fallo) se escribe en `BitacoraAcceso`.

## 5. Matriz de permisos (Fase 1 + Fase 2, `spec-empresas-comparacion.md` §3)

| Acción | Admin Tenant | Revisor | Editor | Visualizador |
|---|---|---|---|---|
| Gestionar usuarios del tenant | ✅ | ❌ | ❌ | ❌ |
| Gestionar empresas del catálogo (Fase 2) | ✅ | ❌ | ✅ | ❌ |
| Cargar documento (ingesta) | ✅ | ❌ | ✅ | ❌ |
| Ver cola de revisión | ✅ | ✅ | ❌ | ❌ |
| Aprobar/corregir cláusula (Art. IV.8) | ✅ | ✅ | ❌ | ❌ |
| Ver comparador / reporte publicado | ✅ | ✅ | ✅ | ✅ |
| Exportar PDF | ✅ | ✅ | ✅ | ❌ |

Esta matriz es el candado de negocio detrás del "publicación no negociable" del Art. IV.8: **solo Admin Tenant y Revisor pueden aprobar**, ningún otro rol tiene ese permiso ni siquiera vía API directa.

Los catálogos globales de segmentación (sector, tipo de empresa, categoría, actividad,
geografía — Art. II.5) **no son editables por ningún rol de tenant**, ni siquiera Admin
Tenant: "Gestionar empresas del catálogo" cubre solo la entidad Empresa propia del tenant,
no los catálogos globales de los que esa empresa toma sus atributos. La administración de
esos catálogos queda reservada al rol de Plataforma (Art. VII.4, Fase 5 — sin UI propia
todavía, ver `spec-plataforma.md`).

## 6. Abierto / pendiente (para Art. XI)

- TTL exacto de access/refresh token — no definido aún.
- Si `Visualizador` debe ser un rol de solo-lectura también fuera del tenant (ej. cliente final del reporte) o si eso es una entidad distinta ("usuario externo") — no resuelto.
- Cómo se relaciona el bloqueo por vencimiento de licencia (Art. VII.3) con el login — depende de que se cierren los tiers (Art. XI.4).
- Diseño concreto de `IIdentityProvider` para que la migración a WorkOS/Auth0 en Fase 3 no requiera cambiar el modelo de claims.

**Regla de enmienda aplicable:** cualquier cambio a la matriz de permisos de aprobación (§5) toca el Art. IV (revisión humana obligatoria) y por lo tanto requiere enmienda explícita a la constitución, no solo a esta spec.
