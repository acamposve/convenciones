import React, { useState } from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import "./index.css";
import { AuthProvider, useAuth } from "./context/AuthContext";
import { ProtectedRoute } from "./auth/ProtectedRoute";
import { LoginPage } from "./auth/LoginPage";
import { RegisterPage } from "./auth/RegisterPage";
import { ResetPasswordPage } from "./auth/ResetPasswordPage";
import { DocumentUploadForm } from "./documentos/DocumentUploadForm";
import { DocumentList } from "./documentos/DocumentList";
import { DocumentDetail } from "./documentos/DocumentDetail";
import { EmpresasPage } from "./empresas/EmpresasPage";
import { RevisionPage } from "./revision/RevisionPage";
import { ComparadorPage } from "./comparador/ComparadorPage";
import { NegociacionesPage } from "./negociacion/NegociacionesPage";
import { NegociacionDetailPage } from "./negociacion/NegociacionDetailPage";
import { PlataformaPage } from "./plataforma/PlataformaPage";

// Fase 2 (constitution.md v2.0.0): Empresa + cola de revisión (Art IV.8) + comparador
// intra-tenant (Art IV.9) ya conviven con el pipeline de ingesta/clasificación de la
// Fase 1 (spec-mvp-demo.md).
function DocumentosPage() {
  const { rol, logout } = useAuth();
  const [reloadToken, setReloadToken] = useState(0);

  return (
    <div className="page">
      <div className="app-header">
        <h1>
          Comparador de Documentos Legales <span className="role-tag">({rol})</span>
        </h1>
        <div style={{ display: "flex", gap: "0.6rem", alignItems: "center" }}>
          <Link to="/empresas">Empresas</Link>
          <Link to="/revision">Revisión</Link>
          <Link to="/comparador">Comparador</Link>
          <button className="btn-secondary" onClick={logout}>Cerrar sesión</button>
        </div>
      </div>
      <div className="banner banner-warning">
        Clasificación automática por IA — pasa por revisión humana antes de poder compararse (Art. IV.8/IV.9).
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
          <Route path="/registro" element={<RegisterPage />} />
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
          <Route
            path="/empresas"
            element={
              <ProtectedRoute>
                <EmpresasPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/revision"
            element={
              <ProtectedRoute>
                <RevisionPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/comparador"
            element={
              <ProtectedRoute>
                <ComparadorPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/empresas/:empresaId/negociaciones"
            element={
              <ProtectedRoute>
                <NegociacionesPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/negociaciones/:id"
            element={
              <ProtectedRoute>
                <NegociacionDetailPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/plataforma"
            element={
              <ProtectedRoute>
                <PlataformaPage />
              </ProtectedRoute>
            }
          />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>
);
