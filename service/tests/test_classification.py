import json
from unittest.mock import MagicMock, patch

from app.classification import build_system_prompt, summarize_clause

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


def _mock_response(payload: dict):
    bloque = MagicMock(type="text", text=json.dumps(payload))
    return MagicMock(stop_reason="end_turn", content=[bloque])


# Fase 6 (spec-resumen-ejecutivo.md, Art IV.6/6 bis): campo_comparativo solo se pide al
# modelo si la categoria del titulo lo requiere -- el cliente de Anthropic se mockea para
# poder verificar la construccion del schema/prompt sin depender de una API key real.
def test_summarize_clause_pide_campo_comparativo_cuando_la_categoria_lo_requiere():
    with patch("app.classification._client") as mock_client:
        mock_client.messages.create.return_value = _mock_response(
            {"resumen_ejecutivo": "15 dias habiles de vacaciones.", "campo_comparativo": "15 dias habiles"}
        )
        resultado = summarize_clause("texto de la clausula", "Vacaciones", requiere_campo_comparativo=True)

    assert resultado == {
        "resumen_ejecutivo": "15 dias habiles de vacaciones.",
        "campo_comparativo": "15 dias habiles",
    }
    schema = mock_client.messages.create.call_args.kwargs["output_config"]["format"]["schema"]
    assert "campo_comparativo" in schema["properties"]
    assert "campo_comparativo" in schema["required"]


def test_summarize_clause_omite_campo_comparativo_cuando_no_se_requiere():
    with patch("app.classification._client") as mock_client:
        mock_client.messages.create.return_value = _mock_response(
            {"resumen_ejecutivo": "Aplica a todos los trabajadores sin excepcion."}
        )
        resultado = summarize_clause("texto de la clausula", "Ambito de Aplicación", requiere_campo_comparativo=False)

    assert resultado == {
        "resumen_ejecutivo": "Aplica a todos los trabajadores sin excepcion.",
        "campo_comparativo": None,
    }
    schema = mock_client.messages.create.call_args.kwargs["output_config"]["format"]["schema"]
    assert "campo_comparativo" not in schema["properties"]
    assert "campo_comparativo" not in schema["required"]


def test_summarize_clause_refusal_lanza_classification_error():
    from app.classification import ClassificationError

    with patch("app.classification._client") as mock_client:
        mock_client.messages.create.return_value = MagicMock(stop_reason="refusal", content=[])
        try:
            summarize_clause("texto", "Vacaciones", requiere_campo_comparativo=True)
            assert False, "se esperaba ClassificationError"
        except ClassificationError:
            pass
