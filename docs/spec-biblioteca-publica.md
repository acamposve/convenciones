# Spec — Biblioteca pública (Fase 7)

> **Depende de:** `constitution.md` v2.1.0 (Art. VI.1/VI.7)
> **Estado:** borrador
> **Origen:** reunión con Luis Villegas (especialista de dominio), 2026-08-29 — mismo origen que Fase 6 (`docs/transcripcion_reunion_convenciones_ia.md`), "banco de datos del sector".

## 1. Alcance de esta fase

| Incluido | Excluido |
|---|---|
| Endpoint público (sin autenticación) que lista documentos `es_publico=true` de todos los tenants, buscable por nombre de empresa | Contenido clasificado (resumen ejecutivo, campo comparativo, texto de cláusulas) — decisión cerrada, ver §5 |
| Página pública `/biblioteca` en el frontend, fuera del login | Gate de revisión humana (Art IV.9) para aparecer en la biblioteca — decisión cerrada, ver §5 |
| | Búsqueda por sector/categoría/título (ya decidido en Fase 6: solo por empresa) |

## 2. Modelo de datos

Ninguno nuevo. Reusa `documentos.es_publico` (ya existe, wireado desde la ingesta por URL — Art VI.1) y `empresas.nombre`. No se toca `clausulas` en absoluto: esta fase es deliberadamente un directorio, no un comparador cross-tenant.

## 3. API

Nuevo endpoint, explícitamente **sin `require_role`** (Art VI.7 — única otra excepción al aislamiento por tenant además del rol de Plataforma, Art VI.6):

```
GET /biblioteca?empresa=<texto opcional, ILIKE>
```

- Query cruza **todos los tenants** (sin filtro `tenant_id` — a diferencia de cada otro endpoint del sistema).
- Filtra `documentos.es_publico = true` únicamente. Sin condición sobre `estado_revision` de sus cláusulas (decisión cerrada, §5) ni sobre `documentos.estado` (un documento público aparece aunque el pipeline de clasificación todavía no haya corrido o haya fallado — la biblioteca no depende del pipeline).
- Devuelve por documento: `empresa_nombre`, `url_origen`, `created_at`. **Nunca** `tenant_id`, `documento.id` interno, ni ningún dato de `clausulas` (Art VI.7 in fine).
- Sin paginación en esta fase (volumen bajo, mismo criterio pragmático que el resto del MVP).

## 4. Permisos

Sin autenticación — no requiere JWT, no requiere sesión. Es la única superficie del producto pensada para ser accedida por alguien que no tiene cuenta. Nunca expone qué tenant subió el documento, solo el nombre de la empresa (dato de negocio, no de la cuenta que lo gestiona).

## 5. Decisiones cerradas

- **Solo directorio con link al original** (no contenido clasificado): el visitante ve qué empresas tienen documentos públicos y un link a la URL de origen; lee el documento ahí mismo, no dentro de la plataforma. Descarta explícitamente un comparador cross-tenant en esta fase.
- **Sin gate de revisión**: alcanza con `es_publico=true` al momento de la ingesta (que ya valida que la URL responda sin autenticación, Art VI.1) — no espera a que el pipeline clasifique ni a que un Revisor apruebe nada, porque no se expone ese contenido de todos modos.

## 6. Preguntas abiertas

Ninguna.

## 7. Plan de implementación ✅ terminado

Alcance chico, un solo bloque:

- [x] Backend: `GET /biblioteca` en `service/app/main.py`, sin `require_role`, JOIN `documentos`+`empresas` cross-tenant, filtro `es_publico=true` + `ILIKE` opcional por nombre de empresa
- [x] Frontend: nueva página `web/src/biblioteca/BibliotecaPage.jsx` — buscador por empresa + lista de resultados con link al documento original (`target="_blank"`), ruta `/biblioteca` fuera de `ProtectedRoute` en `main.jsx`; link de descubribilidad desde `LoginPage.jsx` ("Ver biblioteca pública", ya que no requiere sesión)
- [x] Verificado de punta a punta contra el stack completo (docker compose + uvicorn local en :8010): se creó un segundo tenant ("Consultora Segunda") con una empresa propia, además de una empresa pública y una privada en el tenant demo. Sin login, `/biblioteca` listó las dos empresas públicas de **ambos tenants**; el buscador por nombre filtró correctamente ("Uno" → solo esa empresa); buscar "Privada" (nombre de la empresa NO pública) devolvió vacío, confirmando que el filtro `es_publico=true` es el único gate y que ningún dato de tenant se filtra en la respuesta. El link desde `LoginPage` funciona. Datos de prueba eliminados al terminar
- [x] `npm run build` limpio y `pytest` 25/25 verdes, sin regresiones

## 8. Checklist resumido

- [x] Backend + frontend + verificación end-to-end

**Fase 7 completa.**

*(Se actualiza a medida que avanzamos, mismo criterio que las specs de Fases 2-6.)*
