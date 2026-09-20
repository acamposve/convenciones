using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using System.Net;
using System.Net.Sockets;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

[ApiController]
[Route("api")]
[Route("")]
public class DocumentosController : ControllerBase
{
    private const long DefaultMaxDownloadBytes = 50 * 1024 * 1024;
    private readonly ComparadorDbContext _db;
    private readonly IConfiguration _configuration;
    private readonly DocumentProcessingQueue _processingQueue;

    public DocumentosController(
        ComparadorDbContext db,
        IConfiguration configuration,
        DocumentProcessingQueue processingQueue)
    {
        _db = db;
        _configuration = configuration;
        _processingQueue = processingQueue;
    }

    private Guid RequireTenantId()
    {
        var tenantId = HttpContext.Items["tenant_id"] as string ?? User.FindFirst("tenant_id")?.Value;
        if (string.IsNullOrWhiteSpace(tenantId) || !Guid.TryParse(tenantId, out var parsed))
        {
            throw new UnauthorizedAccessException("No se pudo resolver el tenant_id del usuario autenticado.");
        }

        return parsed;
    }

    [HttpGet("documentos")]
    [Authorize]
    public async Task<IActionResult> GetDocumentos()
    {
        var tenantId = RequireTenantId();

        var documentos = await _db.Documentos
            .Where(d => d.TenantId == tenantId)
            .Include(d => d.Empresa)
            .OrderByDescending(d => d.CreatedAt)
            .Select(d => new
            {
                id = d.Id,
                empresa_id = d.EmpresaId,
                empresa_nombre = d.Empresa != null ? d.Empresa.Nombre : null,
                origen = d.Origen,
                url_origen = d.UrlOrigen,
                ruta_archivo = d.RutaArchivo,
                es_publico = d.EsPublico,
                estado = d.Estado,
                estado_detalle = d.EstadoDetalle,
                created_at = d.CreatedAt
            })
            .ToListAsync();

        return Ok(documentos);
    }

    [HttpGet("documentos/{id:int}")]
    [Authorize]
    public async Task<IActionResult> GetDocumento(int id)
    {
        var tenantId = RequireTenantId();

        var documento = await _db.Documentos
            .Where(d => d.Id == id && d.TenantId == tenantId)
            .Include(d => d.Empresa)
            .Select(d => new
            {
                id = d.Id,
                empresa_id = d.EmpresaId,
                empresa_nombre = d.Empresa != null ? d.Empresa.Nombre : null,
                origen = d.Origen,
                url_origen = d.UrlOrigen,
                ruta_archivo = d.RutaArchivo,
                es_publico = d.EsPublico,
                estado = d.Estado,
                estado_detalle = d.EstadoDetalle,
                negociacion_id = d.NegociacionId,
                version_negociacion = d.VersionNegociacion,
                created_at = d.CreatedAt
            })
            .FirstOrDefaultAsync();

        if (documento == null)
        {
            return NotFound();
        }

        return Ok(documento);
    }

