using System.Threading.Channels;

namespace Comparador.Api.Services;

public sealed class DocumentProcessingQueue
{
    private readonly Channel<int> _channel = Channel.CreateUnbounded<int>(new UnboundedChannelOptions
    {
        SingleReader = true,
        SingleWriter = false,
        AllowSynchronousContinuations = false
    });

    public ValueTask EnqueueAsync(int documentId, CancellationToken cancellationToken = default)
    {
        return _channel.Writer.WriteAsync(documentId, cancellationToken);
    }

    public IAsyncEnumerable<int> ReadAllAsync(CancellationToken cancellationToken)
    {
        return _channel.Reader.ReadAllAsync(cancellationToken);
    }
}