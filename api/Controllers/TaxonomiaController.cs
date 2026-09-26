using System.Data;
using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public sealed record ClonarTaxonomiaRequest(
    [FromForm(Name = "pais_origen_id")] int PaisOrigenId,
    [FromForm(Name = "pais_destino_id")] int PaisDestinoId);

public sealed record CrearTituloTaxonomiaRequest(
    [FromForm(Name = "pais_id")] int PaisId,
    [FromForm(Name = "categoria_id")] int CategoriaId,
    [FromForm(Name = "nombre")] string Nombre,
    [FromForm(Name = "descripcion")] string? Descripcion);

public sealed record EditarTituloTaxonomiaRequest(
    [FromForm(Name = "nombre")] string Nombre,
    [FromForm(Name = "categoria_id")] int CategoriaId,
    [FromForm(Name = "descripcion")] string? Descripcion);

public sealed record ActivarTituloTaxonomiaRequest([FromForm(Name = "activo")] bool Activo);

// Fase 8 (spec-taxonomia-por-pais.md Bloque B/D, Art II.3) / Fase 5.2 (cutover): portado
// desde service/app/main.py, que hasta ahora era la unica implementacion -- docFetch en el
// frontend apuntaba directo a ese servicio. Con docFetch re-apuntado a esta API (Fase 5.2),
// estas rutas tenian que existir aca tambien. Mismas reglas que el original: solo titulos
// activos se clonan, nunca se borra un titulo (se desactiva, por integridad referencial con
// clausulas/titulo_articulo_ley ya clasificados contra el), y los ids nuevos siempre salen
// de taxonomia_titulos_clon_seq (nunca reutiliza ids del dump legado de Venezuela).
//
// Nota de revision: GET /taxonomia/categorias no llevaba ningun chequeo de auth en el
// servicio Python (ni siquiera JWT). Acá queda gateado por PuedeVerPlataforma, igual que el
// resto del panel -- es el unico consumidor real (PlataformaPage.jsx) y no tiene sentido
// exponerlo sin autenticacion.
[ApiController]
[Route("")]
public class TaxonomiaController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public TaxonomiaController(ComparadorDbContext db)
    {
        _db = db;
    }

    [HttpGet("taxonomia/categorias")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerPlataforma)]
    public async Task<IActionResult> ListarCategorias(CancellationToken cancellationToken)
    {
        var categorias = await _db.TaxonomiaCategorias
            .OrderBy(c => c.Nombre)
            .Select(c => new { id = c.Id, nombre = c.Nombre })
            .ToListAsync(cancellationToken);

        return Ok(categorias);
    }

    [HttpGet("plataforma/taxonomia/titulos")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerPlataforma)]
    public async Task<IActionResult> ListarTitulos([FromQuery(Name = "pais_id")] int paisId, CancellationToken cancellationToken)
    {
        if (!await _db.Paises.AnyAsync(p => p.Id == paisId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"pais_id {paisId} no existe en paises" });
        }

        var titulos = await _db.TaxonomiaTitulos
            .Include(t => t.Categoria)
            .Where(t => t.PaisId == paisId)
            .OrderBy(t => t.Categoria!.Nombre)
            .ThenBy(t => t.Nombre)
            .Select(t => new
            {
                id = t.Id,
                nombre = t.Nombre,
                descripcion = t.Descripcion,
                activo = t.Activo,
                categoria_id = t.CategoriaId,
                categoria_nombre = t.Categoria!.Nombre
            })
            .ToListAsync(cancellationToken);

        return Ok(titulos);
    }

    [HttpPost("plataforma/taxonomia/clonar")]
    [Authorize(Policy = AuthorizationPolicies.PuedeGestionarTaxonomia)]
    public async Task<IActionResult> ClonarTaxonomia([FromForm] ClonarTaxonomiaRequest request, CancellationToken cancellationToken)
    {
        if (!await _db.Paises.AnyAsync(p => p.Id == request.PaisOrigenId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"pais_origen_id {request.PaisOrigenId} no existe en paises" });
        }
        if (!await _db.Paises.AnyAsync(p => p.Id == request.PaisDestinoId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"pais_destino_id {request.PaisDestinoId} no existe en paises" });
        }

        // Evita duplicar por clonar dos veces (spec §3.3) -- para reintentar, el pais
        // destino debe estar vacio.
        if (await _db.TaxonomiaTitulos.AnyAsync(t => t.PaisId == request.PaisDestinoId, cancellationToken))
        {
            return UnprocessableEntity(new
            {
                detail = $"pais_destino_id {request.PaisDestinoId} ya tiene títulos; " +
                    "para volver a clonar, el país destino debe estar vacío."
            });
        }

        var origen = await _db.TaxonomiaTitulos
            .Where(t => t.PaisId == request.PaisOrigenId && t.Activo)
            .Select(t => new { t.Nombre, t.Descripcion, t.CategoriaId })
            .ToListAsync(cancellationToken);
        if (origen.Count == 0)
        {
            return UnprocessableEntity(new { detail = $"pais_origen_id {request.PaisOrigenId} no tiene títulos activos para clonar" });
        }

        foreach (var titulo in origen)
        {
            _db.TaxonomiaTitulos.Add(new TaxonomiaTitulo
            {
                Id = await SiguienteIdClonAsync(cancellationToken),
                Nombre = titulo.Nombre,
                Descripcion = titulo.Descripcion,
                CategoriaId = titulo.CategoriaId,
                PaisId = request.PaisDestinoId,
                Activo = true,
            });
        }
        await _db.SaveChangesAsync(cancellationToken);

        return StatusCode(StatusCodes.Status201Created, new
        {
            pais_origen_id = request.PaisOrigenId,
            pais_destino_id = request.PaisDestinoId,
            titulos_clonados = origen.Count
        });
    }

    [HttpPost("plataforma/taxonomia/titulos")]
    [Authorize(Policy = AuthorizationPolicies.PuedeGestionarTaxonomia)]
    public async Task<IActionResult> CrearTitulo([FromForm] CrearTituloTaxonomiaRequest request, CancellationToken cancellationToken)
    {
        if (!await _db.Paises.AnyAsync(p => p.Id == request.PaisId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"pais_id {request.PaisId} no existe en paises" });
        }
        if (!await _db.TaxonomiaCategorias.AnyAsync(c => c.Id == request.CategoriaId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"categoria_id {request.CategoriaId} no existe en la taxonomía" });
        }

        var nuevoTitulo = new TaxonomiaTitulo
        {
            Id = await SiguienteIdClonAsync(cancellationToken),
            Nombre = request.Nombre,
            Descripcion = request.Descripcion,
            CategoriaId = request.CategoriaId,
            PaisId = request.PaisId,
            Activo = true,
        };
        _db.TaxonomiaTitulos.Add(nuevoTitulo);
        await _db.SaveChangesAsync(cancellationToken);

        return StatusCode(StatusCodes.Status201Created, new
        {
            id = nuevoTitulo.Id,
            nombre = nuevoTitulo.Nombre,
            descripcion = nuevoTitulo.Descripcion,
            categoria_id = nuevoTitulo.CategoriaId,
            pais_id = nuevoTitulo.PaisId,
            activo = nuevoTitulo.Activo
        });
    }

    [HttpPut("plataforma/taxonomia/titulos/{tituloId:int}")]
    [Authorize(Policy = AuthorizationPolicies.PuedeGestionarTaxonomia)]
    public async Task<IActionResult> EditarTitulo(int tituloId, [FromForm] EditarTituloTaxonomiaRequest request, CancellationToken cancellationToken)
    {
        if (!await _db.TaxonomiaCategorias.AnyAsync(c => c.Id == request.CategoriaId, cancellationToken))
        {
            return UnprocessableEntity(new { detail = $"categoria_id {request.CategoriaId} no existe en la taxonomía" });
        }

        var titulo = await _db.TaxonomiaTitulos.FindAsync([tituloId], cancellationToken);
        if (titulo is null)
        {
            return NotFound(new { detail = $"titulo_id {tituloId} no existe en la taxonomía" });
        }

        titulo.Nombre = request.Nombre;
        titulo.Descripcion = request.Descripcion;
        titulo.CategoriaId = request.CategoriaId;
        await _db.SaveChangesAsync(cancellationToken);

        return Ok(new
        {
            id = titulo.Id,
            nombre = titulo.Nombre,
            descripcion = titulo.Descripcion,
            categoria_id = titulo.CategoriaId,
            pais_id = titulo.PaisId,
            activo = titulo.Activo
        });
    }

    [HttpPut("plataforma/taxonomia/titulos/{tituloId:int}/activo")]
    [Authorize(Policy = AuthorizationPolicies.PuedeGestionarTaxonomia)]
    public async Task<IActionResult> ActivarTitulo(int tituloId, [FromForm] ActivarTituloTaxonomiaRequest request, CancellationToken cancellationToken)
    {
        var titulo = await _db.TaxonomiaTitulos.FindAsync([tituloId], cancellationToken);
        if (titulo is null)
        {
            return NotFound(new { detail = $"titulo_id {tituloId} no existe en la taxonomía" });
        }

        titulo.Activo = request.Activo;
        await _db.SaveChangesAsync(cancellationToken);

        return Ok(new { id = titulo.Id, nombre = titulo.Nombre, pais_id = titulo.PaisId, activo = titulo.Activo });
    }

    // taxonomia_titulos.id no es SERIAL (schema.sql): los ids nuevos (clonar/crear) salen
    // siempre de esta secuencia, igual que el servicio Python que este controller reemplaza.
    // ADO.NET puro (no SqlQuery<T> de EF) para no depender de convenciones de mapeo de
    // columnas escalares que varían entre versiones de EF Core.
    private async Task<int> SiguienteIdClonAsync(CancellationToken cancellationToken)
    {
        var connection = _db.Database.GetDbConnection();
        if (connection.State != ConnectionState.Open)
        {
            await connection.OpenAsync(cancellationToken);
        }

        using var command = connection.CreateCommand();
        command.CommandText = "SELECT nextval('taxonomia_titulos_clon_seq')";
        var resultado = await command.ExecuteScalarAsync(cancellationToken);
        return Convert.ToInt32(resultado);
    }
}
