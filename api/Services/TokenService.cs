using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;
using Comparador.Api.Models;
using Microsoft.IdentityModel.Tokens;

namespace Comparador.Api.Services;

public class TokenService
{
    private readonly IConfiguration _config;

    public TokenService(IConfiguration config)
    {
        _config = config;
    }

    // Claims mínimos según auth-spec.md §2: user_id, tenant_id, role, pais.
    // El tenant_id acá es la única fuente de verdad para el filtro de aislamiento (Art. VI.2) —
    // nunca se lee tenant_id de query params ni de body.
    //
    // Fase 5 (spec-plataforma.md): un usuario de Plataforma no tiene tenant ni pais propio
    // -- paisCodigo llega null en ese caso. Omitimos ambos claims en vez de emitir un string
    // vacio (Nullable<Guid>.ToString() da "", que un consumidor descuidado podria confundir
    // con un tenant_id real); el middleware de Program.cs y require_role() del lado Python
    // ya tratan la ausencia del claim como "sin tenant", no como string vacio.
    public string GenerarAccessToken(Usuario usuario, string? paisCodigo)
    {
        var claims = new List<Claim>
        {
            new Claim("user_id", usuario.Id.ToString()),
            new Claim(ClaimTypes.Role, usuario.Rol.ToString()),
        };
        if (usuario.TenantId is { } tenantId)
        {
            claims.Add(new Claim("tenant_id", tenantId.ToString()));
        }
        if (paisCodigo != null)
        {
            claims.Add(new Claim("pais", paisCodigo));
        }

        var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_config["Jwt:SigningKey"]!));
        var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);
        var minutes = int.Parse(_config["Jwt:AccessTokenMinutes"] ?? "15");

        var token = new JwtSecurityToken(
            issuer: _config["Jwt:Issuer"],
            audience: _config["Jwt:Audience"],
            claims: claims,
            expires: DateTime.UtcNow.AddMinutes(minutes),
            signingCredentials: creds);

        return new JwtSecurityTokenHandler().WriteToken(token);
    }

    public (string raw, string hash) GenerarRefreshToken() => GenerarTokenOpaco();

    // Token de un solo uso para /api/auth/reset-password (README del scaffold, punto 1):
    // mismo mecanismo que el refresh token (aleatorio, se guarda solo el hash), pero en su
    // propia tabla reset_password_tokens, con su propio TTL corto e independiente de la sesion.
    public (string raw, string hash) GenerarResetToken() => GenerarTokenOpaco();

    public string HashToken(string raw) =>
        Convert.ToBase64String(SHA256.HashData(Encoding.UTF8.GetBytes(raw)));

    private (string raw, string hash) GenerarTokenOpaco()
    {
        var bytes = RandomNumberGenerator.GetBytes(64);
        var raw = Convert.ToBase64String(bytes);
        return (raw, HashToken(raw));
    }
}
