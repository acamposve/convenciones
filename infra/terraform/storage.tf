# El nombre debe ser único global, solo minúsculas/números, máx 24 chars.
resource "azurerm_storage_account" "documentos" {
  name                     = "st${var.project_name}${var.environment}doc"
  resource_group_name     = azurerm_resource_group.main.name
  location                = azurerm_resource_group.main.location
  account_tier             = "Standard"
  account_replication_type = "LRS"

  # Cifrado en reposo está activo por defecto en toda Storage Account de
  # Azure — no requiere configuración adicional (Art. VI.3).
  min_tls_version = "TLS1_2"

  # Art. VI.1: documentos privados por defecto. Nada de acceso público
  # a nivel de blob o contenedor.
  allow_nested_items_to_be_public = false
}

resource "azurerm_storage_container" "documentos" {
  name                  = "documentos"
  storage_account_name  = azurerm_storage_account.documentos.name
  container_access_type = "private"
}
