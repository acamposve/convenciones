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
        var query = _db.BibliotecaPublica.AsQueryable();

        if (!string.IsNullOrWhiteSpace(empresa))
        {
            query = query.Where(d => EF.Functions.ILike(d.EmpresaNombre, $"%{empresa}%"));
        }

        var documentos = await query
            .OrderByDescending(d => d.CreatedAt)
            .Select(d => new
            {
                empresa_nombre = d.EmpresaNombre,
                url_origen = d.UrlOrigen,
                created_at = d.CreatedAt
            })
            .ToListAsync();

        return Ok(documentos);
    }
}
