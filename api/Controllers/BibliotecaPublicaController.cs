using Comparador.Api.Data;
using Comparador.Api.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

[ApiController]
[Route("api")]
[Route("")]
public class BibliotecaPublicaController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public BibliotecaPublicaController(ComparadorDbContext db)
    {
        _db = db;
    }

    [HttpGet("biblioteca")]
    public async Task<IActionResult> GetBiblioteca([FromQuery] string? empresa)
    {
        var query = _db.Documentos
            .Where(d => d.EsPublico && d.Origen == "url")
            .Include(d => d.Empresa)
            .AsQueryable();

        if (!string.IsNullOrWhiteSpace(empresa))
        {
            query = query.Where(d => d.Empresa!.Nombre.Contains(empresa));
        }

        var documentos = await query
            .OrderByDescending(d => d.CreatedAt)
            .Select(d => new
            {
                id = d.Id,
                empresa_id = d.EmpresaId,
                empresa_nombre = d.Empresa != null ? d.Empresa.Nombre : null,
                origen = d.Origen,
                url_origen = d.UrlOrigen,
                es_publico = d.EsPublico,
                created_at = d.CreatedAt
            })
            .ToListAsync();

        return Ok(documentos);
    }
}
