using Comparador.Api.Models;
using Microsoft.EntityFrameworkCore;

namespace Comparador.Api.Data;

public class ComparadorDbContext : DbContext
{
    public ComparadorDbContext(DbContextOptions<ComparadorDbContext> options) : base(options) { }

    public DbSet<Pais> Paises => Set<Pais>();
    public DbSet<Tenant> Tenants => Set<Tenant>();
    public DbSet<Usuario> Usuarios => Set<Usuario>();
    public DbSet<RefreshToken> RefreshTokens => Set<RefreshToken>();
    public DbSet<ResetPasswordToken> ResetPasswordTokens => Set<ResetPasswordToken>();
    public DbSet<TenantPaisHabilitado> TenantPaisesHabilitados => Set<TenantPaisHabilitado>();
    public DbSet<BitacoraAcceso> BitacoraAccesos => Set<BitacoraAcceso>();
    public DbSet<TaxonomiaCategoria> TaxonomiaCategorias => Set<TaxonomiaCategoria>();
    public DbSet<TaxonomiaTitulo> TaxonomiaTitulos => Set<TaxonomiaTitulo>();
    public DbSet<Ley> Leyes => Set<Ley>();
    public DbSet<ArticuloLey> ArticulosLey => Set<ArticuloLey>();
    public DbSet<TituloArticuloLey> TituloArticuloLey => Set<TituloArticuloLey>();
    public DbSet<Sector> Sectores => Set<Sector>();
    public DbSet<TipoEmpresa> TiposEmpresa => Set<TipoEmpresa>();
    public DbSet<CategoriaSector> CategoriasSector => Set<CategoriaSector>();
    public DbSet<ActividadEmpresa> ActividadesEmpresa => Set<ActividadEmpresa>();
    public DbSet<Estado> Estados => Set<Estado>();
    public DbSet<Localidad> Localidades => Set<Localidad>();
    public DbSet<Empresa> Empresas => Set<Empresa>();
    public DbSet<Negociacion> Negociaciones => Set<Negociacion>();
    public DbSet<Peticion> Peticiones => Set<Peticion>();
    public DbSet<Oferta> Ofertas => Set<Oferta>();
    public DbSet<Reunion> Reuniones => Set<Reunion>();
    public DbSet<Acuerdo> Acuerdos => Set<Acuerdo>();
    public DbSet<BitacoraNegociacion> BitacoraNegociaciones => Set<BitacoraNegociacion>();
    public DbSet<Documento> Documentos => Set<Documento>();
    public DbSet<Clausula> Clausulas => Set<Clausula>();

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.HasPostgresEnum<RolUsuario>("rol_usuario");

        modelBuilder.Entity<Pais>().ToTable("paises");
        modelBuilder.Entity<Tenant>().ToTable("tenants");
        modelBuilder.Entity<Usuario>().ToTable("usuarios");
        modelBuilder.Entity<RefreshToken>().ToTable("refresh_tokens");
        modelBuilder.Entity<ResetPasswordToken>().ToTable("reset_password_tokens");
        modelBuilder.Entity<TenantPaisHabilitado>().ToTable("tenant_paises_habilitados");
        modelBuilder.Entity<BitacoraAcceso>().ToTable("bitacora_accesos");
        modelBuilder.Entity<TaxonomiaCategoria>().ToTable("taxonomia_categorias");
        modelBuilder.Entity<TaxonomiaTitulo>().ToTable("taxonomia_titulos");
        modelBuilder.Entity<Ley>().ToTable("leyes");
        modelBuilder.Entity<ArticuloLey>().ToTable("articulos_ley");
        modelBuilder.Entity<TituloArticuloLey>().ToTable("titulo_articulo_ley");
        modelBuilder.Entity<Sector>().ToTable("sectores");
        modelBuilder.Entity<TipoEmpresa>().ToTable("tipos_empresa");
        modelBuilder.Entity<CategoriaSector>().ToTable("categorias_sector");
        modelBuilder.Entity<ActividadEmpresa>().ToTable("actividades_empresa");
        modelBuilder.Entity<Estado>().ToTable("estados");
        modelBuilder.Entity<Localidad>().ToTable("localidades");
        modelBuilder.Entity<Empresa>().ToTable("empresas");
        modelBuilder.Entity<Negociacion>().ToTable("negociaciones");
        modelBuilder.Entity<Peticion>().ToTable("peticiones");
        modelBuilder.Entity<Oferta>().ToTable("ofertas");
        modelBuilder.Entity<Reunion>().ToTable("reuniones");
        modelBuilder.Entity<Acuerdo>().ToTable("acuerdos");
        modelBuilder.Entity<BitacoraNegociacion>().ToTable("bitacora_negociacion");
        modelBuilder.Entity<Documento>().ToTable("documentos");
        modelBuilder.Entity<Clausula>().ToTable("clausulas");

