import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const PUEDE_VER = ["AdminTenant", "Revisor", "Editor"]; // spec-negociacion.md §4
const PUEDE_EDITAR = ["AdminTenant", "Editor"];
const PUEDE_CERRAR = ["AdminTenant"];

// Detalle de una negociacion (Art IV bis): registrar peticiones/ofertas/reuniones/acuerdos,
// y cerrar (genera Documento, entra al pipeline del Art IV) o reabrir (addendum, genera una
// nueva version sin tocar la anterior -- spec-negociacion.md §5).
export function NegociacionDetailPage() {
  const { id } = useParams();
  const { rol, docFetch } = useAuth();
  const [negociacion, setNegociacion] = useState(null);
  const [titulos, setTitulos] = useState([]);
  const [error, setError] = useState(null);
  const [procesando, setProcesando] = useState(false);
  const [reloadToken, setReloadToken] = useState(0);

  const [formPeticion, setFormPeticion] = useState({ nro_peticion: "", texto: "", titulo_id: "" });
  const [ofertaTexto, setOfertaTexto] = useState({}); // { [peticionId]: texto }
  const [formReunion, setFormReunion] = useState({ fecha: "", asistentes: "", resumen: "" });
  const [formAcuerdo, setFormAcuerdo] = useState({ titulo_id: "", texto_acordado: "", peticion_id: "", oferta_id: "" });

  // Fase 8 (spec-taxonomia-por-pais.md Bloque C): la taxonomia depende del pais de la
  // empresa de ESTA negociacion (Art II.3) -- se pide recien cuando se conoce ese pais,
  // nunca una lista global que podria mezclar titulos de otro pais.
  useEffect(() => {
    if (!negociacion?.empresa_pais_id) {
      setTitulos([]);
      return;
    }
    docFetch(`/taxonomia?pais_id=${negociacion.empresa_pais_id}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la taxonomía.");
        return res.json();
      })
      .then(setTitulos)
      .catch(() => setTitulos([]));
  }, [docFetch, negociacion?.empresa_pais_id]);

  useEffect(() => {
    let cancelado = false;
    docFetch(`/negociaciones/${id}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo cargar la negociación.");
        return res.json();
      })
      .then((data) => {
        if (!cancelado) setNegociacion(data);
      })
      .catch((err) => {
        if (!cancelado) setError(err.message);
      });
    return () => {
      cancelado = true;
    };
  }, [docFetch, id, reloadToken]);

  if (!PUEDE_VER.includes(rol)) {
    return (
      <div className="page">
        <p className="banner-muted">Tu rol ({rol}) no tiene permiso para ver negociaciones.</p>
      </div>
    );
  }

  if (error) return <div className="page page-wide"><div className="banner banner-error">{error}</div></div>;
  if (negociacion === null) return <div className="page page-wide"><p className="banner-muted">Cargando…</p></div>;

  const abierta = negociacion.estado === "abierta";
  const todasLasOfertas = negociacion.peticiones.flatMap((p) => p.ofertas.map((o) => ({ ...o, peticion_id: p.id })));

  async function enviar(path, formData, mensajeError) {
    setProcesando(true);
    setError(null);
    try {
      const res = await docFetch(path, { method: "POST", body: formData });
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.detail ?? mensajeError);
      }
      setReloadToken((t) => t + 1);
      return true;
    } catch (err) {
      setError(err.message);
      return false;
    } finally {
      setProcesando(false);
    }
  }

  async function crearPeticion(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("nro_peticion", formPeticion.nro_peticion);
    formData.append("texto", formPeticion.texto);
    if (formPeticion.titulo_id) formData.append("titulo_id", formPeticion.titulo_id);
    if (await enviar(`/negociaciones/${id}/peticiones`, formData, "No se pudo registrar la petición.")) {
      setFormPeticion({ nro_peticion: "", texto: "", titulo_id: "" });
    }
  }

  async function crearOferta(peticionId) {
    const texto = ofertaTexto[peticionId];
    if (!texto) return;
    const formData = new FormData();
    formData.append("texto", texto);
    if (await enviar(`/peticiones/${peticionId}/ofertas`, formData, "No se pudo registrar la oferta.")) {
      setOfertaTexto((o) => ({ ...o, [peticionId]: "" }));
    }
  }

  async function crearReunion(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("fecha", formReunion.fecha);
    if (formReunion.asistentes) formData.append("asistentes", formReunion.asistentes);
    if (formReunion.resumen) formData.append("resumen", formReunion.resumen);
    if (await enviar(`/negociaciones/${id}/reuniones`, formData, "No se pudo registrar la reunión.")) {
      setFormReunion({ fecha: "", asistentes: "", resumen: "" });
    }
  }

  async function crearAcuerdo(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append("titulo_id", formAcuerdo.titulo_id);
    formData.append("texto_acordado", formAcuerdo.texto_acordado);
    if (formAcuerdo.peticion_id) formData.append("peticion_id", formAcuerdo.peticion_id);
    if (formAcuerdo.oferta_id) formData.append("oferta_id", formAcuerdo.oferta_id);
    if (await enviar(`/negociaciones/${id}/acuerdos`, formData, "No se pudo registrar el acuerdo.")) {
      setFormAcuerdo({ titulo_id: "", texto_acordado: "", peticion_id: "", oferta_id: "" });
    }
  }

  async function cerrar() {
    if (!window.confirm("¿Cerrar la negociación? Se genera un Documento con el acuerdo vigente de cada título.")) return;
    await enviar(`/negociaciones/${id}/cerrar`, new FormData(), "No se pudo cerrar la negociación.");
  }

  async function reabrir() {
    await enviar(`/negociaciones/${id}/reabrir`, new FormData(), "No se pudo reabrir la negociación.");
  }

  return (
    <div className="page page-wide">
      <Link className="back-link" to={`/empresas/${negociacion.empresa_id}/negociaciones`}>&larr; volver a negociaciones</Link>
      <div className="app-header">
        <h1>Negociación — {negociacion.empresa_nombre}</h1>
        <span className={`badge badge-${abierta ? "clasificado" : "aprobado"}`}>
          {abierta ? "Abierta" : "Cerrada"}
        </span>
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      {PUEDE_CERRAR.includes(rol) && (
        <div className="card">
          <div className="card-body">
            {abierta ? (
              <button className="btn-primary" onClick={cerrar} disabled={procesando}>
                Cerrar negociación (genera Documento)
              </button>
            ) : (
              <button className="btn-secondary" onClick={reabrir} disabled={procesando}>
                Reabrir negociación (addendum)
              </button>
            )}
          </div>
        </div>
      )}

      {negociacion.documentos.length > 0 && (
        <>
          <h2>Documentos generados</h2>
          <table className="table">
            <thead>
              <tr><th>Versión</th><th>Estado</th><th>Creado</th><th></th></tr>
            </thead>
            <tbody>
              {negociacion.documentos.map((d) => (
                <tr key={d.id}>
                  <td>v{d.version_negociacion}</td>
                  <td><span className={`badge badge-${d.estado}`}>{d.estado}</span></td>
                  <td>{new Date(d.created_at).toLocaleString()}</td>
                  <td><Link to={`/documentos/${d.id}`}>ver documento</Link></td>
                </tr>
              ))}
            </tbody>
          </table>
        </>
      )}

      <h2>Peticiones</h2>
      {PUEDE_EDITAR.includes(rol) && abierta && (
        <form onSubmit={crearPeticion} className="card">
          <div className="card-body">
            <label className="field">
              <span className="field-label">N.º de petición</span>
              <input type="number" required value={formPeticion.nro_peticion}
                onChange={(e) => setFormPeticion((f) => ({ ...f, nro_peticion: e.target.value }))} />
            </label>
            <label className="field">
              <span className="field-label">Título sugerido (opcional)</span>
              <select value={formPeticion.titulo_id}
                onChange={(e) => setFormPeticion((f) => ({ ...f, titulo_id: e.target.value }))}>
                <option value="">— sin especificar —</option>
                {titulos.map((t) => <option key={t.id} value={t.id}>{t.categoria_nombre} · {t.nombre}</option>)}
              </select>
            </label>
            <label className="field">
              <span className="field-label">Texto</span>
              <textarea required value={formPeticion.texto}
                onChange={(e) => setFormPeticion((f) => ({ ...f, texto: e.target.value }))} />
            </label>
            <button className="btn-primary" type="submit" disabled={procesando}>Registrar petición</button>
          </div>
        </form>
      )}
      {negociacion.peticiones.length === 0 ? (
        <p className="banner-muted">Todavía no hay peticiones registradas.</p>
      ) : (
        negociacion.peticiones.map((p) => (
          <div key={p.id} className="card">
            <div className="card-body">
              <p><strong>Petición #{p.nro_peticion}</strong>{p.titulo_nombre ? ` — ${p.titulo_nombre}` : ""}</p>
              <p className="clause-texto">{p.texto}</p>
              {p.ofertas.map((o) => (
                <p key={o.id} className="clause-texto" style={{ marginLeft: "1.5rem" }}>↳ oferta: {o.texto}</p>
              ))}
              {PUEDE_EDITAR.includes(rol) && abierta && (
                <div style={{ display: "flex", gap: "0.5rem", marginTop: "0.5rem" }}>
                  <input
                    type="text" placeholder="Responder con una oferta…"
                    value={ofertaTexto[p.id] ?? ""}
                    onChange={(e) => setOfertaTexto((o) => ({ ...o, [p.id]: e.target.value }))}
                  />
                  <button className="btn-secondary" onClick={() => crearOferta(p.id)} disabled={procesando}>
                    Registrar oferta
                  </button>
                </div>
              )}
            </div>
          </div>
        ))
      )}

      <h2>Reuniones</h2>
      {PUEDE_EDITAR.includes(rol) && abierta && (
        <form onSubmit={crearReunion} className="card">
          <div className="card-body">
            <label className="field">
              <span className="field-label">Fecha</span>
              <input type="date" required value={formReunion.fecha}
                onChange={(e) => setFormReunion((f) => ({ ...f, fecha: e.target.value }))} />
            </label>
            <label className="field">
              <span className="field-label">Asistentes</span>
              <input type="text" value={formReunion.asistentes}
                onChange={(e) => setFormReunion((f) => ({ ...f, asistentes: e.target.value }))} />
            </label>
            <label className="field">
              <span className="field-label">Resumen</span>
              <textarea value={formReunion.resumen}
                onChange={(e) => setFormReunion((f) => ({ ...f, resumen: e.target.value }))} />
            </label>
            <button className="btn-primary" type="submit" disabled={procesando}>Registrar reunión</button>
          </div>
        </form>
      )}
      {negociacion.reuniones.length === 0 ? (
        <p className="banner-muted">Todavía no hay reuniones registradas.</p>
      ) : (
        <table className="table">
          <thead><tr><th>Fecha</th><th>Asistentes</th><th>Resumen</th></tr></thead>
          <tbody>
            {negociacion.reuniones.map((r) => (
              <tr key={r.id}>
                <td>{new Date(r.fecha).toLocaleDateString()}</td>
                <td>{r.asistentes ?? "—"}</td>
                <td>{r.resumen ?? "—"}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}

      <h2>Acuerdos</h2>
      {PUEDE_EDITAR.includes(rol) && abierta && (
        <form onSubmit={crearAcuerdo} className="card">
          <div className="card-body">
            <label className="field">
              <span className="field-label">Título</span>
              <select required value={formAcuerdo.titulo_id}
                onChange={(e) => setFormAcuerdo((f) => ({ ...f, titulo_id: e.target.value }))}>
                <option value="">— elegí un título —</option>
                {titulos.map((t) => <option key={t.id} value={t.id}>{t.categoria_nombre} · {t.nombre}</option>)}
              </select>
            </label>
            <label className="field">
              <span className="field-label">Petición de origen (opcional)</span>
              <select value={formAcuerdo.peticion_id}
                onChange={(e) => setFormAcuerdo((f) => ({ ...f, peticion_id: e.target.value }))}>
                <option value="">— sin especificar —</option>
                {negociacion.peticiones.map((p) => <option key={p.id} value={p.id}>Petición #{p.nro_peticion}</option>)}
              </select>
            </label>
            <label className="field">
              <span className="field-label">Oferta de origen (opcional)</span>
              <select value={formAcuerdo.oferta_id}
                onChange={(e) => setFormAcuerdo((f) => ({ ...f, oferta_id: e.target.value }))}>
                <option value="">— sin especificar —</option>
                {todasLasOfertas.map((o) => <option key={o.id} value={o.id}>oferta de petición #{negociacion.peticiones.find(p => p.id === o.peticion_id)?.nro_peticion}</option>)}
              </select>
            </label>
            <label className="field">
              <span className="field-label">Texto acordado</span>
              <textarea required value={formAcuerdo.texto_acordado}
                onChange={(e) => setFormAcuerdo((f) => ({ ...f, texto_acordado: e.target.value }))} />
            </label>
            <button className="btn-primary" type="submit" disabled={procesando}>Registrar acuerdo</button>
          </div>
        </form>
      )}
      {negociacion.acuerdos.length === 0 ? (
        <p className="banner-muted">Todavía no hay acuerdos registrados.</p>
      ) : (
        <table className="table">
          <thead><tr><th>Título</th><th>Texto acordado</th><th>Fecha</th></tr></thead>
          <tbody>
            {negociacion.acuerdos.map((a) => (
              <tr key={a.id}>
                <td>{a.titulo_nombre}</td>
                <td className="clause-texto">{a.texto_acordado}</td>
                <td>{new Date(a.created_at).toLocaleString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
