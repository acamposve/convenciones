# CI/CD Workflows

## Overview

Workflows de GitHub Actions automatizados para build, test, y deploy. Ubicados en `.github/workflows/`.

## Workflows

### `build-and-test.yml` (en construcción)

Ejecuta en cada push a rama `main`:
1. Build de API (.NET)
2. Tests de API (xUnit)
3. Build de service (Python)
4. Tests de service (pytest)
5. Build de frontend (Vite)

**Disparadores:** Push a `main`, PR a `main`  
**Artifacts:** Imágenes Docker (enviadas a ACR cuando esté listo)

### `terraform-plan.yml` (en construcción)

Ejecuta en PR que toque `infra/terraform/`:
1. `terraform fmt -check` — verificar formato
2. `terraform validate` — validar sintaxis
3. `terraform plan` — mostrar cambios propuestos

**Disparadores:** PR con cambios en `infra/`  
**Output:** Plan en comentario de PR (solo lectura)

### `terraform-apply.yml` (en construcción)

Ejecuta en merge a `main` que toque `infra/terraform/`:
1. Plan completo
2. Apply automático a Azure Dev
3. Update outputs (URLs, IPs, etc.)

**Disparadores:** Merge a `main` con cambios en `infra/`  
**Requisitos:** Credenciales de Azure en GitHub Secrets

### `deploy.yml` (en construcción)

Ejecuta en release (tag `v*`):
1. Build final de todas las imágenes
2. Push a Azure Container Registry
3. Deploy a Azure Container Apps (prod)

**Disparadores:** Tag `v1.0.0`, `v1.0.1`, etc.  
**Requisitos:** Credenciales de Azure + production secrets

---

## Secrets necesarios en GitHub

```
# Azure
AZURE_TENANT_ID
AZURE_SUBSCRIPTION_ID
AZURE_CLIENT_ID
AZURE_CLIENT_SECRET

# Database
DATABASE_PASSWORD

# AI
ANTHROPIC_API_KEY

# Registry
ACR_REGISTRY
ACR_USERNAME
ACR_PASSWORD
```

## Cómo agregar un workflow nuevo

1. Crear archivo en `.github/workflows/` (extensión `.yml`)
2. Definir triggers (`on:`), jobs, steps
3. Usar `actions/checkout`, `actions/setup-*` estándar
4. Para secrets, usar `secrets.NOMBRE` (no hardcodear valores)
5. Enviar en PR para revisión

**Referencia:** [GitHub Actions Documentation](https://docs.github.com/actions)

---

## Status badges (agregar a README.md cuando haya workflows reales)

```markdown
![Build](https://github.com/[org]/[repo]/actions/workflows/build-and-test.yml/badge.svg)
![Terraform](https://github.com/[org]/[repo]/actions/workflows/terraform-plan.yml/badge.svg)
```
