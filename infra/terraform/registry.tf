# El nombre de un ACR debe ser único a nivel GLOBAL de Azure (sin guiones,
# solo alfanumérico). Si "comparadordemoacr" está tomado, ajustar acá y
# en deploy-apps.yml donde se referencia el login server.
resource "azurerm_container_registry" "main" {
  name                = "${var.project_name}${var.environment}acr"
  resource_group_name = azurerm_resource_group.main.name
  location            = azurerm_resource_group.main.location
  sku                 = "Basic"

  # admin_enabled=true simplifica el demo (usuario/password para docker push).
  # Antes de producción real, migrar a autenticación por managed identity
  # y desactivar esto — ver Art. VI de la constitución sobre credenciales.
  admin_enabled = true
}
