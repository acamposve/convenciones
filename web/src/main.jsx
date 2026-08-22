import React, { useState } from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import "./index.css";
import { AuthProvider, useAuth } from "./context/AuthContext";
import { ProtectedRoute } from "./auth/ProtectedRoute";
import { LoginPage } from "./auth/LoginPage";
import { ResetPasswordPage } from "./auth/ResetPasswordPage";
import { DocumentUploadForm } from "./documentos/DocumentUploadForm";
import { DocumentList } from "./documentos/DocumentList";
import { DocumentDetail } from "./documentos/DocumentDetail";

// Alcance actual (spec-mvp-demo.md): solo carga y lectura de documentos clasificados.
// Sin cola de revision, sin reportes, sin selector — eso es fase posterior (Art X).
// (cambio trivial: smoke test del pipeline de deploy-apps.yml, primera prueba en Azure)
function DocumentosPage() {
  const { rol, logout } = useAuth();
  const [reloadToken, setReloadToken] = useState(0);

  return (
    <div className="page">
      <div className="app-header">
        <h1>
          Comparador de Documentos Legales <span className="role-tag">({rol})</span>
        </h1>
        <button className="btn-secondary" onClick={logout}>Cerrar sesión</button>
      </div>
      <div className="banner banner-warning">
        Clasificación automática por IA — sin revisión humana. Demo interna, no publicada.
      </div>

      <DocumentUploadForm onUploaded={() => setReloadToken((t) => t + 1)} />

      <h2>Documentos</h2>
      <DocumentList reloadToken={reloadToken} />
    </div>
  );
}

function NoAutorizado() {
  return (
    <div className="page page-narrow">
      <h1>No tenés permiso para ver esta sección</h1>
    </div>
  );
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
