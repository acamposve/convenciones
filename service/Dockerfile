FROM python:3.12-slim
WORKDIR /app

# tesseract-ocr: pytesseract solo envuelve el binario, no lo instala via pip — sin esto
# el OCR automatico de documentos escaneados (Art IV.3) fallaria en runtime.
# tesseract-ocr-spa: el paquete base solo trae ingles, y app/extraction.py llama a
# image_to_string(..., lang="spa") — sin los datos de espanol, todo PDF escaneado del
# corpus venezolano muere en "Failed loading language 'spa'".
RUN apt-get update && apt-get install -y --no-install-recommends tesseract-ocr tesseract-ocr-spa \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

EXPOSE 8000
CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
