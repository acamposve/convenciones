using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using DocumentFormat.OpenXml;
using DocumentFormat.OpenXml.Packaging;
using DocumentFormat.OpenXml.Wordprocessing;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public class NegociacionCreateRequest
{
    [FromForm(Name = "empresa_id")] public Guid EmpresaId { get; set; }
}

public class PeticionCreateRequest
{
    [FromForm(Name = "titulo_id")] public int? TituloId { get; set; }
    [FromForm(Name = "nro_peticion")] public int? NroPeticion { get; set; }
    [FromForm(Name = "texto")] public string Texto { get; set; } = string.Empty;
}

public class OfertaCreateRequest
{
    [FromForm(Name = "texto")] public string Texto { get; set; } = string.Empty;
}

public class ReunionCreateRequest
{
    [FromForm(Name = "fecha")] public DateOnly Fecha { get; set; }
    [FromForm(Name = "asistentes")] public string? Asistentes { get; set; }
    [FromForm(Name = "resumen")] public string? Resumen { get; set; }
}

public class AcuerdoCreateRequest
{
    [FromForm(Name = "titulo_id")] public int TituloId { get; set; }
    [FromForm(Name = "texto_acordado")] public string TextoAcordado { get; set; } = string.Empty;
    [FromForm(Name = "peticion_id")] public int? PeticionId { get; set; }
    [FromForm(Name = "oferta_id")] public int? OfertaId { get; set; }
}

[ApiController]
[Route("api")]
[Route("")]
public class NegociacionController : ControllerBase
{
    private readonly ComparadorDbContext _db;
    private readonly IConfiguration _configuration;

    public NegociacionController(ComparadorDbContext db, IConfiguration configuration)
    {
        _db = db;
        _configuration = configuration;
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

    private Guid? CurrentUserId()
    {
        return Guid.TryParse(User.FindFirst("user_id")?.Value, out var userId) ? userId : null;
    }

    private void AddAudit(Guid negociacionId, string evento, string? detalle = null)
    {
        _db.BitacoraNegociaciones.Add(new BitacoraNegociacion
        {
            NegociacionId = negociacionId,
            Evento = evento,
            UsuarioId = CurrentUserId(),
            Detalle = detalle,
            CreatedAt = DateTimeOffset.UtcNow
        });
    }

    [HttpGet("negociaciones")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerNegociacion)]
    public async Task<IActionResult> GetNegociaciones([FromQuery] Guid? empresa_id)
    {
        var tenantId = RequireTenantId();

        var query = _db.Negociaciones
            .Where(n => n.TenantId == tenantId)
            .Include(n => n.Empresa)
            .AsQueryable();

        if (empresa_id.HasValue)
        {
            query = query.Where(n => n.EmpresaId == empresa_id.Value);
        }

        var negociaciones = await query
            .OrderByDescending(n => n.FechaInicio)
            .Select(n => new
            {
                id = n.Id,
                empresa_id = n.EmpresaId,
                empresa_nombre = n.Empresa != null ? n.Empresa.Nombre : null,
                estado = n.Estado,
                fecha_inicio = n.FechaInicio,
                fecha_cierre = n.FechaCierre,
                created_at = n.CreatedAt
            })
            .ToListAsync();

        return Ok(negociaciones);
    }

