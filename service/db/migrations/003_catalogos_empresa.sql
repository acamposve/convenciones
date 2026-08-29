-- 003_catalogos_empresa.sql
-- Agrega los catalogos globales de segmentacion de empresas (Art. II.5 de la constitucion,
-- Fase 2 / spec-empresas-comparacion.md Bloque A) a una base que ya tiene el esquema de
-- 002_auth.sql aplicado (o el de schema.sql directo). Idempotente-friendly: falla claro
-- (no silencioso) si se corre dos veces, porque las tablas ya existirian -- mismo criterio
-- que 002_auth.sql.
--
-- Uso: aplicar una sola vez contra una base que todavia no tiene la tabla `sectores`.
-- Despues de aplicar esto, correr service/db/seed_catalogos_empresa.py para sembrar los
-- datos reales (docs/catalogos_empresa_venezuela.json).

BEGIN;

CREATE TABLE sectores (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE tipos_empresa (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE categorias_sector (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE actividades_empresa (
    id           INTEGER PRIMARY KEY,
    nombre       TEXT NOT NULL,
    descripcion  TEXT
);

CREATE TABLE estados (
    id       INTEGER PRIMARY KEY,
    pais_id  INTEGER NOT NULL REFERENCES paises(id),
    nombre   TEXT NOT NULL
);

CREATE INDEX idx_estados_pais_id ON estados(pais_id);

CREATE TABLE localidades (
    id         INTEGER PRIMARY KEY,
    estado_id  INTEGER NOT NULL REFERENCES estados(id),
    nombre     TEXT NOT NULL
);

CREATE INDEX idx_localidades_estado_id ON localidades(estado_id);

COMMIT;
