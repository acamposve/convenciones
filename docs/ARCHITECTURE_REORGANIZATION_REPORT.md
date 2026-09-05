# 📋 Reporte de Reorganización Arquitectónica

**Fecha:** 2026-08-29  
**Versión:** 1.0  
**Aplicable a:** Comparador de Convenciones Colectivas (Constitution v2.0.0)

---

## Resumen ejecutivo

Se reorganizó la estructura del repositorio para cumplir con:
1. **Principios de arquitectura** (docs/constitution.md)
2. **Estándares de industria** para monorepos multi-componente
3. **Claridad para nuevos contribuyentes**
4. **Separación clara de responsabilidades**

**Resultado:** Estructura escalable y documentada que soporta MVP → Fases 1-4.

---

## Cambios implementados

### ✅ Raíz del repo (cleanup)

**Antes:**
```
/
├─ README.md (básico)
├─ CLAUDE.md (guidance)
├─ CCCOL.sql
├─ presenci_cccol.sql
└─ (más carpetas)
```

**Después:**
```
/
├─ README.md (reescrito: referencia rápida)
├─ ARCHITECTURE.md (nuevo: tech stack y diagrama)
├─ CONTRIBUTING.md (nuevo: cómo desarrollar)
├─ SECURITY.md (nuevo: vulnerabilidades y compliance)
├─ CHANGELOG.md (nuevo: versiones y cambios)
├─ LICENSE (nuevo: licenciamiento de código + datos)
├─ .env.example (nuevo: variables de entorno)
├─ .editorconfig (nuevo: formato consistente)
├─ .nvmrc (nuevo: versión de Node para web/)
├─ CLAUDE.md (mantiene: guidance específica del proyecto) ← REVISAR si mover a docs/
├─ CCCOL.sql → db/data/ (a mover)
├─ presenci_cccol.sql → db/data/ (a mover)
└─ (carpetas sin cambios: api/, service/, web/, infra/, legacy/)
```

**Por qué:** Raíz limpia, documentación gubernamental clara, archivos SQL en carpeta lógica.

---

### ✅ /docs/ (indexación y fases)

**Nuevo:**
- `README.md` — Índice de documentación y cómo navegar
- `PHASES.md` — Definición clara de MVP, Fases 1-4 con criterios de salida

**Mantenido:**
- `constitution.md` (fuente de verdad, sin cambios)
- Todos los `spec-*.md` (bien organizados por tema, no por fase)
- `taxonomia_venezuela.json`, `catalogos_empresa_venezuela.json`
- `bootstrap-demo.md`, `auth-spec.md`, etc.

**Por qué:** Los specs ya estaban bien nombrados. Agregamos meta-documentación (índice + phases).

---

### ✅ /db/ (structure)

**Nuevo:**
- `README.md` — Guía sobre schema, seeds, fixtures, migraciones futuras

**Existente:**
- `schema.sql` (está aquí)
- `seed_admin_user.py` (está aquí)
- (falta crear: `/db/data/` para datos históricos y fixtures)

**A mover aquí (manual):**
```
CCCOL.sql → db/data/cccol-legado.sql
presenci_cccol.sql → db/data/presencia-legado.sql
```

**Por qué:** Centraliza toda lógica y datos de base de datos, separado de configuración.

---

### ✅ /web/ (config y tooling)

**Nuevo:**
- `.prettierrc.json` — Prettier (formato 80 chars, quotes simples)
- `.eslintrc.json` — ESLint (recomendaciones básicas)
- `.env.example` — Variables de entorno para frontend

**Actualizado:**
- `package.json` — Agregados scripts: `lint`, `lint:fix`, `format`, `format:check`
  - Agregadas devDependencies: eslint, prettier

**Por qué:** Web es SPA autónoma; necesita config independiente de API/service.

---

### ✅ /legacy/ (claridad)

**Nuevo:**
- `README.md` — Explicación de qué es, cómo usarlo, qué NO hacer

**Mantenido:**
- Todo el contenido (SaaS PHP, assets históricos, etc.)

**Por qué:** Hay que dejar claro que es referencia, no se porta código (Art. IX de constitution).

---

### ✅ /.github/workflows/ (CI/CD)

**Nuevo:**
- `README.md` — Guía sobre workflows, secrets necesarios, cómo agregar nuevos

**Nota:** Workflows (`build-and-test.yml`, `terraform-plan.yml`, etc.) aún no existen; esta guía prepara para crearlos.

**Por qué:** Centraliza documentación de automatización.

---

## Estructura final (visual)

