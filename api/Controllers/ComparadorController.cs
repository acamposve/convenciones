using Comparador.Api.Data;
using Comparador.Api.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

[ApiController]
[Route("api")]
[Route("")]
public class ComparadorController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public ComparadorController(ComparadorDbContext db)
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

    [HttpGet("comparador/titulos")]
    [Authorize]
    public async Task<IActionResult> GetTitulos()
    {
        if (!User.HasClaim(c => c.Type == "tenant_id")) return Forbid();
        var tenantId = RequireTenantId();

        var titulos = await _db.Clausulas
            .Where(c => c.TenantId == tenantId && c.EstadoRevision == "aprobado")
            .Include(c => c.Titulo)
            .ThenInclude(t => t!.Categoria)
            .Select(c => new
            {
                id = c.TituloId,
                categoria_nombre = c.Titulo != null && c.Titulo.Categoria != null ? c.Titulo.Categoria.Nombre : null,
                nombre = c.Titulo != null ? c.Titulo.Nombre : null
            })
            .Distinct()
            .Where(x => x.id != null)
            .OrderBy(x => x.categoria_nombre)
            .ThenBy(x => x.nombre)
            .ToListAsync();

        return Ok(titulos);
    }

    [HttpGet("comparador")]
    [Authorize]
    public async Task<IActionResult> Comparar(
        [FromQuery] int titulo_id,
        [FromQuery] int? sector_id,
        [FromQuery] int? tipo_id,
        [FromQuery] int? categoria_id,
        [FromQuery] int? actividad_id,
        [FromQuery] int? estado_id)
    {
        if (!User.HasClaim(c => c.Type == "tenant_id")) return Forbid();
        var tenantId = RequireTenantId();

        var clausulas = await _db.Clausulas
            .Where(c => c.TenantId == tenantId && c.EstadoRevision == "aprobado" && c.TituloId == titulo_id)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.Pais)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.Sector)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.TipoEmpresa)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.CategoriaSector)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.ActividadEmpresa)
            .Include(c => c.Documento)
            .ThenInclude(d => d!.Empresa)
            .ThenInclude(e => e!.Estado)
            .ToListAsync();

        var empresasFiltradas = clausulas
            .Select(c => c.Documento!.Empresa)
            .Where(e => e != null)
            .Where(e => !sector_id.HasValue || e!.SectorId == sector_id)
            .Where(e => !tipo_id.HasValue || e!.TipoId == tipo_id)
            .Where(e => !categoria_id.HasValue || e!.CategoriaId == categoria_id)
            .Where(e => !actividad_id.HasValue || e!.ActividadId == actividad_id)
            .Where(e => !estado_id.HasValue || e!.EstadoId == estado_id)
            .DistinctBy(e => e!.Id)
            .ToList();

        var resultado = empresasFiltradas
            .Select(e => new
            {
                empresa_id = e!.Id,
                empresa_nombre = e.Nombre,
                clausulas = clausulas
                    .Where(c => c.Documento != null && c.Documento.EmpresaId == e.Id)
                    .Select(c => new
                    {
                        id = c.Id,
                        texto = c.Texto,
                        campo_comparativo = c.CampoComparativo,
                        resumen_ejecutivo = c.EstadoRevisionResumen == "aprobado" ? c.ResumenEjecutivo : null,
                        estado_revision = c.EstadoRevision,
                        estado_revision_resumen = c.EstadoRevisionResumen,
                        confianza = c.Confianza,
                        cumplimiento_legal = c.CumplimientoLegal
                    })
                    .ToList()
            })
            .ToList();

        return Ok(resultado);
    }
}
