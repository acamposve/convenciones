import { Navigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

// rolesPermitidos viene directo de la matriz de auth-spec.md §5.
// Ej: <ProtectedRoute rolesPermitidos={["AdminTenant", "Revisor"]}><ColaRevision /></ProtectedRoute>
export function ProtectedRoute({ rolesPermitidos, children }) {
  const { accessToken, rol } = useAuth();

  if (!accessToken) return <Navigate to="/login" replace />;

  if (rolesPermitidos && !rolesPermitidos.includes(rol)) {
    return <Navigate to="/no-autorizado" replace />;
  }

  return children;
}
