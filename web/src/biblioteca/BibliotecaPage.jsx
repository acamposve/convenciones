import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const DOCUMENT_API_BASE = import.meta.env.VITE_DOCUMENT_API_BASE_URL ?? "http://localhost:8000";

// Art VI.7 / spec-biblioteca-publica.md: unica pagina del producto sin sesion -- directorio
// de solo lectura de documentos publicos de TODOS los tenants. No usa docFetch (no hay JWT
// que adjuntar) ni AuthContext. Nunca muestra clausulas ni de que tenant es cada empresa,
// solo el nombre de la empresa y el link al documento original.
export function BibliotecaPage() {
  const [empresa, setEmpresa] = useState("");
  const [resultado, setResultado] = useState(null);
  const [error, setError] = useState(null);

  function buscar(empresaQuery) {
    setError(null);
    const params = new URLSearchParams();
    if (empresaQuery) params.set("empresa", empresaQuery);
    fetch(`${DOCUMENT_API_BASE}/biblioteca?${params.toString()}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la biblioteca.");
        return res.json();
      })
      .then(setResultado)
      .catch((err) => setError(err.message));
  }

  useEffect(() => {
    buscar("");
  }, []);

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/login">&larr; volver a iniciar sesión</Link>
      <div className="app-header">
        <h1>Biblioteca pública</h1>
      </div>
      <div className="banner banner-warning">
        Directorio de convenciones colectivas que sus empresas eligieron hacer públicas (Art. VI.7). No requiere cuenta. Cada documento se lee en su fuente original.
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      <form
        className="card"
        onSubmit={(e) => {
          e.preventDefault();
          buscar(empresa);
        }}
      >
        <div className="card-body">
          <label className="field">
            <span className="field-label">Buscar por empresa</span>
            <input
              type="text"
              value={empresa}
              onChange={(e) => setEmpresa(e.target.value)}
              placeholder="Nombre de la empresa"
            />
          </label>
          <button className="btn-primary btn-block" type="submit">Buscar</button>
        </div>
      </form>

      {resultado === null ? (
        <p className="banner-muted">Cargando…</p>
      ) : resultado.length === 0 ? (
        <p className="banner-muted">Ninguna empresa tiene documentos públicos con ese nombre.</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Empresa</th>
              <th>Documento</th>
              <th>Publicado</th>
            </tr>
          </thead>
          <tbody>
            {resultado.map((doc, i) => (
              <tr key={i}>
                <td>{doc.empresa_nombre}</td>
                <td>
                  <a href={doc.url_origen} target="_blank" rel="noopener noreferrer">
                    Ver documento original
                  </a>
                </td>
                <td>{new Date(doc.created_at).toLocaleDateString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
