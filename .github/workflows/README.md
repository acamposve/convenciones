# CI/CD Workflows

> **Se eliminó el deploy a Azure** (Terraform y los workflows que empujaban a Azure Container
> Registry/Container Apps) — el proyecto va a desplegar a otro proveedor, todavía sin decidir.
> Mientras tanto solo queda el workflow de build+test, que no depende de ningún proveedor de
> nube. Cuando se elija el proveedor nuevo, este documento y los workflows de deploy se
> vuelven a escribir desde cero — lo de abajo no es un plan a futuro, es lo que existe hoy.

## Overview

Workflows de GitHub Actions ubicados en `.github/workflows/`.

## Workflows

### `ci.yml`

Corre en cada PR contra `main` (no en push directo):
1. Build + test de la API (.NET 8, `dotnet test`)
2. Build + test del servicio de IA (Python, `pytest`)
3. Build del frontend (React/Vite, `npm run build`)

No construye ni publica imágenes Docker, y no requiere ningún secret — solo compila y corre
los tests de cada componente.

## Cómo agregar un workflow nuevo

1. Crear archivo en `.github/workflows/` (extensión `.yml`)
2. Definir triggers (`on:`), jobs, steps
3. Usar `actions/checkout`, `actions/setup-*` estándar
4. Para secrets, usar `secrets.NOMBRE` (no hardcodear valores)
5. Enviar en PR para revisión

**Referencia:** [GitHub Actions Documentation](https://docs.github.com/actions)
