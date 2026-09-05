"""Validacion de JWT emitidos por api/ (docs/auth-spec.md) para proteger endpoints del
pipeline de ingesta que ya existen. Misma signing key que api/Program.cs — no hay una
llamada de red por request, solo se verifica la firma localmente."""
import uuid
from typing import Optional

import jwt
from fastapi import HTTPException, Request

from app.config import JWT_AUDIENCE, JWT_ISSUER, JWT_SIGNING_KEY

# ASP.NET Core serializa ClaimTypes.Role con esta URI larga, no como "role" simple.
_ROLE_CLAIM = "http://schemas.microsoft.com/ws/2008/06/identity/claims/role"


class TokenClaims:
    def __init__(self, user_id: uuid.UUID, tenant_id: uuid.UUID, role: str, pais: str):
        self.user_id = user_id
        self.tenant_id = tenant_id
        self.role = role
        self.pais = pais


def _extract_token(request: Request) -> Optional[str]:
    auth_header = request.headers.get("authorization")
    if auth_header and auth_header.lower().startswith("bearer "):
        return auth_header[7:]
    return None


def get_claims(request: Request) -> TokenClaims:
    token = _extract_token(request)
    if not token:
        raise HTTPException(401, "No autenticado: falta el token de acceso.")
    try:
        payload = jwt.decode(
            token,
            JWT_SIGNING_KEY,
            algorithms=["HS256"],
            issuer=JWT_ISSUER,
            audience=JWT_AUDIENCE,
        )
    except jwt.PyJWTError as exc:
        raise HTTPException(401, f"Token invalido o expirado: {exc}") from exc

    return TokenClaims(
        user_id=uuid.UUID(payload["user_id"]),
        tenant_id=uuid.UUID(payload["tenant_id"]),
        role=payload.get(_ROLE_CLAIM, ""),
        pais=payload.get("pais", ""),
    )


def require_role(request: Request, *roles: str) -> TokenClaims:
    """Candado de negocio de auth-spec.md §5. tenant_id sale SIEMPRE del claim del JWT,
    nunca de un parametro de la request (Art VI.2) — el llamador no debe volver a leer
    tenant_id de un form field o query param para esta operacion."""
    claims = get_claims(request)
    if claims.role not in roles:
        raise HTTPException(403, f"Rol '{claims.role}' no autorizado para esta accion.")
    return claims


class PlataformaClaims:
    def __init__(self, user_id: uuid.UUID, role: str):
        self.user_id = user_id
        self.role = role


def get_plataforma_claims(request: Request) -> PlataformaClaims:
    """Analogo a get_claims(), pero para un usuario de Plataforma (Art VII.4): TokenService
    (api/) omite el claim tenant_id por completo para estos usuarios -- get_claims() de
    arriba asume que el claim siempre existe y revienta con KeyError/TypeError si no. Un
    token de tenant (con tenant_id) tambien se rechaza aca: este candado es exclusivo para
    acciones de Plataforma (Fase 8, spec-taxonomia-por-pais.md §3.3)."""
    token = _extract_token(request)
    if not token:
        raise HTTPException(401, "No autenticado: falta el token de acceso.")
    try:
        payload = jwt.decode(
            token,
            JWT_SIGNING_KEY,
            algorithms=["HS256"],
            issuer=JWT_ISSUER,
            audience=JWT_AUDIENCE,
        )
    except jwt.PyJWTError as exc:
        raise HTTPException(401, f"Token invalido o expirado: {exc}") from exc

    if payload.get("tenant_id") is not None:
        raise HTTPException(403, "Este endpoint es exclusivo del rol de Plataforma.")

    try:
        user_id = uuid.UUID(payload["user_id"])
    except (KeyError, ValueError) as exc:
        raise HTTPException(401, f"Token invalido: falta o esta mal formado user_id ({exc}).") from exc

    return PlataformaClaims(user_id=user_id, role=payload.get(_ROLE_CLAIM, ""))


def require_plataforma_role(request: Request, *roles: str) -> PlataformaClaims:
    claims = get_plataforma_claims(request)
    if claims.role not in roles:
        raise HTTPException(403, f"Rol '{claims.role}' no autorizado para esta accion.")
    return claims
