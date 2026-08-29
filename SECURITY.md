# Política de Seguridad

## Reportar vulnerabilidades

**No abras issues públicos para reportar vulnerabilidades.** 

Contacta privadamente a través de:
- GitHub Security Advisory: [Usar reporte privado en Settings → Security]
- Email: [Configurar cuando tengas contacto principal]

Incluye:
- Descripción de la vulnerabilidad
- Cómo reproducirla (pasos mínimos)
- Impacto potencial
- Versiones afectadas

## Cumplimiento y regulaciones

El proyecto maneja convenciones colectivas de trabajo y marcos legales de Venezuela, Uruguay, Argentina y Chile. Todo lo relacionado con:

- Datos personales de empleados/sindicatos → GDPR / Ley de Protección de Datos local
- Documentos legales → Régimen de acceso y privacidad de cada país
- Información de empresas → Según Art. VI de constitution.md (privado por defecto)

Ver [`docs/constitution.md`](docs/constitution.md) Art. VI (Seguridad y privacidad) para los principios irrevocables.

## Dependencias

- Revisa regularmente `npm audit` (web), `pip install --upgrade` (service), y análisis de vulnerabilidades en la pipeline de CI/CD
- No ignores advertencias de seguridad sin justificar explícitamente

## CI/CD y Infraestructura

- Todos los secrets (DB passwords, API keys, etc.) en Azure Key Vault o GitHub Secrets, nunca en el repo
- Acceso a Azure: MFA obligatorio
- Logs: auditar acceso a contenido privado de tenants
