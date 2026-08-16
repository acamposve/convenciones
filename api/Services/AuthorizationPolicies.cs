using Microsoft.AspNetCore.Authorization;

namespace Comparador.Api.Services;

// Matriz de permisos: auth-spec.md §5.
// Estas políticas son el "candado" que hace cumplir en código el Art. IV.8:
// solo AdminTenant y Revisor pueden aprobar/corregir una cláusula, sin excepción vía API.
public static class AuthorizationPolicies
{
    public const string PuedeAprobarClausula = "PuedeAprobarClausula";
    public const string PuedeCargarDocumento = "PuedeCargarDocumento";
    public const string PuedeGestionarUsuarios = "PuedeGestionarUsuarios";
    public const string PuedeExportarPdf = "PuedeExportarPdf";

    public static void Configurar(AuthorizationOptions options)
    {
        options.AddPolicy(PuedeAprobarClausula, p =>
            p.RequireRole("AdminTenant", "Revisor"));

        options.AddPolicy(PuedeCargarDocumento, p =>
            p.RequireRole("AdminTenant", "Editor"));

        options.AddPolicy(PuedeGestionarUsuarios, p =>
            p.RequireRole("AdminTenant"));

        options.AddPolicy(PuedeExportarPdf, p =>
            p.RequireRole("AdminTenant", "Revisor", "Editor"));
    }
}
