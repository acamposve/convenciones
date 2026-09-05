import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";

const PUEDE_VER = ["PlataformaAdmin", "PlataformaSoporte", "PlataformaAuditor"];
const PUEDE_EDITAR = ["PlataformaAdmin", "PlataformaSoporte"];
const PUEDE_ADMIN = ["PlataformaAdmin"];

// Los endpoints de taxonomia (service/app/main.py) usan Form(...), no JSON -- a diferencia
// del resto de este panel, que habla con api/ (.NET) en JSON.
function toFormData(campos) {
  const formData = new FormData();
  Object.entries(campos).forEach(([k, v]) => formData.append(k, v));
  return formData;
}

// Panel de Plataforma (Fase 5, spec-plataforma.md): gestion de tenants despues de creados.
// El alta de un tenant nuevo es self-service (RegisterPage.jsx) -- esto no crea tenants,
// los administra.
export function PlataformaPage() {
  const { rol, logout, authFetch, docFetch } = useAuth();
  const [tenants, setTenants] = useState(null);
  const [paises, setPaises] = useState(null);
  const [error, setError] = useState(null);
  const [reloadToken, setReloadToken] = useState(0);
  const [licenciaForm, setLicenciaForm] = useState({}); // { [tenantId]: {planLicencia, fechaVencimiento} }
  const [nuevoUsuario, setNuevoUsuario] = useState({ email: "", password: "", rol: "PlataformaSoporte" });

  // Fase 8 (spec-taxonomia-por-pais.md Bloque B/D, Art II.3): a diferencia de todo lo de
  // arriba (api/, .NET), el clonado/edicion de taxonomia vive en el servicio Python -- por
  // eso usa docFetch, no authFetch (ver nota junto a los endpoints en service/app/main.py).
  const [categorias, setCategorias] = useState([]);
  const [taxonomiaPaisId, setTaxonomiaPaisId] = useState("");
  const [taxonomiaTitulos, setTaxonomiaTitulos] = useState(null);
  const [clonarOrigenId, setClonarOrigenId] = useState("");
  const [nuevoTitulo, setNuevoTitulo] = useState({ categoria_id: "", nombre: "", descripcion: "" });
  const [edicionTitulo, setEdicionTitulo] = useState(null); // { id, nombre, descripcion, categoria_id }
  const [taxonomiaError, setTaxonomiaError] = useState(null);

  const cargar = () => {
    authFetch("/api/plataforma/tenants")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la lista de tenants.");
        return res.json();
      })
      .then(setTenants)
      .catch((err) => setError(err.message));
    authFetch("/api/plataforma/paises")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la lista de países.");
        return res.json();
      })
      .then(setPaises)
      .catch(() => setPaises([]));
  };

  useEffect(cargar, [authFetch, reloadToken]);

  useEffect(() => {
    docFetch("/taxonomia/categorias")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudieron cargar las categorías.");
        return res.json();
      })
      .then(setCategorias)
      .catch(() => setCategorias([]));
  }, [docFetch]);

  const cargarTitulosPais = () => {
    setTaxonomiaError(null);
    setTaxonomiaTitulos(null);
    if (!taxonomiaPaisId) return;
    docFetch(`/plataforma/taxonomia/titulos?pais_id=${taxonomiaPaisId}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudieron cargar los títulos de este país.");
        return res.json();
      })
      .then(setTaxonomiaTitulos)
      .catch((err) => setTaxonomiaError(err.message));
  };

  useEffect(cargarTitulosPais, [docFetch, taxonomiaPaisId]);

  if (!PUEDE_VER.includes(rol)) {
    return (
      <div className="page">
        <p className="banner-muted">Tu rol ({rol}) no tiene acceso al panel de Plataforma.</p>
      </div>
    );
  }

  async function accion(path, options, mensajeError) {
    setError(null);
    try {
      const res = await authFetch(path, options);
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.message ?? mensajeError);
      }
      setReloadToken((t) => t + 1);
    } catch (err) {
      setError(err.message);
    }
  }

  async function accionTaxonomia(path, options, mensajeError) {
    setTaxonomiaError(null);
    try {
      const res = await docFetch(path, options);
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.detail ?? mensajeError);
      }
      cargarTitulosPais();
      return true;
    } catch (err) {
      setTaxonomiaError(err.message);
      return false;
    }
  }

  function clonarTaxonomia(e) {
    e.preventDefault();
    if (!clonarOrigenId) return;
    accionTaxonomia(
      "/plataforma/taxonomia/clonar",
      { method: "POST", body: toFormData({ pais_origen_id: clonarOrigenId, pais_destino_id: taxonomiaPaisId }) },
      "No se pudo clonar la taxonomía."
    );
  }

  function agregarTitulo(e) {
    e.preventDefault();
    accionTaxonomia(
      "/plataforma/taxonomia/titulos",
      { method: "POST", body: toFormData({ ...nuevoTitulo, pais_id: taxonomiaPaisId }) },
      "No se pudo agregar el título."
    ).then((ok) => {
      if (ok) setNuevoTitulo({ categoria_id: "", nombre: "", descripcion: "" });
    });
  }

  function guardarEdicionTitulo() {
    if (!edicionTitulo) return;
    const { id, ...datos } = edicionTitulo;
    accionTaxonomia(
      `/plataforma/taxonomia/titulos/${id}`,
      { method: "PUT", body: toFormData(datos) },
      "No se pudo editar el título."
    ).then((ok) => {
      if (ok) setEdicionTitulo(null);
    });
  }

  function toggleActivoTitulo(titulo) {
    accionTaxonomia(
      `/plataforma/taxonomia/titulos/${titulo.id}/activo`,
      { method: "PUT", body: toFormData({ activo: !titulo.activo }) },
      "No se pudo actualizar el título."
    );
  }

  function guardarLicencia(tenantId) {
    const form = licenciaForm[tenantId];
    if (!form) return;
    accion(
      `/api/plataforma/tenants/${tenantId}/licencia`,
      {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ planLicencia: form.planLicencia, fechaVencimiento: form.fechaVencimiento || null }),
      },
      "No se pudo actualizar la licencia."
    );
  }

  function togglePais(tenantId, paisId, habilitado) {
    accion(
      `/api/plataforma/tenants/${tenantId}/paises/${paisId}`,
      { method: habilitado ? "DELETE" : "POST" },
      "No se pudo actualizar el país habilitado."
    );
  }

  function toggleSuspension(tenantId, suspendido) {
    accion(
      `/api/plataforma/tenants/${tenantId}/${suspendido ? "reactivar" : "suspender"}`,
      { method: "POST" },
      "No se pudo actualizar el estado del tenant."
    );
  }

  function activarPaisGlobal(paisId, activo) {
    accion(
      `/api/plataforma/paises/${paisId}/activo`,
      {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ activo }),
      },
      "No se pudo actualizar la activación global del país."
    );
  }

  async function crearUsuarioPlataforma(e) {
    e.preventDefault();
    setError(null);
    try {
      const res = await authFetch("/api/plataforma/usuarios", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(nuevoUsuario),
      });
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.message ?? "No se pudo crear el usuario de Plataforma.");
      }
      setNuevoUsuario({ email: "", password: "", rol: "PlataformaSoporte" });
      window.alert("Usuario creado. Requiere resetear su contraseña en el primer login.");
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="page page-wide">
      <div className="app-header">
        <h1>
          Panel de Plataforma <span className="role-tag">({rol})</span>
        </h1>
        <button className="btn-secondary" onClick={logout}>Cerrar sesión</button>
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      <h2>Tenants</h2>
      {tenants === null ? (
        <p className="banner-muted">Cargando…</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Operador</th>
              <th>Plan</th>
              <th>Vencimiento</th>
              <th>Países habilitados</th>
              <th>Estado</th>
              {PUEDE_EDITAR.includes(rol) && <th>Acciones</th>}
            </tr>
          </thead>
          <tbody>
            {tenants.map((t) => (
              <tr key={t.id}>
                <td>{t.nombreEmpresa}</td>
                <td>
                  {PUEDE_EDITAR.includes(rol) ? (
                    <input
                      type="text"
                      value={licenciaForm[t.id]?.planLicencia ?? t.planLicencia}
                      onChange={(e) =>
                        setLicenciaForm((f) => ({ ...f, [t.id]: { ...f[t.id], planLicencia: e.target.value } }))
                      }
                      style={{ width: "6rem" }}
                    />
                  ) : (
                    t.planLicencia
                  )}
                </td>
                <td>
                  {PUEDE_EDITAR.includes(rol) ? (
                    <input
                      type="date"
                      value={licenciaForm[t.id]?.fechaVencimiento ?? t.fechaVencimiento ?? ""}
                      onChange={(e) =>
                        setLicenciaForm((f) => ({ ...f, [t.id]: { ...f[t.id], fechaVencimiento: e.target.value } }))
                      }
                    />
                  ) : (
                    t.fechaVencimiento ?? "—"
                  )}
                </td>
                <td>
                  {paises?.map((p) => {
                    const habilitado = t.paisesHabilitados.includes(p.codigo);
                    if (!habilitado && !PUEDE_EDITAR.includes(rol)) return null;
                    return (
                      <label key={p.id} style={{ display: "inline-block", marginRight: "0.6rem" }}>
                        <input
                          type="checkbox"
                          checked={habilitado}
                          disabled={!PUEDE_EDITAR.includes(rol)}
                          onChange={() => togglePais(t.id, p.id, habilitado)}
                        />{" "}
                        {p.codigo}
                      </label>
                    );
                  })}
                </td>
                <td>
                  <span className={`badge ${t.suspendido ? "badge-error" : "badge-aprobado"}`}>
                    {t.suspendido ? "Suspendido" : "Activo"}
                  </span>
                </td>
                {PUEDE_EDITAR.includes(rol) && (
                  <td>
                    <button className="btn-secondary" onClick={() => guardarLicencia(t.id)}>Guardar licencia</button>{" "}
                    <button className="btn-secondary" onClick={() => toggleSuspension(t.id, t.suspendido)}>
                      {t.suspendido ? "Reactivar" : "Suspender"}
                    </button>
                  </td>
                )}
              </tr>
            ))}
          </tbody>
        </table>
      )}

      <h2>Países (activación legal global, Art. II.4)</h2>
      {paises === null ? (
        <p className="banner-muted">Cargando…</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Activo</th>
              {PUEDE_ADMIN.includes(rol) && <th>Acción</th>}
            </tr>
          </thead>
          <tbody>
            {paises.map((p) => (
              <tr key={p.id}>
                <td>{p.codigo}</td>
                <td>{p.nombre}</td>
                <td>
                  <span className={`badge ${p.activo ? "badge-aprobado" : ""}`}>{p.activo ? "sí" : "no"}</span>
                </td>
                {PUEDE_ADMIN.includes(rol) && (
                  <td>
                    <button className="btn-secondary" onClick={() => activarPaisGlobal(p.id, !p.activo)}>
                      {p.activo ? "Desactivar" : "Activar"}
                    </button>
                  </td>
                )}
              </tr>
            ))}
          </tbody>
        </table>
      )}

      <h2>Taxonomía por país (Art. II.3)</h2>
      {taxonomiaError && <div className="banner banner-error">{taxonomiaError}</div>}
      <label className="field" style={{ maxWidth: "20rem" }}>
        <span className="field-label">País</span>
        <select
          value={taxonomiaPaisId}
          onChange={(e) => {
            setTaxonomiaError(null);
            setEdicionTitulo(null);
            setClonarOrigenId("");
            setTaxonomiaPaisId(e.target.value);
          }}
        >
          <option value="">— elegir país —</option>
          {paises?.map((p) => (
            <option key={p.id} value={p.id}>{p.nombre}</option>
          ))}
        </select>
      </label>

      {taxonomiaPaisId && taxonomiaTitulos === null && <p className="banner-muted">Cargando títulos…</p>}

      {taxonomiaPaisId && taxonomiaTitulos !== null && taxonomiaTitulos.length === 0 && (
        <div className="card">
          <div className="card-body">
            <p>
              Este país todavía no tiene títulos propios. Un país nuevo arranca clonando el set de
              otro ya activo (spec-taxonomia-por-pais.md §3.1) — a partir de ahí es independiente.
            </p>
            {PUEDE_ADMIN.includes(rol) ? (
              <form onSubmit={clonarTaxonomia} style={{ display: "flex", gap: "0.6rem", alignItems: "flex-end" }}>
                <label className="field">
                  <span className="field-label">Clonar desde</span>
                  <select required value={clonarOrigenId} onChange={(e) => setClonarOrigenId(e.target.value)}>
                    <option value="">— elegir país origen —</option>
                    {paises?.filter((p) => String(p.id) !== taxonomiaPaisId).map((p) => (
                      <option key={p.id} value={p.id}>{p.nombre}</option>
                    ))}
                  </select>
                </label>
                <button className="btn-primary" type="submit">Clonar</button>
              </form>
            ) : (
              <p className="banner-muted">Necesitás rol PlataformaAdmin para clonar.</p>
            )}
          </div>
        </div>
      )}

      {taxonomiaTitulos && taxonomiaTitulos.length > 0 && (
        <>
          <table className="table">
            <thead>
              <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Activo</th>
                {PUEDE_ADMIN.includes(rol) && <th>Acciones</th>}
              </tr>
            </thead>
            <tbody>
              {taxonomiaTitulos.map((t) =>
                edicionTitulo?.id === t.id ? (
                  <tr key={t.id}>
                    <td>
                      <input
                        type="text"
                        value={edicionTitulo.nombre}
                        onChange={(e) => setEdicionTitulo((f) => ({ ...f, nombre: e.target.value }))}
                        style={{ marginBottom: "0.3rem", width: "100%" }}
                      />
                      <input
                        type="text"
                        placeholder="Descripción"
                        value={edicionTitulo.descripcion}
                        onChange={(e) => setEdicionTitulo((f) => ({ ...f, descripcion: e.target.value }))}
                        style={{ width: "100%" }}
                      />
                    </td>
                    <td>
                      <select
                        value={edicionTitulo.categoria_id}
                        onChange={(e) => setEdicionTitulo((f) => ({ ...f, categoria_id: e.target.value }))}
                      >
                        {categorias.map((c) => (
                          <option key={c.id} value={c.id}>{c.nombre}</option>
                        ))}
                      </select>
                    </td>
                    <td>
                      <span className={`badge ${t.activo ? "badge-aprobado" : ""}`}>{t.activo ? "sí" : "no"}</span>
                    </td>
                    <td>
                      <button className="btn-primary" onClick={guardarEdicionTitulo}>Guardar</button>{" "}
                      <button className="btn-secondary" onClick={() => setEdicionTitulo(null)}>Cancelar</button>
                    </td>
                  </tr>
                ) : (
                  <tr key={t.id}>
                    <td>{t.nombre}</td>
                    <td>{t.categoria_nombre}</td>
                    <td>
                      <span className={`badge ${t.activo ? "badge-aprobado" : ""}`}>{t.activo ? "sí" : "no"}</span>
                    </td>
                    {PUEDE_ADMIN.includes(rol) && (
                      <td>
                        <button
                          className="btn-secondary"
                          onClick={() =>
                            setEdicionTitulo({
                              id: t.id, nombre: t.nombre, descripcion: t.descripcion ?? "",
                              categoria_id: t.categoria_id,
                            })
                          }
                        >
                          Editar
                        </button>{" "}
                        <button className="btn-secondary" onClick={() => toggleActivoTitulo(t)}>
                          {t.activo ? "Desactivar" : "Activar"}
                        </button>
                      </td>
                    )}
                  </tr>
                )
              )}
            </tbody>
          </table>

          {PUEDE_ADMIN.includes(rol) && (
            <form onSubmit={agregarTitulo} className="card">
              <div className="card-body">
                <h3>Agregar título</h3>
                <label className="field">
                  <span className="field-label">Nombre</span>
                  <input
                    type="text" required value={nuevoTitulo.nombre}
                    onChange={(e) => setNuevoTitulo((f) => ({ ...f, nombre: e.target.value }))}
                  />
                </label>
                <label className="field">
                  <span className="field-label">Categoría</span>
                  <select
                    required value={nuevoTitulo.categoria_id}
                    onChange={(e) => setNuevoTitulo((f) => ({ ...f, categoria_id: e.target.value }))}
                  >
                    <option value="">— elegir categoría —</option>
                    {categorias.map((c) => (
                      <option key={c.id} value={c.id}>{c.nombre}</option>
                    ))}
                  </select>
                </label>
                <label className="field">
                  <span className="field-label">Descripción</span>
                  <input
                    type="text" value={nuevoTitulo.descripcion}
                    onChange={(e) => setNuevoTitulo((f) => ({ ...f, descripcion: e.target.value }))}
                  />
                </label>
                <button className="btn-primary" type="submit">Agregar título</button>
              </div>
            </form>
          )}
        </>
      )}

      {PUEDE_ADMIN.includes(rol) && (
        <>
          <h2>Nuevo usuario de Plataforma</h2>
          <form onSubmit={crearUsuarioPlataforma} className="card">
            <div className="card-body">
              <label className="field">
                <span className="field-label">Email</span>
                <input
                  type="email" required value={nuevoUsuario.email}
                  onChange={(e) => setNuevoUsuario((u) => ({ ...u, email: e.target.value }))}
                />
              </label>
              <label className="field">
                <span className="field-label">Contraseña temporal</span>
                <input
                  type="password" required minLength={8} value={nuevoUsuario.password}
                  onChange={(e) => setNuevoUsuario((u) => ({ ...u, password: e.target.value }))}
                />
              </label>
              <label className="field">
                <span className="field-label">Rol</span>
                <select
                  value={nuevoUsuario.rol}
                  onChange={(e) => setNuevoUsuario((u) => ({ ...u, rol: e.target.value }))}
                >
                  <option value="PlataformaAdmin">PlataformaAdmin</option>
                  <option value="PlataformaSoporte">PlataformaSoporte</option>
                  <option value="PlataformaAuditor">PlataformaAuditor</option>
                </select>
              </label>
              <button className="btn-primary" type="submit">Crear usuario</button>
            </div>
          </form>
        </>
      )}
    </div>
  );
}
