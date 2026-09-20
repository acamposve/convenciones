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
| Paridad de extracción normalizada | 0/5 (0%) |
| Paridad de segmentación normalizada | 0/5 (0%) |

## Resultado por documento

| Documento | SHA-256 | .NET caracteres | Python caracteres | .NET segmentos | Python segmentos | Extracción | Segmentación | Discrepancias |
|---|---|---:|---:|---:|---:|---|---|---|
| `documentos/1/contrato.pdf` | `5cbe0de8...1791e3` | 2.979 | 3.150 | 1 | 2 | No | No | extracción, segmentación |
| `documentos/8/convencion_colectiva PDVSA_PETROLEO_2007-2009[1].pdf` | `c2c4d90e...0a972` | 430.080 | 437.773 | 139 | 95 | No | No | extracción, segmentación |
| `documentos/83/Contrato de C.A.N.T.V.[1].pdf` | `d9bf31b0...1a17` | 182.315 | 185.679 | 102 | 183 | No | No | extracción, segmentación |
| `documentos/99/TELARES PALO GRANDE.pdf` | `4a3c83cb...9052f` | 97.852 | 99.893 | 103 | 160 | No | No | extracción, segmentación |
| `documentos/103/Banco Mercantil 2010 - 2012.pdf` | `aeeb1c18...a4265` | 105.011 | 107.084 | 158 | 163 | No | No | extracción, segmentación |

Los hashes completos y las rutas exactas están en `artifacts/phase5/parity-report.json`
tras ejecutar el arnés.

## Discrepancias y ajustes requeridos

1. **Extracción:** PdfPig y PyMuPDF no producen texto normalizado idéntico en este lote.
   La reconstrucción de líneas desde las coordenadas de palabras mejora la estructura
   disponible para segmentación, pero no iguala todavía el texto histórico.
2. **Segmentación:** el ajuste de reconstrucción de líneas redujo el error absoluto de
   conteos del lote, de 572 a 392 segmentos, pero todavía hay diferencias importantes.
   No se habilitó una regex tolerante global porque capturaba referencias internas como
   `CLÁUSULA SEGURO` y sobresegmentaba el resultado.
3. **Banco Mercantil:** el conteo C# quedó en 158 frente a 163 del Python legado; es el
   caso más cercano y sirve como referencia para ajustar los restantes sin sobresegmentar.
4. **Resultado:** no se deben hacer cutover ni cerrar la paridad funcional como aprobada
   con este resultado. Los casos anteriores requieren ajuste y una nueva ejecución.

## Limitaciones

La referencia disponible es la implementación Python actual, no un dump histórico congelado
por documento. La comparación es reproducible contra ese runtime y el lote identificado por
hash, pero una validación definitiva contra resultados históricos persistidos requerirá
incorporar esos resultados de referencia cuando estén disponibles.
