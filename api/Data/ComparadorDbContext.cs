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

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        // Debe coincidir con el MapEnum<RolUsuario>("rol_usuario") registrado en Program.cs
        // sobre el NpgsqlDataSource — EF Core tambien necesita saberlo para su propio modelo.
        modelBuilder.HasPostgresEnum<RolUsuario>("rol_usuario");

        // Nombres de tabla explicitos (aunque la convencion snake_case + el nombre del DbSet
        // ya darian el mismo resultado) para que quede documentado: estos son los nombres
        // reales de service/db/schema.sql, en plural, la misma convencion que usa el
        // servicio de ingesta en Python. El scaffold original traia singular
        // (tenant, usuario, refresh_token) — se ajusta aca para no tener dos convenciones
        // en la misma base de datos.
        modelBuilder.Entity<Pais>().ToTable("paises");
        modelBuilder.Entity<Tenant>().ToTable("tenants");
        modelBuilder.Entity<Usuario>().ToTable("usuarios");
        modelBuilder.Entity<RefreshToken>().ToTable("refresh_tokens");
        modelBuilder.Entity<ResetPasswordToken>().ToTable("reset_password_tokens");
        modelBuilder.Entity<TenantPaisHabilitado>().ToTable("tenant_paises_habilitados");

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

        // Postgres trata cada NULL como distinto en el UNIQUE de arriba, asi que no evita
        // emails duplicados entre usuarios de Plataforma (TenantId null) -- indice parcial
        // aparte, igual que en schema.sql/009_plataforma.sql.
        modelBuilder.Entity<Usuario>()
            .HasIndex(u => u.Email)
            .IsUnique()
            .HasFilter("tenant_id IS NULL")
            .HasDatabaseName("idx_usuarios_email_plataforma");
    }
}
