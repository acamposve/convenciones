import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

// El nucleo del producto original (comparador.php del legado): comparar el mismo titulo
// de taxonomia entre varias empresas del catalogo, filtrando por sector/tipo/actividad/
// geografia. Intra-tenant (Art VI.2) y solo clausulas aprobadas (Art IV.9).
//
// Fase 6 (spec-resumen-ejecutivo.md Art IV.9 §5): si el resumen ejecutivo esta aprobado
// (estado_revision_resumen), se muestra colapsado con un control para desplegar el texto
// completo -- si no, se muestra el texto completo directo, nunca un resumen sin validar.
export function ComparadorPage() {
  const { docFetch } = useAuth();
  const [titulos, setTitulos] = useState(null);
  const [catalogos, setCatalogos] = useState(null);
  const [tituloId, setTituloId] = useState("");
  const [filtros, setFiltros] = useState({ sector_id: "", tipo_id: "", categoria_id: "", actividad_id: "", estado_id: "" });
  const [resultado, setResultado] = useState(null);
  const [error, setError] = useState(null);
  const [expandidas, setExpandidas] = useState({}); // { [clausulaId]: true }

  useEffect(() => {
    docFetch("/comparador/titulos").then((res) => res.json()).then(setTitulos).catch(() => setTitulos([]));
    docFetch("/catalogos").then((res) => res.json()).then(setCatalogos).catch(() => setCatalogos(null));
  }, [docFetch]);

  function buscar(e) {
    e?.preventDefault();
    if (!tituloId) return;
    setError(null);
    const params = new URLSearchParams({ titulo_id: tituloId });
    Object.entries(filtros).forEach(([k, v]) => {
      if (v) params.set(k, v);
    });
    docFetch(`/comparador?${params.toString()}`)
      .then((res) => {
        if (!res.ok) throw new Error("No se pudo generar la comparación.");
        return res.json();
      })
      .then(setResultado)
      .catch((err) => setError(err.message));
  }

  return (
    <div className="page page-wide">
      <Link className="back-link" to="/">&larr; volver a documentos</Link>
      <div className="app-header">
        <h1>Comparador</h1>
      </div>
      <div className="banner banner-warning">
        Solo se comparan cláusulas ya aprobadas en la cola de revisión (Art. IV.9) — dentro del catálogo de empresas de tu propia cuenta.
      </div>

      {error && <div className="banner banner-error">{error}</div>}

      <form onSubmit={buscar} className="card">
        <div className="card-body">
          <label className="field">
            <span className="field-label">Título comparativo</span>
            <select value={tituloId} onChange={(e) => setTituloId(e.target.value)} required>
              <option value="">— elegí un título —</option>
              {titulos?.map((t) => (
                <option key={t.id} value={t.id}>{t.categoria_nombre} · {t.nombre}</option>
              ))}
            </select>
          </label>

          {catalogos && (
            <>
              <label className="field">
                <span className="field-label">Filtrar por sector</span>
                <select value={filtros.sector_id} onChange={(e) => setFiltros((f) => ({ ...f, sector_id: e.target.value }))}>
                  <option value="">— todos —</option>
                  {catalogos.sectores.map((s) => <option key={s.id} value={s.id}>{s.nombre}</option>)}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Filtrar por tipo de empresa</span>
                <select value={filtros.tipo_id} onChange={(e) => setFiltros((f) => ({ ...f, tipo_id: e.target.value }))}>
                  <option value="">— todos —</option>
                  {catalogos.tipos_empresa.map((t) => <option key={t.id} value={t.id}>{t.nombre}</option>)}
                </select>
              </label>
              <label className="field">
                <span className="field-label">Filtrar por estado</span>
                <select value={filtros.estado_id} onChange={(e) => setFiltros((f) => ({ ...f, estado_id: e.target.value }))}>
                  <option value="">— todos —</option>
                  {catalogos.estados.map((es) => <option key={es.id} value={es.id}>{es.nombre}</option>)}
                </select>
              </label>
            </>
          )}

          <button className="btn-primary btn-block" type="submit">Comparar</button>
        </div>
      </form>

      {resultado && (
        resultado.length === 0 ? (
          <p className="banner-muted">Ninguna empresa tiene cláusulas aprobadas para este título (con estos filtros).</p>
        ) : (
          <div style={{ display: "flex", gap: "1rem", overflowX: "auto", marginTop: "1.25rem" }}>
            {resultado.map((emp) => (
              <div key={emp.empresa_id} className="card" style={{ minWidth: 260, flex: "1 0 260px" }}>
                <div className="card-body">
                  <h2 style={{ fontSize: "1rem" }}>{emp.empresa_nombre}</h2>
                  {emp.clausulas.map((cl) => {
                    const tieneResumen = Boolean(cl.resumen_ejecutivo);
                    const desplegada = expandidas[cl.id];
                    return (
                      <div key={cl.id} style={{ marginBottom: "0.75rem" }}>
                        {cl.campo_comparativo && (
                          <div className="badge" style={{ marginBottom: "0.25rem" }}>{cl.campo_comparativo}</div>
                        )}
                        {tieneResumen && !desplegada ? (
                          <>
                            <p className="clause-texto">{cl.resumen_ejecutivo}</p>
                            <button
                              type="button"
                              className="btn-secondary"
                              onClick={() => setExpandidas((e) => ({ ...e, [cl.id]: true }))}
                            >
                              Ver texto completo
                            </button>
                          </>
                        ) : (
                          <>
                            <p className="clause-texto">{cl.texto}</p>
                            {tieneResumen && (
                              <button
                                type="button"
                                className="btn-secondary"
                                onClick={() => setExpandidas((e) => ({ ...e, [cl.id]: false }))}
                              >
                                Ver resumen
                              </button>
                            )}
                          </>
                        )}
                      </div>
                    );
                  })}
                </div>
              </div>
            ))}
          </div>
        )
      )}
    </div>
  );
}
