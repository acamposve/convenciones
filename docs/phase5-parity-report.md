# Fase 5.1: informe de paridad

**Fecha de ejecución:** 2026-09-20  
**Dataset:** `legacy-contracts-v1`  
**Lote:** 5 PDFs contractuales venezolanos seleccionados del directorio `legacy/`  
**Referencia:** pipeline Python legado (`service/app/extraction.py` + `service/app/segmentation.py`)  
**Comparado:** pipeline determinista C#/.NET 10 (`PdfPig` + `ClauseSegmenter`)  
**Clasificación automática/IA:** excluida explícitamente.

## Procedimiento reproducible

```powershell
powershell -ExecutionPolicy Bypass -File tools/phase5-parity/run.ps1
```

El manifiesto y el código del arnés están en [`tools/phase5-parity`](../tools/phase5-parity/).
Los resultados completos, incluidos hashes y texto extraído, se generan localmente en
`artifacts/phase5/` y están excluidos de Git por tratarse de documentos históricos.

## Resultado global

| Métrica | Resultado |
|---|---:|
| Documentos en el lote | 5 |
| Procesados por .NET 10 | 5 |
| Errores de procesamiento | 0 |
| Tasa de procesamiento | 100% |
| Documentos comparables | 5 |
| Paridad de extracción normalizada | 1/5 (20%) |
| Paridad de segmentación normalizada | 0/5 (0%) |

## Resultado por documento

| Documento | SHA-256 | .NET caracteres | Python caracteres | .NET segmentos | Python segmentos | Extracción | Segmentación | Discrepancias |
|---|---|---:|---:|---:|---:|---|---|---|
| `documentos/1/contrato.pdf` | `5cbe0de8...1791e3` | 2.979 | 3.150 | 1 | 2 | No | No | extracción, segmentación |
| `documentos/8/convencion_colectiva PDVSA_PETROLEO_2007-2009[1].pdf` | `c2c4d90e...0a972` | 430.080 | 437.773 | 4 | 95 | No | No | extracción, segmentación |
| `documentos/83/Contrato de C.A.N.T.V.[1].pdf` | `d9bf31b0...1a17` | 182.315 | 185.679 | 15 | 183 | No | No | extracción, segmentación |
| `documentos/99/TELARES PALO GRANDE.pdf` | `4a3c83cb...9052f` | 97.852 | 99.893 | 10 | 160 | No | No | extracción, segmentación |
| `documentos/103/Banco Mercantil 2010 - 2012.pdf` | `aeeb1c18...a4265` | 105.011 | 107.084 | 1 | 163 | Sí | No | segmentación |

Los hashes completos y las rutas exactas están en `artifacts/phase5/parity-report.json`
tras ejecutar el arnés.

## Discrepancias y ajustes requeridos

1. **Extracción:** PdfPig y PyMuPDF no producen texto idéntico en cuatro de cinco PDFs.
   Las diferencias incluyen longitud y orden/representación del texto extraído.
2. **Segmentación:** el segmentador C# detecta muchos menos límites que el Python legado
   en los cuatro contratos estructurados. Esto indica que la salida de PdfPig no conserva
   siempre los encabezados en la forma que espera `GeneratedRegex`, o que la heurística
   actual no cubre los formatos del lote.
3. **Banco Mercantil:** la extracción normalizada coincide, pero el segmentador C# produce
   un solo bloque frente a 163 del Python legado; es el caso más claro para ajustar la
   segmentación independientemente de la extracción.
4. **Resultado:** no se deben hacer cutover ni cerrar la paridad funcional como aprobada
   con este resultado. Los casos anteriores requieren ajuste y una nueva ejecución.

## Limitaciones

La referencia disponible es la implementación Python actual, no un dump histórico congelado
por documento. La comparación es reproducible contra ese runtime y el lote identificado por
hash, pero una validación definitiva contra resultados históricos persistidos requerirá
incorporar esos resultados de referencia cuando estén disponibles.
