"""Workflows "pegamento" de Academia Valenzuela: entrada de leads → pipeline Captación.

  landing   AV · Lead de landing → Captación (v1)
            Trigger tag: lead-landing-133 (el FORMULARIO de la landing debe
            aplicar este tag al enviarse — opción "Add tag" del form).
            Crea oportunidad en Captación / "Nuevo lead".
  bot       AV · Lead del bot → Captación (v1)
            Trigger tag: lead-bot (el BOT debe aplicar este tag al completar
            la cualificación — configurarlo en el Flow Builder del bot).
            Crea oportunidad en Captación / "Cualificado".

El mismo tag lead-landing-133 dispara en paralelo el workflow de nurturing —
comportamiento buscado: oportunidad y secuencia arrancan a la vez.

Remates en UI tras crear (no soportados por la API interna):
  - Paso "Internal Notification" al equipo en cada workflow.
  - La acción create_opportunity clonada del espejo no duplica si ya hay una
    abierta (comportamiento create-or-update) — verificar en la primera prueba.

Uso:
    python3 builders/av-glue-builder.py --dry-run
    python3 builders/av-glue-builder.py
"""
import argparse
import json
import sys
import uuid

from cli_anything.gohighlevel.utils.workflow_builder import (
    link_steps,
    validate_campaign,
)

PIPELINE_CAPTACION = "Zfz0z86Mk3LaxHfK1yYb"
STAGE_NUEVO_LEAD = "7eeecc15-2a40-4bb9-967f-ae694c6a844b"
STAGE_CUALIFICADO = "3e0a9c47-5124-47c6-8630-024f6022ac69"


def opportunity_step(name: str, stage_id: str, source: str) -> dict:
    # Estructura clonada del workflow espejo (create_opportunity) que ya
    # funciona en esta sub-cuenta; solo cambian etapa y fuente.
    return {
        "id": str(uuid.uuid4()),
        "type": "create_opportunity",
        "name": name,
        "attributes": {
            "fields": [],
            "type": "create_opportunity",
            "pipeline_id": PIPELINE_CAPTACION,
            "pipeline_stage_id": stage_id,
            "opportunity_name": "",
            "opportunity_source": source,
            "monetary_value": "",
        },
    }


WORKFLOWS = {
    "landing": {
        "name": "AV · Lead de landing → Captación (v1)",
        "tag": "lead-landing-133",
        "templates": [opportunity_step(
            "Oportunidad en Captación / Nuevo lead", STAGE_NUEVO_LEAD, "Landing 133")],
    },
    "bot": {
        "name": "AV · Lead del bot → Captación (v1)",
        "tag": "lead-bot",
        "templates": [opportunity_step(
            "Oportunidad en Captación / Cualificado", STAGE_CUALIFICADO, "Bot web")],
    },
}


def main():
    p = argparse.ArgumentParser(description="Workflows pegamento AV → Captación")
    p.add_argument("--dry-run", action="store_true")
    args = p.parse_args()

    campaign = {
        key: {"name": cfg["name"], "tag": cfg["tag"],
              "templates": link_steps(list(cfg["templates"]))}
        for key, cfg in WORKFLOWS.items()
    }

    errors = validate_campaign(campaign)
    if errors:
        print("Errores de validación:")
        [print(f"  - {e}") for e in errors]
        sys.exit(1)

    for key, cfg in WORKFLOWS.items():
        print(f"✓ {cfg['name']}  · trigger tag: {cfg['tag']} · {len(cfg['templates'])} paso(s)")

    if args.dry_run:
        print("\n--- DRY RUN OK ---")
        print(json.dumps(campaign, indent=1, default=str)[:600])
        return

    import os
    from cli_anything.gohighlevel.utils.ghl_internal_client import InternalGHLClient, TokenManager
    from cli_anything.gohighlevel.utils.workflow_builder import CampaignBuilder

    client = InternalGHLClient(TokenManager(), os.environ.get("GHL_LOCATION_ID", "hBvP7lemQSMibPYcJPEP"))
    builder = CampaignBuilder(client)
    builder.build(campaign, folder_name="AV Secuencias 133")
    print()
    print(builder.format_summary())
    print()
    print("REMATES EN UI:")
    print("  1. Añadir paso 'Internal Notification' al equipo en ambos workflows.")
    print("  2. Formulario de la landing: opción 'Add tag' → lead-landing-133.")
    print("  3. Bot: aplicar tag lead-bot al completar la cualificación (Flow Builder).")
    print("  4. Ambos quedan en DRAFT: activarlos junto con la landing / el bot.")


if __name__ == "__main__":
    main()
