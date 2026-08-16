import { createContext, useContext, useState, useCallback } from "react";

const AuthContext = createContext(null);

const API_BASE = import.meta.env.VITE_API_BASE_URL ?? "http://localhost:5080";
const DOCUMENT_API_BASE = import.meta.env.VITE_DOCUMENT_API_BASE_URL ?? "http://localhost:8000";

function decodeJwt(token) {
  const payload = token.split(".")[1];
  return JSON.parse(atob(payload.replace(/-/g, "+").replace(/_/g, "/")));
}

export function AuthProvider({ children }) {
  // En memoria, no localStorage: evita dejar el token expuesto ante XSS.
  // Trade-off: se pierde la sesión al refrescar la página (aceptable para MVP,
  // ver auth-spec.md §6 pendientes de TTL/refresh).
  const [accessToken, setAccessToken] = useState(null);
  const [claims, setClaims] = useState(null); // { user_id, tenant_id, role, pais }
  const [resetPending, setResetPending] = useState(null);

  const login = useCallback(async (email, password) => {
    const res = await fetch(`${API_BASE}/api/auth/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
    });
    if (!res.ok) throw new Error("Credenciales inválidas");
    const data = await res.json();

    if (data.resetRequired) {
      setResetPending(data.resetToken);
      return { resetRequired: true };
    }

    setAccessToken(data.accessToken);
    setClaims(decodeJwt(data.accessToken));
    return { resetRequired: false };
  }, []);

  const logout = useCallback(() => {
    setAccessToken(null);
    setClaims(null);
  }, []);

  const resetPassword = useCallback(
    async (newPassword) => {
      const res = await fetch(`${API_BASE}/api/auth/reset-password`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ resetToken: resetPending, newPassword }),
      });
      if (!res.ok) throw new Error("No se pudo actualizar la contraseña");
      setResetPending(null);
    },
    [resetPending]
  );

  // Rol viene del claim, nunca de estado local editable — evita escalar
  // permisos manipulando el store del cliente.
  const rol = claims?.role ?? null;
  const tenantId = claims?.tenant_id ?? null;

  const authFetch = useCallback(
    (path, options = {}) =>
      fetch(`${API_BASE}${path}`, {
        ...options,
        headers: {
          ...options.headers,
          Authorization: accessToken ? `Bearer ${accessToken}` : "",
        },
      }),
    [accessToken]
  );

  // Mismo patron que authFetch, pero contra el servicio Python de ingesta/clasificacion
  // (Art IV pasos 1-5) — es un microservicio separado (Art V), con su propio origen.
  const docFetch = useCallback(
    (path, options = {}) =>
      fetch(`${DOCUMENT_API_BASE}${path}`, {
        ...options,
        headers: {
          ...options.headers,
          Authorization: accessToken ? `Bearer ${accessToken}` : "",
        },
      }),
    [accessToken]
  );

  return (
    <AuthContext.Provider
      value={{ accessToken, rol, tenantId, resetPending, login, logout, resetPassword, authFetch, docFetch }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth debe usarse dentro de AuthProvider");
  return ctx;
}
