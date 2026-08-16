"""Extraccion de texto (Art IV.3): parseo nativo de PDF/Word, OCR si el PDF es escaneado.

Opera sobre bytes en memoria, no sobre una ruta en disco: el original puede vivir en Azure
Blob Storage (app/storage.py, Art V/VI.3), y el pipeline ya tiene los bytes en memoria desde
la ingesta (main.py), asi que no hay razon para volver a tocar el filesystem para procesarlo.
"""
import io

import pymupdf as fitz  # PyMuPDF
from docx import Document as DocxDocument

# Umbral heuristico: menos de esto por pagina en promedio sugiere PDF escaneado sin texto nativo.
MIN_CHARS_PER_PAGE = 20


class ExtractionError(Exception):
    pass


def extract_text(contenido: bytes, extension: str) -> str:
    ext = extension.lower()
    if ext == ".pdf":
        return _extract_pdf(contenido)
    if ext == ".docx":
        return _extract_docx(contenido)
    raise ExtractionError(f"Formato de archivo no soportado para extraccion: '{ext}'")


def _extract_pdf(contenido: bytes) -> str:
    doc = fitz.open(stream=contenido, filetype="pdf")
    try:
        texto_nativo = "\n".join(page.get_text("text") for page in doc)
        if len(texto_nativo.strip()) >= MIN_CHARS_PER_PAGE * doc.page_count:
            return texto_nativo
        return _ocr_pdf(doc)
    finally:
        doc.close()


def _ocr_pdf(doc: "fitz.Document") -> str:
    import pytesseract
    from PIL import Image

    textos = []
    for page in doc:
        pix = page.get_pixmap(dpi=300)
        img = Image.open(io.BytesIO(pix.tobytes("png")))
        try:
            textos.append(pytesseract.image_to_string(img, lang="spa"))
        except pytesseract.TesseractNotFoundError as exc:
            raise ExtractionError(
                "El documento parece un PDF escaneado (sin texto nativo) y requiere OCR, "
                "pero el motor Tesseract OCR no esta instalado en este entorno. "
                "Instala Tesseract (con el paquete de idioma 'spa') y vuelve a intentar."
            ) from exc
        except Exception as exc:  # pytesseract.TesseractError u otros fallos del binario
            raise ExtractionError(f"Fallo el OCR sobre el documento escaneado: {exc}") from exc
    return "\n".join(textos)


def _extract_docx(contenido: bytes) -> str:
    doc = DocxDocument(io.BytesIO(contenido))
    return "\n".join(p.text for p in doc.paragraphs if p.text.strip())
