# Plan de Publicación — Azure (Demo Fase 1, Venezuela)

> Alcance: pipeline de pasos 1–5 (ingesta → extracción → segmentación → clasificación) +
> auth mínima (tenant demo + AdminTenant sembrado). Alineado con Art. V de `constitution.md`
> (Azure Container Apps, PostgreSQL, blob storage cifrado). No incluye SSO/SAML (Fase 3)
> ni licenciamiento (Fase 2).
>
> Convención de este documento: cada paso marcado **🔴 MANUAL** lo haces tú, fuera de
> Terraform y GitHub Actions. Todo lo demás lo automatizan los archivos de este mismo commit.

## 0. Antes de tocar Terraform

| # | Paso | Por qué es manual |
|---|---|---|
| 1 | 🔴 **MANUAL** — Tener una suscripción de Azure activa y anotar su `subscription_id` y `tenant_id` (`az account show`) | Nadie puede automatizar la creación de la cuenta/suscripción misma |
| 2 | 🔴 **MANUAL** — Instalar Azure CLI localmente y correr `az login` una vez, para los pasos siguientes | Autenticación inicial interactiva |
| 3 | 🔴 **MANUAL** — Crear el resource group + storage account para el **remote state** de Terraform (ver comando abajo) | El backend de Terraform no puede crearse a sí mismo — es el huevo y la gallina de siempre |
| 4 | 🔴 **MANUAL** — Registrar una app de Azure AD con **federated credential (OIDC)** para que GitHub Actions se autentique sin guardar un client secret | Requiere decisiones de seguridad (qué repo/rama puede asumir el rol) que no deben quedar en código versionado |
| 5 | 🔴 **MANUAL** — Cargar los secretos resultantes en GitHub (Settings → Secrets and variables → Actions) | Los secretos nunca deben pasar por un commit, ni siquiera en un PR de infra |

### Comando para el paso 3 (remote state)

```bash
az group create --name rg-tfstate-comparador --location eastus
az storage account create \
  --name sttfstatecomparador \
  --resource-group rg-tfstate-comparador \
  --sku Standard_LRS \
  --encryption-services blob
az storage container create \
  --name tfstate \
  --account-name sttfstatecomparador
```

> El nombre `sttfstatecomparador` debe ser único a nivel global de Azure — si está tomado,
> ajústalo aquí y en `infra/terraform/providers.tf`.

### Comandos para el paso 4 (OIDC federado)

```bash
# Crea la app de Azure AD
az ad app create --display-name "comparador-github-actions"
# Anota el appId que devuelve — es tu AZURE_CLIENT_ID

# Crea el service principal asociado
az ad sp create --id <APP_ID>

# Asigna rol Contributor sobre la suscripción (o sobre el resource group
# si prefieres acotar el blast radius)
az role assignment create \
  --assignee <APP_ID> \
  --role Contributor \
  --scope /subscriptions/<SUBSCRIPTION_ID>

# Federated credential: permite que SOLO el workflow en la rama master
# de tu repo se autentique como esta app, sin client secret
# (la rama por defecto de este repo es "master", no "main" — confirmar con
# `git branch -r` / github/HEAD antes de correr esto si el repo cambia)
az ad app federated-credential create \
  --id <APP_ID> \
  --parameters '{
    "name": "github-master-branch",
    "issuer": "https://token.actions.githubusercontent.com",
    "subject": "repo:<TU_ORG>/<TU_REPO>:ref:refs/heads/master",
    "audiences": ["api://AzureADTokenExchange"]
  }'
```

### Secretos a cargar en GitHub (paso 5)

| Nombre del secret | Valor | Notas |
|---|---|---|
| `AZURE_CLIENT_ID` | `appId` del paso 4 | |
| `AZURE_TENANT_ID` | de `az account show` | |
| `AZURE_SUBSCRIPTION_ID` | de `az account show` | |
| `TF_VAR_postgres_admin_password` | 🔴 **MANUAL** — generá una contraseña fuerte URL-safe (solo letras, números y `-_`) | Nunca en `terraform.tfvars` |
| `TF_VAR_jwt_signing_key` | 🔴 **MANUAL** — generá 64 bytes aleatorios (`openssl rand -base64 48`) | Debe coincidir con lo que espera `TokenService.cs` |
| `TF_VAR_anthropic_api_key` | 🔴 **MANUAL** — tu API key de Claude para el servicio de clasificación | Ver `product-self-knowledge` si necesitas confirmar cómo generarla en console.anthropic.com |

## 1. Qué gestiona Terraform (`infra/terraform/`)

Automatizado, sin intervención manual una vez configurado el paso 0:

- Resource Group
- Azure Container Registry (Basic SKU)
- Log Analytics Workspace + Container Apps Environment
- PostgreSQL Flexible Server (Burstable B1ms — el tier más barato) + base de datos `comparador`
- Storage Account con contenedor privado `documentos` (Art. VI.1 — privado por defecto, sin acceso público)
- 3 Container Apps: `api` (.NET), `ai-service` (FastAPI), `frontend` (React)

