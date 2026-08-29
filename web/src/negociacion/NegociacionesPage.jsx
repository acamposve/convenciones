import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const PUEDE_CREAR = ["AdminTenant", "Editor"]; // spec-negociacion.md §4
const PUEDE_VER = ["AdminTenant", "Revisor", "Editor"];

const ESTADO_LABEL = { abierta: "Abierta", cerrada: "Cerrada" };

// Negociacion colectiva pre-firma (Art IV bis): peticiones/ofertas/reuniones/acuerdos de
// una Empresa antes de que exista un documento firmado. Listado por empresa -- se llega
// aca desde EmpresasPage.jsx o desde la columna "Empresa" de DocumentList.jsx.
export function NegociacionesPage() {
  const { empresaId } = useParams();
  const { rol, docFetch } = useAuth();
  const [negociaciones, setNegociaciones] = useState(null);
  const [error, setError] = useState(null);
  const [creando, setCreando] = useState(false);
  const [reloadToken, setReloadToken] = useState(0);

  useEffect(() => {
    let cancelado = false;
    docFetch(`/negociaciones?empresa_id=${empresaId}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar las negociaciones de esta empresa.");
        return res.json();
      })
      .then((data) => {
        if (!cancelado) setNegociaciones(data);
      })
      .catch((err) => {
        if (!cancelado) setError(err.message);
      });
    return () => {
      cancelado = true;
    };
  }, [docFetch, empresaId, reloadToken]);

  if (!PUEDE_VER.includes(rol)) {
    return (
      <div className="page">
        <p className="banner-muted">Tu rol ({rol}) no tiene permiso para ver negociaciones.</p>
      </div>
    );
  }

  async function crearNegociacion() {
    setCreando(true);
    setError(null);
    try {
      const formData = new FormData();
      formData.append("empresa_id", empresaId);
      const res = await docFetch("/negociaciones", { method: "POST", body: formData });
      if (!res.ok) throw new Error("No se pudo crear la negociación.");
      setReloadToken((t) => t + 1);
    } catch (err) {
      setError(err.message);
    } finally {
      setCreando(false);
    }
  }

  const empresaNombre = negociaciones?.[0]?.empresa_nombre;

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/">&larr; volver a documentos</Link>
      <div className="app-header">
        <h1>Negociaciones{empresaNombre ? ` — ${empresaNombre}` : ""}</h1>
      </div>
      <div className="banner banner-warning">
        Proceso pre-firma (Art. IV bis): al cerrar una negociación se genera un Documento
        que entra al pipeline normal de clasificación (Art. IV).
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      {PUEDE_CREAR.includes(rol) && (
        <button className="btn-primary" onClick={crearNegociacion} disabled={creando}>
          {creando ? "Creando…" : "Nueva negociación"}
        </button>
      )}

      {negociaciones === null ? (
        <p className="banner-muted">Cargando…</p>
      ) : negociaciones.length === 0 ? (
        <p className="banner-muted">Todavía no hay negociaciones para esta empresa.</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Estado</th>
              <th>Inicio</th>
              <th>Cierre</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {negociaciones.map((n) => (
              <tr key={n.id}>
                <td>
                  <span className={`badge badge-${n.estado === "cerrada" ? "aprobado" : "clasificado"}`}>
                    {ESTADO_LABEL[n.estado]}
                  </span>
                </td>
                <td>{new Date(n.fecha_inicio).toLocaleDateString()}</td>
                <td>{n.fecha_cierre ? new Date(n.fecha_cierre).toLocaleDateString() : "—"}</td>
                <td>
                  <Link to={`/negociaciones/${n.id}`}>ver detalle</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
