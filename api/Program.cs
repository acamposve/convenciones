using System.Text;
using Comparador.Api.Data;
using Comparador.Api.Models;
using Comparador.Api.Services;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using Npgsql;
using Npgsql.NameTranslation;
using Serilog;
using Serilog.Context;
using Serilog.Formatting.Json;

var builder = WebApplication.CreateBuilder(args);

// Configuración de Serilog: logging estructurado JSON a consola (la plataforma de deploy
// captura stdout; JSON permite filtrar/consultar por campo — TenantId, UserId,
// CorrelationId — en vez de por texto libre, criterio de éxito de Fase 1).
builder.Host.UseSerilog((context, services, configuration) => configuration
    .ReadFrom.Configuration(context.Configuration)
    .ReadFrom.Services(services)
    .Enrich.FromLogContext()
    .WriteTo.Console(new JsonFormatter()));

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
// Lista separada por coma, no un origen único: el frontend puede quedar accesible tanto
// por la URL que genere la plataforma de deploy como por un dominio propio
// (presenciavirtual.com.uy), y ambos necesitan poder loguearse desde el navegador.
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
// obligatorio y enriquece el LogContext de Serilog. Va ANTES del exception handler y de
// UseSerilogRequestLogging (más abajo) a propósito: LogContext.PushProperty usa un
// AsyncLocal que se desapila al salir del `using`, así que si el exception handler o el
// request logging estuvieran registrados antes (más "afuera") que este middleware,
// emitirían su log de excepción/finalización de request DESPUÉS de que este `using` ya
// se desapiló — y quedarían sin TenantId/UserId/CorrelationId. Para que los reciban,
// tienen que estar anidados DENTRO de este scope, es decir, registrados después.
app.Use(async (context, next) =>
{
    var tenantClaim = context.User?.FindFirst("tenant_id")?.Value;
    // TokenService.GenerarAccessToken emite el claim custom "user_id" (no el estándar
    // ClaimTypes.NameIdentifier/"sub") — ver api/Services/TokenService.cs.
    var userClaim = context.User?.FindFirst("user_id")?.Value;
    var correlationId = context.TraceIdentifier;

    using (LogContext.PushProperty("TenantId", tenantClaim ?? "Anonymous"))
    using (LogContext.PushProperty("UserId", userClaim ?? "Anonymous"))
    using (LogContext.PushProperty("CorrelationId", correlationId))
    {
        if (tenantClaim != null)
        {
            context.Items["tenant_id"] = tenantClaim;
        }
        await next();
    }
});

// Middleware global de manejo de excepciones no controladas (RFC 7807 ProblemDetails + Log
// estructurado). Registrado después del middleware de arriba para que Log.Error() incluya
// TenantId/UserId/CorrelationId.
app.UseExceptionHandler(errorApp =>
{
    errorApp.Run(async context =>
    {
        context.Response.StatusCode = StatusCodes.Status500InternalServerError;
        context.Response.ContentType = "application/problem+json";

        var exceptionHandlerPathFeature = context.Features.Get<Microsoft.AspNetCore.Diagnostics.IExceptionHandlerPathFeature>();
        var ex = exceptionHandlerPathFeature?.Error;

        Log.Error(ex, "Excepción no controlada procesando solicitud en {Path}", context.Request.Path);

        var problem = new Microsoft.AspNetCore.Mvc.ProblemDetails
        {
            Status = StatusCodes.Status500InternalServerError,
            Title = "Error interno del servidor",
            Detail = app.Environment.IsDevelopment() ? ex?.Message : "Ocurrió un error inesperado al procesar la solicitud.",
            Instance = context.Request.Path
        };

        await context.Response.WriteAsJsonAsync(problem);
    });
});

// También después del middleware de contexto, por el mismo motivo: para que el log de
// "Request completed" incluya TenantId/UserId/CorrelationId.
app.UseSerilogRequestLogging();

app.UseAuthorization();
app.MapControllers();

app.Run();
