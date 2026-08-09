"""Segmentacion en clausulas/articulos individuales (Art IV.4), con fallback
para documentos sin estructura clara de numeracion (spec-mvp-demo.md #4)."""
import re

# Encabezados tipicos de convenciones colectivas venezolanas: "CLAUSULA PRIMERA",
# "CLAUSULA 15", "ARTICULO 3", etc.
_CLAUSE_HEADER_RE = re.compile(
    r"^\s*(CL[ÁA]USULA|ART[ÍI]CULO)\s+\S.*$",
    re.IGNORECASE | re.MULTILINE,
)
# Fallback secundario: lineas que empiezan con numeracion simple, ej. "1.- " o "1) ".
_NUMBERED_LINE_RE = re.compile(r"^\s*\d{1,3}[.)]\s+\S", re.MULTILINE)

MIN_MATCHES_PARA_ESTRUCTURA = 2


def segment_clauses(texto: str) -> list[str]:
    matches = list(_CLAUSE_HEADER_RE.finditer(texto))
    if len(matches) < MIN_MATCHES_PARA_ESTRUCTURA:
        matches = list(_NUMBERED_LINE_RE.finditer(texto))
    if len(matches) >= MIN_MATCHES_PARA_ESTRUCTURA:
        return _split_at_matches(texto, matches)
    return _split_by_paragraphs(texto)


def _split_at_matches(texto: str, matches) -> list[str]:
    clausulas = []
    for i, m in enumerate(matches):
        start = m.start()
        end = matches[i + 1].start() if i + 1 < len(matches) else len(texto)
        fragmento = texto[start:end].strip()
        if fragmento:
            clausulas.append(fragmento)
    return clausulas


def _split_by_paragraphs(texto: str) -> list[str]:
    partes = re.split(r"\n\s*\n", texto)
    return [p.strip() for p in partes if p.strip()]
