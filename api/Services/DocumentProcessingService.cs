using Comparador.Api.Data;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Services;

public sealed class DocumentProcessingService
{
    private readonly ComparadorDbContext _db;
    private readonly DocumentTextExtractor _extractor;
    private readonly ILogger<DocumentProcessingService> _logger;

    public DocumentProcessingService(
        ComparadorDbContext db,
        DocumentTextExtractor extractor,
        ILogger<DocumentProcessingService> logger)
    {
        _db = db;
        _extractor = extractor;
        _logger = logger;
    }

    public async Task ProcessAsync(int documentId, CancellationToken cancellationToken)
    {
        var document = await _db.Documentos.FirstOrDefaultAsync(
            item => item.Id == documentId,
            cancellationToken);
        if (document is null)
        {
            _logger.LogWarning("Documento {DocumentId} no existe al iniciar procesamiento", documentId);
            return;
        }

        try
        {
            if (string.IsNullOrWhiteSpace(document.RutaArchivo))
            {
                throw new DocumentExtractionException(
                    "El documento no tiene un archivo persistido para procesar.");
            }

            var content = await File.ReadAllBytesAsync(document.RutaArchivo, cancellationToken);
            var extension = Path.GetExtension(document.RutaArchivo);
            var text = await _extractor.ExtractAsync(content, extension, cancellationToken);
            if (string.IsNullOrWhiteSpace(text))
            {
                throw new DocumentExtractionException("No se pudo extraer texto del documento.");
            }

            document.Estado = "extraido";
            document.EstadoDetalle = null;
            await _db.SaveChangesAsync(cancellationToken);

            var clauses = ClauseSegmenter.Segment(text);
            if (clauses.Count == 0)
            {
                throw new DocumentExtractionException("No se pudo segmentar ninguna cláusula del texto extraído.");
            }

            _db.Clausulas.RemoveRange(_db.Clausulas.Where(item => item.DocumentoId == documentId));
            for (var index = 0; index < clauses.Count; index++)
            {
                _db.Clausulas.Add(new Models.Clausula
                {
                    DocumentoId = document.Id,
                    TenantId = document.TenantId,
                    Texto = clauses[index],
                    Orden = index + 1,
                    EstadoRevision = "pendiente",
                    EstadoRevisionResumen = "pendiente",
                    CreatedAt = DateTimeOffset.UtcNow
                });
            }

            document.Estado = "segmentado";
            await _db.SaveChangesAsync(cancellationToken);
            _logger.LogInformation(
                "Documento {DocumentId}: extraído y segmentado en {ClauseCount} cláusulas",
                documentId,
                clauses.Count);
        }
        catch (OperationCanceledException) when (cancellationToken.IsCancellationRequested)
        {
            throw;
        }
        catch (Exception exception)
        {
            document.Estado = "error";
            document.EstadoDetalle = "No se pudo procesar el documento. Revisa los logs del servicio o contacta a soporte.";
            _logger.LogError(exception, "Error procesando documento {DocumentId}", documentId);
            try
            {
                await _db.SaveChangesAsync(CancellationToken.None);
            }
            catch (Exception persistenceException)
            {
                _logger.LogError(
                    persistenceException,
                    "No se pudo persistir el estado de error del documento {DocumentId}",
                    documentId);
            }
        }
    }
}