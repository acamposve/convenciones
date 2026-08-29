using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Controllers;

public record LoginRequest(string Email, string Password);
public record LoginResponse(string? AccessToken, string? RefreshToken, bool ResetRequired, string? ResetToken);
public record ResetPasswordRequest(string ResetToken, string NewPassword);
public record RefreshRequest(string RefreshToken);

[ApiController]
[Route("api/auth")]
public class AuthController : ControllerBase
{
    private readonly ComparadorDbContext _db;
    private readonly TokenService _tokens;

    public AuthController(ComparadorDbContext db, TokenService tokens)
    {
        _db = db;
        _tokens = tokens;
    }

    // Flujo completo en auth-spec.md §4.
    [HttpPost("login")]
    public async Task<ActionResult<LoginResponse>> Login(LoginRequest req)
    {
        var usuario = await _db.Usuarios
            .Include(u => u.Tenant)
            .ThenInclude(t => t!.Pais)
            .FirstOrDefaultAsync(u => u.Email == req.Email && u.Activo);

        var ip = HttpContext.Connection.RemoteIpAddress?.ToString();
        var userAgent = Request.Headers.UserAgent.ToString();

        if (usuario == null || !BCrypt.Net.BCrypt.Verify(req.Password, usuario.PasswordHash))
        {
            await Bitacora(null, null, "login_fail", ip, userAgent);
            return Unauthorized(new { message = "Credenciales inválidas." });
        }

        // Fase 5 (spec-plataforma.md): un tenant suspendido no puede iniciar sesion nueva
        // -- no revoca sesiones ya emitidas (el access token expira solo, Art VII), mismo
        // criterio de granularidad que ya usa el check de usuario.Activo mas abajo.
        if (usuario.Tenant?.Suspendido == true)
        {
            await Bitacora(usuario.Id, usuario.TenantId, "login_fail", ip, userAgent);
            return Unauthorized(new { message = "Este operador está suspendido. Contactá a soporte." });
        }

        // Art. VI.4: usuarios migrados del legado nunca tienen password_hash reutilizable;
        // arrancan con requiere_reset_password = true y no reciben sesión completa, solo un
        // token de un solo uso para /api/auth/reset-password.
        if (usuario.RequiereResetPassword)
        {
            var (resetRaw, resetHash) = _tokens.GenerarResetToken();
            _db.ResetPasswordTokens.Add(new ResetPasswordToken
            {
                Id = Guid.NewGuid(),
                UsuarioId = usuario.Id,
                TokenHash = resetHash,
                ExpiraAt = DateTimeOffset.UtcNow.AddMinutes(30),
            });
            await _db.SaveChangesAsync();
            await Bitacora(usuario.Id, usuario.TenantId, "login_ok_reset_pending", ip, userAgent);
            return Ok(new LoginResponse(null, null, true, resetRaw));
        }

        var paisCodigo = ResolverPaisCodigo(usuario);
        var access = _tokens.GenerarAccessToken(usuario, paisCodigo);
        var (refreshRaw, refreshHash) = _tokens.GenerarRefreshToken();

        _db.RefreshTokens.Add(new RefreshToken
        {
            Id = Guid.NewGuid(),
            UsuarioId = usuario.Id,
            TokenHash = refreshHash,
            ExpiraAt = DateTimeOffset.UtcNow.AddDays(7),
        });
        usuario.UltimoLoginAt = DateTimeOffset.UtcNow;
        await _db.SaveChangesAsync();
        await Bitacora(usuario.Id, usuario.TenantId, "login_ok", ip, userAgent);

        return Ok(new LoginResponse(access, refreshRaw, false, null));
    }

    [HttpPost("reset-password")]
    public async Task<IActionResult> ResetPassword(ResetPasswordRequest req)
    {
        var hash = _tokens.HashToken(req.ResetToken);
        var token = await _db.ResetPasswordTokens
            .Include(t => t.Usuario)
            .FirstOrDefaultAsync(t => t.TokenHash == hash);

        if (token == null || token.Usado || token.ExpiraAt < DateTimeOffset.UtcNow)
        {
            return Unauthorized(new { message = "Token de reseteo invalido o expirado." });
        }

        var usuario = token.Usuario!;
        usuario.PasswordHash = BCrypt.Net.BCrypt.HashPassword(req.NewPassword);
        usuario.RequiereResetPassword = false;
        token.Usado = true;

        await _db.SaveChangesAsync();
        await Bitacora(usuario.Id, usuario.TenantId, "reset_password", null, null);

        return Ok(new { message = "Password actualizada. Iniciar sesión nuevamente." });
    }

    [HttpPost("refresh")]
    public async Task<ActionResult<LoginResponse>> Refresh(RefreshRequest req)
    {
        var hash = _tokens.HashToken(req.RefreshToken);
        var refresh = await _db.RefreshTokens
            .Include(r => r.Usuario)
            .ThenInclude(u => u!.Tenant)
            .ThenInclude(t => t!.Pais)
            .FirstOrDefaultAsync(r => r.TokenHash == hash);

        if (refresh == null || refresh.Revocado || refresh.ExpiraAt < DateTimeOffset.UtcNow)
        {
            return Unauthorized(new { message = "Refresh token invalido, revocado o expirado." });
        }

        var usuario = refresh.Usuario!;
        if (!usuario.Activo)
        {
            return Unauthorized(new { message = "Usuario inactivo." });
        }
        if (usuario.Tenant?.Suspendido == true)
        {
            return Unauthorized(new { message = "Este operador está suspendido. Contactá a soporte." });
        }

        // Rotacion: el refresh token usado se revoca y se emite uno nuevo, para que un
        // token robado y ya usado por el legitimo deje de servir.
        refresh.Revocado = true;
        var (newRefreshRaw, newRefreshHash) = _tokens.GenerarRefreshToken();
        _db.RefreshTokens.Add(new RefreshToken
        {
            Id = Guid.NewGuid(),
            UsuarioId = usuario.Id,
            TokenHash = newRefreshHash,
            ExpiraAt = DateTimeOffset.UtcNow.AddDays(7),
        });

        var paisCodigo = ResolverPaisCodigo(usuario);
        var access = _tokens.GenerarAccessToken(usuario, paisCodigo);

        await _db.SaveChangesAsync();

        return Ok(new LoginResponse(access, newRefreshRaw, false, null));
    }

    // Fase 5 (spec-plataforma.md): un usuario de Plataforma (TenantId null) no tiene pais
    // propio -- antes esto explotaba con InvalidOperationException para cualquier usuario
    // sin tenant resuelto, que ahora es un caso valido en vez de un bug de datos.
    private static string? ResolverPaisCodigo(Usuario usuario)
    {
        if (usuario.TenantId is null)
        {
            return null;
        }
        return usuario.Tenant?.Pais?.Codigo
            ?? throw new InvalidOperationException($"Tenant {usuario.TenantId} sin pais resuelto.");
    }

    private async Task Bitacora(Guid? usuarioId, Guid? tenantId, string evento, string? ip, string? userAgent)
    {
        await _db.Database.ExecuteSqlInterpolatedAsync($@"
            INSERT INTO bitacora_accesos (usuario_id, tenant_id, evento, ip, user_agent)
            VALUES ({usuarioId}, {tenantId}, {evento}, {ip}, {userAgent})");
    }
}
