import pymupdf as fitz
import pytest
from docx import Document as DocxDocument

from app.extraction import ExtractionError, extract_text


def test_extension_no_soportada_lanza_extraction_error(tmp_path):
    archivo = tmp_path / "convencion.txt"
    archivo.write_text("no deberia procesarse")

    with pytest.raises(ExtractionError, match="no soportado"):
        extract_text(str(archivo))


def test_extrae_texto_nativo_de_docx(tmp_path):
    doc = DocxDocument()
    doc.add_paragraph("CLAUSULA PRIMERA: Objeto del contrato.")
    doc.add_paragraph("")  # parrafo vacio — no debe aparecer en el resultado
    doc.add_paragraph("CLAUSULA SEGUNDA: Vigencia.")
    ruta = tmp_path / "convencion.docx"
    doc.save(str(ruta))

    texto = extract_text(str(ruta))

    assert "CLAUSULA PRIMERA: Objeto del contrato." in texto
    assert "CLAUSULA SEGUNDA: Vigencia." in texto
    # Sin lineas en blanco colgando de parrafos vacios (Document.paragraphs los filtra).
    assert "\n\n" not in texto


def test_extrae_texto_nativo_de_pdf(tmp_path):
    doc = fitz.open()
    page = doc.new_page()
    page.insert_text((72, 72), "CLAUSULA PRIMERA texto nativo suficiente para no disparar OCR " * 3)
    ruta = tmp_path / "convencion.pdf"
    doc.save(str(ruta))
    doc.close()

    texto = extract_text(str(ruta))

    assert "CLAUSULA PRIMERA" in texto
