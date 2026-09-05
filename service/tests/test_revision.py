"""Fase 8 (spec-taxonomia-por-pais.md Bloque C, Art II.3): candado server-side de
aprobar_clausula() -- corregir una clausula con un titulo_id no puede aceptar cualquier
id de la taxonomia global, tiene que ser del mismo pais que la empresa dueña del
documento y estar activo. No hay infra de Postgres real en este repo para tests (ver
service/conftest.py) -- se fake-ea get_conn()/cursor() con una secuencia fija de
fetchone() que replica, en orden, las queries reales de aprobar_clausula(), asi el test
ejercita la funcion real (no una reimplementacion de la regla) sin una base real."""
import uuid
from contextlib import contextmanager
from datetime import datetime, timedelta, timezone

import jwt as pyjwt
import pytest
from fastapi import HTTPException
from starlette.requests import Request

from app import auth, main
from app.config import JWT_AUDIENCE, JWT_ISSUER, JWT_SIGNING_KEY


def _request(headers: dict[str, str] | None = None) -> Request:
    raw_headers = [(k.lower().encode(), v.encode()) for k, v in (headers or {}).items()]
    return Request({"type": "http", "headers": raw_headers})


def _token(role: str = "Revisor", **overrides) -> str:
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


def _revisor_request() -> Request:
    return _request({"Authorization": f"Bearer {_token()}"})


class _FakeCursor:
    """Devuelve, en orden, un fetchone() por llamada -- alcanza para simular la
    secuencia fija de queries de aprobar_clausula() sin una base real."""

    def __init__(self, fetchone_resultados):
        self._resultados = list(fetchone_resultados)

    def execute(self, query, params=None):
        pass

    def fetchone(self):
        return self._resultados.pop(0)

    def __enter__(self):
        return self

    def __exit__(self, *exc):
        return False


class _FakeConn:
    def __init__(self, cursor):
        self._cursor = cursor
        self.committed = False

    def cursor(self):
        return self._cursor

    def commit(self):
        self.committed = True

    def __enter__(self):
        return self

    def __exit__(self, *exc):
        return False


def _mockear_get_conn(monkeypatch, fetchone_resultados):
    @contextmanager
    def _fake_get_conn():
        yield _FakeConn(_FakeCursor(fetchone_resultados))

    monkeypatch.setattr(main, "get_conn", _fake_get_conn)


def test_aprobar_clausula_rechaza_titulo_id_de_otro_pais(monkeypatch):
    # clausula_fila: la empresa de esta clausula es de pais_id=2 (Uruguay). El segundo
    # fetchone (SELECT ... WHERE id=titulo_id AND pais_id=2 AND activo=true) no encuentra
    # nada porque el titulo_id pedido es, por ejemplo, el venezolano "Jornada de Trabajo".
    _mockear_get_conn(monkeypatch, [{"pais_id": 2}, None])

    with pytest.raises(HTTPException) as exc:
        main.aprobar_clausula(_revisor_request(), clausula_id=1, titulo_id=4, campo_comparativo=None)

    assert exc.value.status_code == 422


def test_aprobar_clausula_rechaza_titulo_id_desactivado(monkeypatch):
    # Mismo pais que la empresa, pero taxonomia_titulos.activo=false -- el filtro
    # "AND activo = true" de la query lo excluye igual que un titulo de otro pais.
    _mockear_get_conn(monkeypatch, [{"pais_id": 2}, None])

    with pytest.raises(HTTPException) as exc:
        main.aprobar_clausula(_revisor_request(), clausula_id=1, titulo_id=1099, campo_comparativo=None)

    assert exc.value.status_code == 422


def test_aprobar_clausula_acepta_titulo_id_del_mismo_pais_y_activo(monkeypatch):
    cursor = _FakeCursor([{"pais_id": 2}, {"categoria_id": 3}, {"id": 1}])
    conn = _FakeConn(cursor)

    @contextmanager
    def _fake_get_conn():
        yield conn

    monkeypatch.setattr(main, "get_conn", _fake_get_conn)

    resultado = main.aprobar_clausula(_revisor_request(), clausula_id=1, titulo_id=1002, campo_comparativo=None)

    assert resultado == {"id": 1, "estado_revision": "aprobado"}
    assert conn.committed


def test_aprobar_clausula_con_clausula_inexistente_da_404(monkeypatch):
    _mockear_get_conn(monkeypatch, [None])

    with pytest.raises(HTTPException) as exc:
        main.aprobar_clausula(_revisor_request(), clausula_id=999, titulo_id=1002, campo_comparativo=None)

    assert exc.value.status_code == 404


def test_aprobar_clausula_sin_titulo_id_no_valida_pais(monkeypatch):
    # Aprobar sin corregir el titulo (titulo_id=None) no pasa por el candado de pais --
    # solo la UPDATE final de estado_revision.
    _mockear_get_conn(monkeypatch, [{"id": 1}])

    resultado = main.aprobar_clausula(_revisor_request(), clausula_id=1, titulo_id=None, campo_comparativo=None)

    assert resultado == {"id": 1, "estado_revision": "aprobado"}


def test_aprobar_clausula_rechaza_rol_no_autorizado():
    request = _request({"Authorization": f"Bearer {_token(role='Visualizador')}"})

    with pytest.raises(HTTPException) as exc:
        main.aprobar_clausula(request, clausula_id=1, titulo_id=None, campo_comparativo=None)

    assert exc.value.status_code == 403
