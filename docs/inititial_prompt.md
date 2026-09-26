Quiero realizar un análisis de modernización del sistema legacy de Convenciones.

Este repositorio contiene la implementación histórica del sistema, principalmente desarrollada en PHP.

Lee primero `docs/constitution.md` y úsala como conjunto de reglas obligatorias para este análisis.

IMPORTANTE:

No modifiques ningún archivo todavía.

No implementes código.

No propongas todavía una migración concreta.

Primero necesito comprender qué tenemos actualmente.

Analiza el repositorio y genera un documento de diagnóstico que incluya:

1. Current State Assessment
2. System Inventory
3. Business Capability Map
4. Application Architecture actual
5. Database Architecture
6. Dependency Map
7. Authentication and Authorization
8. Document and Contract workflows
9. External integrations
10. Scheduled/background processes
11. Security assessment
12. Data quality assessment
13. Technical debt assessment
14. Dead or potentially obsolete functionality
15. Critical business workflows
16. Areas that are difficult or dangerous to migrate
17. Unknown behavior that requires business confirmation

Para cada módulo importante determina:

* Qué hace
* Qué datos utiliza
* Qué otros módulos necesita
* Qué reglas de negocio contiene
* Qué riesgos tiene
* Si parece ser necesario para el negocio
* Si debería preservarse, modernizarse, rediseñarse, reemplazarse o posiblemente retirarse

Después del análisis, compara estas alternativas:

A. Modernizar progresivamente el PHP existente
B. Migrar progresivamente a Laravel
C. Migrar progresivamente al nuevo stack .NET de Convenciones
D. Estrategia híbrida
E. Reemplazo completo

No asumas que ninguna de estas opciones es automáticamente correcta.

Evalúalas utilizando evidencia del sistema actual.

Para cada alternativa analiza:

* Complejidad
* Riesgo
* Datos
* Seguridad
* Tiempo/effort relativo
* Mantenibilidad
* Testabilidad
* Escalabilidad
* Multi-tenancy
* Integración con document intelligence/AI
* Operación
* Capacidad de retirar el legacy
* Riesgo para el negocio

Finalmente genera una propuesta de estrategia de modernización y un roadmap de alto nivel.

NO IMPLEMENTES NADA TODAVÍA.

El objetivo de esta primera etapa es producir suficiente conocimiento del sistema actual para que posteriormente podamos decidir qué partes:

* mantener,
* modernizar,
* migrar a Laravel,
* migrar a .NET,
* rediseñar,
* reemplazar,
* o retirar.

Si no puedes determinar algo a partir del código, indícalo explícitamente como `UNKNOWN` y explica qué información sería necesaria para resolverlo.
