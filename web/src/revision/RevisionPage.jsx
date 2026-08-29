import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const PUEDE_REVISAR = ["AdminTenant", "Revisor"]; // spec-empresas-comparacion.md §3

const CONFIANZA_LABEL = { alto: "Alta", medio: "Media", bajo: "Baja" };
const CUMPLIMIENTO_LABEL = { por_debajo: "Por debajo", iguala: "Iguala", supera: "Supera", no_aplica: "No aplica" };

// Cola de revision humana (Art. IV.7-8, no negociable): nada llega al comparador
// (Bloque E) sin pasar por aca. "Corregir" no es una accion separada -- el Revisor puede
// cambiar el titulo sugerido en el mismo gesto de aprobar.
export function RevisionPage() {
  const { rol, docFetch } = useAuth();
  const [cola, setCola] = useState(null);
  const [titulos, setTitulos] = useState([]);
  const [correcciones, setCorrecciones] = useState({}); // { [clausulaId]: titulo_id elegido }
  const [error, setError] = useState(null);
  const [procesando, setProcesando] = useState(null);

  useEffect(() => {
    docFetch("/taxonomia").then((res) => res.json()).then(setTitulos).catch(() => setTitulos([]));
  }, [docFetch]);

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
              <th>Título sugerido</th>
              <th>Confianza</th>
              <th>Cumplimiento legal</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {cola.map((cl) => (
              <tr key={cl.id}>
                <td>{cl.empresa_nombre}</td>
                <td className="clause-texto">{cl.texto}</td>
                <td>
                  <select
                    value={correcciones[cl.id] ?? cl.titulo_id ?? ""}
                    onChange={(e) => setCorrecciones((c) => ({ ...c, [cl.id]: e.target.value }))}
                  >
                    <option value="">— sin clasificar —</option>
                    {titulos.map((t) => (
                      <option key={t.id} value={t.id}>{t.categoria_nombre} · {t.nombre}</option>
                    ))}
                  </select>
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
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
