output "frontend_url" {
  description = "URL pública del frontend — esta es la que compartes con el promotor."
  value       = "https://${azurerm_container_app.frontend.latest_revision_fqdn}"
}

output "api_url" {
  description = "URL pública de la API (útil para smoke tests manuales)."
  value       = "https://${azurerm_container_app.api.latest_revision_fqdn}"
}

output "acr_login_server" {
  description = "Login server del ACR, usado por deploy-apps.yml para push de imágenes."
  value       = azurerm_container_registry.main.login_server
}

output "postgres_fqdn" {
  description = "FQDN del servidor Postgres (para diagnóstico manual si algo falla)."
  value       = azurerm_postgresql_flexible_server.main.fqdn
}

output "resource_group_name" {
  value = azurerm_resource_group.main.name
}
