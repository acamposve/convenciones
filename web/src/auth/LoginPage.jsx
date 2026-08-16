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
    <form onSubmit={onSubmit} style={{ maxWidth: 360, margin: "4rem auto" }}>
      <h1>Iniciar sesión</h1>
      {error && <p style={{ color: "crimson" }}>{error}</p>}
      <label>
        Email
        <input value={email} onChange={(e) => setEmail(e.target.value)} type="email" required />
      </label>
      <label>
        Contraseña
        <input value={password} onChange={(e) => setPassword(e.target.value)} type="password" required />
      </label>
      <button type="submit">Entrar</button>
    </form>
  );
}
