#!/bin/bash
# Validación de reorganización arquitectónica
# Ejecutar desde la raíz del repo: bash VALIDATE_REORGANIZATION.sh

set -e

echo "🔍 Validando reorganización arquitectónica..."
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

pass() {
    echo -e "${GREEN}✓${NC} $1"
}

fail() {
    echo -e "${RED}✗${NC} $1"
}

warn() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# 1. Archivos gubernamental en raíz
echo "📖 Documentación gubernamental..."
for file in README.md ARCHITECTURE.md CONTRIBUTING.md SECURITY.md CHANGELOG.md LICENSE .env.example .editorconfig .nvmrc; do
    if [ -f "$file" ]; then
        pass "$file existe"
    else
        fail "$file falta"
    fi
done
echo ""

# 2. Documentación en docs/
echo "📚 Documentación en docs/..."
for file in docs/README.md docs/PHASES.md docs/constitution.md docs/spec-mvp-demo.md; do
    if [ -f "$file" ]; then
        pass "$file existe"
    else
        fail "$file falta"
    fi
done
echo ""

# 3. db/
echo "🗄️ Base de datos..."
if [ -f "db/README.md" ]; then
    pass "db/README.md existe"
else
    fail "db/README.md falta"
fi
if [ -d "db/data" ]; then
    pass "db/data/ existe (estructura para SQL histórico)"
else
    warn "db/data/ no existe — crear manualmente si hay SQL histórico"
fi
echo ""

# 4. web/
echo "⚛️ Frontend (web/)..."
for file in web/.prettierrc.json web/.eslintrc.json web/.env.example; do
    if [ -f "$file" ]; then
        pass "$file existe"
    else
        fail "$file falta"
    fi
done
if grep -q "lint" web/package.json && grep -q "format" web/package.json; then
    pass "web/package.json tiene scripts lint y format"
else
    fail "web/package.json falta scripts lint o format"
fi
echo ""

# 5. legacy/
echo "📜 Legacy..."
if [ -f "legacy/README.md" ]; then
    pass "legacy/README.md existe"
else
    fail "legacy/README.md falta"
fi
echo ""

# 6. .github/
echo "🔧 CI/CD..."
if [ -f ".github/workflows/README.md" ]; then
    pass ".github/workflows/README.md existe"
else
    fail ".github/workflows/README.md falta"
fi
echo ""

# 7. .gitignore
echo "🔐 Seguridad (.gitignore)..."
if [ -f ".gitignore" ]; then
    pass ".gitignore existe"
    if grep -q "\.env" .gitignore; then
        pass ".gitignore ignora .env"
    else
        warn ".gitignore no ignora .env — revisar seguridad"
    fi
else
    fail ".gitignore falta"
fi
echo ""

# 8. Resúmenes de reorganización
echo "📋 Documentos de resumen..."
if [ -f "ARCHITECTURE_REORGANIZATION_REPORT.md" ]; then
    pass "ARCHITECTURE_REORGANIZATION_REPORT.md existe"
else
    fail "ARCHITECTURE_REORGANIZATION_REPORT.md falta"
fi
if [ -f "REORGANIZATION_SUMMARY.md" ]; then
    pass "REORGANIZATION_SUMMARY.md existe"
else
    fail "REORGANIZATION_SUMMARY.md falta"
fi
echo ""

# 9. Manual actions
echo "📝 Acciones manuales pendientes..."
if [ -d "db/data" ]; then
    if [ -f "CCCOL.sql" ]; then
        warn "CCCOL.sql en raíz — mover a db/data/cccol-legado.sql"
    fi
    if [ -f "presenci_cccol.sql" ]; then
        warn "presenci_cccol.sql en raíz — mover a db/data/presencia-legado.sql"
    fi
else
    warn "db/data/ no existe — crear y luego mover SQL"
fi
echo ""

echo "=========================================="
echo "✅ Validación completada"
echo "=========================================="
echo ""
echo "⏭️  Próximos pasos:"
echo "  1. Si hay fallos (✗), corregirlos"
echo "  2. Si hay advertencias (⚠), revisar"
echo "  3. cd web && npm install && npm run lint"
echo "  4. Leer REORGANIZATION_SUMMARY.md"
echo ""
