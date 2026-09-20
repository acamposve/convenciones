using System.Diagnostics;
using System.ComponentModel;
using System.Globalization;

namespace Comparador.Api.Services;

public sealed class TesseractOcr
{
    private readonly string _tesseractPath;
    private readonly string _pdfToPpmPath;

    public TesseractOcr(IConfiguration configuration)
    {
        _tesseractPath = configuration["Ocr:TesseractPath"] ?? "tesseract";
        _pdfToPpmPath = configuration["Ocr:PdfToPpmPath"] ?? "pdftoppm";
    }

    public async Task<string> ExtractPdfAsync(byte[] content, CancellationToken cancellationToken)
    {
        var workDirectory = Path.Combine(Path.GetTempPath(), "comparador-ocr", Guid.NewGuid().ToString("N"));
        Directory.CreateDirectory(workDirectory);
        var pdfPath = Path.Combine(workDirectory, "document.pdf");
        var imagePrefix = Path.Combine(workDirectory, "page");

        try
        {
            await File.WriteAllBytesAsync(pdfPath, content, cancellationToken);
            await RunProcessAsync(
                _pdfToPpmPath,
                ["-png", "-r", "300", pdfPath, imagePrefix],
                workDirectory,
                cancellationToken);

            var pages = Directory.GetFiles(workDirectory, "page-*.png")
                .OrderBy(GetPageNumber)
                .ToArray();
            if (pages.Length == 0)
            {
                throw new DocumentExtractionException("El OCR no pudo rasterizar ninguna página del PDF.");
            }

            var text = new List<string>(pages.Length);
            foreach (var page in pages)
            {
                text.Add(await RunProcessAsync(
                    _tesseractPath,
                    [page, "stdout", "-l", "spa", "--psm", "3"],
                    workDirectory,
                    cancellationToken));
            }

            return string.Join("\n", text);
        }
        catch (Win32Exception exception)
        {
            throw new DocumentExtractionException(
                "El OCR requiere los binarios Tesseract y pdftoppm instalados en el entorno.", exception);
        }
        finally
        {
            try
            {
                Directory.Delete(workDirectory, recursive: true);
            }
            catch (IOException)
            {
                // El texto ya fue obtenido; un temporal huérfano no debe ocultar el resultado.
            }
        }
    }

    private static async Task<string> RunProcessAsync(
        string executable,
        IEnumerable<string> arguments,
        string workingDirectory,
        CancellationToken cancellationToken)
    {
        using var process = new Process
        {
            StartInfo = new ProcessStartInfo
            {
                FileName = executable,
                WorkingDirectory = workingDirectory,
                RedirectStandardOutput = true,
                RedirectStandardError = true,
                UseShellExecute = false,
                CreateNoWindow = true
            }
        };
        foreach (var argument in arguments)
        {
            process.StartInfo.ArgumentList.Add(argument);
        }

        process.Start();
        var outputTask = process.StandardOutput.ReadToEndAsync(cancellationToken);
        var errorTask = process.StandardError.ReadToEndAsync(cancellationToken);
        await process.WaitForExitAsync(cancellationToken);
        var output = await outputTask;
        var error = await errorTask;
        if (process.ExitCode != 0)
        {
            throw new DocumentExtractionException(
                $"El proceso OCR terminó con código {process.ExitCode.ToString(CultureInfo.InvariantCulture)}: {error.Trim()}");
        }

        return output;
    }

    private static int GetPageNumber(string path)
    {
        var fileName = Path.GetFileNameWithoutExtension(path);
        var separator = fileName.LastIndexOf('-');
        return separator >= 0 && int.TryParse(fileName[(separator + 1)..], out var page)
            ? page
            : int.MaxValue;
    }
}