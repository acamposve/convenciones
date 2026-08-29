from app.extraction import extract_text
from app.main import _armar_docx_acuerdos
from app.segmentation import segment_clauses


def test_armar_docx_acuerdos_incluye_titulo_y_texto():
    acuerdos = [
        {"titulo_nombre": "Salario mínimo", "texto_acordado": "Se acuerda un incremento del 15%."},
        {"titulo_nombre": "Vacaciones", "texto_acordado": "30 días continuos por año."},
    ]

    contenido = _armar_docx_acuerdos(acuerdos)
    texto = extract_text(contenido, ".docx")

    assert "Salario mínimo" in texto
    assert "Se acuerda un incremento del 15%." in texto
    assert "Vacaciones" in texto
    assert "30 días continuos por año." in texto


def test_armar_docx_acuerdos_segmenta_un_titulo_por_clausula():
    # Regresion: extract_docx une los parrafos con un solo salto de linea (sin linea en
    # blanco), asi que sin el prefijo "CLAUSULA" que espera segment_clauses(), dos o mas
    # acuerdos terminaban fusionados en una sola clausula (detectado probando el cierre de
    # una negociacion con 2 titulos contra un stack real).
    acuerdos = [
        {"titulo_nombre": "Aumento de Salario", "texto_acordado": "Incremento del 15% sobre el tabulador vigente."},
        {"titulo_nombre": "Vacaciones", "texto_acordado": "20 dias habiles de vacaciones anuales."},
    ]

    contenido = _armar_docx_acuerdos(acuerdos)
    texto = extract_text(contenido, ".docx")
    clausulas = segment_clauses(texto)

    assert len(clausulas) == 2
    assert "Aumento de Salario" in clausulas[0]
    assert "Incremento del 15%" in clausulas[0]
    assert "Vacaciones" in clausulas[1]
    assert "20 dias habiles" in clausulas[1]
