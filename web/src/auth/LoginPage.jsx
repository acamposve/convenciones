import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export function LoginPage() {
  const { login, resetPending } = useAuth();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  async function onSubmit(e) {
    e.preventDefault();
    setError(null);
    try {
      const { resetRequired } = await login(email, password);
      if (resetRequired) {
        // Usuario migrado del legado (Art. VI.4): no tiene sesión completa
        // hasta que fije una contraseña nueva.
        navigate("/reset-password");
      } else {
        navigate("/");
      }
    } catch {
      setError("Email o contraseña incorrectos.");
    }
  }

  return (
    <div className="page page-narrow">
      <form className="card" onSubmit={onSubmit}>
        <div className="card-body">
          <h1>Iniciar sesión</h1>
          {error && <div className="banner banner-error">{error}</div>}
          <label className="field">
            <span className="field-label">Email</span>
            <input value={email} onChange={(e) => setEmail(e.target.value)} type="email" required />
          </label>
          <label className="field">
            <span className="field-label">Contraseña</span>
            <input value={password} onChange={(e) => setPassword(e.target.value)} type="password" required />
          </label>
          <button className="btn-primary btn-block" type="submit">Entrar</button>
        </div>
      </form>
    </div>
  );
}
