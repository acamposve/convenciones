using Comparador.Api.Data;
using Comparador.Api.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public sealed record CreateTenantRequest(string NombreEmpresa, string Email, string Password);

[ApiController]
[Route("")]
public class TenantsController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public TenantsController(ComparadorDbContext db)
    {
        _db = db;
    }

    [HttpPost("tenants")]
    public async Task<IActionResult> Create([FromForm] CreateTenantRequest request, CancellationToken cancellationToken)
    {
        if (string.IsNullOrWhiteSpace(request.NombreEmpresa))
        {
            return UnprocessableEntity(new { detail = "Debe indicar el nombre de la empresa." });
        }

        if (!request.Email.Contains('@', StringComparison.Ordinal))
        {
            return UnprocessableEntity(new { detail = "email invalido" });
        }

        if (request.Password.Length < 8)
        {
            return UnprocessableEntity(new { detail = "la contraseña debe tener al menos 8 caracteres" });
        }

        var pais = await _db.Paises
            .SingleOrDefaultAsync(p => p.Codigo == "VE", cancellationToken);
        if (pais is null)
        {
            return Problem(statusCode: StatusCodes.Status503ServiceUnavailable,
                detail: "Venezuela no está configurada en el catálogo de países.");
        }

        var tenant = new Tenant
        {
            Id = Guid.NewGuid(),
            NombreEmpresa = request.NombreEmpresa.Trim(),
            PaisId = pais.Id,
        };
        var usuario = new Usuario
        {
            Id = Guid.NewGuid(),
            TenantId = tenant.Id,
            Email = request.Email.Trim(),
            PasswordHash = BCrypt.Net.BCrypt.HashPassword(request.Password),
            Rol = RolUsuario.AdminTenant,
            RequiereResetPassword = false,
        };

        _db.Tenants.Add(tenant);
        _db.Usuarios.Add(usuario);
        _db.TenantPaisesHabilitados.Add(new TenantPaisHabilitado
        {
            TenantId = tenant.Id,
            PaisId = pais.Id,
        });

        try
        {
            await _db.SaveChangesAsync(cancellationToken);
        }
        catch (DbUpdateException)
        {
            return Conflict(new { detail = "Ya existe una cuenta con esos datos." });
        }

        return Created("/tenants", new
        {
            id = tenant.Id,
            nombre_empresa = tenant.NombreEmpresa,
            pais_id = tenant.PaisId,
            plan_licencia = tenant.PlanLicencia,
        });
    }
}