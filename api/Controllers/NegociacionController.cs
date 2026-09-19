using Comparador.Api.Data;
using Comparador.Api.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public class NegociacionCreateRequest
{
    public Guid EmpresaId { get; set; }
}

public class PeticionCreateRequest
{
    public int? TituloId { get; set; }
    public int? NroPeticion { get; set; }
    public string Texto { get; set; } = string.Empty;
}

public class ReunionCreateRequest
{
    public DateOnly Fecha { get; set; }
    public string? Asistentes { get; set; }
    public string? Resumen { get; set; }
}

public class AcuerdoCreateRequest
{
    public int TituloId { get; set; }
    public string TextoAcordado { get; set; } = string.Empty;
    public int? PeticionId { get; set; }
    public int? OfertaId { get; set; }
}

[ApiController]
[Route("api")]
[Route("")]
public class NegociacionController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public NegociacionController(ComparadorDbContext db)
    {
        _db = db;
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

    [HttpGet("negociaciones")]
    [Authorize]
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
    [Authorize]
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
        await _db.SaveChangesAsync();

        return CreatedAtAction(nameof(GetNegociaciones), new { id = negociacion.Id }, new { id = negociacion.Id });
    }

    [HttpGet("negociaciones/{id:guid}")]
    [Authorize]
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
    [Authorize]
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
        await _db.SaveChangesAsync();

        return Ok(new { id = peticion.Id, nro_peticion = peticion.NroPeticion });
    }

    [HttpPost("negociaciones/{id:guid}/reuniones")]
    [Authorize]
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
        await _db.SaveChangesAsync();

        return Ok(new { id = reunion.Id });
    }

    [HttpPost("negociaciones/{id:guid}/acuerdos")]
    [Authorize]
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
        await _db.SaveChangesAsync();

        return Ok(new { id = acuerdo.Id });
    }

    [HttpPost("negociaciones/{id:guid}/cerrar")]
    [Authorize]
    public async Task<IActionResult> CerrarNegociacion(Guid id)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        negociacion.Estado = "cerrada";
        negociacion.FechaCierre = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("negociaciones/{id:guid}/reabrir")]
    [Authorize]
    public async Task<IActionResult> ReabrirNegociacion(Guid id)
    {
        var tenantId = RequireTenantId();
        var negociacion = await _db.Negociaciones.FirstOrDefaultAsync(n => n.Id == id && n.TenantId == tenantId);
        if (negociacion == null)
        {
            return NotFound();
        }

        negociacion.Estado = "abierta";
        negociacion.FechaCierre = null;
        await _db.SaveChangesAsync();

        return Ok();
    }
}
