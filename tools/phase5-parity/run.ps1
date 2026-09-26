$ErrorActionPreference = 'Stop'
$root = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
Set-Location $root

python tools/phase5-parity/reference_pipeline.py
if ($LASTEXITCODE -ne 0) {
    throw "reference_pipeline.py terminó con código $LASTEXITCODE"
}

# Proyecto standalone, fuera de Comparador.sln: hay que restaurarlo de forma explícita,
# el restore de la solución de CI no genera su project.assets.json.
dotnet restore tools/phase5-parity/Phase5Parity.csproj
if ($LASTEXITCODE -ne 0) {
    throw "dotnet restore terminó con código $LASTEXITCODE"
}

dotnet run --project tools/phase5-parity/Phase5Parity.csproj --no-restore
if ($LASTEXITCODE -ne 0) {
    throw "dotnet run terminó con código $LASTEXITCODE"
}

$reportPath = Join-Path $root 'artifacts\phase5\parity-report.json'
$report = Get-Content $reportPath -Raw | ConvertFrom-Json
@"
# Fase 5.1 - Informe de paridad

- Dataset: ``$($report.dataset)``
- Runtime comparado: Python legacy vs .NET 10
- Lote: ``$($report.summary.total)`` documentos
- Procesados: ``$($report.summary.processed)``
- Errores: ``$($report.summary.errors)``
- Comparables: ``$($report.summary.comparable)``
- Tasa de procesamiento: ``$($report.summary.processingRate)``
- Paridad de extracción normalizada: ``$($report.summary.extractionParityRate)``
- Paridad de segmentación normalizada: ``$($report.summary.segmentationParityRate)``

La comparación excluye clasificación automática e IA. Los hashes y discrepancias por documento
quedan en ``artifacts/phase5/parity-report.json``, un artefacto local excluido de Git porque el
lote contiene documentos históricos del repositorio legacy.
"@ | Set-Content artifacts/phase5/parity-report.md -Encoding utf8
Write-Output "Reporte: $reportPath"
