terraform {
  required_version = ">= 1.7.0"

  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~> 3.116"
    }
  }

  # 🔴 MANUAL: el resource group + storage account de este backend deben
  # existir ANTES de correr `terraform init` — ver plan-publish-azure.md
  # sección 0, paso 3. Sin esto, el state vive solo en el runner que
  # corrió el apply, lo cual es inutilizable desde GitHub Actions.
  backend "azurerm" {
    resource_group_name  = "rg-tfstate-comparador"
    storage_account_name = "sttfstatecomparador" # debe ser único global — ajustar si está tomado
    container_name       = "tfstate"
    key                  = "comparador-demo.tfstate"
  }
}

provider "azurerm" {
  features {}
  subscription_id = var.azure_subscription_id
}