        modelBuilder.Entity<TenantPaisHabilitado>()
            .HasKey(tph => new { tph.TenantId, tph.PaisId });
        modelBuilder.Entity<TenantPaisHabilitado>()
            .HasOne(tph => tph.Tenant)
            .WithMany()
            .HasForeignKey(tph => tph.TenantId);
        modelBuilder.Entity<TenantPaisHabilitado>()
            .HasOne(tph => tph.Pais)
            .WithMany()
            .HasForeignKey(tph => tph.PaisId);

        modelBuilder.Entity<Tenant>()
            .HasOne(t => t.Pais)
            .WithMany()
            .HasForeignKey(t => t.PaisId);

        modelBuilder.Entity<Usuario>()
            .HasIndex(u => new { u.TenantId, u.Email })
            .IsUnique();
        modelBuilder.Entity<Usuario>()
            .HasIndex(u => u.Email)
            .IsUnique()
            .HasFilter("tenant_id IS NULL")
            .HasDatabaseName("idx_usuarios_email_plataforma");

        modelBuilder.Entity<BitacoraAcceso>()
            .HasOne(ba => ba.Usuario)
            .WithMany()
            .HasForeignKey(ba => ba.UsuarioId);
        modelBuilder.Entity<BitacoraAcceso>()
            .HasOne(ba => ba.Tenant)
            .WithMany()
            .HasForeignKey(ba => ba.TenantId);

        modelBuilder.Entity<TaxonomiaTitulo>()
            .HasOne(tt => tt.Categoria)
            .WithMany()
            .HasForeignKey(tt => tt.CategoriaId);
        modelBuilder.Entity<TaxonomiaTitulo>()
            .HasOne(tt => tt.Pais)
            .WithMany()
            .HasForeignKey(tt => tt.PaisId);

        modelBuilder.Entity<Ley>()
            .HasOne(l => l.Pais)
            .WithMany()
            .HasForeignKey(l => l.PaisId);

        modelBuilder.Entity<ArticuloLey>()
            .HasOne(al => al.Ley)
            .WithMany()
            .HasForeignKey(al => al.LeyId);

        modelBuilder.Entity<TituloArticuloLey>()
            .HasKey(tal => new { tal.TituloId, tal.ArticuloLeyId });
        modelBuilder.Entity<TituloArticuloLey>()
            .HasOne(tal => tal.Titulo)
            .WithMany()
            .HasForeignKey(tal => tal.TituloId);
        modelBuilder.Entity<TituloArticuloLey>()
            .HasOne(tal => tal.ArticuloLey)
            .WithMany()
            .HasForeignKey(tal => tal.ArticuloLeyId);

        modelBuilder.Entity<Estado>()
            .HasOne(e => e.Pais)
            .WithMany()
            .HasForeignKey(e => e.PaisId);

