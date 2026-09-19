using Comparador.Api.Data;
using Comparador.Api.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

[ApiController]
[Route("api")]
[Route("")]
public class RevisionController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public RevisionController(ComparadorDbContext db)
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

    [HttpGet("revision")]
    [Authorize]
    public async Task<IActionResult> GetColaRevision()
    {
        var tenantId = RequireTenantId();

        var clausulas = await _db.Clausulas
            .Where(c => c.TenantId == tenantId && c.EstadoRevision == "pendiente")
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .Include(c => c.Titulo)
            .ThenInclude(t => t!.Categoria)
            .Select(c => new
            {
                id = c.Id,
                documento_id = c.DocumentoId,
                texto = c.Texto,
                titulo_id = c.TituloId,
                titulo_nombre = c.Titulo != null ? c.Titulo.Nombre : null,
                categoria_nombre = c.Titulo != null && c.Titulo.Categoria != null ? c.Titulo.Categoria.Nombre : null,
                empresa_id = c.Documento != null ? (Guid?)c.Documento.EmpresaId : null,
                empresa_nombre = c.Documento != null && c.Documento.Empresa != null ? c.Documento.Empresa.Nombre : null,
                empresa_pais_id = c.Documento != null && c.Documento.Empresa != null ? (int?)c.Documento.Empresa.PaisId : null,
                confianza = c.Confianza,
                cumplimiento_legal = c.CumplimientoLegal,
                campo_comparativo = c.CampoComparativo,
                resumen_ejecutivo = c.ResumenEjecutivo,
                estado_revision = c.EstadoRevision,
                estado_revision_resumen = c.EstadoRevisionResumen,
                created_at = c.CreatedAt
            })
            .OrderBy(c => c.created_at)
            .ToListAsync();

        return Ok(clausulas);
    }

    [HttpPost("revision/{id:int}/aprobar")]
    [Authorize]
    public async Task<IActionResult> Aprobar(int id, [FromForm] int? titulo_id, [FromForm] string? campo_comparativo)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        if (titulo_id.HasValue)
        {
            clausula.TituloId = titulo_id.Value;
        }

        if (campo_comparativo != null)
        {
            clausula.CampoComparativo = campo_comparativo;
        }

        clausula.EstadoRevision = "aprobado";
        clausula.RevisadoAt = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/rechazar")]
    [Authorize]
    public async Task<IActionResult> Rechazar(int id)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        clausula.EstadoRevision = "rechazado";
        clausula.RevisadoAt = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/aprobar-resumen")]
    [Authorize]
    public async Task<IActionResult> AprobarResumen(int id, [FromForm] string? resumen_ejecutivo)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        if (resumen_ejecutivo != null)
        {
            clausula.ResumenEjecutivo = resumen_ejecutivo;
        }

        clausula.EstadoRevisionResumen = "aprobado";
        clausula.RevisadoAtResumen = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/rechazar-resumen")]
    [Authorize]
    public async Task<IActionResult> RechazarResumen(int id)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        clausula.EstadoRevisionResumen = "rechazado";
        clausula.RevisadoAtResumen = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }
}
