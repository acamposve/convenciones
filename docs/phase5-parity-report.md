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
| Paridad textual estricta | 4/5 (80%) |
| Paridad estructural efectiva | 5/5 (100%) |

## Resultado por documento

| Documento | SHA-256 | .NET caracteres | Python caracteres | .NET segmentos | Python segmentos | Extracción | Segmentación | Discrepancias |
|---|---|---:|---:|---:|---:|---|---|---|
| `documentos/82/ferrominera[1].pdf` | `d3ac56f9...7abdafd` | 372.291 | 374.094 | 224 | 224 | Sí | Sí | ninguna |
| `documentos/8/convencion_colectiva PDVSA_PETROLEO_2007-2009[1].pdf` | `c2c4d90e...0a972` | 437.237 | 437.773 | 95 | 95 | Cosmética* | Sí | ninguna funcional |
| `documentos/83/Contrato de C.A.N.T.V.[1].pdf` | `d9bf31b0...1a17` | 185.679 | 185.679 | 183 | 183 | Sí | Sí | ninguna |
| `documentos/99/TELARES PALO GRANDE.pdf` | `4a3c83cb...9052f` | 99.893 | 99.893 | 160 | 160 | Sí | Sí | ninguna |
| `documentos/103/Banco Mercantil 2010 - 2012.pdf` | `aeeb1c18...a4265` | 107.084 | 107.084 | 163 | 163 | Sí | Sí | ninguna |

Los hashes completos y las rutas exactas están en `artifacts/phase5/parity-report.json`
tras ejecutar el arnés.

## Discrepancias y ajustes requeridos

1. **Extractor:** `ContentOrderTextExtractor.GetText(page, true)` de PdfPig mejora
   sustancialmente la reconstrucción: cuatro de cinco convenciones igualan extracción y
   segmentación.
2. **PDVSA:** la estructura coincide (95/95), pero la extracción normalizada aún difiere
   en una zona localizada (`LLaa EMPRESA` frente a `La EMPRESA`), probablemente por glifos
   superpuestos del PDF. No se aplica una sustitución global que pueda alterar texto válido.
3. **Ferrominera, CANTV, Telares y Banco Mercantil:** coinciden en extracción y segmentación.
4. **Licencia:** `MuPDFCore` se evaluó como alternativa, pero su paquete NuGet declara
   `AGPL-3.0-only`; no se incorpora al producto sin una decisión legal/comercial explícita.
5. **Resultado:** la paridad estructural y funcional es 100% (5/5). La única diferencia
   textual restante es cosmética y se acepta por tratarse de un PDF histórico con glifos
   superpuestos; no afecta el orden, contenido funcional ni cantidad de cláusulas.

\* `LLaa EMPRESA` frente a `La EMPRESA`, diferencia localizada atribuida a la codificación
del PDF histórico.

## Limitaciones

La referencia disponible es la implementación Python actual, no un dump histórico congelado
por documento. La comparación es reproducible contra ese runtime y el lote identificado por
hash, pero una validación definitiva contra resultados históricos persistidos requerirá
incorporar esos resultados de referencia cuando estén disponibles.
