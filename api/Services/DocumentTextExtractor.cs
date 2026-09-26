using DocumentFormat.OpenXml.Packaging;
using UglyToad.PdfPig;
using UglyToad.PdfPig.DocumentLayoutAnalysis.TextExtractor;

namespace Comparador.Api.Services;

public sealed class DocumentExtractionException : Exception
{
    public DocumentExtractionException(string message) : base(message) { }
    public DocumentExtractionException(string message, Exception innerException) : base(message, innerException) { }
}

public sealed class DocumentTextExtractor
{
    private readonly TesseractOcr _ocr;

    public DocumentTextExtractor(TesseractOcr ocr)
    {
        _ocr = ocr;
    }

    public async Task<string> ExtractAsync(
        byte[] content,
        string extension,
        CancellationToken cancellationToken)
    {
        return extension.ToLowerInvariant() switch
        {
            ".pdf" => await ExtractPdfAsync(content, cancellationToken),
            ".docx" => ExtractDocx(content),
            _ => throw new DocumentExtractionException(
                $"Formato de archivo no soportado para extracción: '{extension}'.")
        };
    }

    private async Task<string> ExtractPdfAsync(byte[] content, CancellationToken cancellationToken)
    {
        using var stream = new MemoryStream(content);
        using var document = PdfDocument.Open(stream);
        var text = string.Join("\n", document.GetPages()
            .Select(page => ContentOrderTextExtractor.GetText(page, true)));

        if (text.Trim().Length < document.NumberOfPages * 20)
        {
            return await _ocr.ExtractPdfAsync(content, cancellationToken);
        }

        return text;
    }

    private static string ExtractDocx(byte[] content)
    {
        using var stream = new MemoryStream(content);
        using var document = WordprocessingDocument.Open(stream, false);
        var body = document.MainDocumentPart?.Document.Body;
        var paragraphs = body?.Descendants<DocumentFormat.OpenXml.Wordprocessing.Paragraph>()
            .Select(paragraph => paragraph.InnerText.Trim())
            .Where(text => text.Length > 0)
            .ToArray() ?? [];

        return string.Join("\n", paragraphs);
    }
}