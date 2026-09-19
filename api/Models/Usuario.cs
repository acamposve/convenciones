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

public class BitacoraAcceso
{
    public long Id { get; set; }
    public Guid? UsuarioId { get; set; }
    public Guid? TenantId { get; set; }
    public string Evento { get; set; } = default!;
    public string? Ip { get; set; }
    public string? UserAgent { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Usuario? Usuario { get; set; }
    public Tenant? Tenant { get; set; }
}

public class TaxonomiaCategoria
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
    public bool RequiereCampoComparacionEconomica { get; set; }
}

public class TaxonomiaTitulo
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
    public int CategoriaId { get; set; }
    public int PaisId { get; set; }
    public bool Activo { get; set; }

    public TaxonomiaCategoria? Categoria { get; set; }
    public Pais? Pais { get; set; }
}

public class Ley
{
    public int Id { get; set; }
    public int PaisId { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Gaceta { get; set; }
    public DateOnly? FechaPublicacion { get; set; }

    public Pais? Pais { get; set; }
}

public class ArticuloLey
{
    public int Id { get; set; }
    public int LeyId { get; set; }
    public int NroArticulo { get; set; }
    public string? TituloArticulo { get; set; }
    public string TextoCompleto { get; set; } = default!;

    public Ley? Ley { get; set; }
}

public class TituloArticuloLey
{
    public int TituloId { get; set; }
    public int ArticuloLeyId { get; set; }

    public TaxonomiaTitulo? Titulo { get; set; }
    public ArticuloLey? ArticuloLey { get; set; }
}

public class Sector
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
}

public class TipoEmpresa
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
}

public class CategoriaSector
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
}

public class ActividadEmpresa
{
    public int Id { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Descripcion { get; set; }
}

public class Estado
{
    public int Id { get; set; }
    public int PaisId { get; set; }
    public string Nombre { get; set; } = default!;

    public Pais? Pais { get; set; }
}

public class Localidad
{
    public int Id { get; set; }
    public int EstadoId { get; set; }
    public string Nombre { get; set; } = default!;

    public Estado? Estado { get; set; }
}

public class Empresa
{
    public Guid Id { get; set; }
    public Guid TenantId { get; set; }
    public int PaisId { get; set; }
    public string Nombre { get; set; } = default!;
    public string? Rif { get; set; }
    public int? SectorId { get; set; }
    public int? TipoId { get; set; }
    public int? CategoriaId { get; set; }
    public int? ActividadId { get; set; }
    public int? EstadoId { get; set; }
    public int? LocalidadId { get; set; }
    public string? ContactoNombre { get; set; }
    public string? ContactoEmail { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Tenant? Tenant { get; set; }
    public Pais? Pais { get; set; }
    public Sector? Sector { get; set; }
    public TipoEmpresa? TipoEmpresa { get; set; }
    public CategoriaSector? CategoriaSector { get; set; }
    public ActividadEmpresa? ActividadEmpresa { get; set; }
    public Estado? Estado { get; set; }
    public Localidad? Localidad { get; set; }
}

public class Negociacion
{
    public Guid Id { get; set; }
    public Guid TenantId { get; set; }
    public Guid EmpresaId { get; set; }
    public string Estado { get; set; } = default!;
    public DateTimeOffset FechaInicio { get; set; }
    public DateTimeOffset? FechaCierre { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Tenant? Tenant { get; set; }
    public Empresa? Empresa { get; set; }
}

public class Peticion
{
    public int Id { get; set; }
    public Guid NegociacionId { get; set; }
    public int? TituloId { get; set; }
    public int NroPeticion { get; set; }
    public string Texto { get; set; } = default!;
    public DateTimeOffset CreatedAt { get; set; }

    public Negociacion? Negociacion { get; set; }
    public TaxonomiaTitulo? Titulo { get; set; }
}

public class Oferta
{
    public int Id { get; set; }
    public int PeticionId { get; set; }
    public string Texto { get; set; } = default!;
    public DateTimeOffset CreatedAt { get; set; }

    public Peticion? Peticion { get; set; }
}

public class Reunion
{
    public int Id { get; set; }
    public Guid NegociacionId { get; set; }
    public DateOnly Fecha { get; set; }
    public string? Asistentes { get; set; }
    public string? Resumen { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Negociacion? Negociacion { get; set; }
}

public class Acuerdo
{
    public int Id { get; set; }
    public Guid NegociacionId { get; set; }
    public int TituloId { get; set; }
    public string TextoAcordado { get; set; } = default!;
    public int? PeticionId { get; set; }
    public int? OfertaId { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Negociacion? Negociacion { get; set; }
    public TaxonomiaTitulo? Titulo { get; set; }
    public Peticion? Peticion { get; set; }
    public Oferta? Oferta { get; set; }
}

public class BitacoraNegociacion
{
    public int Id { get; set; }
    public Guid NegociacionId { get; set; }
    public string Evento { get; set; } = default!;
    public Guid? UsuarioId { get; set; }
    public string? Detalle { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Negociacion? Negociacion { get; set; }
    public Usuario? Usuario { get; set; }
}

public class Documento
{
    public int Id { get; set; }
    public Guid TenantId { get; set; }
    public Guid EmpresaId { get; set; }
    public string Origen { get; set; } = default!;
    public string? UrlOrigen { get; set; }
    public string? RutaArchivo { get; set; }
    public bool EsPublico { get; set; }
    public string Estado { get; set; } = default!;
    public string? EstadoDetalle { get; set; }
    public Guid? NegociacionId { get; set; }
    public int? VersionNegociacion { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Tenant? Tenant { get; set; }
    public Empresa? Empresa { get; set; }
    public Negociacion? Negociacion { get; set; }
}

public class BibliotecaPublicaEntry
{
    public string EmpresaNombre { get; set; } = default!;
    public string? UrlOrigen { get; set; }
    public DateTimeOffset CreatedAt { get; set; }
}

public class Clausula
{
    public int Id { get; set; }
    public int DocumentoId { get; set; }
    public Guid TenantId { get; set; }
    public string Texto { get; set; } = default!;
    public int? TituloId { get; set; }
    public int? CategoriaId { get; set; }
    public int Orden { get; set; }
    public string? Confianza { get; set; }
    public string EstadoRevision { get; set; } = default!;
    public Guid? RevisadoPor { get; set; }
    public DateTimeOffset? RevisadoAt { get; set; }
    public string? CumplimientoLegal { get; set; }
    public string? CumplimientoJustificacion { get; set; }
    public string? CampoComparativo { get; set; }
    public string? ResumenEjecutivo { get; set; }
    public string EstadoRevisionResumen { get; set; } = default!;
    public Guid? RevisadoPorResumen { get; set; }
    public DateTimeOffset? RevisadoAtResumen { get; set; }
    public DateTimeOffset CreatedAt { get; set; }

    public Documento? Documento { get; set; }
    public Tenant? Tenant { get; set; }
    public TaxonomiaTitulo? Titulo { get; set; }
    public TaxonomiaCategoria? Categoria { get; set; }
    public Usuario? RevisadoPorUsuario { get; set; }
    public Usuario? RevisadoPorResumenUsuario { get; set; }
}