        modelBuilder.Entity<Localidad>()
            .HasOne(l => l.Estado)
            .WithMany()
            .HasForeignKey(l => l.EstadoId);

        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.Tenant)
            .WithMany()
            .HasForeignKey(e => e.TenantId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.Pais)
            .WithMany()
            .HasForeignKey(e => e.PaisId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.Sector)
            .WithMany()
            .HasForeignKey(e => e.SectorId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.TipoEmpresa)
            .WithMany()
            .HasForeignKey(e => e.TipoId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.CategoriaSector)
            .WithMany()
            .HasForeignKey(e => e.CategoriaId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.ActividadEmpresa)
            .WithMany()
            .HasForeignKey(e => e.ActividadId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.Estado)
            .WithMany()
            .HasForeignKey(e => e.EstadoId);
        modelBuilder.Entity<Empresa>()
            .HasOne(e => e.Localidad)
            .WithMany()
            .HasForeignKey(e => e.LocalidadId);

        modelBuilder.Entity<Negociacion>()
            .HasOne(n => n.Tenant)
            .WithMany()
            .HasForeignKey(n => n.TenantId);
        modelBuilder.Entity<Negociacion>()
            .HasOne(n => n.Empresa)
            .WithMany()
            .HasForeignKey(n => n.EmpresaId);

        modelBuilder.Entity<Peticion>()
            .HasOne(p => p.Negociacion)
            .WithMany()
            .HasForeignKey(p => p.NegociacionId);
        modelBuilder.Entity<Peticion>()
            .HasOne(p => p.Titulo)
            .WithMany()
            .HasForeignKey(p => p.TituloId);

        modelBuilder.Entity<Oferta>()
            .HasOne(o => o.Peticion)
            .WithMany()
            .HasForeignKey(o => o.PeticionId);

        modelBuilder.Entity<Reunion>()
            .HasOne(r => r.Negociacion)
            .WithMany()
            .HasForeignKey(r => r.NegociacionId);

        modelBuilder.Entity<Acuerdo>()
            .HasOne(a => a.Negociacion)
            .WithMany()
            .HasForeignKey(a => a.NegociacionId);
        modelBuilder.Entity<Acuerdo>()
            .HasOne(a => a.Titulo)
            .WithMany()
            .HasForeignKey(a => a.TituloId);
        modelBuilder.Entity<Acuerdo>()
            .HasOne(a => a.Peticion)
            .WithMany()
            .HasForeignKey(a => a.PeticionId);
        modelBuilder.Entity<Acuerdo>()
            .HasOne(a => a.Oferta)
            .WithMany()
            .HasForeignKey(a => a.OfertaId);

        modelBuilder.Entity<BitacoraNegociacion>()
            .HasOne(bn => bn.Negociacion)
            .WithMany()
            .HasForeignKey(bn => bn.NegociacionId);
        modelBuilder.Entity<BitacoraNegociacion>()
            .HasOne(bn => bn.Usuario)
            .WithMany()
            .HasForeignKey(bn => bn.UsuarioId);

        modelBuilder.Entity<Documento>()
            .HasOne(d => d.Tenant)
            .WithMany()
            .HasForeignKey(d => d.TenantId);
        modelBuilder.Entity<Documento>()
            .HasOne(d => d.Empresa)
            .WithMany()
            .HasForeignKey(d => d.EmpresaId);
        modelBuilder.Entity<Documento>()
            .HasOne(d => d.Negociacion)
            .WithMany()
            .HasForeignKey(d => d.NegociacionId);

        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.Documento)
            .WithMany()
            .HasForeignKey(c => c.DocumentoId);
        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.Tenant)
            .WithMany()
            .HasForeignKey(c => c.TenantId);
        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.Titulo)
            .WithMany()
            .HasForeignKey(c => c.TituloId);
        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.Categoria)
            .WithMany()
            .HasForeignKey(c => c.CategoriaId);
        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.RevisadoPorUsuario)
            .WithMany()
            .HasForeignKey(c => c.RevisadoPor);
        modelBuilder.Entity<Clausula>()
            .HasOne(c => c.RevisadoPorResumenUsuario)
            .WithMany()
            .HasForeignKey(c => c.RevisadoPorResumen);
    }
}
