"""LS03 · Segmento de re-enganche 132ª → 133ª.

Calcula el segmento por tags (reproducible, sin listas de ids commiteadas) y
opcionalmente lo etiqueta con `reenganche-133` — lo que DISPARA los 3 emails
de LS03. Por eso: DRY-RUN por defecto, como el plugin.

    python3 av-reenganche-segmento.py                 # solo informa (dry-run)
    python3 av-reenganche-segmento.py --apply --limit 5   # tanda de prueba
    python3 av-reenganche-segmento.py --apply             # los 69

Criterio (ver docs/entregables/reenganche_ls03_segmento_y_b2_2026-08-10.md):
incluye ex-alumnos que PAGARON (suscripción o intensivo); excluye becados y
convenio de colegio — su continuidad la decide Paco, no una campaña.
"""
from __future__ import annotations

import argparse
import json
import os
import ssl
import sys
import urllib.request

sys.path.insert(0, os.path.join(os.path.dirname(__file__), ".."))
from cli_anything.gohighlevel.utils.ghl_internal_client import (  # noqa: E402
    CHROME_UA,
    TokenManager,
)

BASE = "https://backend.leadconnectorhq.com"
TAG = "reenganche-133"
INCLUYE = {"pago-suscripcion", "pago-intensivo"}
EXCLUYE = {"becado", "colegio-173"}
CTX = ssl.create_default_context()


def _req(method: str, path: str, body: dict | None = None) -> tuple[int, str]:
    token = TokenManager().get_token()
    headers = {
        "token-id": token,
        "channel": "APP",
        "source": "WEB_USER",
        "Version": "2021-07-28",
        "Content-Type": "application/json",
        "Accept": "application/json",
        "User-Agent": CHROME_UA,
    }
    data = json.dumps(body).encode() if body is not None else None
    req = urllib.request.Request(BASE + path, data=data, headers=headers, method=method)
    try:
        with urllib.request.urlopen(req, context=CTX, timeout=25) as resp:
            return resp.status, resp.read().decode()
    except urllib.error.HTTPError as exc:
        return exc.code, (exc.read().decode() if exc.fp else "")


def todos_los_contactos(location: str) -> list[dict]:
    out: list[dict] = []
    page = 1
    while True:
        code, txt = _req(
            "POST", "/contacts/search/2", {"locationId": location, "pageLimit": 100, "page": page}
        )
        if code != 200:
            sys.exit(f"Error listando contactos: HTTP {code} {txt[:200]}")
        data = json.loads(txt)
        lote = data.get("contacts", [])
        out.extend(lote)
        if not lote or len(out) >= data.get("total", 0):
            return out
        page += 1


def segmentar(contactos: list[dict]) -> tuple[list[dict], dict[str, int]]:
    incluidos, motivos = [], {"becado/colegio": 0, "sin pago web / otros": 0, "sin email": 0}
    for c in contactos:
        tags = set(c.get("tags") or [])
        if EXCLUYE & tags:
            motivos["becado/colegio"] += 1
        elif not (INCLUYE & tags):
            motivos["sin pago web / otros"] += 1
        elif not (c.get("email") or "").strip():
            motivos["sin email"] += 1
        else:
            incluidos.append(c)
    return incluidos, motivos


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument("--apply", action="store_true", help="etiqueta de verdad (DISPARA LS03)")
    ap.add_argument("--limit", type=int, help="etiquetar solo los N primeros (tanda de prueba)")
    args = ap.parse_args()

    location = os.environ.get("GHL_LOCATION_ID", "").strip()
    if not location:
        sys.exit("Falta GHL_LOCATION_ID (usa: set -a; . gohighlevel-cli/.env; set +a)")

    incluidos, motivos = segmentar(todos_los_contactos(location))
    print(f"Segmento '{TAG}': {len(incluidos)} contactos")
    for motivo, n in motivos.items():
        print(f"  excluidos por {motivo}: {n}")

    objetivo = incluidos[: args.limit] if args.limit else incluidos
    if not args.apply:
        print(f"\nDRY-RUN — no se ha escrito nada. Se etiquetarían {len(objetivo)}.")
        print("Repasa el checklist del entregable antes de usar --apply: etiquetar")
        print("dispara los 3 emails de LS03 a personas reales.")
        return

    print(f"\nEtiquetando {len(objetivo)} contactos…")
    ok = 0
    for c in objetivo:
        tags = sorted(set(c.get("tags") or []) | {TAG})
        code, txt = _req("PUT", f"/contacts/{c['id']}", {"tags": tags})
        if code == 200:
            ok += 1
        else:
            print(f"  ERROR {c.get('contactName')}: HTTP {code} {txt[:120]}")
    print(f"Etiquetados {ok}/{len(objetivo)}.")


if __name__ == "__main__":
    main()
