# Fase 5.1: validación de paridad

Este arnés compara el pipeline determinista de .NET 10 con la implementación Python
legada para un lote fijo de cinco PDFs contractuales del repositorio `legacy/`.

La comparación cubre únicamente:

- extracción de texto normalizada por espacios;
- segmentación normalizada por espacios;
- estado de procesamiento y errores por documento.

No compara clasificación automática, IA ni títulos de taxonomía.

## Ejecución

Desde la raíz del repositorio:

```powershell
powershell -ExecutionPolicy Bypass -File tools/phase5-parity/run.ps1
```

Requisitos:

- Python con las dependencias de `service/`;
- SDK .NET 10;
- Tesseract y `pdftoppm` en `PATH` si el lote incluye PDFs escaneados.

El manifiesto del lote está en `manifest.json`. Los resultados detallados se escriben en
`artifacts/phase5/`, que está excluido de Git porque contiene texto derivado de documentos
históricos.
