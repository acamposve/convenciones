using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public class EmpresaCreateRequest
{
    [FromForm(Name = "nombre")] public string Nombre { get; set; } = string.Empty;
    [FromForm(Name = "pais_id")] public int PaisId { get; set; }
    [FromForm(Name = "rif")] public string? Rif { get; set; }
    [FromForm(Name = "sector_id")] public int? SectorId { get; set; }
    [FromForm(Name = "tipo_id")] public int? TipoId { get; set; }
    [FromForm(Name = "categoria_id")] public int? CategoriaId { get; set; }
    [FromForm(Name = "actividad_id")] public int? ActividadId { get; set; }
    [FromForm(Name = "estado_id")] public int? EstadoId { get; set; }
    [FromForm(Name = "localidad_id")] public int? LocalidadId { get; set; }
    [FromForm(Name = "contacto_nombre")] public string? ContactoNombre { get; set; }
    [FromForm(Name = "contacto_email")] public string? ContactoEmail { get; set; }
}

[ApiController]
[Route("api")]
[Route("")]
public class EmpresasController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public EmpresasController(ComparadorDbContext db)
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

    [HttpGet("catalogos")]
    [Authorize]
    public async Task<IActionResult> GetCatalogos()
    {
        var sectores = await _db.Sectores.OrderBy(s => s.Nombre).ToListAsync();
        var tiposEmpresa = await _db.TiposEmpresa.OrderBy(t => t.Nombre).ToListAsync();
        var categoriasSector = await _db.CategoriasSector.OrderBy(c => c.Nombre).ToListAsync();
        var actividadesEmpresa = await _db.ActividadesEmpresa.OrderBy(a => a.Nombre).ToListAsync();
        var estados = await _db.Estados.OrderBy(e => e.Nombre).ToListAsync();

        return Ok(new
        {
            sectores = sectores.Select(s => new { s.Id, s.Nombre, s.Descripcion }),
            tipos_empresa = tiposEmpresa.Select(t => new { t.Id, t.Nombre, t.Descripcion }),
            categorias_sector = categoriasSector.Select(c => new { c.Id, c.Nombre, c.Descripcion }),
            actividades_empresa = actividadesEmpresa.Select(a => new { a.Id, a.Nombre, a.Descripcion }),
            estados = estados.Select(e => new { e.Id, e.PaisId, e.Nombre })
        });
    }

    [HttpGet("catalogos/localidades")]
    [Authorize]
    public async Task<IActionResult> GetLocalidades([FromQuery] int estado_id)
    {
        var localidades = await _db.Localidades
            .Where(l => l.EstadoId == estado_id)
            .OrderBy(l => l.Nombre)
            .Select(l => new { l.Id, l.EstadoId, l.Nombre })
            .ToListAsync();

        return Ok(localidades);
    }

    [HttpGet("tenants/paises-habilitados")]
    [Authorize]
    public async Task<IActionResult> GetPaisesHabilitados()
    {
        var tenantId = RequireTenantId();

        var paises = await _db.TenantPaisesHabilitados
            .Where(tph => tph.TenantId == tenantId)
            .Include(tph => tph.Pais)
            .OrderBy(tph => tph.Pais!.Nombre)
            .Select(tph => new
            {
                id = tph.PaisId,
                codigo = tph.Pais!.Codigo,
                nombre = tph.Pais.Nombre,
                activo = tph.Pais.Activo
            })
            .ToListAsync();

        return Ok(paises);
    }

    [HttpGet("empresas")]
    [Authorize]
    public async Task<IActionResult> GetEmpresas()
    {
        var tenantId = RequireTenantId();

        var empresas = await _db.Empresas
            .Where(e => e.TenantId == tenantId)
            .Include(e => e.Pais)
            .Include(e => e.Estado)
            .Include(e => e.Localidad)
            .OrderBy(e => e.Nombre)
            .ToListAsync();

        return Ok(empresas
            .Select(e => new
            {
                id = e.Id,
                nombre = e.Nombre,
                pais_id = e.PaisId,
                pais_nombre = e.Pais?.Nombre,
                rif = e.Rif,
                sector_id = e.SectorId,
                tipo_id = e.TipoId,
                categoria_id = e.CategoriaId,
                actividad_id = e.ActividadId,
                estado_id = e.EstadoId,
                localidad_id = e.LocalidadId,
                contacto_nombre = e.ContactoNombre,
                contacto_email = e.ContactoEmail,
                created_at = e.CreatedAt
            })
            .ToList());
    }

    [HttpPost("empresas")]
    [Authorize(Policy = AuthorizationPolicies.PuedeGestionarEmpresas)]
    public async Task<IActionResult> CrearEmpresa([FromForm] EmpresaCreateRequest req)
    {
        var tenantId = RequireTenantId();

        if (string.IsNullOrWhiteSpace(req.Nombre))
        {
            return BadRequest(new { detail = "El nombre de la empresa es obligatorio." });
        }

        if (req.PaisId <= 0)
        {
            return BadRequest(new { detail = "Debe indicar un país válido." });
        }

        var existePais = await _db.Paises.AnyAsync(p => p.Id == req.PaisId);
        if (!existePais)
        {
            return BadRequest(new { detail = "El país indicado no existe." });
        }

        var paisHabilitado = await _db.TenantPaisesHabilitados
            .AnyAsync(t => t.TenantId == tenantId && t.PaisId == req.PaisId);
        if (!paisHabilitado)
        {
            return BadRequest(new { detail = "El país indicado no está habilitado para este tenant." });
        }

        var empresa = new Empresa
        {
            Id = Guid.NewGuid(),
            TenantId = tenantId,
            PaisId = req.PaisId,
            Nombre = req.Nombre.Trim(),
            Rif = string.IsNullOrWhiteSpace(req.Rif) ? null : req.Rif.Trim(),
            SectorId = req.SectorId,
            TipoId = req.TipoId,
            CategoriaId = req.CategoriaId,
            ActividadId = req.ActividadId,
            EstadoId = req.EstadoId,
            LocalidadId = req.LocalidadId,
            ContactoNombre = string.IsNullOrWhiteSpace(req.ContactoNombre) ? null : req.ContactoNombre.Trim(),
            ContactoEmail = string.IsNullOrWhiteSpace(req.ContactoEmail) ? null : req.ContactoEmail.Trim(),
            CreatedAt = DateTimeOffset.UtcNow
        };

        _db.Empresas.Add(empresa);
        await _db.SaveChangesAsync();

        return CreatedAtAction(nameof(GetEmpresas), new { id = empresa.Id }, new { id = empresa.Id, nombre = empresa.Nombre });
    }

    [HttpGet("taxonomia")]
    [Authorize]
    public async Task<IActionResult> GetTaxonomia([FromQuery] int? pais_id)
    {
        var query = _db.TaxonomiaTitulos
            .Include(t => t.Categoria)
            .Where(t => t.Activo);

        if (pais_id.HasValue)
        {
            query = query.Where(t => t.PaisId == pais_id.Value);
        }

        var titulos = await query
            .OrderBy(t => t.Categoria!.Nombre)
            .ThenBy(t => t.Nombre)
            .Select(t => new
            {
                id = t.Id,
                pais_id = t.PaisId,
                categoria_id = t.CategoriaId,
                categoria_nombre = t.Categoria!.Nombre,
                nombre = t.Nombre,
                descripcion = t.Descripcion
            })
            .ToListAsync();

        return Ok(titulos);
    }
}
