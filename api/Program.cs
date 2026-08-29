using System.Text;
using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using Npgsql;
using Npgsql.NameTranslation;

var builder = WebApplication.CreateBuilder(args);

// El tipo `rol_usuario` es un ENUM nativo de Postgres (creado por SQL plano, no por EF).
// Npgsql 8 ya no lo mapea a RolUsuario automaticamente ("unmapped enums requiere opt-in") —
// hay que registrarlo en el NpgsqlDataSource antes de que EF Core lo use, si no cada login
// revienta en runtime con InvalidCastException aunque el build compile sin errores.
// nameTranslator identidad: el enum de Postgres se creo con labels PascalCase literales
// ('AdminTenant', 'Revisor', ...) para que coincidan exactamente con los nombres del enum
// C#. Sin esto, Npgsql aplica snake_case por default (AdminTenant -> admin_tenant) y el
// login revienta con "Received enum value 'AdminTenant' ... wasn't found on enum".
var dataSourceBuilder = new NpgsqlDataSourceBuilder(builder.Configuration.GetConnectionString("Default"));
dataSourceBuilder.MapEnum<RolUsuario>("rol_usuario", nameTranslator: new NpgsqlNullNameTranslator());
var dataSource = dataSourceBuilder.Build();

// UseSnakeCaseNamingConvention: el esquema real (service/db/schema.sql) usa columnas y
// tablas snake_case creadas por SQL plano, no por `dotnet ef migrations`. Sin esto, EF Core
// genera identificadores entrecomillados en PascalCase (ej. "TenantId") que no matchean
// ninguna columna real y el runtime falla con "column does not exist".
builder.Services.AddDbContext<ComparadorDbContext>(opt =>
    opt.UseNpgsql(dataSource)
       .UseSnakeCaseNamingConvention());

builder.Services.AddScoped<TokenService>();

builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(opt =>
    {
        opt.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuer = true,
            ValidIssuer = builder.Configuration["Jwt:Issuer"],
            ValidateAudience = true,
            ValidAudience = builder.Configuration["Jwt:Audience"],
            ValidateIssuerSigningKey = true,
            IssuerSigningKey = new SymmetricSecurityKey(
                Encoding.UTF8.GetBytes(builder.Configuration["Jwt:SigningKey"]!)),
            ValidateLifetime = true
        };
    });

builder.Services.AddAuthorization(AuthorizationPolicies.Configurar);

// CORS: api (5080) y web (5173) son origenes distintos — sin esto el navegador bloquea el
// login con "No 'Access-Control-Allow-Origin' header" aunque la API funcione perfecto por
// curl/Postman. Origen configurable (Cors__WebOrigin) para no hardcodear localhost cuando
// esto corra en docker-compose bajo otro host/puerto.
// Lista separada por coma, no un origen único: el frontend en Azure quedó accesible tanto
// por el FQDN largo de Container Apps como por un dominio propio (presenciavirtual.com.uy),
// y ambos necesitan poder loguearse desde el navegador.
const string CorsPolicyWeb = "web";
var webOrigins = (builder.Configuration["Cors:WebOrigin"] ?? "http://localhost:5173")
    .Split(',', StringSplitOptions.RemoveEmptyEntries | StringSplitOptions.TrimEntries);
builder.Services.AddCors(opt =>
{
    opt.AddPolicy(CorsPolicyWeb, policy =>
        policy.WithOrigins(webOrigins).AllowAnyHeader().AllowAnyMethod());
});

builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();

var app = builder.Build();

app.UseCors(CorsPolicyWeb);

app.UseAuthentication();

// Middleware de aislamiento por tenant (Art. VI.2): expone tenant_id del JWT
// como HttpContext.Items["tenant_id"] para que cada repositorio lo use como filtro
// obligatorio. Ningún endpoint debe leer tenant_id de la URL o del body.
app.Use(async (context, next) =>
{
    var tenantClaim = context.User?.FindFirst("tenant_id")?.Value;
    if (tenantClaim != null)
    {
        context.Items["tenant_id"] = tenantClaim;
    }
    await next();
});

app.UseAuthorization();
app.MapControllers();

app.Run();
