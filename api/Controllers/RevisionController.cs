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

    private Guid RequireUserId()
    {
        var userId = User.FindFirst("user_id")?.Value;
        if (string.IsNullOrWhiteSpace(userId) || !Guid.TryParse(userId, out var parsed))
        {
            throw new UnauthorizedAccessException("No se pudo resolver el user_id del usuario autenticado.");
        }

        return parsed;
    }

    [HttpGet("revision")]
    [Authorize(Policy = AuthorizationPolicies.PuedeAprobarClausula)]
    public async Task<IActionResult> GetColaRevision()
    {
        var tenantId = RequireTenantId();

        var clausulas = await _db.Clausulas
            .Where(c => c.TenantId == tenantId && (c.EstadoRevision == "pendiente" || c.EstadoRevisionResumen == "pendiente"))
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
                cumplimiento_justificacion = c.CumplimientoJustificacion,
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
    [Authorize(Policy = AuthorizationPolicies.PuedeAprobarClausula)]
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
            var paisEmpresa = await _db.Documentos
                .Where(d => d.Id == clausula.DocumentoId)
                .Select(d => (int?)d.Empresa!.PaisId)
                .SingleOrDefaultAsync();

            var titulo = await _db.TaxonomiaTitulos
                .Where(t => t.Id == titulo_id.Value && t.Activo && t.PaisId == paisEmpresa)
                .FirstOrDefaultAsync();

            if (titulo == null)
            {
                return BadRequest(new { detail = "El título indicado no está activo o no pertenece al país de la empresa." });
            }

            clausula.TituloId = titulo.Id;
            clausula.CategoriaId = titulo.CategoriaId;
        }

        if (campo_comparativo != null)
        {
            clausula.CampoComparativo = campo_comparativo;
        }

        clausula.EstadoRevision = "aprobado";
        clausula.RevisadoPor = RequireUserId();
        clausula.RevisadoAt = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/rechazar")]
    [Authorize(Policy = AuthorizationPolicies.PuedeAprobarClausula)]
    public async Task<IActionResult> Rechazar(int id)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        clausula.EstadoRevision = "rechazado";
        clausula.RevisadoPor = RequireUserId();
        clausula.RevisadoAt = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/aprobar-resumen")]
    [Authorize(Policy = AuthorizationPolicies.PuedeAprobarClausula)]
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
        clausula.RevisadoPorResumen = RequireUserId();
        clausula.RevisadoAtResumen = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }

    [HttpPost("revision/{id:int}/rechazar-resumen")]
    [Authorize(Policy = AuthorizationPolicies.PuedeAprobarClausula)]
    public async Task<IActionResult> RechazarResumen(int id)
    {
        var tenantId = RequireTenantId();
        var clausula = await _db.Clausulas.FirstOrDefaultAsync(c => c.Id == id && c.TenantId == tenantId);
        if (clausula == null)
        {
            return NotFound();
        }

        clausula.EstadoRevisionResumen = "rechazado";
        clausula.RevisadoPorResumen = RequireUserId();
        clausula.RevisadoAtResumen = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();

        return Ok();
    }
}
