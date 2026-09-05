import uuid
from datetime import datetime, timedelta, timezone

import jwt as pyjwt
import pytest
from fastapi import HTTPException
from starlette.requests import Request

from app import auth
from app.config import JWT_AUDIENCE, JWT_ISSUER, JWT_SIGNING_KEY


def _request(headers: dict[str, str] | None = None) -> Request:
    raw_headers = [(k.lower().encode(), v.encode()) for k, v in (headers or {}).items()]
    return Request({"type": "http", "headers": raw_headers})


def _token(role: str = "AdminTenant", **overrides) -> str:
    payload = {
        "user_id": str(uuid.uuid4()),
        "tenant_id": str(uuid.uuid4()),
        auth._ROLE_CLAIM: role,
        "pais": "VE",
        "iss": JWT_ISSUER,
        "aud": JWT_AUDIENCE,
        "exp": datetime.now(timezone.utc) + timedelta(minutes=5),
        **overrides,
    }
    return pyjwt.encode(payload, JWT_SIGNING_KEY, algorithm="HS256")


def test_get_claims_extrae_los_cuatro_claims_minimos_de_auth_spec():
    token = _token(role="Revisor")
    request = _request({"Authorization": f"Bearer {token}"})

    claims = auth.get_claims(request)

    assert claims.role == "Revisor"
    assert claims.pais == "VE"
    assert isinstance(claims.user_id, uuid.UUID)
    assert isinstance(claims.tenant_id, uuid.UUID)


def test_get_claims_sin_authorization_header_da_401():
    request = _request({})

    with pytest.raises(HTTPException) as exc:
        auth.get_claims(request)

    assert exc.value.status_code == 401


def test_get_claims_con_token_invalido_da_401():
    request = _request({"Authorization": "Bearer esto-no-es-un-jwt-valido"})

    with pytest.raises(HTTPException) as exc:
        auth.get_claims(request)

    assert exc.value.status_code == 401


def test_get_claims_con_token_expirado_da_401():
    token = _token(exp=datetime.now(timezone.utc) - timedelta(minutes=1))
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.get_claims(request)

    assert exc.value.status_code == 401


def test_get_claims_ignora_un_header_que_no_empieza_con_bearer():
    request = _request({"Authorization": _token()})  # falta el prefijo "Bearer "

    with pytest.raises(HTTPException) as exc:
        auth.get_claims(request)

    assert exc.value.status_code == 401


# Candado de negocio de auth-spec.md §5 — el mismo que protege, por ejemplo,
# GET /documentos en app/main.py.
def test_require_role_permite_cuando_el_rol_coincide():
    token = _token(role="Editor")
    request = _request({"Authorization": f"Bearer {token}"})

    claims = auth.require_role(request, "AdminTenant", "Editor")

    assert claims.role == "Editor"


def test_require_role_rechaza_con_403_cuando_el_rol_no_esta_permitido():
    token = _token(role="Visualizador")
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.require_role(request, "AdminTenant", "Revisor", "Editor")

    assert exc.value.status_code == 403


def _token_plataforma(role: str = "PlataformaAdmin", **overrides) -> str:
    # TokenService.cs omite el claim tenant_id por completo para un usuario de Plataforma
    # (nunca lo emite como null/"") -- este helper refleja exactamente eso, sin pasar
    # tenant_id salvo que un test lo pida explicitamente via overrides.
    payload = {
        "user_id": str(uuid.uuid4()),
        auth._ROLE_CLAIM: role,
        "iss": JWT_ISSUER,
        "aud": JWT_AUDIENCE,
        "exp": datetime.now(timezone.utc) + timedelta(minutes=5),
        **overrides,
    }
    return pyjwt.encode(payload, JWT_SIGNING_KEY, algorithm="HS256")


# Fase 8 (spec-taxonomia-por-pais.md §3.3): candado nuevo para los endpoints de taxonomia
# por pais -- exclusivo de un usuario de Plataforma, que nunca trae tenant_id en el token.
def test_get_plataforma_claims_extrae_user_id_y_rol_de_un_token_sin_tenant_id():
    token = _token_plataforma(role="PlataformaSoporte")
    request = _request({"Authorization": f"Bearer {token}"})

    claims = auth.get_plataforma_claims(request)

    assert claims.role == "PlataformaSoporte"
    assert isinstance(claims.user_id, uuid.UUID)


def test_get_plataforma_claims_sin_authorization_header_da_401():
    request = _request({})

    with pytest.raises(HTTPException) as exc:
        auth.get_plataforma_claims(request)

    assert exc.value.status_code == 401


def test_get_plataforma_claims_rechaza_con_403_un_token_de_tenant():
    # Un token de tenant valido (con tenant_id), aunque diga rol PlataformaAdmin, no debe
    # colarse por este candado -- es exclusivo de tokens que de verdad no tienen tenant_id.
    token = _token(role="PlataformaAdmin")
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.get_plataforma_claims(request)

    assert exc.value.status_code == 403


def test_get_plataforma_claims_con_user_id_mal_formado_da_401_no_500():
    # uuid.UUID(...) sin capturar convertiria un user_id invalido en un 500 -- debe
    # tratarse como token invalido (401), igual que un JWT malformado.
    token = _token_plataforma(user_id="esto-no-es-un-uuid")
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.get_plataforma_claims(request)

    assert exc.value.status_code == 401


def test_get_plataforma_claims_sin_user_id_da_401_no_500():
    token = pyjwt.encode(
        {
            auth._ROLE_CLAIM: "PlataformaAdmin",
            "iss": JWT_ISSUER,
            "aud": JWT_AUDIENCE,
            "exp": datetime.now(timezone.utc) + timedelta(minutes=5),
        },
        JWT_SIGNING_KEY,
        algorithm="HS256",
    )
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.get_plataforma_claims(request)

    assert exc.value.status_code == 401


def test_require_plataforma_role_permite_cuando_el_rol_coincide():
    token = _token_plataforma(role="PlataformaAdmin")
    request = _request({"Authorization": f"Bearer {token}"})

    claims = auth.require_plataforma_role(request, "PlataformaAdmin")

    assert claims.role == "PlataformaAdmin"


def test_require_plataforma_role_rechaza_con_403_cuando_el_rol_no_esta_permitido():
    token = _token_plataforma(role="PlataformaAuditor")
    request = _request({"Authorization": f"Bearer {token}"})

    with pytest.raises(HTTPException) as exc:
        auth.require_plataforma_role(request, "PlataformaAdmin")

    assert exc.value.status_code == 403
