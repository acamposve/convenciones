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
| Paridad de extracción normalizada | 3/5 (60%) |
| Paridad de segmentación normalizada | 3/5 (60%) |

## Resultado por documento

| Documento | SHA-256 | .NET caracteres | Python caracteres | .NET segmentos | Python segmentos | Extracción | Segmentación | Discrepancias |
|---|---|---:|---:|---:|---:|---|---|---|
| `documentos/1/contrato.pdf` | `5cbe0de8...1791e3` | 2.979 | 3.150 | 1 | 2 | No | No | extracción, segmentación |
| `documentos/8/convencion_colectiva PDVSA_PETROLEO_2007-2009[1].pdf` | `c2c4d90e...0a972` | 437.773 | 437.773 | 95 | 95 | No | Sí | extracción |
| `documentos/83/Contrato de C.A.N.T.V.[1].pdf` | `d9bf31b0...1a17` | 185.679 | 185.679 | 183 | 183 | Sí | Sí | ninguna |
| `documentos/99/TELARES PALO GRANDE.pdf` | `4a3c83cb...9052f` | 99.893 | 99.893 | 160 | 160 | Sí | Sí | ninguna |
| `documentos/103/Banco Mercantil 2010 - 2012.pdf` | `aeeb1c18...a4265` | 107.084 | 107.084 | 163 | 163 | Sí | Sí | ninguna |

Los hashes completos y las rutas exactas están en `artifacts/phase5/parity-report.json`
tras ejecutar el arnés.

## Discrepancias y ajustes requeridos

1. **Extractor:** `ContentOrderTextExtractor.GetText(page, true)` de PdfPig mejora
   sustancialmente la reconstrucción: tres de cinco documentos igualan extracción y
   segmentación, frente a cero en la medición anterior.
2. **PDVSA:** la segmentación coincide (95/95), pero la extracción normalizada aún difiere.
3. **Contrato 1:** sigue sin una estructura equivalente y requiere un ajuste específico.
4. **Licencia:** `MuPDFCore` se evaluó como alternativa, pero su paquete NuGet declara
   `AGPL-3.0-only`; no se incorpora al producto sin una decisión legal/comercial explícita.
5. **Resultado:** no se debe hacer cutover ni cerrar la paridad funcional como aprobada
   con este resultado. Los casos anteriores requieren ajuste y una nueva ejecución.

## Limitaciones

La referencia disponible es la implementación Python actual, no un dump histórico congelado
por documento. La comparación es reproducible contra ese runtime y el lote identificado por
hash, pero una validación definitiva contra resultados históricos persistidos requerirá
incorporar esos resultados de referencia cuando estén disponibles.
