# Instrucciones para Oliver — Mapping Reference de los 2 triggers (Opción B)

> Solo necesario si se opta por la vía Inbound Webhook (Opción B). Si se activa
> la Opción A del plugin (API pública con PIT, v0.6.0), esto NO hace falta.
>
> Problema que resuelve: hoy los 2 workflows espejo están publicados pero
> **no crean el contacto** (la sub-cuenta tiene 0 contactos pese a decenas de
> disparos). Sin el mapeo, el webhook dispara pero el workflow no tiene sobre
> quién aplicar tags/email/oportunidad. Ver el diagnóstico completo en
> `estado_workflows_ghl_2026-07-10.md`.

## Qué hay que hacer (5 clics por trigger, 2 triggers)

Sub-cuenta **Academia Valenz**. Repetir en los dos workflows:
- "Espejo EvoCampus — Baja/Impago (WooCommerce)"
- "Espejo EvoCampus — Reactivación (WooCommerce)"

1. Abrir el workflow → clic en el trigger **Inbound Webhook** (el primer nodo).
2. En el panel del trigger, pulsar **"Fetch Sample Request"**. GHL mostrará el
   último payload que ya envió el plugin (tiene `email`, `first_name`,
   `last_name`, `phone`, `event`, …). Si no aparece ninguno, lanzar una
   conciliación de prueba desde WordPress (WooCommerce → EvoCampus Sync →
   "Ejecutar conciliación ahora") y volver a pulsar Fetch Sample.
3. En **"Map to Contact Fields"** (o "Customize / Map Data"), mapear:
   - `email` → **Email** del contacto  ← clave de deduplicación (imprescindible)
   - `first_name` → First Name
   - `last_name`  → Last Name
   - `phone`      → Phone
4. Asegurar que la opción de **crear/actualizar el contacto** (Create/Update
   Contact) está activada con el email como identificador.
5. **Guardar** el trigger y **Publicar** el workflow.

## Cómo verificar que quedó bien

- Lanzar una conciliación de prueba desde WordPress (sigue en DRY-RUN: el
  plugin no toca EvoCampus, pero sí envía el espejo a GHL).
- En GHL → Contacts: deben aparecer los contactos del censo con su tag
  (`alumno-impago` en baja / `alumno-activo`+`alumno-recuperado` en
  reactivación).
- En el workflow de Baja: cada contacto impago debe generar una oportunidad en
  el pipeline **Recobro impagos / Impago detectado**.

## Payload real que envía el plugin (referencia del mapeo)

```json
{
  "event": "baja",              // o "reactivacion"
  "email": "alumno@ejemplo.com",
  "first_name": "NOMBRE",
  "last_name": "APELLIDOS",
  "phone": "+34...",
  "subscription_id": 1234,
  "woo_status": "on-hold",
  "enrollments": [2937, 3045],
  "dryrun": true,
  "timestamp": "2026-07-10T16:40:00Z"
}
```

## Extra (ya hecho, solo confirmar)

- El paso **Create Opportunity** del workflow de Baja ya existe y apunta a
  Recobro impagos / Impago detectado. No hay que añadirlo.
- Ambos workflows ya están publicados.
