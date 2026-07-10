# Estado verificado de los workflows espejo GHL — 10-jul-2026

> Sub-cuenta **Academia Valenz** (`hBvP7lemQSMibPYcJPEP`). Inspección y pruebas
> vía API interna de GHL con token de agencia (Víctor). Todo lo de abajo está
> comprobado en vivo, no supuesto.

## Qué estaba pendiente (de Oliver) y qué encontré

| Tarea pendiente | Estado real |
|---|---|
| Paso "Create Opportunity" en el workflow de Baja | ✅ **YA EXISTE** — apunta a pipeline "Recobro impagos" → stage "Impago detectado" |
| Guardar/publicar los workflows | ✅ **HECHO** — ambos `status: published` y `active: true` |
| Mapping Reference en los 2 triggers | ❌ **NO configurado** — y es la causa de que el espejo no funcione |

## Estructura actual de los 2 workflows

**Espejo EvoCampus — Baja/Impago** (`166ef9e7-…`, publicado)
- Trigger `inbound_webhook` `vJkOtiVDBtx0TChtWl9U` (= `OMNIA_GHL_WEBHOOK_URL_BAJA` del plugin) ✅
- Pasos: quitar tags(activo,recuperado) → tag(alumno-impago) → email "Aviso de pago pendiente" → **create_opportunity** (Recobro impagos / Impago detectado) ✅

**Espejo EvoCampus — Reactivación** (`e00f1ee1-…`, publicado)
- Trigger `inbound_webhook` `DHGCTUxHMdwjVIMKbSOt` (= `OMNIA_GHL_WEBHOOK_URL_REACTIVACION`) ✅
- Pasos: quitar tags(impago,baja) → tags(activo,recuperado) → email "Bienvenida de vuelta" ✅

## El problema (con prueba empírica)

**La sub-cuenta tiene 0 contactos** pese a las 51 peticiones DRY-RUN de hoy y a
un disparo de prueba adicional (webhook aceptó con HTTP 200 y encoló ejecución
`GMsxPQAOuPZDNrbfqoVj`), pero **no se creó ningún contacto**.

Causa raíz: en GHL, un workflow opera **sobre un contacto que ya debe existir**.
El único elemento capaz de *crear* el contacto a partir del payload de un
Inbound Webhook es el **mapeo del propio trigger** ("Mapping Reference": mapear
`email/first_name/last_name/phone` del JSON entrante → campos del contacto).
Ese mapeo nunca se configuró, así que el webhook dispara, pero no hay contacto
→ los tags, el email y la oportunidad no se aplican a nadie. **El espejo está
publicado pero inoperativo.**

No existe ninguna "acción de crear contacto" dentro del workflow que resuelva
esto: tiene que ser el mapeo del trigger (o una integración directa por API,
ver abajo).

## Lo que SÍ funciona (verificado creando y borrando un contacto de prueba)

- Crear contacto con tag `alumno-impago` → **HTTP 201** ✅
- Crear oportunidad en Recobro impagos / Impago detectado → **HTTP 201** ✅
- Borrado de limpieza → HTTP 200 (la sub-cuenta queda de nuevo en 0 contactos) ✅

Es decir: pipelines, stages y tags están bien montados. El CRM está listo; solo
falta que el contacto entre.

## Pipelines y tags de la sub-cuenta (referencia)

- Pipeline **Recobro impagos** (`TwmjrZZ5LLAYmnVdIkNT`): Impago detectado
  (`d8904ba6…`) · Avisado (WhatsApp/email) · Recuperado · Baja definitiva.
- Pipeline **Captación** (`Zfz0z86Mk3LaxHfK1yYb`): Nuevo lead · Contactado ·
  Cualificado · Agendado · Alta alumno · Perdido.
- Tags usados por los workflows: `alumno-activo`, `alumno-impago`,
  `alumno-baja`, `alumno-recuperado`.

## Payload real que envía el plugin (base del Mapping Reference)

```json
{
  "event": "baja",              // o "reactivacion"
  "email": "alumno@ejemplo.com",
  "first_name": "NOMBRE",
  "last_name": "APELLIDOS",
  "phone": "+34…",
  "subscription_id": 1234,
  "woo_status": "on-hold",
  "enrollments": [2937, 3045],
  "dryrun": true,
  "timestamp": "2026-07-10T16:40:00Z"
}
```

## Dos caminos para arreglarlo

**A) Mapeo en la UI de GHL (rápido, conserva la arquitectura actual).**
En cada trigger Inbound Webhook: "Fetch Sample Request" (recoge el último
payload que ya mandó el plugin) → mapear `email` → Email del contacto (clave de
deduplicación), y `first_name`/`last_name`/`phone` a sus campos → guardar.
GHL pasará a hacer *upsert* del contacto en cada disparo. 5 clics por trigger.
No se puede hacer desde este entorno: el API del mapeo no es accesible y los
dominios de la app (app.gohighlevel.com / accesocrm.omniainbusiness.com) están
bloqueados por la política de red.

**B) Integración directa del plugin con la API pública de GHL (robusto,
implementable y testeable por Claude).** El plugin ya conoce todos los datos;
en vez de depender de un mapeo manual que se olvidó y falla en silencio, haría
*upsert* del contacto + tags + oportunidad directamente contra la API v2 de GHL
(PIT en `wp-config`). Elimina la fragilidad del Inbound Webhook. Requiere un
Private Integration Token de la sub-cuenta (Settings → Private Integrations),
que además desbloquea el CLI por API pública.