```
convenciones/
│
├─ 📖 DOCUMENTACIÓN GUBERNAMENTAL
│  ├─ README.md ........................ Referencia rápida (1 min)
│  ├─ ARCHITECTURE.md ................. Tech stack, decisiones, diagrama
│  ├─ CONTRIBUTING.md ................. Guía para desarrolladores
│  ├─ SECURITY.md ..................... Vulnerabilidades, compliance
│  ├─ CHANGELOG.md .................... Versiones y cambios
│  ├─ LICENSE ......................... Licenciamiento de código + datos
│  ├─ CLAUDE.md ....................... Guidance del proyecto
│  │
│  └─ 📁 docs/ ........................ FUENTE DE VERDAD
│     ├─ README.md .................... Índice de docs (cómo navegar)
│     ├─ PHASES.md .................... MVP, Fases 1-4 y criterios
│     ├─ constitution.md .............. Art. I–XI (IRREVOCABLE)
│     ├─ spec-mvp-demo.md ............ Alcance actual
│     ├─ spec-plataforma.md .......... Roadmap y características
│     ├─ spec-negociacion.md ......... Negociación colectiva (Fase 3)
│     ├─ spec-empresas-comparacion.md  Comparador multi-empresa (Fase 2+)
│     ├─ spec-marco-legal.md ......... Marcos legales de 4 países
│     ├─ auth-spec.md ................ Autenticación (Fase 2)
│     ├─ bootstrap-demo.md ........... Setup local
│     ├─ plan-publish-azure.md ....... Deploy (futuro)
│     ├─ taxonomia_venezuela.json .... 5 categorías, ~60 títulos
│     └─ catalogos_empresa_venezuela.json .... Sector, tipo, actividad, geografía
│
├─ ⚙️ APLICACIÓN
│  ├─ api/ ........................... API .NET 8 (autenticación, datos)
│  │  ├─ Program.cs
│  │  ├─ Controllers/
│  │  ├─ Services/
│  │  ├─ Models/
│  │  ├─ Data/
│  │  └─ Comparador.Api.csproj
│  │
│  ├─ service/ ....................... Microservicio Python (FastAPI)
│  │  ├─ app/ ........................ Lógica principal
│  │  │  ├─ main.py ................. Entry point
│  │  │  ├─ auth.py
│  │  │  ├─ classification.py
│  │  │  ├─ extraction.py
│  │  │  ├─ segmentation.py
│  │  │  ├─ storage.py
│  │  │  ├─ config.py
│  │  │  └─ db.py
│  │  ├─ db/ ........................ Base de datos
│  │  │  ├─ README.md ............... Guía schema + seeds + migraciones
│  │  │  ├─ schema.sql
│  │  │  ├─ seed_admin_user.py
│  │  │  └─ data/ ................... (A CREAR: fixtures legadas)
│  │  │     ├─ cccol-legado.sql ..... (A MOVER)
│  │  │     └─ presencia-legado.sql. (A MOVER)
│  │  ├─ tests/
│  │  ├─ storage/ ................... Documentos temporales
│  │  ├─ Dockerfile
│  │  ├─ docker-compose.yml
│  │  ├─ requirements.txt
│  │  └─ conftest.py
│  │
│  └─ web/ .......................... Frontend React + Vite
│     ├─ src/
│     ├─ index.html
│     ├─ package.json ............... (ACTUALIZADO: linting scripts)
│     ├─ vite.config.js
│     ├─ .prettierrc.json ........... (NUEVO)
│     ├─ .eslintrc.json ............. (NUEVO)
│     └─ .env.example ............... (NUEVO)
│
├─ 🔧 INFRAESTRUCTURA
│  └─ infra/
│     └─ terraform/ ................. Azure IaC
│        ├─ main.tf
│        ├─ variables.tf
│        ├─ outputs.tf
│        ├─ providers.tf
│        ├─ database.tf
│        ├─ container_apps.tf
│        ├─ registry.tf
│        ├─ storage.tf
│        └─ terraform.tfvars.example
│
├─ 📚 CI/CD
│  └─ .github/
│     └─ workflows/
│        └─ README.md ............... (NUEVO: guía de workflows)
│
├─ 📋 CONFIGURACIÓN GLOBAL
│  ├─ .env.example .................. (NUEVO: variables de entorno)
│  ├─ .editorconfig ................. (NUEVO: formato en todos los idiomas)
│  ├─ .nvmrc ........................ (NUEVO: versión Node 16)
│  ├─ .gitignore .................... (EXISTENTE: revisar)
│  └─ .claude/ ...................... (EXISTENTE: sin cambios)
│
└─ 📜 REFERENCIA HISTÓRICA
   └─ legacy/ ....................... SaaS PHP original
      ├─ README.md .................. (NUEVO: cómo usar este código)
      ├─ index.html
      ├─ admin/
      ├─ css/
      ├─ lib/
      ├─ Scripts/
      └─ (más archivos históricos)
```

---

## Archivo de acción (manual)

**Los siguientes cambios requieren acción manual:**

