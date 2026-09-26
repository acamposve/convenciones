# Visión de arquitectura

**Referencia:** Esta es una síntesis ejecutiva. Para detalles, ver [`constitution.md`](constitution.md) Art. III–V.

> **Migración de stack en curso (Enmienda 2.2.0):** el backend ya está unificado en
> **.NET 10 LTS** — el microservicio Python (`service/`) se eliminó del repositorio (Fase
> 5.2 cutover + Fase 5.4 limpieza de
> [`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)).
> Lo que sigue pendiente del stack objetivo es Supabase en sí: base de datos (hoy PostgreSQL
> local, Fase 3.2 del plan) y storage de documentos (hoy disco local vía `Storage:Root` /
> el volumen `api_storage` de `docker-compose.yml`, no Supabase Storage todavía).
>
> **Sin infraestructura en la nube (Enmienda 2.3.0):** se retiró Azure (Terraform, Container
> Apps) — hoy no hay ningún ambiente desplegado, el proyecto corre solo local. El proveedor
> de deploy nuevo todavía no está decidido; las referencias a "Azure" que quedan abajo son
> **históricas** (documentan la decisión anterior), no el estado actual.

## Principios

1. **Multi-tenancy por columna** (`tenant_id` en todas las tablas públicas) + **Row Level Security nativo** en Supabase como segunda capa de aislamiento (Art. VI.2)
2. **Backend unificado en .NET 10** (API + procesamiento determinista de documentos) — sustituye la separación anterior .NET/Python para el MVP; la IA queda diferida
3. **Documentos privados por defecto** — solo públicos si se declara explícitamente
4. **Revisión humana obligatoria antes de publicar** — nunca automatizado

## Capas

```
┌─────────────────────────────────────────────────────┐
│  Web Frontend (React + Vite)                        │
│  - Login, carga de documentos                       │
│  - Cola de revisión (Fase 1)                        │
│  - Reporte web (Fase 1)                             │
└────────────────────┬────────────────────────────────┘
                     │ HTTP/REST
                     ▼
┌─────────────────────────────────────────────────────┐
│  API (.NET 10 LTS) — servicio único                  │
│  - Autenticación vía Supabase Auth (SSO/SAML Fase 2) │
│  - Gestión de tenants, usuarios, roles               │
│  - CRUD de documentos, cláusulas                     │
│  - Orquestación de publicación                       │
│  - Worker en proceso (Channel + BackgroundService):  │
│    OCR, extracción y segmentación deterministas      │
│  - Clasificación automática y análisis IA: diferidos │
└────────┬──────────────────────────┬──────────────────┘
         │                          │
         │ Supabase Storage         │ Npgsql / EF Core
         ▼                          ▼
    ┌──────────────┐        ┌──────────────────────────┐
    │  Docs PDF    │        │  Supabase                │
    │  (Encrypted, │        │  PostgreSQL 16            │
    │   RLS)       │        │  (multi-tenant + RLS)     │
    └──────────────┘        └──────────────────────────┘
```

> Este diagrama ya es el estado real en cuanto al servicio: un solo servicio .NET 10, sin
> "AI Service" Python separado ni cola entre procesos que desacoplar
> (`System.Threading.Channels` en el mismo contenedor). Lo que sigue objetivo, no
> desplegado, son los dos recuadros de abajo — "Docs PDF" es hoy disco local, no Supabase
> Storage, y "PostgreSQL 16" es local, no Supabase (ver nota de migración arriba).

## Flujo de procesamiento (MVP Demo sin IA)

```
1. Usuario carga PDF
   ↓
2. API recibe, guarda en Supabase Storage
   ↓
3. API encola el documento en el Channel en proceso
   ↓
4. DocumentProcessingWorker (BackgroundService) toma el item
   ├─ Extrae texto (UglyToad.PdfPig + Tesseract OCR)
   ├─ Segmenta en cláusulas (heurística, [GeneratedRegex])
   └─ Devuelve cláusulas sin título automático
   ↓
5. API guarda cláusulas en Supabase (Postgres)
   ↓
6. Usuario ve lista de cláusulas + títulos asignados
   (FIN en MVP; Fase 1 agrega revisión + publicación)
```

> Flujo real hoy: stack unificado en .NET 10, sin microservicio Python que ejecute esto
> aparte. Los pasos conceptuales (extracción → segmentación → clasificación) son los mismos
> que en el diseño original en Python; solo cambió el runtime y el mecanismo de background
> entre pasos (`Channel` + `BackgroundService` en vez de `BackgroundTasks` de FastAPI). El
> paso 2 ("guarda en Supabase Storage") sigue objetivo, no desplegado — hoy guarda en disco
> local (ver nota de migración arriba); el paso 5 sí es real (Postgres local).

## Entidades de datos (simplificado)

```sql
-- Multi-tenant
Tenant (tenant_id, nombre, país, plan, vigencia)
Usuario (user_id, tenant_id, rol, email, ...)
Empresa (company_id, tenant_id, nombre, rif, sector, ...)

-- Contenido
Documento (doc_id, tenant_id, empresa_id, nombre, estado, público)
Cláusula (clause_id, doc_id, tenant_id, número, texto, 
          título_asignado, score_confianza, estado_revisión)

-- Referencia global (no duplicadas por tenant)
País (country_code, nombre, ...)
Categoría (category_id, país, nombre, descripción)
Título (title_id, país, categoría_id, nombre, requiere_comparativa)
LeyArtículo (law_id, país, número_artículo, contenido, títulos_relacionados)

-- Auditoría
Bitácora (log_id, tenant_id, usuario_id, acción, recurso, timestamp)
```

## Decisiones técnicas (Art. V)

Stack objetivo (Enmienda 2.2.0). La columna "Antes" documenta el diseño previo al cutover
(Fase 5.2 del plan de migración, ya completo) — el microservicio Python ya no existe en el
repositorio.

| Componente | Decisión | Antes (pre-cutover) | Razón del cambio |
|---|---|---|---|
| API + procesamiento | C# / .NET 10 LTS, servicio único — **ya aplicado** | API en .NET 8 + servicio Python/FastAPI separado | Un solo runtime que mantener y desplegar; el MVP procesa documentos sin IA |
| Clasificación IA | Fuera del MVP; queda diferida | Claude (API) directo desde Python | Se requiere una decisión posterior de alcance, proveedor y validación |
| Base de datos | Supabase (PostgreSQL 16 + RLS) — **pendiente** (hoy PostgreSQL local, Fase 3.2) | PostgreSQL (Azure Flexible Server autoadministrado) | RLS nativo refuerza aislamiento por tenant; Auth/Storage integrados; menos infraestructura propia que operar |
| Storage | Supabase Storage — **pendiente** (hoy disco local, `Storage:Root`/volumen `api_storage`) | Azure Blob | Documentos encriptados en reposo, con RLS unificado a la política de datos |
| Cola | `System.Threading.Channels` en proceso | Service Bus / RabbitMQ | Suficiente para el volumen actual; ya no hay dos procesos que desacoplar |
| Frontend | React + Vite | React + Vite | Sin cambio |
| Auth | Supabase Auth + SSO (Fase 2) | OIDC + SSO (Fase 2) | Autenticación y base de datos bajo el mismo proveedor |
| Infra | Contenedores (Docker) — proveedor sin decidir (Enmienda 2.3.0, se retiró Azure) | Contenedores + Azure Container Apps (`api` + `ai-service`) | Azure Container Apps ya no es la decisión vigente; una vez elegido el proveedor nuevo, sigue aplicando el motivo original (menos superficie de infra que mantener, sin Postgres administrado) |

## Seguridad (Art. VI)

- **Documentos privados por defecto**
- **Encriptación en reposo** (storage + DB)
- **Encriptación en tránsito** (HTTPS)
- **Aislamiento de datos** por `tenant_id` en todas las queries, reforzado por **Row Level Security nativo** en Supabase (política `tenant_id = (auth.jwt() ->> 'tenant_id')::uuid`)
- **Auditoría obligatoria** de cambios y accesos sensibles
- **Roles y permisos** por tenant (no cross-tenant)
- **Tokens JWT** con expiración, emitidos por Supabase Auth (SSO en Fase 2)

## Evolución de infraestructura

**[Enmienda 2.3.0]** Se retiró Azure Container Apps (y Terraform) del repositorio — hoy no
hay ningún ambiente desplegado en la nube, solo desarrollo local. El proveedor de deploy
nuevo todavía no está decidido; cuando se elija, esta sección se vuelve a escribir con el
camino real de escalado (contenedores simples → orquestador tipo Kubernetes si hace falta
autoscaling fino o multi-tenant a gran escala).

(Los Dockerfiles y docker-compose siguen siendo el empaquetado — no dependen de Azure — así
que no hace falta reescribirlos para el proveedor nuevo, solo definir dónde correrlos)

---

**Stack objetivo MVP: .NET 10 LTS (API + procesamiento determinista) · React/Vite · Supabase (PostgreSQL + RLS) · proveedor de deploy sin decidir**
**Stack desplegado hoy: .NET 10 LTS unificado (API + procesamiento determinista, ya sin microservicio Python) · React/Vite · PostgreSQL local + disco local para documentos — sin ambiente en la nube, Supabase (base de datos y storage) todavía pendiente**

Justificación detallada de cada decisión y del plan de transición en
[`constitution.md`](constitution.md) Art. V y en
[`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