**Deliberadamente fuera de alcance de este Terraform** (para no inflar el demo):
- Key Vault dedicado — los secretos van como Container App secrets directamente. Esto es una
  simplificación aceptable para demo, **no para Fase 1 de producción real** — hay que
  revisitarlo antes de tener clientes pagos reales (Art. VI.3 exige cifrado, esto lo cumple,
  pero Key Vault da rotación y auditoría que un secret plano en Container Apps no da).
- Aislamiento de red privada (VNet injection) — Fase 3 según Art. VIII.2.
- Custom domain / certificado — ver sección 4.

## 2. Qué gestionan los GitHub Actions (`.github/workflows/`)

| Workflow | Dispara con | Qué hace |
|---|---|---|
| `ci.yml` | Cualquier PR | Build + test de API (.NET), servicio IA (Python) y frontend (React). No toca Azure. |
| `terraform.yml` | PR que toca `infra/terraform/**` → `plan`. Push a `master` → `apply` | Aplica la infraestructura. El `apply` requiere aprobación manual (ver 🔴 abajo) |
| `deploy-apps.yml` | Push a `master` que toca `api/**`, `service/**` o `web/**` | Build de las 3 imágenes Docker, push a ACR, actualiza las Container Apps, aplica `db/schema.sql` o la migración legacy necesaria y corre los seeds de taxonomía y AdminTenant |

### 🔴 MANUAL — Configurar el Environment de aprobación

En GitHub: **Settings → Environments → New environment** → nómbralo `azure-production`.
Actívale **Required reviewers** y agrégate a ti mismo. Esto hace que `terraform apply`
se detenga y espere tu clic antes de tocar la infraestructura real — el workflow ya
referencia este nombre de ambiente, pero la protección misma se configura en la UI,
no en código.

## 3. Orden de ejecución recomendado

1. 🔴 **MANUAL** — Completar toda la sección 0 (cuenta, OIDC, secretos)
2. Abrir un PR que incluya `infra/terraform/` → revisa el `plan` que postea el workflow
3. Mergear a `master` (rama por defecto de este repo) → el `apply` queda pendiente de tu aprobación en el Environment
4. 🔴 **MANUAL** — Aprobar el `apply` en la pestaña Actions de GitHub
5. Una vez que la infra existe, mergear o re-disparar `deploy-apps.yml` → build, push,
   deploy de las 3 apps, schema/migración y seeds corren solos. Terraform crea primero las
   apps con una imagen pública temporal; el workflow la reemplaza con las imágenes del ACR.
6. 🔴 **MANUAL** — Verificar el login con `admin@empresademo.local` / la contraseña
  temporal `CambiarAhora123!` que imprime `service/db/seed_admin_user.py` en los logs de
  Actions (job `migrate-database`),
   y confirmar que pide cambio de contraseña (Art. VI.4 — ver también el email logueado,
   no enviado todavía, para `director@presenciavirtual.net`)
7. 🔴 **MANUAL** — Correr el pipeline completo con un PDF real de los 403 del dataset
   histórico, de punta a punta, antes del demo con el promotor

## 4. Dominio y HTTPS (opcional para este demo)

Azure Container Apps da un dominio `*.azurecontainerapps.io` con HTTPS automático sin
hacer nada. Si quieres un dominio propio para el demo:

- 🔴 **MANUAL** — Comprar/tener el dominio
- 🔴 **MANUAL** — Agregar el registro DNS CNAME que Azure te pida
- 🔴 **MANUAL** — Correr `az containerapp hostname add` (no está en Terraform porque
  requiere el registro DNS ya propagado primero — es un paso secuencial, no declarativo)

Para el demo con el promotor, **el dominio por defecto es suficiente** — no lo compliques
si no hace falta.

## 5. Costos esperados

Con tráfico bajo (uso de demo, no producción con usuarios reales), la mayoría de estos
recursos caen dentro del free tier de Azure Container Apps (2M requests, 180K vCPU-seg,
360K GiB-seg gratis por mes). El costo real esperado es principalmente el Postgres
Flexible Server Burstable (no tiene tier gratis) — presupuesta unos pocos dólares al mes
mientras el demo esté arriba. 🔴 **MANUAL** — Configurar una alerta de presupuesto en
Azure Cost Management para no llevarte una sorpresa si el demo queda corriendo más tiempo
del planeado.

## 6. Checklist resumido de TODO lo manual

- [ ] Suscripción Azure activa, `az login` hecho
- [ ] Resource group + storage account de remote state creados
- [ ] App de Azure AD + federated credential OIDC configurados
- [ ] Rol Contributor asignado a la app
- [ ] Los 6 secretos cargados en GitHub Secrets
- [ ] Environment `azure-production` con required reviewers configurado
- [ ] PR de Terraform revisado y mergeado
- [ ] `terraform apply` aprobado manualmente en Actions
- [ ] Taxonomía venezolana sembrada (5 categorías y ~60 títulos)
- [ ] Login de `admin@empresademo.local` verificado post-deploy
- [ ] Pipeline completo probado con un PDF real antes del demo
- [ ] (Opcional) DNS + dominio propio configurado
- [ ] Alerta de presupuesto configurada
