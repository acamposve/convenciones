import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

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
    return <div className="banner banner-error">{error}</div>;
  }

  if (documentos === null) {
    return <p className="banner-muted">Cargando documentos…</p>;
  }

  return (
    <table className="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Empresa</th>
          <th>Origen</th>
          <th>Estado</th>
          <th>Público</th>
          <th>Creado</th>
        </tr>
      </thead>
      <tbody>
        {documentos.length === 0 && (
          <tr>
            <td className="table-empty" colSpan={6}>Todavía no hay documentos cargados.</td>
          </tr>
        )}
        {documentos.map((d) => (
          <tr key={d.id}>
            <td>
              <Link to={`/documentos/${d.id}`}>{d.id}</Link>
            </td>
            <td>{d.empresa_nombre}</td>
            <td>{d.origen}</td>
            <td>
              <span className={`badge badge-${d.estado}`}>{d.estado}</span>
            </td>
            <td>{d.es_publico ? "sí" : "no"}</td>
            <td>{new Date(d.created_at).toLocaleString()}</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}
