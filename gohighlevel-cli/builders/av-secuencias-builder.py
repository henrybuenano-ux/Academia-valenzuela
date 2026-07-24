"""Construye los workflows de secuencias de Academia Valenzuela en GHL.

Cuatro workflows (copy de docs/entregables/*_2026-07-17.md, embebido aquí):

  nurturing-a   Lead nuevo de landing/bot.  Trigger tag: lead-landing-133
                A1 inmediato → +2d A2 → +2d A3 → +3d A4 → tag lead-frio-133
  reenganche-b  Ex-alumnos 132ª (segmento promo-132 + ultimo-estado-baja).
                Trigger tag: reenganche-133
                B1 → +4d B2 → +4d B3 → tag no-133
  dunning       Avisos de impago (política A6: 7 días de cortesía).
                Trigger tag: alumno-impago (lo pone el plugin SIN cortar)
                D0 amable → +3d D3 recordatorio → +3d D6 último aviso →
                +2d D8 post-corte → tag dunning-avisos-completados
                ⚠️ DEJAR EN DRAFT HASTA EL 1-SEP (modo real del plugin).
  recuperacion  Bienvenida al pagar. Trigger tag: alumno-recuperado
                Un solo email de confirmación.

Goal-events (configurar a mano en la UI tras crear el draft; el builder
no los soporta): salir si llega alumno-recuperado/alumno-activo (dunning)
o asesoria-agendada/matriculado-133 (nurturing/reenganche).

Uso:
    python3 builders/av-secuencias-builder.py --dry-run              # valida todo
    python3 builders/av-secuencias-builder.py --wf dunning --dry-run
    python3 builders/av-secuencias-builder.py --wf all               # crea en GHL (DRAFT)
    python3 builders/av-secuencias-builder.py --wf nurturing-a
"""
import argparse
import json
import sys

from cli_anything.gohighlevel.utils.workflow_builder import (
    email_step,
    link_steps,
    tag_step,
    validate_campaign,
    wait_step,
)

FROM_NAME = "Academia Valenzuela"
ENLACE_CUENTA = "https://academiavalenz.com/mi-cuenta/"
ENLACE_CALENDARIO = "{{CALENDARIO_ASESORIAS}}"  # sustituir en la UI al tener D1
ENLACE_MATRICULA = "https://academiavalenz.com/"

# === Copy (fuente: docs/entregables/ *_2026-07-17.md) =======================

NURTURING_A = [
    (0, "A1 Bienvenida", "Tu plaza en la 133ª: por dónde empezar",
     "Hola {{contact.first_name}}, gracias por tu interés en la 133ª promoción "
     "(arrancamos el 1 de septiembre). Dos caminos, elige el tuyo: si lo tienes "
     f"claro, matricúlate aquí {ENLACE_MATRICULA} y tienes acceso al campus desde "
     "el primer día; si prefieres resolver dudas antes, reserva una asesoría "
     f"gratuita de 10 minutos {ENLACE_CALENDARIO}. Un abrazo, Academia Valenzuela."),
    (2, "A2 El método", "Así se prepara una oposición que aprueba",
     "Qué nos diferencia: temario siempre actualizado, clases grabadas para ir a "
     "tu ritmo y —lo que más engancha a nuestros alumnos— los test con ranking "
     "real: sabes en todo momento en qué puesto estás frente al resto de la "
     "promoción, como el día del examen. ¿Lo vemos juntos en 10 minutos? "
     f"Reserva aquí: {ENLACE_CALENDARIO}"),
    (2, "A3 Objeciones", '"¿Y si no llego?" — la duda de todos los que empiezan',
     "Las tres dudas que más escuchamos: ¿voy tarde? (no: el campus completo está "
     "disponible desde el día 1), ¿me ata? (no: 80 €/mes sin permanencia, cancelas "
     "tú mismo en un clic), ¿podré compaginarlo? (las clases son grabadas — tú "
     "pones el horario). ¿Alguna más? Responde a este correo y te contesto "
     f"personalmente. O resérvame 10 minutos: {ENLACE_CALENDARIO}"),
    (3, "A4 Última llamada", "Cerramos tu hueco en la 133ª",
     "{{contact.first_name}}, no queremos ser pesados: este es el último correo "
     "de esta serie. Si la Guardia Civil sigue en tu cabeza, la 133ª empieza el "
     f"1 de septiembre y este es el enlace: {ENLACE_MATRICULA} . Si prefieres que "
     f"te llamemos y verlo en 10 minutos: {ENLACE_CALENDARIO} . Y si no es tu "
     "momento, todo bien — aquí estaremos."),
]

