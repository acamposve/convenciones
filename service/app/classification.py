"""Clasificacion de clausulas contra la taxonomia real de Venezuela (Art IV.5)."""
import json

import anthropic

from app.config import CLASSIFICATION_MODEL

_client = anthropic.Anthropic()


class ClassificationError(Exception):
    pass


def build_system_prompt(titulos: list[dict]) -> str:
    lineas = [
        "Eres un clasificador de clausulas de convenciones colectivas de trabajo de Venezuela.",
        "Tu unica tarea es asignar, a la clausula que te da el usuario, el titulo de taxonomia "
        "mas adecuado de la lista siguiente (agrupada por categoria, con su descripcion):",
        "",
    ]
    categoria_actual = None
    for t in titulos:
        if t["categoria_nombre"] != categoria_actual:
            categoria_actual = t["categoria_nombre"]
            lineas.append(f"## {categoria_actual}")
        lineas.append(f"- id {t['id']}: {t['nombre']} — {t['descripcion'] or 'sin descripcion'}")
    lineas.append("")
    lineas.append(
        "Responde siempre asignando el id de titulo que mejor corresponda al contenido de la "
        "clausula, aunque la correspondencia sea parcial. No inventes ids fuera de la lista."
    )
    return "\n".join(lineas)


def classify_clause(texto_clausula: str, titulos: list[dict], system_prompt: str) -> dict:
    """Devuelve {'titulo_id': int, 'categoria_id': int}."""
    valid_ids = [t["id"] for t in titulos]
    titulo_by_id = {t["id"]: t for t in titulos}

    response = _client.messages.create(
        model=CLASSIFICATION_MODEL,
        max_tokens=256,
        system=[
            {
                "type": "text",
                "text": system_prompt,
                "cache_control": {"type": "ephemeral"},
            }
        ],
        messages=[{"role": "user", "content": texto_clausula}],
        output_config={
            "format": {
                "type": "json_schema",
                "schema": {
                    "type": "object",
                    "properties": {
                        "titulo_id": {"type": "integer", "enum": valid_ids},
                    },
                    "required": ["titulo_id"],
                    "additionalProperties": False,
                },
            }
        },
    )

    if response.stop_reason == "refusal":
        raise ClassificationError("El modelo rehuso clasificar esta clausula (stop_reason=refusal).")

    texto_respuesta = next((b.text for b in response.content if b.type == "text"), None)
    if texto_respuesta is None:
        raise ClassificationError("El modelo no devolvio una respuesta de texto clasificable.")

    data = json.loads(texto_respuesta)
    titulo_id = data["titulo_id"]
    titulo = titulo_by_id[titulo_id]
    return {"titulo_id": titulo_id, "categoria_id": titulo["categoria_id"]}
