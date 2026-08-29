import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const API_BASE = import.meta.env.VITE_DOCUMENT_API_BASE_URL ?? "http://localhost:8000";

// Registro self-service (Fase 5, spec-plataforma.md): crea el Tenant y su primer Usuario
// AdminTenant sin intervencion de Plataforma. Publico -- fuera de ProtectedRoute.
export function RegisterPage() {
  const { login } = useAuth();
  const [nombreEmpresa, setNombreEmpresa] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const [enviando, setEnviando] = useState(false);
  const navigate = useNavigate();

  async function onSubmit(e) {
    e.preventDefault();
    setError(null);
    setEnviando(true);
    try {
      const formData = new FormData();
      formData.append("nombre_empresa", nombreEmpresa);
      formData.append("email", email);
      formData.append("password", password);
      const res = await fetch(`${API_BASE}/tenants`, { method: "POST", body: formData });
      if (!res.ok) {
        const data = await res.json().catch(() => null);
        throw new Error(data?.detail ?? "No se pudo crear la cuenta.");
      }
      // El usuario recien creado no requiere reset (eligio su propia contraseña) —
      // login() lo deja logueado de una, sin pasar por /reset-password.
      await login(email, password);
      navigate("/");
    } catch (err) {
      setError(err.message);
    } finally {
      setEnviando(false);
    }
  }

  return (
    <div className="page page-narrow">
      <form className="card" onSubmit={onSubmit}>
        <div className="card-body">
          <h1>Crear cuenta</h1>
          <p className="banner-muted">
            Registra tu operador (consultora/firma) — arranca con Venezuela habilitado.
          </p>
          {error && <div className="banner banner-error">{error}</div>}
          <label className="field">
            <span className="field-label">Nombre del operador</span>
            <input
              value={nombreEmpresa} onChange={(e) => setNombreEmpresa(e.target.value)}
              type="text" required
            />
          </label>
          <label className="field">
            <span className="field-label">Email</span>
            <input value={email} onChange={(e) => setEmail(e.target.value)} type="email" required />
          </label>
          <label className="field">
            <span className="field-label">Contraseña</span>
            <input
              value={password} onChange={(e) => setPassword(e.target.value)}
              type="password" required minLength={8}
            />
          </label>
          <button className="btn-primary btn-block" type="submit" disabled={enviando}>
            {enviando ? "Creando…" : "Crear cuenta"}
          </button>
          <p className="banner-muted">
            <Link to="/login">¿Ya tenés cuenta? Iniciar sesión</Link>
          </p>
        </div>
      </form>
    </div>
  );
}
