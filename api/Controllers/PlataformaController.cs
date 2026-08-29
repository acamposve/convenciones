using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public record TenantResumen(
    Guid Id, string NombreEmpresa, string PlanLicencia, DateOnly? FechaVencimiento,
    bool Suspendido, List<string> PaisesHabilitados);
public record EditarLicenciaRequest(string PlanLicencia, DateOnly? FechaVencimiento);
public record PaisResumen(int Id, string Codigo, string Nombre, bool Activo);
public record ActivarPaisRequest(bool Activo);
public record CrearUsuarioPlataformaRequest(string Email, string Password, string Rol);

// Panel de Plataforma (Fase 5, spec-plataforma.md §5/§6 Bloque C): gestion de tenants
// despues de creados -- el alta en si es self-service (AuthController/service Python), sin
// intervencion de Plataforma. Cada accion esta gateada por su propia politica de
// AuthorizationPolicies, no por un [Authorize] generico a nivel de controller, para que la
// matriz de permisos (PlataformaAdmin/Soporte/Auditor) sea explicita por endpoint.
[ApiController]
[Route("api/plataforma")]
public class PlataformaController : ControllerBase
{
    private readonly ComparadorDbContext _db;

    public PlataformaController(ComparadorDbContext db)
    {
        _db = db;
    }

    [HttpGet("tenants")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerPlataforma)]
    public async Task<ActionResult<List<TenantResumen>>> ListarTenants()
    {
        var tenants = await _db.Tenants.OrderBy(t => t.NombreEmpresa).ToListAsync();
        var habilitados = await _db.TenantPaisesHabilitados.Include(tph => tph.Pais).ToListAsync();
        var porTenant = habilitados
            .GroupBy(h => h.TenantId)
            .ToDictionary(g => g.Key, g => g.Select(h => h.Pais!.Codigo).OrderBy(c => c).ToList());

        return tenants.Select(t => new TenantResumen(
            t.Id, t.NombreEmpresa, t.PlanLicencia, t.FechaVencimiento, t.Suspendido,
            porTenant.GetValueOrDefault(t.Id, new List<string>())
        )).ToList();
    }

    [HttpPut("tenants/{id}/licencia")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarLicenciaTenant)]
    public async Task<IActionResult> EditarLicencia(Guid id, EditarLicenciaRequest req)
    {
        var tenant = await _db.Tenants.FindAsync(id);
        if (tenant == null) return NotFound();

        tenant.PlanLicencia = req.PlanLicencia;
        tenant.FechaVencimiento = req.FechaVencimiento;
        await _db.SaveChangesAsync();
        return Ok();
    }

    [HttpPost("tenants/{id}/paises/{paisId}")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarLicenciaTenant)]
    public async Task<IActionResult> HabilitarPais(Guid id, int paisId)
    {
        var yaHabilitado = await _db.TenantPaisesHabilitados
            .AnyAsync(tph => tph.TenantId == id && tph.PaisId == paisId);
        if (!yaHabilitado)
        {
            _db.TenantPaisesHabilitados.Add(new TenantPaisHabilitado { TenantId = id, PaisId = paisId });
            await _db.SaveChangesAsync();
        }
        return Ok();
    }

    [HttpDelete("tenants/{id}/paises/{paisId}")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarLicenciaTenant)]
    public async Task<IActionResult> DeshabilitarPais(Guid id, int paisId)
    {
        var fila = await _db.TenantPaisesHabilitados.FindAsync(id, paisId);
        if (fila != null)
        {
            _db.TenantPaisesHabilitados.Remove(fila);
            await _db.SaveChangesAsync();
        }
        return Ok();
    }

    [HttpPost("tenants/{id}/suspender")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarLicenciaTenant)]
    public Task<IActionResult> Suspender(Guid id) => CambiarSuspension(id, suspendido: true);

    [HttpPost("tenants/{id}/reactivar")]
    [Authorize(Policy = AuthorizationPolicies.PuedeEditarLicenciaTenant)]
    public Task<IActionResult> Reactivar(Guid id) => CambiarSuspension(id, suspendido: false);

    private async Task<IActionResult> CambiarSuspension(Guid id, bool suspendido)
    {
        var tenant = await _db.Tenants.FindAsync(id);
        if (tenant == null) return NotFound();

        tenant.Suspendido = suspendido;
        await _db.SaveChangesAsync();
        return Ok();
    }

    [HttpGet("paises")]
    [Authorize(Policy = AuthorizationPolicies.PuedeVerPlataforma)]
    public async Task<ActionResult<List<PaisResumen>>> ListarPaises()
    {
        var paises = await _db.Paises.OrderBy(p => p.Nombre).ToListAsync();
        return paises.Select(p => new PaisResumen(p.Id, p.Codigo, p.Nombre, p.Activo)).ToList();
    }

    // El flip global (Art II.4): confirma que ya existe validacion legal de un abogado
    // laboral local para este pais, en absoluto -- no tiene relacion con si un tenant en
    // particular lo pago (eso es tenant_paises_habilitados, arriba). Reservado a
    // PlataformaAdmin porque es la puerta legal, no una decision comercial del dia a dia.
    [HttpPut("paises/{id}/activo")]
    [Authorize(Policy = AuthorizationPolicies.PuedeActivarPaisGlobal)]
    public async Task<IActionResult> ActivarPaisGlobal(int id, ActivarPaisRequest req)
    {
        var pais = await _db.Paises.FindAsync(id);
        if (pais == null) return NotFound();

        pais.Activo = req.Activo;
        await _db.SaveChangesAsync();
        return Ok();
    }

    [HttpPost("usuarios")]
    [Authorize(Policy = AuthorizationPolicies.PuedeCrearUsuarioPlataforma)]
    public async Task<IActionResult> CrearUsuarioPlataforma(CrearUsuarioPlataformaRequest req)
    {
        var rolesPlataforma = new[] { RolUsuario.PlataformaAdmin, RolUsuario.PlataformaSoporte, RolUsuario.PlataformaAuditor };
        if (!Enum.TryParse<RolUsuario>(req.Rol, out var rol) || !rolesPlataforma.Contains(rol))
        {
            return BadRequest(new { message = "Rol inválido para un usuario de Plataforma." });
        }

        var yaExiste = await _db.Usuarios.AnyAsync(u => u.TenantId == null && u.Email == req.Email);
        if (yaExiste)
        {
            return Conflict(new { message = "Ya existe un usuario de Plataforma con ese email." });
        }

        _db.Usuarios.Add(new Usuario
        {
            Id = Guid.NewGuid(),
            TenantId = null,
            Email = req.Email,
            PasswordHash = BCrypt.Net.BCrypt.HashPassword(req.Password),
            Rol = rol,
            // Lo crea otro usuario de Plataforma (PlataformaAdmin) a nombre de un tercero,
            // igual que seed_admin_user.py -- por eso sí requiere reset, a diferencia del
            // registro self-service donde el usuario elige su propia contraseña.
            RequiereResetPassword = true,
        });
        await _db.SaveChangesAsync();
        return Created(string.Empty, new { message = "Usuario de Plataforma creado." });
    }
}
