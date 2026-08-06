"""Añade los Goal Events (salidas) a los workflows de Academia Valenzuela.

Un workflow_goal con action="exit" saca al contacto de la secuencia en cuanto
recibe cualquiera de los tags indicados — equivale al patrón de casa "IF tiene
tag X que no siga", pero se evalúa de forma continua (no solo al llegar al
nodo), así que nunca se cuela un email a destiempo.

  SP01 (nurturing)   → sale con asesoria-agendada / matriculado-133
  LS03 (re-enganche) → sale con asesoria-agendada / matriculado-133
  RP02 (dunning)     → sale con alumno-recuperado / alumno-activo  ← el crítico

Uso:
    python3 builders/av-goals-builder.py --dry-run
    python3 builders/av-goals-builder.py
"""
import argparse
import sys
import uuid

LOC = "hBvP7lemQSMibPYcJPEP"

GOALS = {
    "SP01": (["asesoria-agendada", "matriculado-133"],
             "Salida: agendó asesoría o se matriculó"),
    "LS03": (["asesoria-agendada", "matriculado-133"],
             "Salida: agendó asesoría o se matriculó"),
    "RP02": (["alumno-recuperado", "alumno-activo"],
             "Salida: pagó — dejar de avisar YA"),
}


def goal_step(name, tags):
    return {
        "id": str(uuid.uuid4()),
        "type": "workflow_goal",
        "name": name,
        "attributes": {
            "op": "or",
            "segments": [{
                "op": "or",
                "conditions": [{
                    "goal_condition": "add_contact_tag",
                    "extras": {"tags": list(tags)},
                    "id": str(uuid.uuid4()),
                }],
            }],
            "type": "workflow_goal",
            "action": "exit",
        },
    }


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--dry-run", action="store_true")
    args = ap.parse_args()

    for code, (tags, name) in GOALS.items():
        print(f"{code}: {name} → tags {', '.join(tags)}")
    if args.dry_run:
        import json
        print("\n--- ejemplo de nodo ---")
        print(json.dumps(goal_step("Salida", ["x"]), indent=1)[:400])
        return

    from cli_anything.gohighlevel.utils.ghl_internal_client import InternalGHLClient, TokenManager
    c = InternalGHLClient(TokenManager(), LOC)
    ws = c.request("GET", f"/workflow/{LOC}")

    for code, (tags, name) in GOALS.items():
        wf = next((w for w in ws if w["name"].startswith(code)), None)
        if not wf:
            print(f"✗ {code} no encontrado"); continue
        full = c.request("GET", f"/workflow/{LOC}/{wf['id']}")
        steps = full["workflowData"]["templates"]
        if any(s.get("type") == "workflow_goal" for s in steps):
            print(f"· {code} ya tiene goal — no se duplica"); continue

        # El goal va como nodo suelto: se evalúa mientras el contacto esté dentro
        g = goal_step(name, tags)
        g["advanceCanvasMeta"] = {"position": {"x": 57.5, "y": 200}}
        g["cat"] = ""
        g["order"] = len(steps)
        new = steps + [g]
        r = c.request("PUT", f"/workflow/{LOC}/{wf['id']}",
                      {"name": wf["name"], "version": full.get("version", 1),
                       "workflowData": {"templates": new}})
        ok = r and not r.get("_error")
        print(f"{'✓' if ok else '✗'} {code}: goal añadido ({len(new)} pasos)" if ok else f"✗ {code}: {str(r)[:150]}")

    print("\nVerificación:")
    for code in GOALS:
        wf = next((w for w in c.request("GET", f"/workflow/{LOC}") if w["name"].startswith(code)), None)
        if wf:
            full = c.request("GET", f"/workflow/{LOC}/{wf['id']}")
            types = [s["type"] for s in full["workflowData"]["templates"]]
            print(f"  {code}: {types.count('workflow_goal')} goal · pasos: {len(types)}")


if __name__ == "__main__":
    main()
