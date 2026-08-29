# Visión de arquitectura

**Referencia:** Esta es una síntesis ejecutiva. Para detalles, ver [`docs/constitution.md`](docs/constitution.md) Art. III–V.

## Principios

1. **Multi-tenancy por columna** (`tenant_id` en todas las tablas públicas)
2. **Separación clara API ↔ IA:** .NET (lógica) + Python (procesamiento de IA)
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
│  API (.NET 8)                                       │
│  - Autenticación (JWT, SSO/SAML en Fase 2)         │
│  - Gestión de tenants, usuarios, roles              │
│  - CRUD de documentos, cláusulas                    │
│  - Orquestación de publicación                      │
└────────┬──────────────────────────┬─────────────────┘
         │                          │
         │ Blob Storage             │ Service Bus/Queue
         ▼                          ▼
    ┌──────────────┐        ┌──────────────────────────┐
    │  Docs PDF    │        │  AI Service (Python)     │
    │  (Encrypted) │        │  - OCR                   │
    │              │        │  - Extracción            │
    └──────────────┘        │  - Segmentación          │
                            │  - Clasificación (Claude)│
                            │  - Verificación legal    │
                            └──────────┬───────────────┘
                                       │
                                       ▼
                            ┌──────────────────────────┐
                            │  Base de datos           │
                            │  PostgreSQL              │
                            │  (multi-tenant)          │
                            └──────────────────────────┘
```

## Flujo de procesamiento (MVP Demo)

```
1. Usuario carga PDF
   ↓
2. API recibe, guarda en Blob Storage
   ↓
3. API envía task a Service Bus
   ↓
4. AI Service consume task
   ├─ Extrae texto (PDFPlumber + Tesseract)
   ├─ Segmenta en cláusulas (heurística)
   ├─ Clasifica c/ LLM → titulo de taxonomía
   └─ Devuelve resultados
   ↓
5. API guarda cláusulas en DB
   ↓
6. Usuario ve lista de cláusulas + títulos asignados
   (FIN en MVP; Fase 1 agrega revisión + publicación)
```

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

| Componente | Decisión | Razón |
|---|---|---|
| API | C# / .NET 8 LTS | Preferencia del equipo; multi-tenancy nativa, auth, licensing |
| AI Service | Python / FastAPI | Ecosistema de IA/OCR; desacoplado de API |
| LLM | Claude (API) | Salida estructurada; evita entrenar modelos propios |
| Base de datos | PostgreSQL | JSON nativo, multi-tenant simple, migrables a Azure SQL |
| Storage | Azure Blob | Documentos encriptados en reposo, no en filesystem |
| Cola | Service Bus / RabbitMQ | Desacopla ingesta de procesamiento |
| Frontend | React + Vite | SPA, build rápido, ecosistema maduro |
| Auth | OIDC + SSO (Fase 2) | Estándar; WorkOS o Auth0 para SAML |
| Infra | Contenedores + Azure Container Apps | Simple para demo, escalable a Kubernetes |

## Seguridad (Art. VI)

- **Documentos privados por defecto**
- **Encriptación en reposo** (storage + DB)
- **Encriptación en tránsito** (HTTPS)
- **Aislamiento de datos** por `tenant_id` en todas las queries
- **Auditoría obligatoria** de cambios y accesos sensibles
- **Roles y permisos** por tenant (no cross-tenant)
- **Tokens JWT** con expiración (SSO en Fase 2)

## Evolución hacia Kubernetes

Hoy: Azure Container Apps (simple)  
Futuro: Migracion a AKS (Kubernetes) cuando:
- Múltiples tenants en producción
- Necesidad de autoscaling fino
- Integración con sistemas complejos

(Los Dockerfiles y docker-compose ya permiten esta migración sin reescritura)

---

**Stack completo: .NET 8 · Python/FastAPI · React/Vite · PostgreSQL · Terraform · Azure**

Justificación detallada de cada decisión en [`docs/constitution.md`](docs/constitution.md) Art. V.
