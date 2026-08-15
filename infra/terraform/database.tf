resource "azurerm_postgresql_flexible_server" "main" {
  name                = "psql-${var.project_name}-${var.environment}"
  resource_group_name = azurerm_resource_group.main.name
  location            = azurerm_resource_group.main.location

  # Tier más barato disponible — suficiente para un demo, no para producción
  # con tráfico real (ver Art. VIII sobre ruta de escalabilidad).
  sku_name   = "B_Standard_B1ms"
  storage_mb = 32768
  version    = "16"

  administrator_login    = var.postgres_admin_username
  administrator_password = var.postgres_admin_password

  zone = "1"

  # Sin alta disponibilidad ni backups extendidos — aceptable para demo,
  # no para Fase 1 de producción real.
  backup_retention_days = 7
}

resource "azurerm_postgresql_flexible_server_database" "main" {
  name      = "comparador"
  server_id = azurerm_postgresql_flexible_server.main.id
  collation = "en_US.utf8"
  charset   = "utf8"
}

# Permite que los Container Apps (y el paso de migración en CI) alcancen
# el servidor. Azure define este rango especial (0.0.0.0-0.0.0.0) como
# "permitir servicios de Azure" — el runner de GitHub Actions NO cae acá,
# por eso deploy-apps.yml abre y cierra su propia regla temporal.
resource "azurerm_postgresql_flexible_server_firewall_rule" "allow_azure_services" {
  name             = "AllowAzureServices"
  server_id        = azurerm_postgresql_flexible_server.main.id
  start_ip_address = "0.0.0.0"
  end_ip_address   = "0.0.0.0"
}