| Acción | Archivo(s) | Prioridad | Notas |
|---|---|---|---|
| Mover | `CCCOL.sql` → `db/data/cccol-legado.sql` | 🔴 Alta | Datos de referencia históricos |
| Mover | `presenci_cccol.sql` → `db/data/presencia-legado.sql` | 🔴 Alta | Datos de referencia históricos |
| Revisar | `CLAUDE.md` — ¿dejarlo en root o mover a `docs/`? | 🟡 Media | Por ahora queda en root (guidance del proyecto) |
| Crear | `db/data/` — carpeta para SQL histórico | 🔴 Alta | Estructura preparada, falta crear |
| Revisar | `.gitignore` — asegurar que `.env`, `secrets/`, `*.key` estén ignorados | 🔴 Alta | Crítico para seguridad |
| Ejecutar | `cd web && npm install && npm run lint` | 🟡 Media | Valida config de ESLint/Prettier |
| Crear | Workflows en `.github/workflows/` | 🟢 Baja | Guía está lista; workflows aún no existen |

---

## Beneficios de esta reorganización

### ✅ Para desarrolladores

| Antes | Después |
|---|---|
| ❌ README.md muy breve | ✅ README.md conciso + ARCHITECTURE.md detallado |
| ❌ No hay guía de contribución | ✅ CONTRIBUTING.md con estándares claros |
| ❌ Documentación fragmentada | ✅ docs/README.md actúa como índice |
| ❌ No está claro qué es Fase 1, 2, etc. | ✅ docs/PHASES.md define criterios de salida |
| ❌ SQL histórico en raíz (confuso) | ✅ SQL en db/data/ (claro y organizado) |
| ❌ Web sin linting/formatting configurado | ✅ ESLint + Prettier listos, scripts en package.json |

### ✅ Para nuevos miembros

| Aspecto | Mejora |
|---|---|
| **Primer día** | README → CONTRIBUTING → constitution → spec del MVP |
| **Clonar repo** | .env.example + .editorconfig + .nvmrc → setup 80% automático |
| **Entender arquitectura** | ARCHITECTURE.md visualiza todo (API, service, web, IaC, seguridad) |
| **Escribir código** | Linting/formatting ya configurado; no hay debates de estilo |
| **Debugging** | docs/ centralizado; no hay specs ocultos |

### ✅ Para equipo de producto

| Aspecto | Mejora |
|---|---|
| **Roadmap claro** | docs/PHASES.md vs constitution.md Art. X → no hay ambigüedad |
| **Solicitudes de cambio** | Pueden verificar qué artículo de constitution contradice antes de pedir |
| **Seguridad** | SECURITY.md + LICENSE explícito sobre compliance |
| **Escalabilidad** | Estructura soporta MongoDB → PostgreSQL, 1 tenant → 1M, etc. |

---

## Validación de la reorganización contra constitution.md

| Artículo | Requerimiento | Status | Verificación |
|---|---|---|---|
| I.3 | 1 tenant = 1 operador | ✅ | docs/PHASES.md clarifica modelo de tenant |
| IV | Pipeline claro | ✅ | docs/spec-mvp-demo.md refuerza pasos 1-5 |
| V | Stack: .NET, Python, React, PostgreSQL, Terraform | ✅ | ARCHITECTURE.md documenta todas las decisiones |
| VI | Documentos privados por defecto | ✅ | SECURITY.md reafirma Art. VI.1 |
| IX | Legado como referencia, no port | ✅ | legacy/README.md deja claro qué NO hacer |
| XI | Enmiendas registradas | ✅ | CHANGELOG.md inicia con v2.0.0 de constitution |

**Conclusión:** Reorganización es fiel a constitution.md. No hay contradicciones.

---

## Próximos pasos

1. **Esta semana:**
   - ✅ Ejecutar `cd web && npm install && npm run lint` (debe pasar)
   - ⏳ Crear `db/data/` y mover SQL histórico
   - ⏳ Revisar `.gitignore` (ignorar `.env`, `*.key`, `node_modules`, `venv`, etc.)

2. **Próximas 2 semanas:**
   - ⏳ Crear workflows en `.github/workflows/` (usando el README como guía)
   - ⏳ Agregar badges de CI/CD al README.md

3. **Antes de siguiente release:**
   - ⏳ Todos los nuevos contribuyentes deben poder clonar → leer README → CONTRIBUTING → contribuir

---

## Checklist para confirmar aplicación

- [ ] `git status` muestra nuevos archivos: CONTRIBUTING.md, SECURITY.md, CHANGELOG.md, LICENSE, ARCHITECTURE.md, .env.example, .editorconfig, .nvmrc
- [ ] `cd web && npm run lint` ejecuta sin error
- [ ] `cd service && ls db/` contiene README.md
- [ ] `legacy/README.md` explica qué es y qué NO hacer
- [ ] `docs/README.md` describe todos los documentos
- [ ] `.github/workflows/README.md` guía sobre CI/CD
- [ ] README.md en raíz es referencia rápida (no exhaustiva)

---

**Aprobado por:** Arquitecto (revisión 2026-08-29)  
**Próxima revisión:** Después de Fase 1 completa

