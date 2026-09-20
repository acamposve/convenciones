# Visión de arquitectura

**Referencia:** Esta es una síntesis ejecutiva. Para detalles, ver [`constitution.md`](constitution.md) Art. III–V.

> **Migración de stack en curso (Enmienda 2.2.0):** este documento describe el stack
> **objetivo** tras adoptar [`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)
> (C#/.NET 10 unificado + Supabase). El stack **desplegado hoy** es **.NET 10 LTS** (Fase 3.1
> ya aplicada: `api/` corre sobre `net10.0`) **+ Python/FastAPI + PostgreSQL** — Python sigue
> atendiendo la mayoría de la lógica de negocio (Fase 3.3 pendiente) y sigue siendo la base
> de datos local, no Supabase todavía (Fase 3.2 pendiente).
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

> Diagrama del stack **objetivo**. Hoy en producción, "AI Service" sigue siendo un
> microservicio Python (FastAPI) separado, y la base de datos es un Azure PostgreSQL
> Flexible Server autoadministrado — ver nota de migración arriba. El transporte entre API
> y AI Service **no** es una cola real: Art. V decide Service Bus/RabbitMQ, pero nunca se
> implementó (`EVALUACION_MADUREZ_MIGRACION.md` §4.3) — hoy el pipeline corre con
> `BackgroundTasks` de FastAPI, en memoria dentro del mismo contenedor Python.

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

> Flujo **objetivo** (stack unificado en .NET 10). El pipeline desplegado hoy usa
> PDFPlumber + Tesseract en el microservicio Python, ejecutado en memoria vía
> `BackgroundTasks` de FastAPI (no hay cola real todavía — ver nota de la sección "Capas"
> arriba) — los pasos conceptuales (extracción → segmentación → clasificación) no cambian,
> solo el runtime y el mecanismo de background entre pasos.

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

Stack objetivo (Enmienda 2.2.0); el desplegado hoy sigue el diseño anterior (columna
"Antes", vigente hasta el cutover — Fase 5.2 del plan de migración).

| Componente | Decisión | Antes (hasta cutover) | Razón del cambio |
|---|---|---|---|
| API + procesamiento | C# / .NET 10 LTS, servicio único | API en .NET 8 + servicio Python/FastAPI separado | Un solo runtime que mantener y desplegar; el MVP procesa documentos sin IA |
| Clasificación IA | Fuera del MVP; queda diferida | Claude (API) directo desde Python | Se requiere una decisión posterior de alcance, proveedor y validación |
| Base de datos | Supabase (PostgreSQL 16 + RLS) | PostgreSQL (Azure Flexible Server autoadministrado) | RLS nativo refuerza aislamiento por tenant; Auth/Storage integrados; menos infraestructura propia que operar |
| Storage | Supabase Storage | Azure Blob | Documentos encriptados en reposo, con RLS unificado a la política de datos |
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
**Stack desplegado hoy: .NET 10 LTS (API, sin unificar todavía) · Python/FastAPI (pipeline legado) · React/Vite · PostgreSQL local — sin ambiente en la nube**

Justificación detallada de cada decisión y del plan de transición en
[`constitution.md`](constitution.md) Art. V y en
[`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
