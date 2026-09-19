using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

[ApiController]
[Route("api")]
[Route("")]
public class DocumentosController : ControllerBase
{
    private readonly ComparadorDbContext _db;
    private readonly IHttpClientFactory _httpClientFactory;

    public DocumentosController(ComparadorDbContext db, IHttpClientFactory httpClientFactory)
    {
        _db = db;
        _httpClientFactory = httpClientFactory;
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
    public async Task<IActionResult> CrearDocumento([FromForm] IFormFile? archivo, [FromForm] string? origen, [FromForm] string? url_origen, [FromForm] Guid? empresa_id, [FromForm] bool es_publico = false)
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
            return StatusCode(StatusCodes.Status501NotImplemented,
                new { detail = "La carga binaria quedará disponible cuando se habilite el almacenamiento de documentos en .NET." });
        }

        if (string.IsNullOrWhiteSpace(url_origen) || !Uri.TryCreate(url_origen, UriKind.Absolute, out var url) || url.Scheme is not ("http" or "https"))
        {
            return BadRequest(new { detail = "Debe indicar una URL HTTP o HTTPS válida." });
        }

        if (!await EsUrlPublicaAsync(url))
        {
            return BadRequest(new { detail = "La URL no responde públicamente sin autenticación." });
        }

        var documento = new Documento
        {
            TenantId = tenantId,
            EmpresaId = empresa_id.Value,
            Origen = origenFinal,
            UrlOrigen = string.IsNullOrWhiteSpace(url_origen) ? null : url_origen,
            RutaArchivo = null,
            EsPublico = es_publico,
            Estado = "pendiente",
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Documentos.Add(documento);
        await _db.SaveChangesAsync();

        return CreatedAtAction(nameof(GetDocumento), new { id = documento.Id }, new { id = documento.Id, estado = documento.Estado });
    }

    private async Task<bool> EsUrlPublicaAsync(Uri url)
    {
        try
        {
            var client = _httpClientFactory.CreateClient();
            client.Timeout = TimeSpan.FromSeconds(10);
            using var head = new HttpRequestMessage(HttpMethod.Head, url);
            using var headResponse = await client.SendAsync(head, HttpCompletionOption.ResponseHeadersRead);
            if (headResponse.IsSuccessStatusCode)
            {
                return true;
            }

            using var get = new HttpRequestMessage(HttpMethod.Get, url);
            using var getResponse = await client.SendAsync(get, HttpCompletionOption.ResponseHeadersRead);
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
}
