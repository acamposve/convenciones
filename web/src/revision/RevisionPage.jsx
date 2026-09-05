import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const PUEDE_REVISAR = ["AdminTenant", "Revisor"]; // spec-empresas-comparacion.md §3

const CONFIANZA_LABEL = { alto: "Alta", medio: "Media", bajo: "Baja" };
const CUMPLIMIENTO_LABEL = { por_debajo: "Por debajo", iguala: "Iguala", supera: "Supera", no_aplica: "No aplica" };
const ESTADO_LABEL = { aprobado: "Aprobado", rechazado: "Rechazado" };

// Cola de revision humana (Art. IV.7-8, no negociable): nada llega al comparador
// (Bloque E) sin pasar por aca. "Corregir" no es una accion separada -- el Revisor puede
// cambiar el titulo sugerido en el mismo gesto de aprobar.
//
// Fase 6 (spec-resumen-ejecutivo.md, Art IV.6 bis/8): el resumen ejecutivo tiene su PROPIA
// aprobacion, independiente de la del titulo/campo comparativo -- una clausula sigue en la
// cola mientras cualquiera de los dos siga pendiente, y cada seccion se resuelve por separado.
export function RevisionPage() {
  const { rol, docFetch } = useAuth();
  const [cola, setCola] = useState(null);
  const [titulosPorPais, setTitulosPorPais] = useState({}); // { [pais_id]: [titulo, ...] }
  const [correcciones, setCorrecciones] = useState({}); // { [clausulaId]: titulo_id elegido }
  const [camposComparativos, setCamposComparativos] = useState({}); // { [clausulaId]: texto }
  const [resumenes, setResumenes] = useState({}); // { [clausulaId]: texto }
  const [error, setError] = useState(null);
  const [procesando, setProcesando] = useState(null);

  // Fase 8 (spec-taxonomia-por-pais.md Bloque C): la cola puede mezclar clausulas de
  // empresas de paises distintos (cada una con su propia capa de titulos, Art II.3) -- se
  // carga la taxonomia de cada pais presente en la cola por separado, nunca una lista unica
  // mezclando titulos que no le corresponden a una clausula.
  useEffect(() => {
    const paisesEnCola = [...new Set((cola ?? []).map((cl) => cl.empresa_pais_id))];
    const faltantes = paisesEnCola.filter((paisId) => !(paisId in titulosPorPais));
    if (faltantes.length === 0) return;
    faltantes.forEach((paisId) => {
      docFetch(`/taxonomia?pais_id=${paisId}`)
        .then((res) => res.json())
        .then((titulos) => setTitulosPorPais((t) => ({ ...t, [paisId]: titulos })))
        .catch(() => setTitulosPorPais((t) => ({ ...t, [paisId]: [] })));
    });
  }, [cola, docFetch, titulosPorPais]);

  const cargarCola = () => {
    docFetch("/revision")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la cola de revisión.");
        return res.json();
      })
      .then(setCola)
      .catch((err) => setError(err.message));
  };

  useEffect(cargarCola, [docFetch]);

  if (!PUEDE_REVISAR.includes(rol)) {
    return (
      <div className="page">
        <p className="banner-muted">Tu rol ({rol}) no tiene permiso para revisar cláusulas (auth-spec.md §5).</p>
      </div>
    );
  }

  async function aprobar(clausulaId) {
    setProcesando(clausulaId);
    try {
      const formData = new FormData();
      const tituloElegido = correcciones[clausulaId];
      if (tituloElegido) formData.append("titulo_id", tituloElegido);
      const campoElegido = camposComparativos[clausulaId];
      if (campoElegido !== undefined) formData.append("campo_comparativo", campoElegido);
      const res = await docFetch(`/revision/${clausulaId}/aprobar`, { method: "POST", body: formData });
      if (!res.ok) throw new Error("No se pudo aprobar la cláusula.");
      cargarCola();
    } catch (err) {
      setError(err.message);
    } finally {
      setProcesando(null);
    }
  }

  async function rechazar(clausulaId) {
    setProcesando(clausulaId);
    try {
      const res = await docFetch(`/revision/${clausulaId}/rechazar`, { method: "POST" });
      if (!res.ok) throw new Error("No se pudo rechazar la cláusula.");
      cargarCola();
    } catch (err) {
      setError(err.message);
    } finally {
      setProcesando(null);
    }
  }

  async function aprobarResumen(clausulaId) {
    setProcesando(clausulaId);
    try {
      const formData = new FormData();
      const resumenElegido = resumenes[clausulaId];
      if (resumenElegido !== undefined) formData.append("resumen_ejecutivo", resumenElegido);
      const res = await docFetch(`/revision/${clausulaId}/aprobar-resumen`, { method: "POST", body: formData });
      if (!res.ok) throw new Error("No se pudo aprobar el resumen.");
      cargarCola();
    } catch (err) {
      setError(err.message);
    } finally {
      setProcesando(null);
    }
  }

  async function rechazarResumen(clausulaId) {
    setProcesando(clausulaId);
    try {
      const res = await docFetch(`/revision/${clausulaId}/rechazar-resumen`, { method: "POST" });
      if (!res.ok) throw new Error("No se pudo rechazar el resumen.");
      cargarCola();
    } catch (err) {
      setError(err.message);
    } finally {
      setProcesando(null);
    }
  }

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/">&larr; volver a documentos</Link>
      <div className="app-header">
        <h1>Cola de revisión</h1>
      </div>
      <div className="banner banner-warning">
        Ninguna cláusula queda disponible para comparar (Art. IV.9) hasta que un Revisor la apruebe acá.
      </div>
      <div className="banner banner-warning">
        La columna "Cumplimiento legal" es una asistencia de IA que cruza la cláusula contra
        el marco legal vigente (Art. IV.5 bis) — <strong>no es asesoría legal</strong> ni una
        determinación vinculante. La decisión final es siempre del Revisor.
      </div>
      <div className="banner banner-warning">
        El resumen ejecutivo se aprueba por separado de la clasificación (Art. IV.6 bis) —
        podés aprobar uno sin el otro, en cualquier orden.
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      {cola === null ? (
        <p className="banner-muted">Cargando cola…</p>
      ) : cola.length === 0 ? (
        <p className="banner-muted">No hay cláusulas pendientes de revisión.</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Empresa</th>
              <th>Cláusula</th>
              <th>Título sugerido / campo comparativo</th>
              <th>Confianza</th>
              <th>Cumplimiento legal</th>
              <th>Clasificación</th>
              <th>Resumen ejecutivo</th>
            </tr>
          </thead>
          <tbody>
            {cola.map((cl) => (
              <tr key={cl.id}>
                <td>{cl.empresa_nombre}</td>
                <td className="clause-texto">{cl.texto}</td>
                <td>
                  {cl.estado_revision === "pendiente" ? (
                    <>
                      <select
                        value={correcciones[cl.id] ?? cl.titulo_id ?? ""}
                        onChange={(e) => setCorrecciones((c) => ({ ...c, [cl.id]: e.target.value }))}
                      >
                        <option value="">— sin clasificar —</option>
                        {(titulosPorPais[cl.empresa_pais_id] ?? []).map((t) => (
                          <option key={t.id} value={t.id}>{t.categoria_nombre} · {t.nombre}</option>
                        ))}
                      </select>
                      <input
                        type="text"
                        placeholder="Campo comparativo (ej. 15 días hábiles)"
                        value={camposComparativos[cl.id] ?? cl.campo_comparativo ?? ""}
                        onChange={(e) => setCamposComparativos((c) => ({ ...c, [cl.id]: e.target.value }))}
                        style={{ marginTop: "0.4rem", width: "100%" }}
                      />
                    </>
                  ) : (
                    <>
                      <div>{cl.titulo_nombre ?? "sin clasificar"}</div>
                      {cl.campo_comparativo && <div className="clause-texto">{cl.campo_comparativo}</div>}
                    </>
                  )}
                </td>
                <td>
                  {cl.confianza ? (
                    <span className={`badge ${cl.confianza === "bajo" ? "badge-error" : ""}`}>
                      {CONFIANZA_LABEL[cl.confianza]}
                    </span>
                  ) : (
                    <span className="badge badge-error">Sin clasificar</span>
                  )}
                </td>
                <td>
                  {cl.cumplimiento_legal ? (
                    <span
                      className={`badge badge-${cl.cumplimiento_legal}`}
                      title={cl.cumplimiento_justificacion ?? ""}
                    >
                      {CUMPLIMIENTO_LABEL[cl.cumplimiento_legal]}
                    </span>
                  ) : (
                    <span className="banner-muted">—</span>
                  )}
                </td>
                <td>
                  {cl.estado_revision === "pendiente" ? (
                    <>
                      <button
                        className="btn-primary"
                        disabled={procesando === cl.id}
                        onClick={() => aprobar(cl.id)}
                      >
                        Aprobar
                      </button>{" "}
                      <button
                        className="btn-secondary"
                        disabled={procesando === cl.id}
                        onClick={() => rechazar(cl.id)}
                      >
                        Rechazar
                      </button>
                    </>
                  ) : (
                    <span className={`badge badge-${cl.estado_revision}`}>{ESTADO_LABEL[cl.estado_revision]}</span>
                  )}
                </td>
                <td>
                  {cl.estado_revision_resumen === "pendiente" ? (
                    <>
                      <textarea
                        value={resumenes[cl.id] ?? cl.resumen_ejecutivo ?? ""}
                        onChange={(e) => setResumenes((r) => ({ ...r, [cl.id]: e.target.value }))}
                        style={{ width: "100%", minHeight: "3rem" }}
                        placeholder="Sin resumen generado"
                      />
                      <button
                        className="btn-primary"
                        disabled={procesando === cl.id}
                        onClick={() => aprobarResumen(cl.id)}
                      >
                        Aprobar
                      </button>{" "}
                      <button
                        className="btn-secondary"
                        disabled={procesando === cl.id}
                        onClick={() => rechazarResumen(cl.id)}
                      >
                        Rechazar
                      </button>
                    </>
                  ) : (
                    <>
                      <p className="clause-texto">{cl.resumen_ejecutivo}</p>
                      <span className={`badge badge-${cl.estado_revision_resumen}`}>
                        {ESTADO_LABEL[cl.estado_revision_resumen]}
                      </span>
                    </>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
