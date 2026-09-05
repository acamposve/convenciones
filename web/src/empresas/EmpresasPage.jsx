import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const PUEDE_GESTIONAR = ["AdminTenant", "Editor"]; // spec-empresas-comparacion.md §3

// Catalogo de empresas del tenant (Art III de la constitucion, Bloque B) -- espejo
// moderno de empresas.php del legado, pero scoped a un solo tenant (Art VI.2).
export function EmpresasPage() {
  const { rol, docFetch } = useAuth();
  const [catalogos, setCatalogos] = useState(null);
  const [paisesHabilitados, setPaisesHabilitados] = useState(null);
  const [empresas, setEmpresas] = useState(null);
  const [localidades, setLocalidades] = useState([]);
  const [error, setError] = useState(null);
  const [enviando, setEnviando] = useState(false);
  const [reloadToken, setReloadToken] = useState(0);

  const [form, setForm] = useState({
    nombre: "",
    pais_id: "",
    rif: "",
    sector_id: "",
    tipo_id: "",
    categoria_id: "",
    actividad_id: "",
    estado_id: "",
    localidad_id: "",
    contacto_nombre: "",
    contacto_email: "",
  });

  useEffect(() => {
    docFetch("/catalogos")
      .then((res) => res.json())
      .then(setCatalogos)
      .catch(() => setError("No se pudieron cargar los catálogos."));
  }, [docFetch]);

  // Fase 8 (spec-taxonomia-por-pais.md §3.2/§4): el selector solo ofrece los paises que el
  // tenant tiene licenciados -- si es uno solo, se preselecciona (caso de hoy: Venezuela).
  useEffect(() => {
    docFetch("/tenants/paises-habilitados")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudieron cargar los países habilitados para tu tenant.");
        return res.json();
      })
      .then((paises) => {
        setPaisesHabilitados(paises);
        if (paises.length === 1) {
          setForm((f) => ({ ...f, pais_id: String(paises[0].id) }));
        }
      })
      .catch(() => setError("No se pudieron cargar los países habilitados para tu tenant."));
  }, [docFetch]);

  useEffect(() => {
    let cancelado = false;
    docFetch("/empresas")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar el listado de empresas.");
        return res.json();
      })
      .then((data) => {
        if (!cancelado) setEmpresas(data);
      })
      .catch((err) => {
        if (!cancelado) setError(err.message);
      });
    return () => {
      cancelado = true;
    };
  }, [docFetch, reloadToken]);

  // Cascada estado -> localidad, mismo patron que el legado (comparador.php, tbl_empresa_add.php).
  useEffect(() => {
    if (!form.estado_id) {
      setLocalidades([]);
      return;
    }
    docFetch(`/catalogos/localidades?estado_id=${form.estado_id}`)
      .then((res) => res.json())
      .then(setLocalidades)
      .catch(() => setLocalidades([]));
  }, [form.estado_id, docFetch]);

  if (!PUEDE_GESTIONAR.includes(rol)) {
    return (
      <div className="page">
        <p className="banner-muted">
          Tu rol ({rol}) no tiene permiso para gestionar el catálogo de empresas.
        </p>
      </div>
    );
  }

  function actualizarCampo(campo, valor) {
    setForm((f) => {
      const siguiente = { ...f, [campo]: valor };
      if (campo === "estado_id") siguiente.localidad_id = ""; // cambiar de estado invalida la localidad elegida
      return siguiente;
    });
  }

  async function onSubmit(e) {
    e.preventDefault();
    setEnviando(true);
    setError(null);
    try {
      const formData = new FormData();
      Object.entries(form).forEach(([k, v]) => {
        if (v !== "") formData.append(k, v);
      });
      const res = await docFetch("/empresas", { method: "POST", body: formData });
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.detail ?? "No se pudo crear la empresa.");
      }
      setForm({
        nombre: "",
        pais_id: paisesHabilitados?.length === 1 ? String(paisesHabilitados[0].id) : "",
        rif: "", sector_id: "", tipo_id: "", categoria_id: "", actividad_id: "",
        estado_id: "", localidad_id: "", contacto_nombre: "", contacto_email: "",
      });
      setReloadToken((t) => t + 1);
    } catch (err) {
      setError(err.message);
    } finally {
      setEnviando(false);
    }
  }

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/">&larr; volver a documentos</Link>
      <div className="app-header">
        <h1>Empresas</h1>
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      <fieldset className="card">
        <legend>Nueva empresa</legend>
        <form onSubmit={onSubmit} className="card-body">
          <label className="field">
            <span className="field-label">Nombre</span>
            <input
              type="text" required value={form.nombre}
              onChange={(e) => actualizarCampo("nombre", e.target.value)}
            />
          </label>
          <label className="field">
            <span className="field-label">País</span>
            <select
              required value={form.pais_id}
              onChange={(e) => actualizarCampo("pais_id", e.target.value)}
              disabled={paisesHabilitados === null}
            >
              <option value="">— elegir país —</option>
              {(paisesHabilitados ?? []).map((p) => (
                <option key={p.id} value={p.id}>{p.nombre}</option>
              ))}
            </select>
          </label>
          <label className="field">
            <span className="field-label">RIF</span>
            <input
              type="text" value={form.rif}
              onChange={(e) => actualizarCampo("rif", e.target.value)}
            />
          </label>

          {catalogos && (
            <>
              <label className="field">
                <span className="field-label">Sector</span>
                <select value={form.sector_id} onChange={(e) => actualizarCampo("sector_id", e.target.value)}>
                  <option value="">— sin especificar —</option>
                  {catalogos.sectores.map((s) => (
                    <option key={s.id} value={s.id}>{s.nombre}</option>
                  ))}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Tipo de empresa</span>
                <select value={form.tipo_id} onChange={(e) => actualizarCampo("tipo_id", e.target.value)}>
                  <option value="">— sin especificar —</option>
                  {catalogos.tipos_empresa.map((t) => (
                    <option key={t.id} value={t.id}>{t.nombre}</option>
                  ))}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Categoría de sector</span>
                <select value={form.categoria_id} onChange={(e) => actualizarCampo("categoria_id", e.target.value)}>
                  <option value="">— sin especificar —</option>
                  {catalogos.categorias_sector.map((c) => (
                    <option key={c.id} value={c.id}>{c.nombre}</option>
                  ))}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Actividad</span>
                <select value={form.actividad_id} onChange={(e) => actualizarCampo("actividad_id", e.target.value)}>
                  <option value="">— sin especificar —</option>
                  {catalogos.actividades_empresa.map((a) => (
                    <option key={a.id} value={a.id}>{a.nombre}</option>
                  ))}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Estado</span>
                <select value={form.estado_id} onChange={(e) => actualizarCampo("estado_id", e.target.value)}>
                  <option value="">— sin especificar —</option>
                  {catalogos.estados.map((es) => (
                    <option key={es.id} value={es.id}>{es.nombre}</option>
                  ))}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Localidad</span>
                <select
                  value={form.localidad_id}
                  onChange={(e) => actualizarCampo("localidad_id", e.target.value)}
                  disabled={!form.estado_id}
                >
                  <option value="">— sin especificar —</option>
                  {localidades.map((l) => (
                    <option key={l.id} value={l.id}>{l.nombre}</option>
                  ))}
                </select>
              </label>
            </>
          )}

          <label className="field">
            <span className="field-label">Persona de contacto</span>
            <input
              type="text" value={form.contacto_nombre}
              onChange={(e) => actualizarCampo("contacto_nombre", e.target.value)}
            />
          </label>
          <label className="field">
            <span className="field-label">Email de contacto</span>
            <input
              type="email" value={form.contacto_email}
              onChange={(e) => actualizarCampo("contacto_email", e.target.value)}
            />
          </label>

          <button className="btn-primary btn-block" type="submit" disabled={enviando}>
            {enviando ? "Guardando…" : "Crear empresa"}
          </button>
        </form>
      </fieldset>

      <h2>Catálogo</h2>
      {empresas === null ? (
        <p className="banner-muted">Cargando empresas…</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>País</th>
              <th>Sector</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Localidad</th>
              <th>Contacto</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {empresas.length === 0 && (
              <tr>
                <td className="table-empty" colSpan={8}>Todavía no hay empresas en el catálogo.</td>
              </tr>
            )}
            {empresas.map((e) => (
              <tr key={e.id}>
                <td>{e.nombre}</td>
                <td>{e.pais_nombre}</td>
                <td>{e.sector_nombre ?? "—"}</td>
                <td>{e.tipo_nombre ?? "—"}</td>
                <td>{e.estado_nombre ?? "—"}</td>
                <td>{e.localidad_nombre ?? "—"}</td>
                <td>{e.contacto_nombre ?? "—"}</td>
                <td>
                  <Link className="table-link-secondary" to={`/empresas/${e.id}/negociaciones`}>
                    negociaciones
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
