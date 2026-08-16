from app.classification import build_system_prompt

TITULOS = [
    {"id": 1, "nombre": "Salario base", "descripcion": "Monto fijo mensual",
     "categoria_id": 10, "categoria_nombre": "ECONOMICO"},
    {"id": 2, "nombre": "Bono vacacional", "descripcion": None,
     "categoria_id": 10, "categoria_nombre": "ECONOMICO"},
    {"id": 3, "nombre": "Cuota sindical", "descripcion": "Aporte mensual",
     "categoria_id": 20, "categoria_nombre": "SINDICALES"},
]


def test_agrupa_titulos_por_categoria_con_un_encabezado_por_grupo():
    prompt = build_system_prompt(TITULOS)

    # Un solo "## ECONOMICO" aunque haya dos titulos en esa categoria — el header
    # solo se emite cuando cambia la categoria respecto al titulo anterior.
    assert prompt.count("## ECONOMICO") == 1
    assert prompt.count("## SINDICALES") == 1
    assert prompt.index("## ECONOMICO") < prompt.index("## SINDICALES")


def test_cada_titulo_incluye_su_id_nombre_y_descripcion():
    prompt = build_system_prompt(TITULOS)

    assert "id 1: Salario base" in prompt
    assert "Monto fijo mensual" in prompt
    assert "id 3: Cuota sindical" in prompt


def test_descripcion_faltante_cae_a_texto_por_defecto():
    prompt = build_system_prompt(TITULOS)

    assert "id 2: Bono vacacional" in prompt
    assert "sin descripcion" in prompt


def test_lista_vacia_de_titulos_no_rompe_y_conserva_instrucciones():
    prompt = build_system_prompt([])

    assert "No inventes ids fuera de la lista" in prompt
