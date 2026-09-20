using Microsoft.Extensions.Hosting;

namespace Comparador.Api.Services;

public sealed class DocumentProcessingWorker : BackgroundService
{
    private readonly DocumentProcessingQueue _queue;
    private readonly IServiceScopeFactory _scopeFactory;
    private readonly ILogger<DocumentProcessingWorker> _logger;

    public DocumentProcessingWorker(
        DocumentProcessingQueue queue,
        IServiceScopeFactory scopeFactory,
        ILogger<DocumentProcessingWorker> logger)
    {
        _queue = queue;
        _scopeFactory = scopeFactory;
        _logger = logger;
    }

    protected override async Task ExecuteAsync(CancellationToken stoppingToken)
    {
        await foreach (var documentId in _queue.ReadAllAsync(stoppingToken))
        {
            try
            {
                using var scope = _scopeFactory.CreateScope();
                var processor = scope.ServiceProvider.GetRequiredService<DocumentProcessingService>();
                await processor.ProcessAsync(documentId, stoppingToken);
            }
            catch (OperationCanceledException) when (stoppingToken.IsCancellationRequested)
            {
                throw;
            }
            catch (Exception exception)
            {
                _logger.LogError(exception, "Excepción no controlada en el worker para el documento {DocumentId}", documentId);
            }
        }
    }
}