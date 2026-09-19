import React from "react";

export class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null, errorInfo: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error };
  }

  componentDidCatch(error, errorInfo) {
    console.error("[ErrorBoundary] Error no capturado en interfaz web:", error, errorInfo);
    this.setState({ errorInfo });
  }

  handleReload = () => {
    window.location.reload();
  };

  render() {
    if (this.state.hasError) {
      return (
        <div className="page page-narrow" style={{ marginTop: "3rem" }}>
          <div className="banner banner-error" style={{ padding: "1.5rem" }}>
            <h2>Ocurrió un error inesperado</h2>
            <p style={{ marginTop: "0.5rem", marginBottom: "1rem" }}>
              La aplicación encontró un problema inesperado al renderizar esta vista.
            </p>
            {this.state.error && (
              <pre
                style={{
                  background: "#1e1e1e",
                  color: "#ff8080",
                  padding: "0.75rem",
                  borderRadius: "4px",
                  overflowX: "auto",
                  fontSize: "0.85rem",
                }}
              >
                {this.state.error.toString()}
              </pre>
            )}
            <div style={{ marginTop: "1rem" }}>
              <button className="btn-primary" onClick={this.handleReload}>
                Recargar página
              </button>
            </div>
          </div>
        </div>
      );
    }

    return this.props.children;
  }
}
