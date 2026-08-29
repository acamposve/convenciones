import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";

const PUEDE_CARGAR = ["AdminTenant", "Editor"]; // auth-spec.md §5

// Sube por archivo o por URL contra POST /documentos (Art IV pasos 1-5, ejecutado
// sincrono del lado del servicio Python). Espejo del formulario que antes vivia en
// service/app/templates/index.html.
export function DocumentUploadForm({ onUploaded }) {
  const { rol, docFetch } = useAuth();
  const [empresas, setEmpresas] = useState(null);
  const [empresaId, setEmpresaId] = useState("");
  const [archivo, setArchivo] = useState(null);
  const [urlOrigen, setUrlOrigen] = useState("");
  const [esPublico, setEsPublico] = useState(false);
  const [enviando, setEnviando] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    docFetch("/empresas")
      .then((res) => res.json())
      .then(setEmpresas)
      .catch(() => setEmpresas([]));
  }, [docFetch]);

  if (!PUEDE_CARGAR.includes(rol)) {
    return (
      <p className="banner-muted">
        Tu rol ({rol}) no tiene permiso para cargar documentos (auth-spec.md §5).
      </p>
    );
  }

  if (empresas !== null && empresas.length === 0) {
    return (
      <div className="banner banner-warning">
        Todavía no hay ninguna empresa en tu catálogo — hay que <a href="/empresas">crear una</a> antes de poder cargar documentos (Bloque C: un documento siempre pertenece a una empresa).
      </div>
    );
  }

  async function subir(formData) {
    if (!empresaId) {
      setError("Elegí una empresa antes de subir el documento.");
      return;
    }
    formData.append("empresa_id", empresaId);
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
    <div>
      {error && <div className="banner banner-error">{error}</div>}

      <label className="field">
        <span className="field-label">Empresa (Bloque C: todo documento pertenece a una)</span>
        <select value={empresaId} onChange={(e) => setEmpresaId(e.target.value)} required>
          <option value="">— elegí una empresa —</option>
          {empresas?.map((e) => (
            <option key={e.id} value={e.id}>{e.nombre}</option>
          ))}
        </select>
      </label>

      <fieldset className="card">
        <legend>Subir documento (archivo PDF o Word)</legend>
        <form onSubmit={onSubmitArchivo} className="card-body">
          <input
            type="file"
            accept=".pdf,.docx"
            required
            onChange={(e) => setArchivo(e.target.files[0] ?? null)}
          />
          <button className="btn-primary btn-block" type="submit" disabled={enviando}>
            {enviando ? "Ingestando…" : "Ingestar archivo"}
          </button>
        </form>
      </fieldset>

      <fieldset className="card">
        <legend>Ingresar documento por URL</legend>
        <form onSubmit={onSubmitUrl} className="card-body">
          <label className="field">
            <span className="field-label">URL del documento (PDF o Word)</span>
            <input
              type="url"
              required
              placeholder="https://..."
              value={urlOrigen}
              onChange={(e) => setUrlOrigen(e.target.value)}
            />
          </label>
          <label className="checkbox-field">
            <input type="checkbox" checked={esPublico} onChange={(e) => setEsPublico(e.target.checked)} />
            Marcar como público (se valida que la URL responda sin autenticación antes de aceptarlo)
          </label>
          <button className="btn-primary btn-block" type="submit" disabled={enviando}>
            {enviando ? "Ingestando…" : "Ingestar por URL"}
          </button>
        </form>
      </fieldset>
    </div>
  );
}
