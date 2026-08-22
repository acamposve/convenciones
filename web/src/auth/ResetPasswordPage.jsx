import { useState } from "react";
import { Navigate, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

// Usuarios migrados del legado (Art. VI.4) llegan aca con requiere_reset_password=true:
// LoginPage navega a esta ruta cuando el login responde resetRequired=true.
export function ResetPasswordPage() {
  const { resetPending, resetPassword } = useAuth();
  const [password, setPassword] = useState("");
  const [confirmacion, setConfirmacion] = useState("");
  const [error, setError] = useState(null);
  const [ok, setOk] = useState(false);
  const navigate = useNavigate();

  if (!resetPending) {
    // Acceso directo a la ruta sin pasar por login con reset pendiente.
    return <Navigate to="/login" replace />;
  }

  async function onSubmit(e) {
    e.preventDefault();
    setError(null);
    if (password !== confirmacion) {
      setError("Las contraseñas no coinciden.");
      return;
    }
    try {
      await resetPassword(password);
      setOk(true);
      setTimeout(() => navigate("/login"), 1500);
    } catch {
      setError("No se pudo actualizar la contraseña. El token puede haber expirado.");
    }
  }

  if (ok) {
    return (
      <div className="page page-narrow">
        <div className="banner banner-warning">Contraseña actualizada. Redirigiendo a iniciar sesión…</div>
      </div>
    );
  }

  return (
    <div className="page page-narrow">
      <form className="card" onSubmit={onSubmit}>
        <div className="card-body">
          <h1>Definir nueva contraseña</h1>
          <p className="banner-muted">Es tu primer inicio de sesión (o tu contraseña fue reseteada). Elegí una nueva.</p>
          {error && <div className="banner banner-error">{error}</div>}
          <label className="field">
            <span className="field-label">Nueva contraseña</span>
            <input value={password} onChange={(e) => setPassword(e.target.value)} type="password" required minLength={8} />
          </label>
          <label className="field">
            <span className="field-label">Confirmar contraseña</span>
            <input value={confirmacion} onChange={(e) => setConfirmacion(e.target.value)} type="password" required minLength={8} />
          </label>
          <button className="btn-primary btn-block" type="submit">Guardar</button>
        </div>
      </form>
    </div>
  );
}
