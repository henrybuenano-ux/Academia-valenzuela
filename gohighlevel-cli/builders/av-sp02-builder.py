"""SP02 · Asesoría agendada — workflow que cierra el circuito de captación.

Cuando un lead reserva en el calendario "Asesorías":
  1) tag `asesoria-agendada`  → es el GOAL EVENT que corta SP01 y LS03
     (el lead que ya agendó deja de recibir la secuencia de emails)
  2) oportunidad → Captación / "Agendado"  (create_opportunity es
     create-or-update: mueve la existente, no duplica)

TRIGGER: se intenta crear por API con los tipos candidatos de cita. Si la API
los rechaza (o aún no existe el calendario), el workflow queda con las
acciones listas y el trigger se elige en la UI en 1 clic:
    Customer Booked Appointment → filtro In Calendar = "Asesorías"

Uso:
    python3 builders/av-sp02-builder.py --dry-run
    python3 builders/av-sp02-builder.py
"""
import argparse
import json
import sys
import time
import uuid

from cli_anything.gohighlevel.utils.workflow_builder import link_steps, validate_campaign

LOC = "hBvP7lemQSMibPYcJPEP"
PIPELINE_CAPTACION = "Zfz0z86Mk3LaxHfK1yYb"
STAGE_AGENDADO = "fc6e5938-1ea4-4dd7-bc9b-9a7babe04d8e"
TAG = "asesoria-agendada"

# Tipos candidatos de trigger de cita en la API interna (se prueban en orden)
TRIGGER_TYPES = ["customer_booked_appointment", "appointment", "appointment_status"]


def steps():
    return link_steps([
        {
            "id": str(uuid.uuid4()),
            "type": "add_contact_tag",
            "name": f"Add Tag: {TAG}",
            "attributes": {"type": "add_contact_tag", "tags": [TAG], "fields": []},
        },
        {
            "id": str(uuid.uuid4()),
            "type": "create_opportunity",
            "name": "Oportunidad → Captación / Agendado",
            "attributes": {
                "fields": [],
                "type": "create_opportunity",
                "pipeline_id": PIPELINE_CAPTACION,
                "pipeline_stage_id": STAGE_AGENDADO,
                "opportunity_name": "",
                "opportunity_source": "Asesoría agendada",
                "monetary_value": "",
            },
        },
    ])


def main():
    p = argparse.ArgumentParser()
    p.add_argument("--dry-run", action="store_true")
    args = p.parse_args()

    tpl = steps()
    campaign = {"sp02": {"name": "SP02 · Asesoría agendada", "tag": None, "templates": tpl}}
    errors = [e for e in validate_campaign(campaign) if "tag" not in e.lower()]
    if errors:
        print("Errores:", errors); sys.exit(1)

    print(f"SP02 · Asesoría agendada — {len(tpl)} pasos:")
    for s in tpl:
        print(f"   · {s['type']}: {s['name']}")

    if args.dry_run:
        print("\n--- DRY RUN OK ---")
        print(json.dumps(tpl, indent=1)[:500])
        return

    from cli_anything.gohighlevel.utils.ghl_internal_client import InternalGHLClient, TokenManager
    c = InternalGHLClient(TokenManager(), LOC)

    # 1. Crear el workflow
    wf = c.request("POST", f"/workflow/{LOC}", {"name": "SP02 · Asesoría agendada"})
    wf_id = (wf or {}).get("id") or (wf or {}).get("_id")
    if not wf_id:
        print("ERROR creando workflow:", str(wf)[:300]); sys.exit(1)
    print(f"\n✓ Workflow creado: {wf_id}")

    # 2. Guardar los pasos (con posiciones de canvas para que se vea en la UI)
    steps_meta = []
    for i, s in enumerate(tpl):
        s = {**s, "advanceCanvasMeta": {"position": {"x": 400 + i * 300, "y": 0}}}
        s.setdefault("cat", ""); s.setdefault("order", i)
        steps_meta.append(s)
    put = c.request("PUT", f"/workflow/{LOC}/{wf_id}",
                    {"name": "SP02 · Asesoría agendada", "version": 1,
                     "workflowData": {"templates": steps_meta}})
    print("✓ Pasos guardados" if put and not put.get("_error") else f"✗ pasos: {str(put)[:200]}")

    # 3. Intentar el trigger de cita
    created = False
    for ttype in TRIGGER_TYPES:
        body = {
            "status": "draft", "workflowId": wf_id, "schedule_config": {},
            "conditions": [], "type": ttype, "masterType": "highlevel",
            "name": "Cita reservada (Asesorías)",
            "actions": [{"workflow_id": wf_id, "type": "add_to_workflow"}],
            "active": True, "triggersChanged": True, "location_id": LOC,
        }
        tr = c.request("POST", f"/workflow/{LOC}/trigger", body)
        if tr and tr.get("id"):
            print(f"✓ Trigger creado con type='{ttype}' (id {tr['id']})")
            first = steps_meta[0]["id"]
            c.request("PUT", f"/workflow/{LOC}/trigger/{tr['id']}",
                      {**body, "targetActionId": first,
                       "advanceCanvasMeta": {"position": {"x": 57.5, "y": -73}}})
            created = True
            break
        else:
            print(f"  · type='{ttype}' rechazado")
    if not created:
        print("⚠️  Trigger NO creado por API — elegirlo en la UI:")
        print("     Customer Booked Appointment → In Calendar = 'Asesorías'")

    print(f"\nURL: https://app.leadgenjay.com/location/{LOC}/workflow/{wf_id}")
    print("Queda en DRAFT. Activar cuando exista el calendario Asesorías.")


if __name__ == "__main__":
    main()
