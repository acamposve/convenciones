variable "azure_subscription_id" {
  description = "Subscription de Azure. Viene del secret AZURE_SUBSCRIPTION_ID en CI."
  type        = string
}

variable "location" {
  description = "Región de Azure para todos los recursos."
  type        = string
  default     = "eastus"
}

variable "project_name" {
  description = "Prefijo corto usado en nombres de recursos."
  type        = string
  default     = "comparador"
}

variable "environment" {
  description = "Nombre del ambiente (demo, staging, production)."
  type        = string
  default     = "demo"
}

variable "image_tag" {
  description = "Tag de las imágenes Docker a desplegar (normalmente el SHA del commit, inyectado por deploy-apps.yml)."
  type        = string
  default     = "latest"
}

variable "bootstrap_image" {
  description = "Imagen pública temporal usada para crear las Container Apps antes del primer push al ACR."
  type        = string
  default     = "mcr.microsoft.com/azuredocs/containerapps-helloworld:latest"
}

# --- Secretos: NUNCA poner un valor real en terraform.tfvars versionado.
# Estos llegan como variables de entorno TF_VAR_<nombre> desde GitHub Secrets.

variable "postgres_admin_username" {
  description = "Usuario admin de PostgreSQL Flexible Server."
  type        = string
  default     = "comparador_admin"
}

variable "postgres_admin_password" {
  description = "🔴 MANUAL: generar y cargar como secret TF_VAR_postgres_admin_password."
  type        = string
  sensitive   = true
}

variable "jwt_signing_key" {
  description = "🔴 MANUAL: 64 bytes aleatorios, debe coincidir con lo que espera TokenService.cs."
  type        = string
  sensitive   = true
}

variable "anthropic_api_key" {
  description = "🔴 MANUAL: API key de Claude para el servicio de clasificación (ai-service)."
  type        = string
  sensitive   = true
}
