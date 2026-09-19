# Visión de arquitectura

**Referencia:** Esta es una síntesis ejecutiva. Para detalles, ver [`constitution.md`](constitution.md) Art. III–V.

> **Migración de stack en curso (Enmienda 2.2.0):** este documento describe el stack
> **objetivo** tras adoptar [`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md)
> (C#/.NET 10 unificado + Supabase). El stack **desplegado hoy** sigue siendo .NET 8 +
> Python/FastAPI + Azure PostgreSQL — Python sigue atendiendo tráfico real hasta el cutover
> (Fase 5.2 del plan). Nada de esto se ha portado a código todavía.

## Principios

1. **Multi-tenancy por columna** (`tenant_id` en todas las tablas públicas) + **Row Level Security nativo** en Supabase como segunda capa de aislamiento (Art. VI.2)
2. **Backend unificado en .NET 10** (API + procesamiento de IA vía `Microsoft.Extensions.AI`) — sustituye la separación anterior .NET (lógica) / Python (IA); ver migración arriba
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
│    OCR, extracción, segmentación,                    │
│    clasificación (Claude vía Microsoft.Extensions.AI)│
│    verificación legal                                │
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

## Flujo de procesamiento (MVP Demo)

```
1. Usuario carga PDF
   ↓
2. API recibe, guarda en Supabase Storage
   ↓
3. API encola el documento en el Channel en proceso
   ↓
4. DocumentProcessingWorker (BackgroundService) toma el item
   ├─ Extrae texto (UglyToad.PdfPig + OCR)
   ├─ Segmenta en cláusulas (heurística, [GeneratedRegex])
   ├─ Clasifica c/ LLM (Claude vía Microsoft.Extensions.AI) → titulo de taxonomía
   └─ Devuelve resultados
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
| API + AI Service | C# / .NET 10 LTS, servicio único | API en .NET 8 + AI Service en Python/FastAPI separado | Un solo runtime que mantener y desplegar; `Microsoft.Extensions.AI` cubre lo que antes requería Python |
| LLM | Claude (API) vía `IChatClient` | Claude (API) directo desde Python | Mismo proveedor; cambia el cliente |
| Base de datos | Supabase (PostgreSQL 16 + RLS) | PostgreSQL (Azure Flexible Server autoadministrado) | RLS nativo refuerza aislamiento por tenant; Auth/Storage integrados; menos infraestructura propia que operar |
| Storage | Supabase Storage | Azure Blob | Documentos encriptados en reposo, con RLS unificado a la política de datos |
| Cola | `System.Threading.Channels` en proceso | Service Bus / RabbitMQ | Suficiente para el volumen actual; ya no hay dos procesos que desacoplar |
| Frontend | React + Vite | React + Vite | Sin cambio |
| Auth | Supabase Auth + SSO (Fase 2) | OIDC + SSO (Fase 2) | Autenticación y base de datos bajo el mismo proveedor |
| Infra | Contenedores + Azure Container Apps (1 app de API) | Contenedores + Azure Container Apps (`api` + `ai-service`) | Menos Container Apps y sin Postgres administrado que mantener |

## Seguridad (Art. VI)

- **Documentos privados por defecto**
- **Encriptación en reposo** (storage + DB)
- **Encriptación en tránsito** (HTTPS)
- **Aislamiento de datos** por `tenant_id` en todas las queries, reforzado por **Row Level Security nativo** en Supabase (política `tenant_id = (auth.jwt() ->> 'tenant_id')::uuid`)
- **Auditoría obligatoria** de cambios y accesos sensibles
- **Roles y permisos** por tenant (no cross-tenant)
- **Tokens JWT** con expiración, emitidos por Supabase Auth (SSO en Fase 2)

## Evolución hacia Kubernetes

Hoy: Azure Container Apps (simple)  
Futuro: Migracion a AKS (Kubernetes) cuando:
- Múltiples tenants en producción
- Necesidad de autoscaling fino
- Integración con sistemas complejos

(Los Dockerfiles y docker-compose ya permiten esta migración sin reescritura)

Con el stack objetivo, esto aplica solo al Container App de la API (.NET 10) — Supabase es
BaaS y no se gestiona en AKS; el ahorro de infraestructura propia es justamente parte del
motivo del cambio (Enmienda 2.2.0).

---

**Stack objetivo: .NET 10 LTS (unificado) · React/Vite · Supabase (PostgreSQL + RLS) · Terraform · Azure**
**Stack desplegado hoy: .NET 8 · Python/FastAPI · React/Vite · Azure PostgreSQL · Terraform · Azure**

Justificación detallada de cada decisión y del plan de transición en
[`constitution.md`](constitution.md) Art. V y en
[`../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md`](../PLAN_MIGRACION_CSHARP_FIREBASE_LOGGING.md).
