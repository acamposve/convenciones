# Database — Schema, Seeds, Fixtures

## Estructura

| Archivo | Propósito |
|---|---|
| `schema.sql` | Definición de tablas, índices, constraints para PostgreSQL |
| `seed_admin_user.py` | Seed inicial: tenant "AdminTenant" + usuario demo (se ejecuta en docker-compose) |
| `fixtures/` | Datos de prueba (convenciones legadas, empresas de demo, etc.) — **privados** |

## Ejecutar localmente

### Con Docker Compose (recomendado)
```bash
cd service
docker compose up --build
```

Esto ejecuta:
1. `postgres:latest` (volumne `postgres_data`)
2. Schema SQL (`db/schema.sql`) via migration init
3. Seed Python (`db/seed_admin_user.py`) después del schema

Credenciales y usuario demo en `docker compose logs seed`.

### Manualmente (desarrollo avanzado)

```bash
# 1. PostgreSQL ejecutándose en localhost:5432
# 2. Aplicar schema
psql -h localhost -U postgres -d comparador -f db/schema.sql

# 3. Seed (Python)
cd service
python db/seed_admin_user.py
```

## Migraciones futuras (Fase 2+)

Cuando haya evolución del schema, crear migraciones versionadas:
- `migrations/001_initial_schema.sql`
- `migrations/002_add_campo_x.sql`
- etc.

Herramienta: Flyway, Liquibase, o o managed en Django/EF si pasan a ORM.

## Backup y datos sensibles

- **Datos de prueba en `fixtures/`:** NO se comitean a git (o solo en `.gitignore`)
- **Credenciales en `docker-compose.yml`:** Reemplazar en producción con secrets de Azure Key Vault
- **Documentos legados:** Privados (Art. VI de constitution.md), bajo `db/fixtures/` con acceso restringido

---

**Toda la base de datos es multi-tenant por columna `tenant_id` — verificar en queries que siempre filtren por tenant actual.**
