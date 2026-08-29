locals {
  postgres_connection_string = "Host=${azurerm_postgresql_flexible_server.main.fqdn};Database=${azurerm_postgresql_flexible_server_database.main.name};Username=${var.postgres_admin_username};Password=${var.postgres_admin_password};SslMode=Require"
  # Mismo servidor/base que postgres_connection_string arriba, pero en formato URL — el
  # microservicio Python (service/app/db.py, via psycopg) espera DATABASE_URL como
  # postgresql://usuario:password@host/db, no la sintaxis Host=...;Database=... de .NET.
  postgres_connection_url   = "postgresql://${var.postgres_admin_username}:${urlencode(var.postgres_admin_password)}@${azurerm_postgresql_flexible_server.main.fqdn}/${azurerm_postgresql_flexible_server_database.main.name}?sslmode=require"
  storage_connection_string = azurerm_storage_account.documentos.primary_connection_string

  # Dominio propio del frontend (presenciavirtual.com.uy), agregado a mano por fuera de
  # Terraform: `az containerapp hostname bind` con validation-method HTTP (el certificado
  # gratis gestionado por Azure no tiene un recurso azurerm estable en este provider para
  # manejarlo declarativamente todavia -- ver nota en container_apps.tf del frontend). Se
  # listan ambos orígenes separados por coma porque Program.cs (.NET) y main.py (Python)
  # ahora parsean Cors__WebOrigin/WEB_ORIGIN como una lista, no un string único -- así la
  # URL vieja de Azure sigue funcionando mientras el dominio nuevo se termina de asentar.
  frontend_allowed_origins = "https://${azurerm_container_app.frontend.ingress[0].fqdn},https://presenciavirtual.com.uy"
}

resource "azurerm_container_app" "api" {
  name                         = "ca-${var.project_name}-api"
  resource_group_name          = azurerm_resource_group.main.name
  container_app_environment_id = azurerm_container_app_environment.main.id
  revision_mode                = "Single"

  registry {
    server               = azurerm_container_registry.main.login_server
    username             = azurerm_container_registry.main.admin_username
    password_secret_name = "acr-password"
  }

  secret {
    name  = "acr-password"
    value = azurerm_container_registry.main.admin_password
  }
  secret {
    name  = "db-connection-string"
    value = local.postgres_connection_string
  }
  secret {
    name  = "jwt-signing-key"
    value = var.jwt_signing_key
  }
  secret {
    name  = "storage-connection-string"
    value = local.storage_connection_string
  }

  template {
    min_replicas = 0 # scale-to-zero: aprovecha el free tier fuera de horario de demo
    max_replicas = 2

    container {
      name   = "api"
      image  = var.bootstrap_image
      cpu    = 0.5
      memory = "1Gi"

      env {
        name        = "ConnectionStrings__Default"
        secret_name = "db-connection-string"
      }
      env {
        name        = "Jwt__SigningKey"
        secret_name = "jwt-signing-key"
      }
      # Sin appsettings.json de produccion (solo existe appsettings.Development.json, que
      # ASP.NET Core no carga bajo ASPNETCORE_ENVIRONMENT=Production, el default del
      # Dockerfile), Jwt:Issuer/Jwt:Audience quedarian null: TokenService.cs emitiria
      # tokens sin claims iss/aud, y service/app/auth.py (que exige issuer="comparador-api"
      # / audience="comparador-web", hardcodeado en config.py) los rechazaria con 401 en
      # todo endpoint del pipeline. Mismos valores que ya usa docker-compose.yml en local.
      env {
        name  = "Jwt__Issuer"
        value = "comparador-api"
      }
      env {
        name  = "Jwt__Audience"
        value = "comparador-web"
      }
      env {
        name        = "Storage__ConnectionString"
        secret_name = "storage-connection-string"
      }
      # ingress[0].fqdn (FQDN estable de la app), NO latest_revision_fqdn: este ultimo
      # incluye el sufijo de la revision (ca-comparador-frontend--du3sbi8...), que cambia
      # cada vez que deploy-apps.yml pushea una imagen nueva. Con el, el allowlist quedaba
      # apuntando a la revision del bootstrap y el navegador recibia el preflight sin
      # Access-Control-Allow-Origin — el login moria con error de CORS.
      env {
        name  = "Cors__WebOrigin"
        value = local.frontend_allowed_origins
      }
    }
  }

  lifecycle {
    ignore_changes = [template[0].container[0].image]
  }

  ingress {
    external_enabled = true
    target_port      = 8080
    traffic_weight {
      latest_revision = true
      percentage      = 100
    }
  }
}

