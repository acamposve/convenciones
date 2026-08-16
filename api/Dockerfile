FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY . .
# api/ contiene Comparador.Api.csproj Y Comparador.sln, asi que el auto-discovery de
# `dotnet` falla con MSB1011 ("more than one project or solution file") — mismo motivo
# que ci.yml ya documenta. Se apunta al .csproj y no al .sln a proposito: la solucion
# incluye Comparador.Api.Tests, que no debe terminar dentro de la imagen de runtime.
RUN dotnet restore Comparador.Api.csproj
RUN dotnet publish Comparador.Api.csproj -c Release -o /app

FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS runtime
WORKDIR /app
COPY --from=build /app .
EXPOSE 8080
ENV ASPNETCORE_URLS=http://+:8080
ENTRYPOINT ["dotnet", "Comparador.Api.dll"]
