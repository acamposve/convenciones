import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

// Espejo de service/app/templates/documento.html: tabla de clausulas con el titulo de
// taxonomia que le asigno la clasificacion (Art IV.5) — sin score, sin revision (spec-mvp-demo.md).
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
    return <div style={{ background: "#fde2e2", border: "1px solid #e07a7a", padding: "0.6rem 0.9rem", borderRadius: 6, margin: "2rem auto", maxWidth: 960 }}>{error}</div>;
  }

  if (documento === null) {
    return <p style={{ color: "#555", margin: "2rem auto", maxWidth: 960 }}>Cargando documento…</p>;
  }

  return (
    <div style={{ maxWidth: 960, margin: "2rem auto", padding: "0 1rem" }}>
      <p><Link to="/">&larr; volver a documentos</Link></p>
      <h1 style={{ fontSize: "1.3rem" }}>Documento #{documento.id}</h1>

      <div style={{ background: "#fff3cd", border: "1px solid #f0c040", padding: "0.7rem 0.9rem", borderRadius: 6, fontWeight: 600, fontSize: "0.95rem", marginBottom: "1.2rem" }}>
        ⚠️ Clasificación automática por IA — sin revisión humana. Este resultado no ha sido validado por una persona ni está publicado.
      </div>

      {(documento.estado === "error" || documento.estado_detalle) && (
        <div style={{ background: "#fde2e2", border: "1px solid #e07a7a", padding: "0.6rem 0.9rem", borderRadius: 6, marginBottom: "1rem" }}>
          {documento.estado === "error" ? `Error en el pipeline: ${documento.estado_detalle}` : documento.estado_detalle}
        </div>
      )}

      <div style={{ fontSize: "0.9rem", color: "#444", marginBottom: "1.2rem" }}>
        <span style={{ marginRight: "1.2rem" }}>Origen: {documento.origen}</span>
        <span style={{ marginRight: "1.2rem" }}>Estado: {documento.estado}</span>
        <span style={{ marginRight: "1.2rem" }}>Público: {documento.es_publico ? "sí" : "no"}</span>
        {documento.url_origen && <span>URL: {documento.url_origen}</span>}
      </div>

      <table style={{ width: "100%", borderCollapse: "collapse" }}>
        <thead>
          <tr>
            <th style={{ ...celda, width: "3rem", background: "#fafafa" }}>#</th>
            <th style={{ ...celda, width: "14rem", background: "#fafafa" }}>Título asignado</th>
            <th style={{ ...celda, background: "#fafafa" }}>Texto de la cláusula</th>
          </tr>
        </thead>
        <tbody>
          {documento.clausulas.length === 0 && (
            <tr>
              <td style={celda} colSpan={3}>Todavía no hay cláusulas para este documento.</td>
            </tr>
          )}
          {documento.clausulas.map((c) => (
            <tr key={c.id}>
              <td style={celda}>{c.orden}</td>
              <td style={celda}>
                {c.titulo_nombre ? (
                  <>
                    <div style={{ fontWeight: 600 }}>{c.titulo_nombre}</div>
                    <div style={{ color: "#555", fontSize: "0.8rem" }}>{c.categoria_nombre}</div>
                  </>
                ) : (
                  <span style={{ color: "#b33", fontStyle: "italic" }}>sin clasificar</span>
                )}
              </td>
              <td style={{ ...celda, whiteSpace: "pre-wrap", maxWidth: 480 }}>{c.texto}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

const celda = { textAlign: "left", padding: "0.5rem 0.6rem", borderBottom: "1px solid #eee", verticalAlign: "top", fontSize: "0.9rem" };
