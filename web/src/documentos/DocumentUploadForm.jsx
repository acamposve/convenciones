import { useState } from "react";
import { useAuth } from "../context/AuthContext";

const PUEDE_CARGAR = ["AdminTenant", "Editor"]; // auth-spec.md §5

// Sube por archivo o por URL contra POST /documentos (Art IV pasos 1-5, ejecutado
// sincrono del lado del servicio Python). Espejo del formulario que antes vivia en
// service/app/templates/index.html.
export function DocumentUploadForm({ onUploaded }) {
  const { rol, docFetch } = useAuth();
  const [archivo, setArchivo] = useState(null);
  const [urlOrigen, setUrlOrigen] = useState("");
  const [esPublico, setEsPublico] = useState(false);
  const [enviando, setEnviando] = useState(false);
  const [error, setError] = useState(null);

  if (!PUEDE_CARGAR.includes(rol)) {
    return (
      <p style={{ color: "#555", fontSize: "0.9rem" }}>
        Tu rol ({rol}) no tiene permiso para cargar documentos (auth-spec.md §5).
      </p>
    );
  }

  async function subir(formData) {
    setEnviando(true);
    setError(null);
    try {
      const res = await docFetch("/documentos", { method: "POST", body: formData });
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.detail ?? "No se pudo cargar el documento.");
      }
      const documento = await res.json();
      onUploaded?.(documento);
    } catch (err) {
      setError(err.message);
    } finally {
      setEnviando(false);
    }
  }

  function onSubmitArchivo(e) {
    e.preventDefault();
    if (!archivo) return;
    const formData = new FormData();
    formData.append("origen", "archivo");
    formData.append("archivo", archivo);
    subir(formData);
  }

  function onSubmitUrl(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("origen", "url");
    formData.append("url_origen", urlOrigen);
    formData.append("es_publico", esPublico ? "true" : "false");
    subir(formData);
  }

  return (
    <div style={{ marginBottom: "1.5rem" }}>
      {error && (
        <div style={{ background: "#fde2e2", border: "1px solid #e07a7a", padding: "0.6rem 0.9rem", borderRadius: 6, marginBottom: "1rem" }}>
          {error}
        </div>
      )}

      <fieldset style={{ border: "1px solid #ddd", borderRadius: 8, marginBottom: "1.2rem" }}>
        <legend style={{ padding: "0 0.4rem", fontWeight: 600 }}>Subir documento (archivo PDF o Word)</legend>
        <form onSubmit={onSubmitArchivo} style={{ padding: "0.6rem 0.9rem 0.9rem" }}>
          <input
            type="file"
            accept=".pdf,.docx"
            required
            onChange={(e) => setArchivo(e.target.files[0] ?? null)}
          />
          <button type="submit" disabled={enviando} style={{ display: "block", marginTop: "0.8rem" }}>
            Ingestar archivo
          </button>
        </form>
      </fieldset>

      <fieldset style={{ border: "1px solid #ddd", borderRadius: 8 }}>
        <legend style={{ padding: "0 0.4rem", fontWeight: 600 }}>Ingresar documento por URL</legend>
        <form onSubmit={onSubmitUrl} style={{ padding: "0.6rem 0.9rem 0.9rem" }}>
          <label style={{ display: "block", marginBottom: "0.2rem", fontSize: "0.9rem" }}>
            URL del documento (PDF o Word)
          </label>
          <input
            type="url"
            required
            placeholder="https://..."
            value={urlOrigen}
            onChange={(e) => setUrlOrigen(e.target.value)}
            style={{ width: "100%", padding: "0.4rem", boxSizing: "border-box" }}
          />
          <label style={{ display: "flex", alignItems: "center", gap: "0.4rem", marginTop: "0.6rem", fontSize: "0.9rem" }}>
            <input type="checkbox" checked={esPublico} onChange={(e) => setEsPublico(e.target.checked)} />
            Marcar como público (se valida que la URL responda sin autenticación antes de aceptarlo)
          </label>
          <button type="submit" disabled={enviando} style={{ display: "block", marginTop: "0.8rem" }}>
            Ingestar por URL
          </button>
        </form>
      </fieldset>
    </div>
  );
}
