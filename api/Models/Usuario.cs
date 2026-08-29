namespace Comparador.Api.Models;

public enum RolUsuario
{
    AdminTenant,
    Revisor,
    Editor,
    Visualizador,
    // Fase 5 (spec-plataforma.md): usuarios sin tenant (TenantId null) que administran
    // operadores despues de creados -- ver AuthorizationPolicies para la matriz de permisos.
    PlataformaAdmin,
    PlataformaSoporte,
    PlataformaAuditor
}

public class Pais
{
    public int Id { get; set; }
    public string Codigo { get; set; } = default!;
    public string Nombre { get; set; } = default!;
    public bool Activo { get; set; }
}

public class Tenant
{
    public Guid Id { get; set; }

    // Mapea a la columna real "nombre_empresa" (service/db/schema.sql) — el resto del
    // sistema (servicio de ingesta en Python, UI) ya usa ese nombre para la misma tabla.
    public string NombreEmpresa { get; set; } = default!;
    public int PaisId { get; set; }
    public string PlanLicencia { get; set; } = "trial";
    public DateOnly? FechaVencimiento { get; set; }

    // Fase 5: Plataforma suspende un operador (licencia vencida, incumplimiento) sin
    // borrar sus datos -- se hace cumplir donde corresponda (Bloque C), no en el login en si.
    public bool Suspendido { get; set; }

    public Pais? Pais { get; set; }
}

public class Usuario
{
    public Guid Id { get; set; }

    // Fase 5: nullable -- NULL identifica a un usuario de Plataforma (PlataformaAdmin/
    // Soporte/Auditor), que por definicion no pertenece a ningun tenant (Art VII.4).
    public Guid? TenantId { get; set; }
    public string Email { get; set; } = default!;
    public string PasswordHash { get; set; } = default!;
    public RolUsuario Rol { get; set; }
    public bool Activo { get; set; } = true;

    // Art. VI.4 — nunca migramos passwords heredadas; todo usuario cargado por ETL
    // arranca en true hasta que resetee su propia contraseña.
    public bool RequiereResetPassword { get; set; } = true;

    public DateTimeOffset? UltimoLoginAt { get; set; }

    public Tenant? Tenant { get; set; }
}

// Fase 5 (spec-plataforma.md §4): que paises tiene habilitados cada tenant segun su
// licencia -- independiente del flip global Pais.Activo (el gate legal de Art II.4).
// Clave compuesta (TenantId, PaisId), configurada en ComparadorDbContext.
public class TenantPaisHabilitado
{
    public Guid TenantId { get; set; }
    public int PaisId { get; set; }

    public Tenant? Tenant { get; set; }
    public Pais? Pais { get; set; }
}

public class RefreshToken
{
    public Guid Id { get; set; }
    public Guid UsuarioId { get; set; }
    public string TokenHash { get; set; } = default!;
    public DateTimeOffset ExpiraAt { get; set; }
    public bool Revocado { get; set; }

    public Usuario? Usuario { get; set; }
}

// Resuelve el placeholder de reset-password (README del scaffold, punto 1): token de un
// solo uso, distinto e independiente del refresh_token de sesion.
public class ResetPasswordToken
{
    public Guid Id { get; set; }
    public Guid UsuarioId { get; set; }
    public string TokenHash { get; set; } = default!;
    public DateTimeOffset ExpiraAt { get; set; }
    public bool Usado { get; set; }

    public Usuario? Usuario { get; set; }
}
