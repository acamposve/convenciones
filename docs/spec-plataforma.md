# Spec — Rol de Plataforma / Onboarding SaaS (Fase 5)

> **Depende de:** `constitution.md` v2.0.0 (Art. VI.6, Art. VII.4, Art. XI.5)
> **Estado:** borrador — tiene una decisión de arquitectura sin cerrar (ver §2). No se
> puede implementar en detalle hasta resolverla.
> **Objetivo:** reemplazar `service/db/seed_admin_user.py` (bootstrap manual por consola)
> por un panel real donde el operador del SaaS (Presencia Virtual) da de alta tenants
> (operadores) nuevos.

## 1. Alcance de esta fase

| Incluido | Excluido |
|---|---|
| Login de Plataforma (usuario "GOD"), fuera del modelo de tenant | Facturación real (cobro, pasarela de pago) — se asume manual/fuera de banda por ahora |
| Alta de un tenant nuevo (operador) + su primer usuario Admin Tenant | Panel de soporte/analytics cross-tenant |
| Ver/editar licencia y fecha de vencimiento por tenant | |
| Activar/desactivar países comercialmente (Art. II.4) — flip de `paises.activo` | |

## 2. La decisión de arquitectura pendiente (Art. XI.5 de la constitución)

Un usuario de Plataforma, por definición, no pertenece a un tenant — necesita existir
*antes* de que exista cualquier tenant al que pertenecer. Hoy `usuarios.tenant_id` es
`NOT NULL`, así que no hay forma de insertarlo sin cambiar algo. Dos caminos, sin decidir:

**a) Tabla separada `usuarios_plataforma`**, fuera del modelo de tenant.
Más limpio conceptualmente, no toca el aislamiento existente (Art. VI.2). Contra: es un
sistema de auth paralelo — otro login, otro JWT, otras políticas de expiración/reset.

**b) `usuarios.tenant_id` nullable**, solo para este rol.
Reutiliza todo lo que ya existe (JWT, bcrypt, `TokenService.cs`, bitácora de accesos).
Contra: toca la regla dura del Art. VI.2 y cada query que hoy asume `tenant_id NOT NULL` —
hay que auditar que ninguna quede accidentalmente abierta a nivel de plataforma.

## 3. Preguntas abiertas

- ¿Cuántas personas de Presencia Virtual van a operar este panel? Afecta si alcanza con un
  rol único "GOD" o si conviene algo con más granularidad (el legado tenía Master/Operador/
  Transcriptor, roles internos distintos entre sí).
- ¿El alta de un tenant nuevo requiere que alguien de Presencia Virtual lo dé de alta a
  mano, o puede ser self-service (alguien se registra solo y paga)? Cambia bastante el
  diseño del flujo de onboarding.
- ¿La activación de un país (Art. II.4) es global (una vez activado, todos los tenants
  pueden usarlo) o se activa por tenant individualmente según su plan de licencia?
