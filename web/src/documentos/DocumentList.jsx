import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const ESTADO_BG = {
  clasificado: "#d9f2d9",
  error: "#fde2e2",
};

// Estados en los que el pipeline ya termino y no hay nada mas que esperar.
const ESTADOS_FINALES = ["clasificado", "error"];
const POLL_MS = 4000;

// reloadToken: cambia cada vez que el padre quiere forzar un refetch (ej. tras subir
// un documento nuevo), espejo de la tabla que antes vivia en index.html.
export function DocumentList({ reloadToken }) {
  const { docFetch } = useAuth();
  const [documentos, setDocumentos] = useState(null);
  const [error, setError] = useState(null);
  const [tick, setTick] = useState(0);

  // El pipeline corre en segundo plano del lado del servicio Python (POST /documentos
  // responde 201 enseguida), asi que el estado avanza solo. Se refresca mientras haya
  // algun documento en vuelo y se corta al terminar — sin recargar la pagina a mano.
  const enProceso = documentos?.some((d) => !ESTADOS_FINALES.includes(d.estado)) ?? false;

  useEffect(() => {
    if (!enProceso) return undefined;
    const id = setInterval(() => setTick((t) => t + 1), POLL_MS);
    return () => clearInterval(id);
  }, [enProceso]);

  useEffect(() => {
    let cancelado = false;
    docFetch("/documentos")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la lista de documentos.");
        return res.json();
      })
      .then((data) => {
        if (!cancelado) setDocumentos(data);
      })
      .catch((err) => {
        if (!cancelado) setError(err.message);
      });
    return () => {
      cancelado = true;
    };
  }, [docFetch, reloadToken, tick]);

  if (error) {
    return <div style={{ background: "#fde2e2", border: "1px solid #e07a7a", padding: "0.6rem 0.9rem", borderRadius: 6 }}>{error}</div>;
  }

  if (documentos === null) {
    return <p style={{ color: "#555" }}>Cargando documentos…</p>;
  }

  return (
    <table style={{ width: "100%", borderCollapse: "collapse", marginTop: "0.5rem" }}>
      <thead>
        <tr>
          <th style={celda}>ID</th>
          <th style={celda}>Origen</th>
          <th style={celda}>Estado</th>
          <th style={celda}>Público</th>
          <th style={celda}>Creado</th>
        </tr>
      </thead>
      <tbody>
        {documentos.length === 0 && (
          <tr>
            <td style={celda} colSpan={5}>Todavía no hay documentos para esta empresa.</td>
          </tr>
        )}
        {documentos.map((d) => (
          <tr key={d.id}>
            <td style={celda}>
              <Link to={`/documentos/${d.id}`}>{d.id}</Link>
            </td>
            <td style={celda}>{d.origen}</td>
            <td style={celda}>
              <span style={{ padding: "0.1rem 0.5rem", borderRadius: 4, fontSize: "0.8rem", background: ESTADO_BG[d.estado] ?? "#eee" }}>
                {d.estado}
              </span>
            </td>
            <td style={celda}>{d.es_publico ? "sí" : "no"}</td>
            <td style={celda}>{new Date(d.created_at).toLocaleString()}</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}

const celda = { textAlign: "left", padding: "0.4rem 0.5rem", borderBottom: "1px solid #eee", fontSize: "0.9rem" };
