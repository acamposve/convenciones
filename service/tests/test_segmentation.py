from app.segmentation import segment_clauses


def test_segmenta_por_encabezados_clausula():
    texto = (
        "CLAUSULA PRIMERA: Objeto\nTexto de la primera clausula.\n\n"
        "CLAUSULA SEGUNDA: Vigencia\nTexto de la segunda clausula."
    )
    clausulas = segment_clauses(texto)
    assert len(clausulas) == 2
    assert clausulas[0].startswith("CLAUSULA PRIMERA")
    assert clausulas[1].startswith("CLAUSULA SEGUNDA")


def test_segmenta_por_encabezados_articulo_sin_distinguir_mayusculas():
    texto = "articulo 1\nTexto uno.\n\nARTÍCULO 2\nTexto dos."
    clausulas = segment_clauses(texto)
    assert len(clausulas) == 2


def test_fallback_a_lineas_numeradas_sin_encabezados_de_clausula():
    # _NUMBERED_LINE_RE exige un solo separador (. o )) seguido de espacio — "1.- " con
    # guion no matchea, solo "1. " o "1) ".
    texto = "1. Primer punto sin estructura de clausula.\n2) Segundo punto."
    clausulas = segment_clauses(texto)
    assert len(clausulas) == 2
    assert clausulas[0].startswith("1.")
    assert clausulas[1].startswith("2)")


def test_fallback_final_a_parrafos_sin_ninguna_estructura():
    texto = "Primer parrafo sin numeracion.\n\nSegundo parrafo sin numeracion."
    clausulas = segment_clauses(texto)
    assert clausulas == ["Primer parrafo sin numeracion.", "Segundo parrafo sin numeracion."]


def test_texto_vacio_no_produce_clausulas():
    assert segment_clauses("") == []
    assert segment_clauses("   \n\n   ") == []


def test_un_solo_encabezado_de_clausula_no_alcanza_el_minimo_y_cae_a_parrafos():
    # MIN_MATCHES_PARA_ESTRUCTURA = 2 — con una sola coincidencia no se confia en la
    # deteccion de estructura, se trata como texto sin clausulas explicitas.
    texto = "CLAUSULA UNICA: Objeto\nEsta convencion tiene una sola clausula."
    clausulas = segment_clauses(texto)
    assert len(clausulas) == 1
    assert "CLAUSULA UNICA" in clausulas[0]
