using System.Threading.Channels;

namespace Comparador.Api.Services;

public sealed class DocumentProcessingQueue
{
    private readonly Channel<int> _channel;

    public DocumentProcessingQueue(IConfiguration configuration)
    {
        var capacity = configuration.GetValue<int?>("DocumentProcessing:QueueCapacity") ?? 100;
        if (capacity < 1)
        {
            throw new InvalidOperationException("DocumentProcessing:QueueCapacity debe ser mayor que cero.");
        }

        _channel = Channel.CreateBounded<int>(new BoundedChannelOptions(capacity)
        {
            SingleReader = true,
            SingleWriter = false,
            FullMode = BoundedChannelFullMode.Wait,
            AllowSynchronousContinuations = false
        });
    }

    public ValueTask EnqueueAsync(int documentId, CancellationToken cancellationToken = default)
    {
        return _channel.Writer.WriteAsync(documentId, cancellationToken);
    }

    public IAsyncEnumerable<int> ReadAllAsync(CancellationToken cancellationToken)
    {
        return _channel.Reader.ReadAllAsync(cancellationToken);
    }
}