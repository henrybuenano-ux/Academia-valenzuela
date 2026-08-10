"""Arregla el bloque de curso de la home: CTA roto (404 al producto 132ª) + copy.

Probado end-to-end contra el staging el 10-ago-2026. Edita el árbol de
Elementor de la portada por la API de admin-ajax, con backup previo y
verificación posterior. DRY-RUN por defecto, como el resto de la casa.

    export WP_BASE=https://academiavalenz.com
    export WP_LOGIN=https://academiavalenz.com/av-login   # login oculto (WPS Hide Login)
    export WP_USER=... WP_PASS=...
    python3 tools/wp-fix-home-cta.py               # informa, no escribe
    python3 tools/wp-fix-home-cta.py --apply       # aplica y verifica

Es idempotente: si la home ya apunta al producto de la 133ª, no hace nada.
El backup del árbol queda en ./elementor_backup_<pageid>_<timestamp>.json —
para revertir: --restore <fichero>.
"""
from __future__ import annotations

import argparse
import copy
import http.cookiejar
import json
import os
import re
import sys
import time
import urllib.parse
import urllib.request

UA = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36"
SLUG_VIEJO = "curso-ingreso-guardia-civil-132-promocion"
SLUG_NUEVO = "curso-ingreso-guardia-civil-133a-promocion"

NUEVO_H2 = "Curso Ingreso Guardia Civil – 133ª Promoción"
NUEVO_P = (
    "<p>Prepárate para las <strong>oposiciones a la Guardia Civil</strong> con nuestro curso "
    "completo para la <strong>133ª promoción</strong>, que arranca el "
    "<strong>1 de septiembre</strong>. Formación actualizada, materiales exclusivos y tutorías "
    "personalizadas con <strong>Paco Valenzuela</strong>. Sin matrícula y sin permanencia.</p>"
)


def entorno() -> dict[str, str]:
    faltan = [k for k in ("WP_BASE", "WP_LOGIN", "WP_USER", "WP_PASS") if not os.environ.get(k)]
    if faltan:
        sys.exit(f"Faltan variables de entorno: {', '.join(faltan)}")
    return {k: os.environ[k].rstrip("/") if k == "WP_BASE" else os.environ[k] for k in
            ("WP_BASE", "WP_LOGIN", "WP_USER", "WP_PASS")}


def conectar(env: dict[str, str]):
    cj = http.cookiejar.LWPCookieJar()
    op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
    op.addheaders = [("User-Agent", UA), ("Accept-Language", "es-ES,es;q=0.9"),
                     ("Referer", env["WP_LOGIN"])]
    op.open(env["WP_LOGIN"], timeout=30).read()
    data = urllib.parse.urlencode({
        "log": env["WP_USER"], "pwd": env["WP_PASS"], "wp-submit": "Acceder",
        "redirect_to": env["WP_BASE"] + "/wp-admin/", "testcookie": "1",
    }).encode()
    op.open(urllib.request.Request(env["WP_LOGIN"], data=data), timeout=40).read()
    if not any(c.name.startswith("wordpress_logged_in") for c in cj):
        sys.exit("Login rechazado: revisa usuario/contraseña o si ese usuario existe en este entorno.")
    return op


def front_page_id(op, base: str) -> int:
    """La portada, leída de la clase `page-id-N` del body (fiable en cualquier tema).

    `options-reading.php` no sirve en este sitio: su select no aparece en el HTML.
    """
    home = op.open(f"{base}/", timeout=60).read().decode("utf-8", "ignore")
    m = re.search(r'<body[^>]*\bclass="[^"]*\bpage-id-(\d+)\b', home)
    if m:
        return int(m.group(1))
    m = re.search(r'\bpage-id-(\d+)\b', home)
    if m:
        return int(m.group(1))
    sys.exit("No se pudo determinar la página de inicio (sin clase page-id-N en el body).")


def _array_balanceado(s: str, inicio: int) -> str | None:
    depth, i, in_str, esc = 0, inicio, False, False
    while i < len(s):
        ch = s[i]
        if in_str:
            esc = (ch == "\\") and not esc
            if ch == '"' and not esc:
                in_str = False
        elif ch == '"':
            in_str, esc = True, False
        elif ch == "[":
            depth += 1
        elif ch == "]":
            depth -= 1
            if depth == 0:
                return s[inicio:i + 1]
        i += 1
    return None


def leer_arbol(op, base: str, pid: int) -> tuple[list, str]:
    ed = op.open(f"{base}/wp-admin/post.php?post={pid}&action=elementor", timeout=90)
    src = ed.read().decode("utf-8", "ignore")
    nonce = re.search(r'"ajax":\s*\{[^}]*"nonce":"([a-f0-9]{8,})"', src)
    if not nonce:
        sys.exit("No se encontró el nonce de Elementor en el editor.")
    ancla = src.find(SLUG_VIEJO)
    if ancla < 0:
        ancla = src.find(SLUG_NUEVO)
    if ancla < 0:
        sys.exit("La portada no contiene el bloque del curso (ni 132 ni 133).")
    for m in [x for x in re.finditer(r'"elements":\s*\[', src) if x.start() < ancla]:
        arr = _array_balanceado(src, src.index("[", m.start()))
        if not arr or (SLUG_VIEJO not in arr and SLUG_NUEVO not in arr):
            continue
        try:
            return json.loads(arr), nonce.group(1)
        except json.JSONDecodeError:
            continue
    sys.exit("No se pudo aislar el árbol de Elementor de la portada.")


