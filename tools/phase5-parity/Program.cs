using System.Security.Cryptography;
using System.Text;
using System.Text.Json;
using System.Text.Json.Serialization;
using Comparador.Api.Services;
using Microsoft.Extensions.Configuration;
using UglyToad.PdfPig;
using UglyToad.PdfPig.DocumentLayoutAnalysis.TextExtractor;

var root = Path.GetFullPath(Path.Combine(AppContext.BaseDirectory, "..", "..", "..", "..", ".."));
var manifestPath = Path.Combine(root, "tools", "phase5-parity", "manifest.json");
var referencePath = Path.Combine(root, "artifacts", "phase5", "python-reference.json");
var outputPath = Path.Combine(root, "artifacts", "phase5", "parity-report.json");

var jsonOptions = new JsonSerializerOptions { PropertyNameCaseInsensitive = true };
var manifest = JsonSerializer.Deserialize<Manifest>(await File.ReadAllTextAsync(manifestPath), jsonOptions)
    ?? throw new InvalidOperationException("No se pudo leer el manifiesto de paridad.");
var reference = JsonSerializer.Deserialize<ReferenceReport>(await File.ReadAllTextAsync(referencePath), jsonOptions)
    ?? throw new InvalidOperationException("No se pudo leer la referencia Python.");
var referenceByPath = reference.Documents.ToDictionary(document => document.Path);
var extractor = new DocumentTextExtractor(new TesseractOcr(new ConfigurationBuilder().Build()));
var results = new List<DocumentResult>();

foreach (var manifestDocument in manifest.Documents)
{
    var relativePath = manifestDocument.Path;
    var path = Path.Combine(root, relativePath.Replace('/', Path.DirectorySeparatorChar));
    var content = await File.ReadAllBytesAsync(path);
    var actualSha256 = Convert.ToHexString(SHA256.HashData(content)).ToLowerInvariant();
    var result = new DocumentResult
    {
        Path = relativePath,
        Sha256 = actualSha256,
        Status = "error"
    };

    if (!string.Equals(actualSha256, manifestDocument.Sha256, StringComparison.OrdinalIgnoreCase))
    {
        result.Error = $"El hash SHA-256 no coincide con el manifiesto (esperado {manifestDocument.Sha256}, " +
            $"obtenido {actualSha256}); el lote no es reproducible.";
        results.Add(result);
        continue;
    }

    try
    {
        var text = await extractor.ExtractAsync(content, Path.GetExtension(path), CancellationToken.None);
        var clauses = ClauseSegmenter.Segment(text).ToArray();
        var referenceDocument = referenceByPath[relativePath];
        result.ExtractedCharacters = text.Length;
        result.ClauseCount = clauses.Length;
        result.Candidates = GetPdfCandidates(path);
        var detectedHeaders = ClauseSegmenterDiagnostics.FindHeaders(text);
        result.DetectedHeaders = detectedHeaders.Count;
        result.HeaderSamples = detectedHeaders.Take(10).ToArray();
        result.ReferenceExtractedCharacters = referenceDocument.Text.Length;
        result.ReferenceClauseCount = referenceDocument.Clauses.Length;

        if (referenceDocument.Status == "processed")
        {
            result.ExtractionParity = Normalize(text) == referenceDocument.NormalizedText;
            result.SegmentationParity = clauses.Select(Normalize).SequenceEqual(referenceDocument.NormalizedClauses);
            result.FirstExtractionDifference = DescribeDifference(Normalize(text), referenceDocument.NormalizedText);
            result.FirstSegmentationDifference = DescribeDifference(
                string.Join("\n", clauses.Select(Normalize)),
                string.Join("\n", referenceDocument.NormalizedClauses));
            if (result.ExtractionParity != true)
            {
                result.Discrepancies.Add("extraccion_normalizada");
            }
            if (result.SegmentationParity != true)
            {
                result.Discrepancies.Add("segmentacion_normalizada");
            }
        }
        else
        {
            result.ReferenceError = referenceDocument.Error;
            result.Discrepancies.Add("referencia_python_no_comparable");
        }

        result.Status = "processed";
    }
    catch (Exception exception)
    {
        result.Status = "error";
        result.Error = $"{exception.GetType().Name}: {exception.Message}";
    }

    results.Add(result);
}

var processed = results.Count(result => result.Status == "processed");
var comparable = results.Count(result => result.Status == "processed" && referenceByPath[result.Path].Status == "processed");
var extractionMatches = results.Count(result => result.ExtractionParity == true);
var segmentationMatches = results.Count(result => result.SegmentationParity == true);
var report = new ParityReport
{
    Dataset = manifest.Dataset,
    Runtime = ".NET 10",
    Documents = results,
    Summary = new Summary
    {
        Total = results.Count,
        Processed = processed,
        Errors = results.Count - processed,
        Comparable = comparable,
        ExtractionMatches = extractionMatches,
        SegmentationMatches = segmentationMatches,
        ProcessingRate = Rate(processed, results.Count),
        ExtractionParityRate = Rate(extractionMatches, comparable),
        SegmentationParityRate = Rate(segmentationMatches, comparable)
    }
};

