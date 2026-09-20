using System.Security.Cryptography;
using System.Text.Json;
using System.Text.Json.Serialization;
using Comparador.Api.Services;
using Microsoft.Extensions.Configuration;

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

foreach (var relativePath in manifest.Documents)
{
    var path = Path.Combine(root, relativePath.Replace('/', Path.DirectorySeparatorChar));
    var result = new DocumentResult
    {
        Path = relativePath,
        Sha256 = Convert.ToHexString(SHA256.HashData(await File.ReadAllBytesAsync(path))).ToLowerInvariant(),
        Status = "error"
    };

    try
    {
        var content = await File.ReadAllBytesAsync(path);
        var text = await extractor.ExtractAsync(content, Path.GetExtension(path), CancellationToken.None);
        var clauses = ClauseSegmenter.Segment(text).ToArray();
        var referenceDocument = referenceByPath[relativePath];
        result.Status = "processed";
        result.ExtractedCharacters = text.Length;
        result.ClauseCount = clauses.Length;
        result.ReferenceExtractedCharacters = referenceDocument.Text.Length;
        result.ReferenceClauseCount = referenceDocument.Clauses.Length;
        result.ExtractionParity = Normalize(text) == referenceDocument.NormalizedText;
        result.SegmentationParity = clauses.Select(Normalize).SequenceEqual(referenceDocument.NormalizedClauses);
        if (result.ExtractionParity != true)
        {
            result.Discrepancies.Add("extraccion_normalizada");
        }
        if (result.SegmentationParity != true)
        {
            result.Discrepancies.Add("segmentacion_normalizada");
        }
    }
    catch (Exception exception)
    {
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

static string Normalize(string value) => string.Join(' ', value.Split((char[]?)null, StringSplitOptions.RemoveEmptyEntries));
static double Rate(int numerator, int denominator) => denominator == 0 ? 0 : Math.Round((double)numerator / denominator, 4);

record Manifest(string Dataset, string[] Documents);
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
    public bool? ExtractionParity { get; set; }
    public bool? SegmentationParity { get; set; }
    public List<string> Discrepancies { get; } = [];
    public string? Error { get; set; }
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
