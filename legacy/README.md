# Legacy Sistema — Referencia histórica

Este directorio contiene el SaaS PHP original (~20 años, ~100 libras de código).

**Propósito:** Referencia funcional y arquetipo de datos — NO se porta código de acá.

**Según constitución.md Art. IX:**
- Legible solo para entender lo que el sistema hacía
- Datos históricos (dump SQL, PDFs reales) pueden usarse como dataset de prueba/validación
- Derechos de reutilización pendiente confirmación (Art. XI.2)
- Estructura de carpetas y módulos adaptadas al nuevo sistema, pero NO se copian métodos/funciones

**Contenido:**
- `index.html` — Portal web histórico
- `admin/` — Interfaz de administración (deprecated)
- `lib/` — Librerías PHP (NO reutilizar directamente)
- `db/` — Scripts SQL legados (datos de referencia)
- `Scripts/`, `SpryAssets/`, `css/` — Frontend antiguo (reemplazado por React)
- `local/config*.php` — Configuración histórica (ignorar)
- `xyiznwsk/` — Carpeta sin identificar (posible basura)

**Cómo usar este código:**
1. ✅ Ver cómo se estructuraban las tablas y relaciones
2. ✅ Extraer SQL de esquema legado para validar migración
3. ✅ Usar datos de prueba (empresas, convenciones clasificadas)
4. ❌ NO copiar funciones PHP
5. ❌ NO reutilizar lógica de negocio directamente
6. ❌ NO importar estilos o scripts JS

Si necesitas información sobre cómo algo funcionaba en el legado, revisa acá — pero reescribelo
en la arquitectura nueva (Art. V: .NET, Python, React).

---

**Arquivos legados marcados en git con `@legacy` tag si es necesario referenciarlos desde
la arquitectura nueva.**
