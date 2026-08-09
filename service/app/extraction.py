"""Extraccion de texto (Art IV.3): parseo nativo de PDF/Word, OCR si el PDF es escaneado."""
import io
from pathlib import Path

import pymupdf as fitz  # PyMuPDF
from docx import Document as DocxDocument

# Umbral heuristico: menos de esto por pagina en promedio sugiere PDF escaneado sin texto nativo.
MIN_CHARS_PER_PAGE = 20


class ExtractionError(Exception):
    pass


def extract_text(ruta_archivo: str) -> str:
    ext = Path(ruta_archivo).suffix.lower()
    if ext == ".pdf":
        return _extract_pdf(ruta_archivo)
    if ext == ".docx":
        return _extract_docx(ruta_archivo)
    raise ExtractionError(f"Formato de archivo no soportado para extraccion: '{ext}'")


def _extract_pdf(ruta_archivo: str) -> str:
    doc = fitz.open(ruta_archivo)
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


def _extract_docx(ruta_archivo: str) -> str:
    doc = DocxDocument(ruta_archivo)
    return "\n".join(p.text for p in doc.paragraphs if p.text.strip())