    [HttpPost("negociaciones")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarNegociacion)]
    public async Task<IActionResult> CrearNegociacion([FromForm] NegociacionCreateRequest req)
    {
        var tenantId = RequireTenantId();

        var empresaExiste = await _db.Empresas.AnyAsync(e => e.Id == req.EmpresaId && e.TenantId == tenantId);
        if (!empresaExiste)
        {
            return BadRequest(new { detail = "La empresa indicada no pertenece a este tenant." });
        }

        var negociacion = new Negociacion
        {
            Id = Guid.NewGuid(),
            TenantId = tenantId,
            EmpresaId = req.EmpresaId,
            Estado = "abierta",
            FechaInicio = DateTimeOffset.UtcNow,
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Negociaciones.Add(negociacion);
        AddAudit(negociacion.Id, "creacion");
        await _db.SaveChangesAsync();

        return CreatedAtAction(nameof(GetNegociaciones), new { id = negociacion.Id }, new { id = negociacion.Id });
    }

    [HttpGet("negociaciones/{id:guid}")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerNegociacion)]
    public async Task<IActionResult> GetNegociacion(Guid id)
    {
        var tenantId = RequireTenantId();

        var negociacion = await _db.Negociaciones
            .Include(n => n.Empresa)
            .ThenInclude(e => e!.Pais)
            .FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);

        if (negociacion == null)
        {
            return NotFound();
        }

        var peticiones = await _db.Peticiones
            .Where(p => p.NegociacionId == id)
            .Include(p => p.Titulo)
            .OrderBy(p => p.NroPeticion)
            .Select(p => new
            {
                id = p.Id,
                negociacion_id = p.NegociacionId,
                titulo_id = p.TituloId,
                titulo_nombre = p.Titulo != null ? p.Titulo.Nombre : null,
                nro_peticion = p.NroPeticion,
                texto = p.Texto,
                created_at = p.CreatedAt,
                ofertas = _db.Ofertas.Where(o => o.PeticionId == p.Id).Select(o => new
                {
                    id = o.Id,
                    peticion_id = o.PeticionId,
                    texto = o.Texto,
                    created_at = o.CreatedAt
                }).ToList()
            })
            .ToListAsync();

        var reuniones = await _db.Reuniones
            .Where(r => r.NegociacionId == id)
            .OrderByDescending(r => r.Fecha)
            .Select(r => new
            {
                id = r.Id,
                negociacion_id = r.NegociacionId,
                fecha = r.Fecha,
                asistentes = r.Asistentes,
                resumen = r.Resumen,
                created_at = r.CreatedAt
            })
            .ToListAsync();

        var acuerdos = await _db.Acuerdos
            .Where(a => a.NegociacionId == id)
            .Include(a => a.Titulo)
            .OrderByDescending(a => a.CreatedAt)
            .Select(a => new
            {
                id = a.Id,
                negociacion_id = a.NegociacionId,
                titulo_id = a.TituloId,
                titulo_nombre = a.Titulo != null ? a.Titulo.Nombre : null,
                texto_acordado = a.TextoAcordado,
                peticion_id = a.PeticionId,
                oferta_id = a.OfertaId,
                created_at = a.CreatedAt
            })
            .ToListAsync();

        var documentos = await _db.Documentos
            .Where(d => d.NegociacionId == id)
            .OrderByDescending(d => d.CreatedAt)
            .Select(d => new
            {
                id = d.Id,
                empresa_id = d.EmpresaId,
                origen = d.Origen,
                url_origen = d.UrlOrigen,
                ruta_archivo = d.RutaArchivo,
                es_publico = d.EsPublico,
                estado = d.Estado,
                version_negociacion = d.VersionNegociacion,
                created_at = d.CreatedAt
            })
            .ToListAsync();

        return Ok(new
        {
            id = negociacion.Id,
            empresa_id = negociacion.EmpresaId,
            empresa_nombre = negociacion.Empresa?.Nombre,
            empresa_pais_id = negociacion.Empresa?.PaisId,
            estado = negociacion.Estado,
            fecha_inicio = negociacion.FechaInicio,
            fecha_cierre = negociacion.FechaCierre,
            created_at = negociacion.CreatedAt,
            peticiones,
            reuniones,
            acuerdos,
            documentos
        });
    }

    [HttpPost("negociaciones/{id:guid}/peticiones")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarNegociacion)]
    public async Task<IActionResult> CrearPeticion(Guid id, [FromForm] PeticionCreateRequest req)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        if (string.IsNullOrWhiteSpace(req.Texto))
        {
            return BadRequest(new { detail = "La petición requiere texto." });
        }

        var nroPeticion = req.NroPeticion ?? (await _db.Peticiones
            .Where(p => p.NegociacionId == id)
            .MaxAsync(p => (int?)p.NroPeticion) ?? 0) + 1;

        var peticion = new Peticion
        {
            NegociacionId = id,
            TituloId = req.TituloId,
            NroPeticion = nroPeticion,
            Texto = req.Texto,
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Peticiones.Add(peticion);
        AddAudit(id, "peticion");
        await _db.SaveChangesAsync();

        return Ok(new { id = peticion.Id, nro_peticion = peticion.NroPeticion });
    }

    [HttpPost("peticiones/{peticionId:int}/ofertas")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarNegociacion)]
    public async Task<IActionResult> CrearOferta(int peticionId, [FromForm] OfertaCreateRequest req)
    {
        var tenantId = RequireTenantId();
        if (string.IsNullOrWhiteSpace(req.Texto))
        {
            return BadRequest(new { detail = "La oferta requiere texto." });
        }

        var peticion = await _db.Peticiones
            .Include(p => p.Negociacion)
            .FirstOrDefaultAsync(p => p.Id == peticionId && p.Negociacion!.TenantId == tenantId);
        if (peticion?.Negociacion == null)
        {
            return NotFound();
        }

        if (peticion.Negociacion.Estado != "abierta")
        {
            return Conflict(new { detail = "No se pueden agregar ofertas a una negociación cerrada." });
        }

        var oferta = new Oferta { PeticionId = peticionId, Texto = req.Texto.Trim(), CreatedAt = DateTimeOffset.UtcNow };
        _db.Ofertas.Add(oferta);
        AddAudit(peticion.NegociacionId, "oferta", $"peticion_id={peticionId}");
        await _db.SaveChangesAsync();
        return Ok(new { id = oferta.Id });
    }

    [HttpPost("negociaciones/{id:guid}/reuniones")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarNegociacion)]
    public async Task<IActionResult> CrearReunion(Guid id, [FromForm] ReunionCreateRequest req)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        var reunion = new Reunion
        {
            NegociacionId = id,
            Fecha = req.Fecha,
            Asistentes = req.Asistentes,
            Resumen = req.Resumen,
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Reuniones.Add(reunion);
        AddAudit(id, "reunion");
        await _db.SaveChangesAsync();

        return Ok(new { id = reunion.Id });
    }

    [HttpPost("negociaciones/{id:guid}/acuerdos")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarNegociacion)]
    public async Task<IActionResult> CrearAcuerdo(Guid id, [FromForm] AcuerdoCreateRequest req)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        if (string.IsNullOrWhiteSpace(req.TextoAcordado))
        {
            return BadRequest(new { detail = "El texto acordado es obligatorio." });
        }

        if (req.PeticionId.HasValue && !await _db.Peticiones.AnyAsync(p => p.Id == req.PeticionId && p.NegociacionId == id))
        {
            return BadRequest(new { detail = "La petición no pertenece a esta negociación." });
        }

        if (req.OfertaId.HasValue && !await _db.Ofertas
            .AnyAsync(o => o.Id == req.OfertaId && o.Peticion!.NegociacionId == id))
        {
            return BadRequest(new { detail = "La oferta no pertenece a esta negociación." });
        }

        var acuerdo = new Acuerdo
        {
            NegociacionId = id,
            TituloId = req.TituloId,
            TextoAcordado = req.TextoAcordado,
            PeticionId = req.PeticionId,
            OfertaId = req.OfertaId,
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Acuerdos.Add(acuerdo);
        AddAudit(id, "acuerdo");
        await _db.SaveChangesAsync();

        return Ok(new { id = acuerdo.Id });
    }

    [HttpPost("negociaciones/{id:guid}/cerrar")]
    [Authorize(Policy = AuthorizationPolicies.PuedeCerrarNegociacion)]
    public async Task<IActionResult> CerrarNegociacion(Guid id)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        if (negociacion.Estado != "abierta")
        {
            return Conflict(new { detail = "La negociación ya está cerrada." });
        }

        var acuerdos = await _db.Acuerdos
            .Where(a => a.NegociacionId == id)
            .Include(a => a.Titulo)
            .OrderByDescending(a => a.CreatedAt)
            .ToListAsync();
        if (acuerdos.Count == 0)
        {
            return BadRequest(new { detail = "La negociación requiere al menos un acuerdo para cerrarse." });
        }

        var acuerdosVigentes = acuerdos
            .GroupBy(a => a.TituloId)
            .Select(g => g.First())
            .OrderBy(a => a.TituloId)
            .ToList();
        var version = (await _db.Documentos
            .Where(d => d.NegociacionId == id)
            .MaxAsync(d => (int?)d.VersionNegociacion) ?? 0) + 1;
        var storageRoot = Environment.GetEnvironmentVariable("STORAGE_DIR")
            ?? _configuration["Storage:Root"]
            ?? Path.Combine(AppContext.BaseDirectory, "storage");
        storageRoot = Path.Combine(storageRoot, "negociaciones");
        Directory.CreateDirectory(storageRoot);
        var filePath = Path.Combine(storageRoot, $"{id}-v{version}.docx");
        using (var document = WordprocessingDocument.Create(filePath, WordprocessingDocumentType.Document))
        {
            var mainPart = document.AddMainDocumentPart();
            mainPart.Document = new Document(new Body(
                acuerdosVigentes.Select(a => new Paragraph(
                    new Run(
                        new Text($"CLAUSULA -- {a.Titulo?.Nombre ?? $"Título {a.TituloId}"}{Environment.NewLine}{a.TextoAcordado}")))).ToArray()));
            mainPart.Document.Save();
        }

        var documento = new Documento
        {
            TenantId = tenantId,
            EmpresaId = negociacion.EmpresaId,
            NegociacionId = id,
            VersionNegociacion = version,
            Origen = "negociacion",
            RutaArchivo = filePath,
            EsPublico = false,
            Estado = "pendiente",
            CreatedAt = DateTimeOffset.UtcNow
        };
        _db.Documentos.Add(documento);
        negociacion.Estado = "cerrada";
        negociacion.FechaCierre = DateTimeOffset.UtcNow;
        AddAudit(id, "cierre", $"documento_version={version}");
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("negociaciones/{id:guid}/reabrir")]
    [Authorize(Policy = AuthorizationPolicies.PuedeCerrarNegociacion)]
    public async Task<IActionResult> ReabrirNegociacion(Guid id)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        if (negociacion.Estado != "cerrada")
        {
            return Conflict(new { detail = "Solo se puede reabrir una negociación cerrada." });
        }

        negociacion.Estado = "abierta";
        negociacion.FechaCierre = null;
        AddAudit(id, "reapertura");
        await _db.SaveChangesAsync();

        return Ok();
    }
}
