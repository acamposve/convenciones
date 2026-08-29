import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export function DocumentDetail() {
  const { id } = useParams();
  const { docFetch } = useAuth();
  const [documento, setDocumento] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    let cancelado = false;
    docFetch(`/documentos/${id}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar el documento.");
        return res.json();
      })
      .then((data) => {
        if (!cancelado) setDocumento(data);
      })
      .catch((err) => {
        if (!cancelado) setError(err.message);
      });
    return () => {
      cancelado = true;
    };
  }, [docFetch, id]);

  if (error) {
    return (
      <div className="page page-wide">
        <div className="banner banner-error">{error}</div>
      </div>
    );
  }

  if (documento === null) {
    return (
      <div className="page page-wide">
        <p className="banner-muted">Cargando documento…</p>
      </div>
    );
  }

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/">&larr; volver a documentos</Link>
      <h1>Documento #{documento.id}</h1>

      <div className="banner banner-warning">
        ⚠️ Clasificación automática por IA — sin revisión humana. Este resultado no ha sido validado por una persona ni está publicado.
      </div>

      {(documento.estado === "error" || documento.estado_detalle) && (
        <div className="banner banner-error">
          {documento.estado === "error" ? `Error en el pipeline: ${documento.estado_detalle}` : documento.estado_detalle}
        </div>
      )}

      <div className="doc-meta">
        <span>Origen: {documento.origen}</span>
        <span>Estado: <span className={`badge badge-${documento.estado}`}>{documento.estado}</span></span>
        <span>Público: {documento.es_publico ? "sí" : "no"}</span>
        {documento.url_origen && <span>URL: {documento.url_origen}</span>}
      </div>

      <table className="table">
        <thead>
          <tr>
            <th style={{ width: "3rem" }}>#</th>
            <th style={{ width: "14rem" }}>Título asignado</th>
            <th>Texto de la cláusula</th>
            <th style={{ width: "8rem" }}>Revisión</th>
          </tr>
        </thead>
        <tbody>
          {documento.clausulas.length === 0 && (
            <tr>
              <td className="table-empty" colSpan={4}>Todavía no hay cláusulas para este documento.</td>
            </tr>
          )}
          {documento.clausulas.map((c) => (
            <tr key={c.id}>
              <td>{c.orden}</td>
              <td>
                {c.titulo_nombre ? (
                  <>
                    <div className="clause-titulo">{c.titulo_nombre}</div>
                    <div className="clause-categoria">{c.categoria_nombre}</div>
                  </>
                ) : (
                  <span className="clause-sin-clasificar">sin clasificar</span>
                )}
              </td>
              <td className="clause-texto">{c.texto}</td>
              <td>
                <span className={`badge badge-${c.estado_revision}`}>{c.estado_revision}</span>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
