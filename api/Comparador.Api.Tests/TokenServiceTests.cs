using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.Extensions.Configuration;

namespace Comparador.Api.Tests;

public class TokenServiceTests
{
    private static TokenService BuildService(string? accessTokenMinutes = null)
    {
        var values = new Dictionary<string, string?>
        {
            ["Jwt:SigningKey"] = "test-signing-key-at-least-32-bytes-long!!",
            ["Jwt:Issuer"] = "comparador-api",
            ["Jwt:Audience"] = "comparador-web",
        };
        if (accessTokenMinutes != null)
        {
            values["Jwt:AccessTokenMinutes"] = accessTokenMinutes;
        }

        var config = new ConfigurationBuilder().AddInMemoryCollection(values).Build();
        return new TokenService(config);
    }

    private static Usuario BuildUsuario() => new()
    {
        Id = Guid.NewGuid(),
        TenantId = Guid.NewGuid(),
        Email = "admin@empresademo.local",
        PasswordHash = "irrelevante-para-este-test",
        Rol = RolUsuario.AdminTenant,
    };

    // Claims minimos segun auth-spec.md §2: user_id, tenant_id, role, pais. El middleware
    // de aislamiento por tenant (Program.cs, Art VI.2) depende de que "tenant_id" este
    // presente con ese nombre exacto de claim.
    [Fact]
    public void GenerarAccessToken_IncluyeLosClaimsMinimosDeAuthSpec()
    {
        var tokens = BuildService();
        var usuario = BuildUsuario();

        var jwt = tokens.GenerarAccessToken(usuario, "VE");

        var token = new JwtSecurityTokenHandler().ReadJwtToken(jwt);
        Assert.Equal(usuario.Id.ToString(), token.Claims.Single(c => c.Type == "user_id").Value);
        Assert.Equal(usuario.TenantId.ToString(), token.Claims.Single(c => c.Type == "tenant_id").Value);
        Assert.Equal("AdminTenant", token.Claims.Single(c => c.Type == ClaimTypes.Role).Value);
        Assert.Equal("VE", token.Claims.Single(c => c.Type == "pais").Value);
        Assert.Equal("comparador-api", token.Issuer);
        Assert.Contains("comparador-web", token.Audiences);
    }

    [Fact]
    public void GenerarAccessToken_ExpiraSegunJwtAccessTokenMinutes()
    {
        var tokens = BuildService(accessTokenMinutes: "5");
        var usuario = BuildUsuario();

        var antesDeGenerar = DateTime.UtcNow;
        var jwt = tokens.GenerarAccessToken(usuario, "VE");
        var token = new JwtSecurityTokenHandler().ReadJwtToken(jwt);

        // GenerarAccessToken no fija "notBefore", asi que ValidFrom no es confiable —
        // se mide la expiracion contra el reloj, no contra ValidFrom.
        var minutosHastaExpirar = (token.ValidTo - antesDeGenerar).TotalMinutes;
        Assert.True(Math.Abs(minutosHastaExpirar - 5) < 0.5,
            $"Se esperaban ~5 minutos hasta expirar, se obtuvieron {minutosHastaExpirar}");
    }

    [Fact]
    public void HashToken_EsDeterministicoYCoincideConSha256()
    {
        var tokens = BuildService();
        const string raw = "un-valor-cualquiera-para-hashear";

        var hash1 = tokens.HashToken(raw);
        var hash2 = tokens.HashToken(raw);
        var esperado = Convert.ToBase64String(SHA256.HashData(Encoding.UTF8.GetBytes(raw)));

        Assert.Equal(hash1, hash2);
        Assert.Equal(esperado, hash1);
    }

    // El refresh token y el reset token (AuthController.cs) solo guardan el hash en DB —
    // si el hash no coincide con HashToken(raw), la validacion en /api/auth/refresh y
    // /api/auth/reset-password nunca podria encontrar el token por su hash.
    [Fact]
    public void GenerarRefreshToken_ElHashDevueltoCoincideConHashTokenDelRaw()
    {
        var tokens = BuildService();

        var (raw, hash) = tokens.GenerarRefreshToken();

        Assert.NotEmpty(raw);
        Assert.Equal(tokens.HashToken(raw), hash);
    }

    [Fact]
    public void GenerarRefreshToken_GeneraValoresUnicosEnCadaLlamada()
    {
        var tokens = BuildService();

        var (raw1, hash1) = tokens.GenerarRefreshToken();
        var (raw2, hash2) = tokens.GenerarRefreshToken();

        Assert.NotEqual(raw1, raw2);
        Assert.NotEqual(hash1, hash2);
    }

    // GenerarResetToken usa el mismo mecanismo opaco que GenerarRefreshToken, pero para
    // /api/auth/reset-password — deben ser indistinguibles en formato aunque su tabla
    // de destino (reset_password_tokens vs refresh_tokens) sea distinta.
    [Fact]
    public void GenerarResetToken_UsaElMismoMecanismoOpacoQueRefreshToken()
    {
        var tokens = BuildService();

        var (raw, hash) = tokens.GenerarResetToken();

        Assert.NotEmpty(raw);
        Assert.Equal(tokens.HashToken(raw), hash);
    }
}
