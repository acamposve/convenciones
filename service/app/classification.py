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


def summarize_clause(texto_clausula: str, titulo_nombre: str, requiere_campo_comparativo: bool) -> dict:
    """Devuelve {'resumen_ejecutivo': str, 'campo_comparativo': str|None}.

    Art IV.6/6 bis (spec-resumen-ejecutivo.md): campo_comparativo solo se pide si la
    categoria del titulo lo requiere (taxonomia_categorias.requiere_campo_comparacion_economica,
    hoy el unico flag real que existe para esto -- no hay uno por titulo) -- si no, se omite
    del schema de salida en vez de forzar al modelo a inventar un valor donde no aplica.

    Restriccion dura de producto (decision cerrada, spec §6, la puso el cliente de dominio
    en la reunion de origen -- no es un detalle de estilo del prompt): el resumen ejecutivo
    nunca interpreta, opina, ni agrega contenido ausente del texto original. Es una sintesis
    practica para lectura ejecutiva rapida ("15 dias habiles de vacaciones + 1 bono anual"),
    no un analisis.
    """
    schema_properties = {"resumen_ejecutivo": {"type": "string"}}
    required = ["resumen_ejecutivo"]
    if requiere_campo_comparativo:
        schema_properties["campo_comparativo"] = {"type": "string"}
        required.append("campo_comparativo")

    system_prompt = (
        f"Eres un asistente que redacta un resumen ejecutivo breve y fiel de una clausula de "
        f"convencion colectiva de trabajo, clasificada bajo el titulo '{titulo_nombre}'. "
        "Reglas estrictas: no interpretes, no agregues contenido que no este literalmente en "
        "el texto original, no opines ni evalues si es buena o mala condicion. El resumen debe "
        "ser una sintesis practica para que un ejecutivo entienda el punto clave sin leer la "
        "clausula completa (ej. 'Aumento salarial del 15%', '15 dias habiles de vacaciones + "
        "1 bono anual')."
    )
    if requiere_campo_comparativo:
        system_prompt += (
            " Ademas extrae el campo comparativo: el valor normalizado y comparable de la "
            "clausula tal como aparece en el texto (ej. '15 dias habiles', '30% del salario')."
        )

    response = _client.messages.create(
        model=CLASSIFICATION_MODEL,
        max_tokens=400,
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
                    "properties": schema_properties,
                    "required": required,
                    "additionalProperties": False,
                },
            }
        },
    )

    if response.stop_reason == "refusal":
        raise ClassificationError("El modelo rehuso resumir esta clausula (stop_reason=refusal).")

    texto_respuesta = next((b.text for b in response.content if b.type == "text"), None)
    if texto_respuesta is None:
        raise ClassificationError("El modelo no devolvio una respuesta de texto resumible.")

    data = json.loads(texto_respuesta)
    return {
        "resumen_ejecutivo": data["resumen_ejecutivo"],
        "campo_comparativo": data.get("campo_comparativo"),
    }
