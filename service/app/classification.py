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
    """Devuelve {'titulo_id': int, 'categoria_id': int, 'confianza': 'alto'|'medio'|'bajo'}.

    confianza (Art IV.7, spec-empresas-comparacion.md §5 -- decision cerrada): auto-reporte
    del modelo, en el mismo llamado, sin costo ni latencia adicional. Es una señal blanda
    (los LLM tienden a sobreestimar su propia confianza) que solo sirve para ordenar la cola
    de revision (Bloque D) -- nunca para certificar nada.
    """
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
                        "confianza": {"type": "string", "enum": ["alto", "medio", "bajo"]},
                    },
                    "required": ["titulo_id", "confianza"],
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
    return {"titulo_id": titulo_id, "categoria_id": titulo["categoria_id"], "confianza": data["confianza"]}


def check_legal_compliance(texto_clausula: str, articulos: list[dict]) -> dict:
    """Devuelve {'cumplimiento': 'por_debajo'|'iguala'|'supera', 'justificacion': str}.

    Art IV.5 bis (spec-marco-legal.md): cruza la clausula ya clasificada contra los
    articulos de ley vinculados a su titulo (titulo_articulo_ley), y señala si queda por
    debajo, iguala, o supera el minimo legal exigido. Es asistencia para la revision
    humana (Art VII) -- nunca una determinacion legal vinculante ni asesoria legal
    automatizada (Art II.6/XI.6): el Revisor decide, igual que ningun resultado se publica
    sin su aprobacion (Art IV.8). Solo se llama cuando ya existen articulos vinculados al
    titulo -- si no hay ninguno, el llamador usa 'no_aplica' sin gastar una llamada al modelo.
    """
    articulos_texto = "\n\n".join(
        f"Articulo {a['nro_articulo']} ({a['titulo_articulo']}): {a['texto_completo']}"
        for a in articulos
    )
    system_prompt = (
        "Eres un asistente que compara una clausula de convencion colectiva de trabajo "
        "contra el minimo legal establecido en los articulos de la ley relacionados "
        "(marco legal de Venezuela). No sos un abogado y esto no es asesoria legal -- es "
        "una señal de apoyo para que un humano decida. Con base solo en los articulos "
        "citados, indica si la clausula queda por debajo, iguala, o supera el minimo "
        "legal exigido, con una justificacion breve (1-2 oraciones) citando el articulo "
        "relevante."
    )

    response = _client.messages.create(
        model=CLASSIFICATION_MODEL,
        max_tokens=300,
        system=[
            {
                "type": "text",
                "text": system_prompt,
                "cache_control": {"type": "ephemeral"},
            }
        ],
        messages=[
            {
                "role": "user",
                "content": f"Articulos de ley relacionados:\n\n{articulos_texto}\n\n"
                f"Clausula a evaluar:\n{texto_clausula}",
            }
        ],
        output_config={
            "format": {
                "type": "json_schema",
                "schema": {
                    "type": "object",
                    "properties": {
                        "cumplimiento": {"type": "string", "enum": ["por_debajo", "iguala", "supera"]},
                        "justificacion": {"type": "string"},
                    },
                    "required": ["cumplimiento", "justificacion"],
                    "additionalProperties": False,
                },
            }
        },
    )

    if response.stop_reason == "refusal":
        raise ClassificationError("El modelo rehuso evaluar el cumplimiento legal de esta clausula (stop_reason=refusal).")

    texto_respuesta = next((b.text for b in response.content if b.type == "text"), None)
    if texto_respuesta is None:
        raise ClassificationError("El modelo no devolvio una respuesta de texto evaluable para cumplimiento legal.")

    return json.loads(texto_respuesta)
