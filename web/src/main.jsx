import React, { useState } from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { AuthProvider, useAuth } from "./context/AuthContext";
import { ProtectedRoute } from "./auth/ProtectedRoute";
import { LoginPage } from "./auth/LoginPage";
import { ResetPasswordPage } from "./auth/ResetPasswordPage";
import { DocumentUploadForm } from "./documentos/DocumentUploadForm";
import { DocumentList } from "./documentos/DocumentList";
import { DocumentDetail } from "./documentos/DocumentDetail";

// Alcance actual (spec-mvp-demo.md): solo carga y lectura de documentos clasificados.
// Sin cola de revision, sin reportes, sin selector — eso es fase posterior (Art X).
function DocumentosPage() {
  const { rol, logout } = useAuth();
  const [reloadToken, setReloadToken] = useState(0);

  return (
    <div style={{ maxWidth: 860, margin: "2rem auto", padding: "0 1rem" }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "baseline" }}>
        <h1 style={{ fontSize: "1.4rem" }}>
          Comparador de Documentos Legales <span style={{ fontSize: "0.8rem", color: "#555" }}>({rol})</span>
        </h1>
        <button onClick={logout} style={{ fontSize: "0.85rem" }}>Cerrar sesión</button>
      </div>
      <div style={{ background: "#fff3cd", border: "1px solid #f0c040", padding: "0.6rem 0.9rem", borderRadius: 6, fontSize: "0.9rem", margin: "1rem 0 1.5rem" }}>
        Clasificación automática por IA — sin revisión humana. Demo interna, no publicada.
      </div>

      <DocumentUploadForm onUploaded={() => setReloadToken((t) => t + 1)} />

      <h2 style={{ fontSize: "1.1rem" }}>Documentos</h2>
      <DocumentList reloadToken={reloadToken} />
    </div>
  );
}

function NoAutorizado() {
  return <h1>No tenés permiso para ver esta sección</h1>;
}

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/reset-password" element={<ResetPasswordPage />} />
          <Route path="/no-autorizado" element={<NoAutorizado />} />
          <Route
            path="/"
            element={
              <ProtectedRoute>
                <DocumentosPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/documentos/:id"
            element={
              <ProtectedRoute>
                <DocumentDetail />
              </ProtectedRoute>
            }
          />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>
);