    [HttpPost("documentos")]
    [Authorize(Policy = AuthorizationPolicies.PuedeCargarDocumento)]
    public async Task<IActionResult> CrearDocumento([FromForm] IFormFile? archivo, [FromForm] string? origen, [FromForm] string? url_origen, [FromForm] Guid? empresa_id, [FromForm] bool es_publico = false, CancellationToken cancellationToken = default)
    {
        var tenantId = RequireTenantId();
        if (empresa_id is null || !await _db.Empresas.AnyAsync(e => e.Id == empresa_id && e.TenantId == tenantId))
        {
            return BadRequest(new { detail = "Debe indicar una empresa válida del tenant." });
        }

        var origenFinal = origen?.Trim().ToLowerInvariant();
        if (origenFinal is not ("archivo" or "url"))
        {
            return BadRequest(new { detail = "El origen debe ser 'archivo' o 'url'." });
        }

        if (origenFinal == "archivo")
        {
            if (archivo is null || archivo.Length == 0)
            {
                return BadRequest(new { detail = "Debe adjuntar un archivo no vacío." });
            }

            var extension = Path.GetExtension(archivo.FileName);
            var maxUploadBytes = _configuration.GetValue<long?>("Documents:MaxDownloadBytes")
                ?? DefaultMaxDownloadBytes;
            if (!string.Equals(extension, ".pdf", StringComparison.OrdinalIgnoreCase) &&
                !string.Equals(extension, ".docx", StringComparison.OrdinalIgnoreCase))
            {
                return BadRequest(new { detail = "El archivo debe ser PDF o DOCX." });
            }

            if (archivo.Length > maxUploadBytes)
            {
                return BadRequest(new { detail = "El archivo excede el tamaño máximo permitido." });
            }

            if (es_publico)
            {
                return BadRequest(new { detail = "Los archivos cargados no pueden publicarse; use una URL pública." });
            }

            var storageRoot = Environment.GetEnvironmentVariable("STORAGE_DIR")
                ?? _configuration["Storage:Root"]
                ?? Path.Combine(AppContext.BaseDirectory, "storage");
            var storagePath = Path.Combine(storageRoot, "documentos");
            Directory.CreateDirectory(storagePath);
            var storedName = $"{Guid.NewGuid():N}{Path.GetExtension(archivo.FileName)}";
            var filePath = Path.Combine(storagePath, storedName);
            await using (var output = System.IO.File.Create(filePath))
            {
                await archivo.CopyToAsync(output, cancellationToken);
            }

            var fileDocument = new Documento
            {
                TenantId = tenantId,
                EmpresaId = empresa_id.Value,
                Origen = "archivo",
                RutaArchivo = filePath,
                EsPublico = false,
                Estado = "pendiente",
                CreatedAt = DateTimeOffset.UtcNow
            };
            _db.Documentos.Add(fileDocument);
            await _db.SaveChangesAsync(cancellationToken);
            await _processingQueue.EnqueueAsync(fileDocument.Id, cancellationToken);
            return CreatedAtAction(nameof(GetDocumento), new { id = fileDocument.Id }, new { id = fileDocument.Id, estado = fileDocument.Estado });
        }

        if (string.IsNullOrWhiteSpace(url_origen) || !Uri.TryCreate(url_origen, UriKind.Absolute, out var url) || url.Scheme is not ("http" or "https"))
        {
            return BadRequest(new { detail = "Debe indicar una URL HTTP o HTTPS válida." });
        }

        IPAddress? allowedAddress;
        try
        {
            allowedAddress = await ResolveAllowedAddressAsync(url);
        }
        catch (SocketException)
        {
            return BadRequest(new { detail = "No se pudo resolver una dirección pública para la URL." });
        }
        catch (ArgumentException)
        {
            return BadRequest(new { detail = "La URL no contiene un host válido." });
        }

        if (allowedAddress is null)
        {
            return BadRequest(new { detail = "La URL apunta a un destino privado no permitido." });
        }

        if (es_publico && !await EsUrlPublicaAsync(url, allowedAddress, cancellationToken))
        {
            return BadRequest(new { detail = "La URL no responde públicamente sin autenticación." });
        }

        var (urlContent, urlFileName) = await DescargarUrlAsync(url, allowedAddress, cancellationToken);
        var urlStorageRoot = Environment.GetEnvironmentVariable("STORAGE_DIR")
            ?? _configuration["Storage:Root"]
            ?? Path.Combine(AppContext.BaseDirectory, "storage");
        var urlStoragePath = Path.Combine(urlStorageRoot, "documentos");
        Directory.CreateDirectory(urlStoragePath);
        var urlStoredName = $"{Guid.NewGuid():N}{Path.GetExtension(urlFileName)}";
        var urlFilePath = Path.Combine(urlStoragePath, urlStoredName);
        await System.IO.File.WriteAllBytesAsync(urlFilePath, urlContent, cancellationToken);

        var documento = new Documento
        {
            TenantId = tenantId,
            EmpresaId = empresa_id.Value,
            Origen = origenFinal,
            UrlOrigen = string.IsNullOrWhiteSpace(url_origen) ? null : url_origen,
            RutaArchivo = urlFilePath,
            EsPublico = es_publico,
            Estado = "pendiente",
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Documentos.Add(documento);
        await _db.SaveChangesAsync(cancellationToken);
        await _processingQueue.EnqueueAsync(documento.Id, cancellationToken);

        return CreatedAtAction(nameof(GetDocumento), new { id = documento.Id }, new { id = documento.Id, estado = documento.Estado });
    }

    private async Task<(byte[] Content, string FileName)> DescargarUrlAsync(
        Uri url,
        IPAddress allowedAddress,
        CancellationToken cancellationToken)
    {
        try
        {
            using var client = CreatePinnedHttpClient(url, allowedAddress);
            client.Timeout = TimeSpan.FromSeconds(30);
            using var response = await client.GetAsync(url, HttpCompletionOption.ResponseHeadersRead, cancellationToken);
            response.EnsureSuccessStatusCode();
            var maxDownloadBytes = _configuration.GetValue<long?>("Documents:MaxDownloadBytes")
                ?? DefaultMaxDownloadBytes;
            if (response.Content.Headers.ContentLength > maxDownloadBytes)
            {
                throw new InvalidOperationException("El documento excede el tamaño máximo permitido.");
            }

            await using var responseStream = await response.Content.ReadAsStreamAsync(cancellationToken);
            using var contentStream = new MemoryStream();
            var buffer = new byte[81920];
            var totalBytes = 0L;
            int bytesRead;
            while ((bytesRead = await responseStream.ReadAsync(buffer, cancellationToken)) > 0)
            {
                totalBytes += bytesRead;
                if (totalBytes > maxDownloadBytes)
                {
                    throw new InvalidOperationException("El documento excede el tamaño máximo permitido.");
                }

                await contentStream.WriteAsync(buffer.AsMemory(0, bytesRead), cancellationToken);
            }

            var content = contentStream.ToArray();
            var fileName = Path.GetFileName(url.AbsolutePath);

            if (string.IsNullOrWhiteSpace(Path.GetExtension(fileName)))
            {
                var contentType = response.Content.Headers.ContentType?.MediaType ?? "";
                fileName += contentType.Contains("pdf", StringComparison.OrdinalIgnoreCase)
                    ? ".pdf"
                    : contentType.Contains("word", StringComparison.OrdinalIgnoreCase)
                        ? ".docx"
                        : "";
            }

            return (content, string.IsNullOrWhiteSpace(fileName) ? "documento" : fileName);
        }
        catch (HttpRequestException exception)
        {
            throw new InvalidOperationException("No se pudo descargar el documento desde la URL.", exception);
        }
        catch (TaskCanceledException exception) when (!cancellationToken.IsCancellationRequested)
        {
            throw new InvalidOperationException("La descarga del documento excedió el tiempo permitido.", exception);
        }
    }

    private async Task<bool> EsUrlPublicaAsync(Uri url, IPAddress allowedAddress, CancellationToken cancellationToken)
    {
        try
        {
            using var client = CreatePinnedHttpClient(url, allowedAddress);
            client.Timeout = TimeSpan.FromSeconds(10);
            using var head = new HttpRequestMessage(HttpMethod.Head, url);
            using var headResponse = await client.SendAsync(head, HttpCompletionOption.ResponseHeadersRead, cancellationToken);
            if (headResponse.IsSuccessStatusCode)
            {
                return true;
            }

            using var get = new HttpRequestMessage(HttpMethod.Get, url);
            using var getResponse = await client.SendAsync(get, HttpCompletionOption.ResponseHeadersRead, cancellationToken);
            return getResponse.IsSuccessStatusCode;
        }
        catch (HttpRequestException)
        {
            return false;
        }
        catch (TaskCanceledException)
        {
            return false;
        }
    }

    private static async Task<IPAddress?> ResolveAllowedAddressAsync(Uri url)
    {
        var addresses = await Dns.GetHostAddressesAsync(url.Host);
        return addresses.FirstOrDefault(address => !IsPrivateAddress(address));
    }

    private HttpClient CreatePinnedHttpClient(Uri url, IPAddress allowedAddress)
    {
        var handler = new SocketsHttpHandler
        {
            AllowAutoRedirect = false,
            ConnectCallback = async (context, cancellationToken) =>
            {
                var socket = new Socket(allowedAddress.AddressFamily, SocketType.Stream, ProtocolType.Tcp);
                try
                {
                    await socket.ConnectAsync(allowedAddress, context.DnsEndPoint.Port, cancellationToken);
                    return new NetworkStream(socket, ownsSocket: true);
                }
                catch
                {
                    socket.Dispose();
                    throw;
                }
            }
        };

        return new HttpClient(handler, disposeHandler: true)
        {
            BaseAddress = new Uri($"{url.Scheme}://{url.Host}")
        };
    }

    private static bool IsPrivateAddress(IPAddress address) =>
        IPAddress.IsLoopback(address) ||
        address.IsIPv6LinkLocal ||
        address.IsIPv6SiteLocal ||
        (address.AddressFamily == AddressFamily.InterNetwork && IsPrivateIpv4(address.GetAddressBytes())) ||
        (address.AddressFamily == AddressFamily.InterNetworkV6 && IsUniqueLocalIpv6(address.GetAddressBytes()));

    private static bool IsPrivateIpv4(byte[] bytes) =>
        bytes[0] == 10 ||
        (bytes[0] == 172 && bytes[1] is >= 16 and <= 31) ||
        (bytes[0] == 192 && bytes[1] == 168) ||
        bytes[0] == 127 ||
        (bytes[0] == 169 && bytes[1] == 254);

    private static bool IsUniqueLocalIpv6(byte[] bytes) => (bytes[0] & 0xFE) == 0xFC;
}
