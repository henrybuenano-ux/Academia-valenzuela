# Auditoría de los workflows en GHL — 6-ago-2026 (sesión 7)

Hecha **por API interna**, leyendo la definición real guardada de cada
workflow (`backend.leadconnectorhq.com/workflow/{loc}` + el JSON de pasos en
Firebase Storage + `/workflow/{loc}/trigger?workflowId=`). No es una lectura
del plan ni de las notas: es lo que hay guardado en la sub-cuenta.

**Límite conocido de esta auditoría**: la UI de GHL sigue bloqueada por la red
del entorno, así que verifico *lo que está guardado*, no *lo que la UI dibuja*.
La distinción importa: `workflow_goal` también se guardaba y la UI no lo
pintaba (sesión 6).

## Inventario real (9 workflows)

| Workflow | Estado | Pasos | Trigger |
|---|---|---|---|
| LS01 · Lead de landing → Captación | **published** | 3 | `form_submission` (form landing) + `contact_tag` (lead-landing-133) |
| SP01 · Nurturing lead nuevo 133ª | **published** | 8 | `contact_tag` (lead-landing-133) |
| SP02 · Asesoría agendada | draft | 2 | ⚠️ `form_submission` (form **landing**) — inactivo |
| LS02 · Lead del bot → Captación | *sin estado* | 2 | `contact_tag` (lead-bot) — inactivo |
| LS03 · Re-enganche ex-alumnos 132ª | *sin estado* | 6 | `contact_tag` |
| RP02 · Dunning impago | *sin estado* | 8 | `contact_tag` |
| RP03 · Bienvenida al recuperar el pago | *sin estado* | 1 | `contact_tag` |
| LEGADO · Espejo Baja/Impago | **published** | — | webhook |
| LEGADO · Espejo Reactivación | **published** | — | webhook |

Los 7 workflows de casa se crearon el **24-jul** y se editaron por última vez
el **6-ago 22:05–22:11** (salvo RP03, intacto desde el 24-jul). SP02 se creó
el 6-ago.

---

## Hallazgos

### 🔴 1. SP02 tiene el trigger equivocado — y es una mina
`SP02 · Asesoría agendada` **no** dispara con una cita agendada. Su único
trigger es:

```
type: form_submission
condición: form.id is-any-of ["EIa3gz2I8ndWcPA2we6v"]   ← el formulario de la LANDING
active: false
```

Es decir, **el mismo formulario que dispara LS01**. Hoy no hace daño porque
el workflow está en `draft` y el trigger `active: false`. Pero si alguien lo
publica tal cual, **todo lead que rellene la landing** quedaría etiquetado
`asesoria-agendada` y abriría una oportunidad en *Captación / Agendado* sin
haber agendado nada — corrompiendo el pipeline y las métricas de conversión.

Esto **contradice el aprendizaje nº3 de la sesión 6**, que daba por creado un
trigger `customer_booked_appointment`. En la definición guardada no existe.

**Acción**: rehacer el trigger como `customer_booked_appointment` cuando
exista el calendario de asesorías (que sigue bloqueado por el horario de
Paco). Hasta entonces, **no publicar SP02**.

### 🔴 2. Los avisos internos no llegan a nadie
El nodo `internal_notification` **sí está guardado** en LS01 y LS02 — eso
responde la pregunta que quedó abierta en la sesión 6. Pero está mal
configurado:

```json
"recipients": [],
"assigned_user": true
```

Manda el aviso **al usuario asignado del contacto**, y ni LS01 ni LS02 tienen
un paso que asigne usuario. Resultado: destinatario vacío → **nadie recibe el
aviso de lead nuevo**. El lead entra bien al CRM, pero el equipo no se entera
en caliente.

**Acción**: decidir destinatario (¿Paco? ¿info@academiavalenz.com? ¿un
usuario del CRM?) y o bien rellenar `recipients`, o bien añadir un paso de
asignación antes del aviso.

### 🟠 3. Cero salidas IF en los 7 workflows
Ninguno de los 7 tiene un solo paso condicional. Las secuencias solo terminan
con su `add_contact_tag` final. Confirma que **los remates de UI nunca se
hicieron**.

Duele especialmente en **SP01, que está PUBLICADO y corriendo**: son 4 emails
repartidos en 7 días sin ninguna condición de salida, así que un lead que ya
agendó asesoría (o que ya se matriculó) sigue recibiendo *A3 Objeciones* y
*A4 Última llamada*. Es el fallo con más impacto visible para el cliente de
todo el sistema ahora mismo.

Mismo problema latente en LS03 y RP02, aún sin publicar.

### 🟠 4. Los 2 workflows LEGADO siguen publicados
`LEGADO · Espejo Baja/Impago` y `LEGADO · Espejo Reactivación` siguen en
`published`. Están sustituidos por la vía A del plugin (API pública con PIT).
Mientras el plugin use PIT no hay doble escritura — el código solo cae al
webhook si **no** hay PIT definido — pero conviene despublicarlos antes del
1-sep para que no haya dos caminos vivos hacia el pipeline de Recobro.

### 🟡 5. LS01 tiene un trigger redundante y autorreferente
LS01 dispara con `contact_tag: lead-landing-133` **y** su propio paso 1 añade
ese mismo tag. Es un resto de cuando el tag lo aplicaba el formulario.

Riesgo real: **bajo**. `allowMultiple=false` y `allowMultipleOpportunity=false`
impiden la reentrada y la oportunidad duplicada. Lo peor que puede pasar es un
aviso interno duplicado (y hoy ni eso, porque el aviso no tiene destinatario).
Limpiarlo cuando se toque LS01, sin urgencia.

---

## Corrección al handoff de la sesión 6

El doc dice "estado al 24-jul (fin de sesión 6)". **Las fechas están
mezcladas**: el 24-jul es cuando se *crearon* los workflows en GHL (sello real
del servidor), pero las ediciones de la sesión 6 están selladas el **6-ago
22:05–22:12** y su commit a las 22:33 — cinco minutos antes del primer commit
de la sesión 7. No hubo un parón de 13 días: la sesión 6 acabó justo antes de
esta. (Mi primera nota de la sesión 7 decía lo contrario; queda corregida.)

Lo que **sí** se confirma es que la red del entorno nunca se amplió:
`app.gohighlevel.com`, `api.omniainbusiness.com` e `info.academiavalenz.com`
siguen devolviendo 403 en el CONNECT del proxy.

## Cómo reproducir esta auditoría

```bash
# 1. Refrescar el id_token (caduca en ~1 h)
curl -s -X POST "https://securetoken.googleapis.com/v1/token?key=$FIREBASE_API_KEY" \
  -d "grant_type=refresh_token&refresh_token=$GHL_FIREBASE_REFRESH_TOKEN"

# 2. Listar workflows con su estado
curl -s "https://backend.leadconnectorhq.com/workflow/$LOC" \
  -H "token-id: $TOK" -H "channel: APP" -H "source: WEB_USER" -H "version: 2021-07-28"

# 3. Los PASOS no vienen en esa respuesta: están en el fileUrl (Firebase
#    Storage) de cada workflow, bajo la clave "templates".
# 4. Los TRIGGERS van aparte:
curl -s "https://backend.leadconnectorhq.com/workflow/$LOC/trigger?workflowId=$WFID" -H "token-id: $TOK" ...
```