resource "azurerm_container_app" "ai_service" {
  name                         = "ca-${var.project_name}-ai"
  resource_group_name          = azurerm_resource_group.main.name
  container_app_environment_id = azurerm_container_app_environment.main.id
  revision_mode                = "Single"

  registry {
    server               = azurerm_container_registry.main.login_server
    username             = azurerm_container_registry.main.admin_username
    password_secret_name = "acr-password"
  }

  secret {
    name  = "acr-password"
    value = azurerm_container_registry.main.admin_password
  }
  secret {
    name  = "anthropic-api-key"
    value = var.anthropic_api_key
  }
  secret {
    name  = "storage-connection-string"
    value = local.storage_connection_string
  }
  # database-url y jwt-signing-key faltaban acá — service/app/config.py exige DATABASE_URL
  # y JWT_SIGNING_KEY con os.environ[...] (no hay default), así que sin estos dos el
  # contenedor no llega a arrancar (falla en el primer import de app.config).
  secret {
    name  = "database-url"
    value = local.postgres_connection_url
  }
  secret {
    name  = "jwt-signing-key"
    value = var.jwt_signing_key
  }

  template {
    min_replicas = 0
    max_replicas = 2

    container {
      name   = "ai-service"
      image  = var.bootstrap_image
      cpu    = 0.5
      memory = "1Gi"

      env {
        name        = "ANTHROPIC_API_KEY"
        secret_name = "anthropic-api-key"
      }
      env {
        name        = "STORAGE_CONNECTION_STRING"
        secret_name = "storage-connection-string"
      }
      env {
        name        = "DATABASE_URL"
        secret_name = "database-url"
      }
      env {
        name        = "JWT_SIGNING_KEY"
        secret_name = "jwt-signing-key"
      }
      # CORS (service/app/main.py) — el frontend llama a este servicio directo desde el
      # navegador, no a traves de "api". Sin esto el navegador bloquea /tenants y
      # /documentos con "No 'Access-Control-Allow-Origin' header".
      # ingress[0].fqdn y no latest_revision_fqdn, por el mismo motivo que Cors__WebOrigin
      # en la app "api" de arriba.
      env {
        name  = "WEB_ORIGIN"
        value = local.frontend_allowed_origins
      }
    }
  }

  # Externo, no interno: a diferencia del comentario original, este servicio no lo llama
  # solo "api" — el frontend (React) llama a /tenants y /documentos DIRECTO desde el
  # navegador del usuario (microservicio separado, Art V), así que necesita ingress
  # publico igual que "api" y "frontend".
  ingress {
    external_enabled = true
    target_port      = 8000
    traffic_weight {
      latest_revision = true
      percentage      = 100
    }
  }

  lifecycle {
    ignore_changes = [template[0].container[0].image]
  }
}

resource "azurerm_container_app" "frontend" {
  name                         = "ca-${var.project_name}-frontend"
  resource_group_name          = azurerm_resource_group.main.name
  container_app_environment_id = azurerm_container_app_environment.main.id
  revision_mode                = "Single"

  registry {
    server               = azurerm_container_registry.main.login_server
    username             = azurerm_container_registry.main.admin_username
    password_secret_name = "acr-password"
  }

  secret {
    name  = "acr-password"
    value = azurerm_container_registry.main.admin_password
  }

  template {
    min_replicas = 0
    max_replicas = 2

    container {
      name   = "frontend"
      image  = var.bootstrap_image
      cpu    = 0.25
      memory = "0.5Gi"

      # Sin env vars de runtime: Vite bakea VITE_API_BASE_URL/VITE_DOCUMENT_API_BASE_URL
      # en el bundle estatico en build time (deploy-apps.yml las pasa como --build-arg al
      # `docker build`), no las lee de un env var del contenedor nginx en runtime — un
      # `env {}` acá no tendría ningún efecto sobre el JS ya compilado.
    }
  }

  ingress {
    external_enabled = true
    target_port      = 80
    traffic_weight {
      latest_revision = true
      percentage      = 100
    }
  }

  lifecycle {
    ignore_changes = [template[0].container[0].image]
  }
}