def aplicar_cambios(arbol: list, base: str) -> list[tuple[str, str, str]]:
    url_nueva = f"{base}/producto/{SLUG_NUEVO}/"
    cambios: list[tuple[str, str, str]] = []

    def recorrer(nodos):
        for n in nodos:
            st = n.get("settings", {})
            tipo = n.get("widgetType")
            if tipo == "heading" and "132" in str(st.get("title", "")):
                cambios.append(("titular", st["title"], NUEVO_H2))
                st["title"] = NUEVO_H2
            elif tipo == "text-editor" and "132" in str(st.get("editor", "")):
                cambios.append(("párrafo", st["editor"][:70] + "…", NUEVO_P[:70] + "…"))
                st["editor"] = NUEVO_P
            elif tipo == "button" and SLUG_VIEJO in str(st.get("link", {}).get("url", "")):
                cambios.append(("botón", st["link"]["url"], url_nueva))
                st["link"]["url"] = url_nueva
            recorrer(n.get("elements", []))

    recorrer(arbol)
    return cambios


def guardar(op, base: str, pid: int, arbol: list, nonce: str) -> bool:
    payload = urllib.parse.urlencode({
        "action": "elementor_ajax", "_nonce": nonce, "editor_post_id": str(pid),
        "actions": json.dumps({"save_builder": {"action": "save_builder",
                               "data": {"status": "publish", "elements": arbol}}}, ensure_ascii=False),
    }).encode()
    req = urllib.request.Request(f"{base}/wp-admin/admin-ajax.php", data=payload)
    req.add_header("X-Requested-With", "XMLHttpRequest")
    req.add_header("Referer", f"{base}/wp-admin/post.php?post={pid}&action=elementor")
    resp = json.loads(op.open(req, timeout=120).read().decode("utf-8", "ignore"))
    return bool(resp.get("success"))


def verificar(op, base: str) -> None:
    time.sleep(3)
    src = op.open(f"{base}/?nocache={int(time.time())}", timeout=60).read().decode("utf-8", "ignore")
    texto = re.sub(r"<[^>]+>", " ", re.sub(r"(?is)<(script|style)[^>]*>.*?</\1>", " ", src))
    checks = [
        ("enlaces al producto 132 (404)", len(re.findall(SLUG_VIEJO, src)), 0),
        ("enlaces al producto 133", len(re.findall(SLUG_NUEVO, src)), 1),
        ("menciones '132ª'", len(re.findall(r"132[ªa]", texto)), 0),
        ("menciones '2025'", len(re.findall(r"\b2025\b", texto)), 0),
    ]
    print("\nVerificación:")
    for etiqueta, valor, esperado in checks:
        estado = "OK " if valor == esperado else "!! "
        print(f"  {estado}{etiqueta}: {valor} (esperado {esperado})")


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument("--apply", action="store_true", help="escribe de verdad")
    ap.add_argument("--restore", metavar="FICHERO", help="revierte el árbol desde un backup")
    args = ap.parse_args()

    env = entorno()
    op = conectar(env)
    pid = front_page_id(op, env["WP_BASE"])
    print(f"Conectado a {env['WP_BASE']} · portada = página {pid}")

    if args.restore:
        arbol = json.load(open(args.restore))
        _, nonce = leer_arbol(op, env["WP_BASE"], pid)
        print("Restaurando…", "OK" if guardar(op, env["WP_BASE"], pid, arbol, nonce) else "FALLÓ")
        return

    arbol, nonce = leer_arbol(op, env["WP_BASE"], pid)
    backup = f"elementor_backup_{pid}_{int(time.time())}.json"
    json.dump(arbol, open(backup, "w"), ensure_ascii=False)
    print(f"Backup del árbol -> {backup}")

    nuevo = copy.deepcopy(arbol)
    cambios = aplicar_cambios(nuevo, env["WP_BASE"])
    if not cambios:
        print("Nada que cambiar: la portada ya está actualizada.")
        return
    for tipo, antes, despues in cambios:
        print(f"  {tipo}:\n    antes: {antes}\n    ahora: {despues}")

    if not args.apply:
        print("\nDRY-RUN — no se ha escrito nada. Repite con --apply.")
        return
    print("\nGuardando…", "OK" if guardar(op, env["WP_BASE"], pid, nuevo, nonce) else "FALLÓ")
    verificar(op, env["WP_BASE"])


if __name__ == "__main__":
    main()
