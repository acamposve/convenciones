import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";

const PUEDE_VER = ["PlataformaAdmin", "PlataformaSoporte", "PlataformaAuditor"];
const PUEDE_EDITAR = ["PlataformaAdmin", "PlataformaSoporte"];
const PUEDE_ADMIN = ["PlataformaAdmin"];

// Panel de Plataforma (Fase 5, spec-plataforma.md): gestion de tenants despues de creados.
// El alta de un tenant nuevo es self-service (RegisterPage.jsx) -- esto no crea tenants,
// los administra.
export function PlataformaPage() {
  const { rol, logout, authFetch } = useAuth();
  const [tenants, setTenants] = useState(null);
  const [paises, setPaises] = useState(null);
  const [error, setError] = useState(null);
  const [reloadToken, setReloadToken] = useState(0);
  const [licenciaForm, setLicenciaForm] = useState({}); // { [tenantId]: {planLicencia, fechaVencimiento} }
  const [nuevoUsuario, setNuevoUsuario] = useState({ email: "", password: "", rol: "PlataformaSoporte" });

  const cargar = () => {
    authFetch("/api/plataforma/tenants")
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la lista de tenants.");
        return res.json();
      })
      .then(setTenants)
      .catch((err) => setError(err.message));
    authFetch("/api/plataforma/paises")
      .then((res) => res.json())
      .then(setPaises)
      .catch(() => setPaises([]));
  };

  useEffect(cargar, [authFetch, reloadToken]);

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
