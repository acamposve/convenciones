-- 008_marco_legal.sql
-- Marco legal y verificacion de cumplimiento (Art II.6 / IV.5 bis de la constitucion,
-- Fase 4 / spec-marco-legal.md). Catalogo de leyes por pais + vinculo M:N artículo↔título,
-- y la señal de cumplimiento en clausulas -- asistencia para el Revisor, nunca una
-- determinacion legal vinculante (Art XI.6, disclaimer obligatorio en la UI).

BEGIN;

CREATE TABLE leyes (
    id                 SERIAL PRIMARY KEY,
    pais_id            INTEGER NOT NULL REFERENCES paises(id),
    nombre             TEXT NOT NULL,
    gaceta             TEXT,
    fecha_publicacion  DATE,
    UNIQUE (pais_id, nombre)
);

CREATE INDEX idx_leyes_pais_id ON leyes(pais_id);

CREATE TABLE articulos_ley (
    id               SERIAL PRIMARY KEY,
    ley_id           INTEGER NOT NULL REFERENCES leyes(id),
    nro_articulo     INTEGER NOT NULL,
    titulo_articulo  TEXT,
    texto_completo   TEXT NOT NULL,
    UNIQUE (ley_id, nro_articulo)
);

CREATE INDEX idx_articulos_ley_ley_id ON articulos_ley(ley_id);

CREATE TABLE titulo_articulo_ley (
    titulo_id       INTEGER NOT NULL REFERENCES taxonomia_titulos(id),
    articulo_ley_id INTEGER NOT NULL REFERENCES articulos_ley(id),
    PRIMARY KEY (titulo_id, articulo_ley_id)
);

CREATE INDEX idx_titulo_articulo_ley_articulo_ley_id ON titulo_articulo_ley(articulo_ley_id);

ALTER TABLE clausulas ADD COLUMN cumplimiento_legal TEXT
    CHECK (cumplimiento_legal IN ('por_debajo', 'iguala', 'supera', 'no_aplica'));
ALTER TABLE clausulas ADD COLUMN cumplimiento_justificacion TEXT;

COMMIT;