REENGANCHE_B = [
    (0, "B1 Te esperamos", "La 133ª empieza el 1 de septiembre — ¿repetimos?",
     "Hola {{contact.first_name}}, fuiste parte de la 132ª y sabemos lo que "
     "cuesta llegar hasta el examen. Si vas a por la siguiente convocatoria, la "
     "133ª arranca el 1 de septiembre con el temario actualizado y todo lo que "
     "ya conoces del campus. Tu cuenta sigue ahí: te suscribes de nuevo y "
     f"recuperas el ritmo desde el día 1. Reserva tu plaza: {ENLACE_MATRICULA}"),
    (4, "B2 Novedades", "Qué hay nuevo en la 133ª",
     "[PENDIENTE: 2-3 novedades reales de Paco — completar antes de activar] "
     "Si tienes dudas de si repetir, responde a este correo y lo vemos — sin "
     "compromiso."),
    (4, "B3 Cierre honesto", "¿Vas a por ella?",
     "Sin rodeos: ¿te apuntamos a la 133ª? Responde SÍ y te mandamos el enlace, "
     "responde LUEGO y te avisamos más adelante, o NO y no te escribimos más "
     "sobre esta convocatoria. Así de fácil."),
]

DUNNING = [
    (0, "D0 Aviso amable", "No hemos podido pasar tu cuota de este mes",
     "Hola {{contact.first_name}}, te escribimos de Academia Valenzuela: hoy no "
     "hemos podido cobrar tu cuota mensual (suele ser por caducidad de la "
     "tarjeta o un rechazo puntual del banco). Tu acceso al campus sigue activo "
     "— tienes 7 días para regularizarlo sin que cambie nada. Puedes actualizar "
     f"tu tarjeta o repetir el pago aquí: {ENLACE_CUENTA} . Si ya lo has "
     "resuelto o crees que es un error, responde a este correo y lo miramos."),
    (3, "D3 Recordatorio", "Recordatorio: tu cuota sigue pendiente (quedan 4 días)",
     "Hola {{contact.first_name}}, hace 3 días no pudimos cobrar tu mensualidad "
     "y sigue pendiente. Tu acceso al campus continúa activo, pero en 4 días se "
     "pausará automáticamente si no se regulariza. Se arregla en 1 minuto desde "
     f"aquí: {ENLACE_CUENTA} . ¿Problemas con el pago? Responde y te ayudamos."),
    (3, "D6 Último aviso", "Último aviso: mañana se pausa tu acceso al campus",
     "Hola {{contact.first_name}}, no queremos que pierdas el ritmo de la "
     "preparación: mañana se pausará tu acceso al campus porque tu cuota lleva "
     f"6 días pendiente. Regularízalo hoy y no notarás ningún cambio: "
     f"{ENLACE_CUENTA} . Y si estás pasando un momento complicado, respóndenos "
     "— preferimos hablarlo contigo antes que cortar sin más."),
    (2, "D8 Post-corte", "Tu acceso al campus está en pausa (recuperarlo es fácil)",
     "Hola {{contact.first_name}}, tu acceso al campus quedó en pausa por la "
     "cuota pendiente. La buena noticia: todo tu progreso está guardado (test, "
     "estadísticas, posición en el ranking) y se reactiva solo en cuanto el "
     f"pago se complete: {ENLACE_CUENTA} . Si has decidido no continuar, "
     "dínoslo y cerramos tu suscripción sin más cargos."),
]

RECUPERACION = [
    (0, "Bienvenida de vuelta", "¡Todo en orden! Tu acceso sigue activo",
     "Hola {{contact.first_name}}, pago recibido. Tu acceso al campus está "
     "activo con todo tu progreso intacto. Gracias por resolverlo rápido — "
     "¡a por la 133ª!"),
]