Directory.CreateDirectory(Path.GetDirectoryName(outputPath)!);
await File.WriteAllTextAsync(outputPath, JsonSerializer.Serialize(report, new JsonSerializerOptions { WriteIndented = true }));
Console.WriteLine(JsonSerializer.Serialize(report.Summary));

static string Normalize(string value) => string.Join(
    ' ',
    value.Normalize(NormalizationForm.FormKC)
        .Replace("\u00ad", string.Empty)
        .Split((char[]?)null, StringSplitOptions.RemoveEmptyEntries));
static double Rate(int numerator, int denominator) => denominator == 0 ? 0 : Math.Round((double)numerator / denominator, 4);

static string? DescribeDifference(string actual, string expected)
{
    var index = 0;
    while (index < actual.Length && index < expected.Length && actual[index] == expected[index])
    {
        index++;
    }

    if (index == actual.Length && index == expected.Length)
    {
        return null;
    }

    var start = Math.Max(0, index - 40);
    var actualContext = actual.Substring(start, Math.Min(80, actual.Length - start));
    var expectedContext = expected.Substring(start, Math.Min(80, expected.Length - start));
    return $"index={index}; actual='{actualContext}'; expected='{expectedContext}'";
}

static Dictionary<string, CandidateResult> GetPdfCandidates(string path)
{
    if (!string.Equals(Path.GetExtension(path), ".pdf", StringComparison.OrdinalIgnoreCase))
    {
        return [];
    }

    using var document = PdfDocument.Open(path);
    var candidates = new Dictionary<string, CandidateResult>();
    var pageText = string.Join("\n", document.GetPages().Select(page => page.Text));
    var readingOrder = string.Join("\n", document.GetPages()
        .Select(page => ContentOrderTextExtractor.GetText(page, true)));
    var physicalOrder = string.Join("\n", document.GetPages()
        .Select(page => ContentOrderTextExtractor.GetText(page, false)));

    foreach (var candidate in new[]
    {
        (Name: "page_text", Text: pageText),
        (Name: "reading_order", Text: readingOrder),
        (Name: "physical_order", Text: physicalOrder)
    })
    {
        candidates[candidate.Name] = new CandidateResult
        {
            Characters = candidate.Text.Length,
            NormalizedCharacters = Normalize(candidate.Text).Length,
            ClauseCount = ClauseSegmenter.Segment(candidate.Text).Count
        };
    }

    return candidates;
}

static class ClauseSegmenterDiagnostics
{
    private static readonly System.Text.RegularExpressions.Regex HeaderRegex = new(
        @"^\s*(CL[ÁA]USULA|ART[ÍI]CULO)\s+\S.*$",
        System.Text.RegularExpressions.RegexOptions.Compiled | System.Text.RegularExpressions.RegexOptions.IgnoreCase | System.Text.RegularExpressions.RegexOptions.Multiline);

    public static IReadOnlyList<string> FindHeaders(string text) =>
        HeaderRegex.Matches(text).Select(match => match.Value.Trim()).ToArray();
}

record Manifest(string Dataset, ManifestDocument[] Documents);
record ManifestDocument(string Path, string Sha256);
record ReferenceReport(string Dataset, string Runtime, DocumentReference[] Documents);
record DocumentReference(
    string Path,
    string Sha256,
    string Status,
    string Text,
    string[] Clauses,
    string Error,
    [property: JsonPropertyName("normalized_text")] string NormalizedText,
    [property: JsonPropertyName("normalized_clauses")] string[] NormalizedClauses);
record ParityReport
{
    public string Dataset { get; init; } = "";
    public string Runtime { get; init; } = "";
    public List<DocumentResult> Documents { get; init; } = [];
    public Summary Summary { get; init; } = new();
}
class DocumentResult
{
    public string Path { get; init; } = "";
    public string Sha256 { get; init; } = "";
    public string Status { get; set; } = "";
    public int ExtractedCharacters { get; set; }
    public int ClauseCount { get; set; }
    public int ReferenceExtractedCharacters { get; set; }
    public int ReferenceClauseCount { get; set; }
    public int DetectedHeaders { get; set; }
    public string[] HeaderSamples { get; set; } = [];
    public Dictionary<string, CandidateResult> Candidates { get; set; } = [];
    public string? FirstExtractionDifference { get; set; }
    public string? FirstSegmentationDifference { get; set; }
    public bool? ExtractionParity { get; set; }
    public bool? SegmentationParity { get; set; }
    public List<string> Discrepancies { get; } = [];
    public string? Error { get; set; }
    public string? ReferenceError { get; set; }
}

class Summary
{
    public int Total { get; init; }
    public int Processed { get; init; }
    public int Errors { get; init; }
    public int Comparable { get; init; }
    public int ExtractionMatches { get; init; }
    public int SegmentationMatches { get; init; }
    public double ProcessingRate { get; init; }
    public double ExtractionParityRate { get; init; }
    public double SegmentationParityRate { get; init; }
}
class CandidateResult
{
    public int Characters { get; init; }
    public int NormalizedCharacters { get; init; }
    public int ClauseCount { get; init; }
}