WORKFLOWS = {
    "nurturing-a": {
        "name": "AV · Nurturing lead nuevo 133ª (v1)",
        "tag": "lead-landing-133",
        "emails": NURTURING_A,
        "final_tag": "lead-frio-133",
        "goal_tags": ["asesoria-agendada", "matriculado-133"],
    },
    "reenganche-b": {
        "name": "AV · Re-enganche ex-alumnos 132ª (v1)",
        "tag": "reenganche-133",
        "emails": REENGANCHE_B,
        "final_tag": "no-133",
        "goal_tags": ["asesoria-agendada", "matriculado-133"],
    },
    "dunning": {
        "name": "AV · Dunning impago — NO ACTIVAR HASTA 1-SEP (v1)",
        "tag": "alumno-impago",
        "emails": DUNNING,
        "final_tag": "dunning-avisos-completados",
        "goal_tags": ["alumno-recuperado", "alumno-activo"],
    },
    "recuperacion": {
        "name": "AV · Bienvenida al recuperar el pago (v1)",
        "tag": "alumno-recuperado",
        "emails": RECUPERACION,
        "final_tag": "",
        "goal_tags": [],
    },
}


def build_campaign(keys):
    campaign = {}
    for key in keys:
        cfg = WORKFLOWS[key]
        raw = []
        for wait_days, name, subject, body in cfg["emails"]:
            if wait_days:
                raw.append(wait_step(f"Espera {wait_days}d antes de {name}", wait_days, "days"))
            raw.append(email_step(name, subject, body, from_name=FROM_NAME))
        if cfg["final_tag"]:
            raw.append(tag_step(f"Tag final {cfg['final_tag']}", [cfg["final_tag"]]))
        campaign[key.replace("-", "_")] = {
            "name": cfg["name"],
            "tag": cfg["tag"],
            "goal_tags": cfg["goal_tags"],
            "templates": link_steps(raw),
        }
    return campaign


def main():
    p = argparse.ArgumentParser(description="Workflows de secuencias Academia Valenzuela")
    p.add_argument("--wf", default="all", choices=[*WORKFLOWS.keys(), "all"])
    p.add_argument("--dry-run", action="store_true")
    args = p.parse_args()

    keys = list(WORKFLOWS.keys()) if args.wf == "all" else [args.wf]
    campaign = build_campaign(keys)

    errors = validate_campaign(campaign)
    if errors:
        print("Errores de validación:")
        for e in errors:
            print(f"  - {e}")
        sys.exit(1)

    for key in keys:
        cfg = WORKFLOWS[key]
        n = len(campaign[key.replace("-", "_")]["templates"])
        days = sum(w for w, *_ in cfg["emails"])
        print(f"✓ {cfg['name']}")
        print(f"    trigger tag: {cfg['tag']} · {n} pasos · {days} días de secuencia"
              f" · goal (UI): {', '.join(cfg['goal_tags']) or '—'}")

    if args.dry_run:
        print("\n--- DRY RUN OK: JSON validado, no se toca GHL ---")
        return

    import os
    from cli_anything.gohighlevel.utils.ghl_internal_client import InternalGHLClient, TokenManager
    from cli_anything.gohighlevel.utils.workflow_builder import CampaignBuilder

    location_id = os.environ.get("GHL_LOCATION_ID", "hBvP7lemQSMibPYcJPEP")
    client = InternalGHLClient(TokenManager(), location_id)
    builder = CampaignBuilder(client)
    builder.build(campaign, folder_name="AV Secuencias 133")
    print()
    print(builder.format_summary())
    print()
    print("SIGUIENTES PASOS (a mano en la UI de GHL):")
    print("  1. Goal Event por workflow: 'Added a contact Tag' con los goal tags → End workflow.")
    print("  2. Sustituir {{CALENDARIO_ASESORIAS}} por el enlace real del calendario (D1).")
    print("  3. Ventana de envío 10:00-20:00 Europe/Madrid en cada email.")
    print("  4. Condición global: si el contacto responde → parar secuencia + notificar.")
    print("  5. TODOS quedan en DRAFT. El de dunning NO se activa hasta el 1-sep.")
    print("  6. Completar B2 (novedades de la 133ª) con el material de Paco antes de activar.")


if __name__ == "__main__":
    main()
