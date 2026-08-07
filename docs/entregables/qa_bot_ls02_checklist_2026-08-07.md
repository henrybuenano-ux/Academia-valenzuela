# QA del bot LS02 (subtarea 14) — checklist ejecutable · 7-ago-2026

> El QA conversacional **no se puede automatizar desde el entorno**: el chat
> del widget habla por su propio canal autenticado (el endpoint inbound de la
> API devuelve 401 sin un conversation provider) y la consola de prueba del
> bot vive en la UI. Esta batería la ejecuta una persona chateando —
> por el widget o por el "Test bot" del builder. Lo que SÍ está verificado
> por API ya está marcado abajo.

## ⚠️ Hallazgo nº 0 (RESUELTO a medias) — dónde está el widget

**Confirmado por el equipo**: el widget está en la página del funnel
**`info.academiavalenz.com/formacion`** (el chat de GHL se inyecta al servir
las páginas del funnel; no aparece en el código de la página y este entorno
no puede cargar la página servida — verificar la burbuja en el navegador).

Lo comprobado por API el 7-ago:
- La página `/formacion` del funnel lleva el **formulario** embebido
  (`EIa3gz2I8ndWcPA2we6v` vía whitelabel) ✓.
- **El step `/landing` ya no existe** — el funnel tiene un único step,
  `/formacion`. Sin impacto: ningún email de las secuencias enlaza a
  `/landing` (van a academiavalenz.com y mi-cuenta) y las campañas de pago
  no han arrancado. Referencias viejas solo en docs.
- **academiavalenz.com (WordPress) NO tiene el widget** (HTML sin caché +
  GTM revisados). Decisión pendiente: ¿se quiere el bot también en la web
  principal, como decía el plan original (subtarea 12 hablaba de
  WordPress)? Si sí: pegar el embed del chat en WP (o vía GTM).

## Lo ya verificado por API (no repetir)

- ✅ Tag `lead-bot` → LS02 (publicado) crea oportunidad en
  **Captación/Cualificado**, fuente "Bot web" + aviso interno a los 3.
- ✅ Cita agendada → SP02 → tag `asesoria-agendada` + Agendado → SP03 saca
  de SP01/LS03.
- ✅ Calendario Asesorías activo con huecos (horario provisional).

Es decir: **todo lo que pasa DESPUÉS de que el bot cualifique ya funciona.**
La batería prueba solo la conversación.

## Batería conversacional (7 pruebas)

Para cada prueba: chatear como un desconocido (ventana de incógnito si es
por widget). Anotar PASA/FALLA y el texto real de la respuesta si falla.

| # | Qué escribir | Debe pasar | Falla si… |
|---|---|---|---|
| 1 | "¿cuánto cuesta?" | Dice **80 €/mes, sin matrícula ni permanencia**, y remata con pregunta de cualificación u oferta de asesoría | Da otro precio, inventa descuentos, o suelta un párrafo enorme |
| 2 | "quiero empezar ya con la 133ª" | Ofrece **asesoría/matrícula**, pide datos **de uno en uno** (nombre → teléfono → email) | Pide todo de golpe, o no captura datos antes de despedirse |
| 3 | "¿me lo puedo pagar en dos veces?" | **Handoff a humano** (es pregunta de condiciones de precio), capturando antes nombre y WhatsApp | El bot negocia o inventa condiciones de pago |
| 4 | "soy alumno y no puedo entrar al campus" | **Handoff a humano** (problema de cuenta), con captura de datos | El bot intenta resolver el acceso él mismo |
| 5 | "¿cuántos aprueban con vosotros?" | **No da cifra** (está en `[PENDIENTE]`): deriva a asesoría/equipo | Inventa un dato de aprobados |
| 6 | "¿me haces los deberes de mates?" (fuera de tema) | Redirige con cortesía a la oposición/academia, no entra al trapo | Se pone a hacer los deberes |
| 7 | Conversación completa: interesado → dudas → "vale, ¿cómo sigo?" | Cualifica ("¿en qué punto estás?" con las 4 opciones), captura los 3 datos, cierra con asesoría. Verificar después en el CRM: contacto creado con **tag `lead-bot`** → oportunidad en Cualificado + aviso a los 3 | No cualifica, no etiqueta, o el contacto no aparece en el CRM |

**Extra si la 7 pasa**: agendar la asesoría desde el enlace/nodo del bot y
comprobar que dispara SP02 (tag `asesoria-agendada` + oportunidad pasa a
Agendado). Con eso el embudo del bot queda probado de conversación a cita.

## RESULTADOS — 1ª pasada (7-ago, chat real de German por el widget)

**Conversación: NOTABLE.** De la transcripción quedan cubiertas las pruebas
1, 2, 3 y la mitad conversacional de la 7:

| # | Resultado | Detalle |
|---|---|---|
| 1 · precio | ✅ PASA | "80 €/mes, sin matrícula ni permanencia, cancelas cuando quieras" + remató cualificando ("¿En qué punto estás?") |
| 2 · lead caliente | ✅ PASA | Capturó nombre → WhatsApp → email, de uno en uno, y cerró con el texto puente ("el equipo te escribe hoy") |
| 3 · pago en 2 veces | ✅ PASA (suave) | No inventó condiciones y lo derivó al equipo ("te lo confirma un compañero en la llamada"). Los datos ya estaban capturados |
| 7 · CRM | 🔴 **FALLA** | Ver abajo |

**🔴 FALLO CRÍTICO (prueba 7): el bot NO aplica el tag `lead-bot`.**
Verificado por API: el chat creó el contacto (`pdGcwnrSHJ2J0nwAQKOf`,
nombre/teléfono/email correctos) pero con `tags: []` → LS02 no disparó →
**cero oportunidad en Cualificado y cero aviso al equipo**.

> **✅ ARREGLADO (7-ago) sin tocar el bot** — el equipo confirmó que el
> builder del Conversation AI clásico no tiene acción de tags (y Agent
> Studio está vacío: el bot no es de ahí). Solución por el lado del
> workflow: **segundo trigger en LS02, "Customer Replied" filtrado a Live
> Chat** (`NbzKRKfk5gkyYB5LtORQ`, activo; el trigger por tag `lead-bot` se
> mantiene como vía alternativa/manual). Ahora el primer mensaje de chat de
> un contacto lo mete en LS02: oportunidad en Cualificado + aviso a los 3.
>
> **Verificar en la UI (10 s)**: abrir LS02 → trigger "Customer Replied
> (Live Chat)" → confirmar que el filtro *Reply Channel* muestra **Live
> Chat** (el valor se escribió por API con formato a ciegas; si el
> desplegable sale vacío, seleccionarlo y guardar).
>
> **Matiz asumido**: dispara con el PRIMER mensaje, antes de cualificar.
> Con el widget solo en la landing y volumen bajo, un curioso que diga
> "hola" y se vaya generará una oportunidad en Cualificado de más — coste
> aceptado a cambio de no perder ningún lead. Si molesta, se cambia la
> etapa del paso de LS02 a "Contactado".
>
> **Reprueba inmediata**: mandar UN mensaje más en el chat de German (o un
> chat nuevo de incógnito) → deben aparecer la oportunidad y los 3 avisos.

**🟡 Fallo menor: el bot no contestó a los 2 primeros mensajes**
("¿cuánto cuesta?" 11:10 y "?" 11:11) — solo despertó con "hola" (11:11).
Puede ser el arranque de sesión del widget o el tiempo de respuesta
configurado del bot. Revisar el ajuste de *response time* y reprobar: un
lead real que pregunta precio y no recibe respuesta se va.

**🟡 Mejora ya desbloqueada**: el bot usa el texto puente "el equipo te
escribe hoy" porque se redactó cuando no había calendario. **El calendario
ya existe** (`Asesorías 133ª`) → cablear el nodo Book Appointment
(subtarea 8) y quitar la frase puente del prompt Objetivo.

**Pendientes de probar en la 2ª pasada** (tras el arreglo del tag):
4 (alumno con problema → handoff), 5 (aprobados → no inventa), 6 (fuera de
tema), y repetir la 7 completa verificando: tag `lead-bot` + oportunidad
Cualificado + aviso a los 3 buzones.
El contacto de la 1ª pasada (`pdGcwnrSHJ2J0nwAQKOf`) queda en el CRM para
la reprueba — borrarlo al terminar la 2ª pasada.

## RESULTADOS — 2ª pasada (7-ago, tarde · ejecutada por Claude desde el navegador del entorno)

**Cómo fue posible** (tras desbloquear la red): la política ya permite
`info.academiavalenz.com` y `widgets.leadconnectorhq.com`, y se descubrió que
el MITM del proxy no completa el handshake TLS 1.3 de Chromium —
**`--ssl-version-max=tls1.2` lo arregla** (detalle en CONTINUAR_AQUI).
`stcdn.leadconnectorhq.com` sigue vetado, así que el bundle del funnel que
inyecta el chat no carga: el widget se inyectó a mano con el **embed real
extraído de la página**:

```html
<script src="https://widgets.leadconnectorhq.com/loader.js"
        data-resources-url="https://widgets.leadconnectorhq.com/chat-widget/loader.js"
        data-widget-id="6a75e4fae425d99b06dba3bf"
        data-source="FUNNEL"></script>
```

(Este snippet es exactamente lo que necesita la **subtarea 12** para poner el
bot en WordPress — cambiando `data-source` si se quiere distinguir origen.)

Dos conversaciones reales contra el bot vivo (dos visitantes distintos,
perfiles de navegador limpios):

| # | Resultado | Textos reales del bot |
|---|---|---|
| 4 · alumno sin acceso | ✅ PASA | "Gracias por decírmelo. Para que un compañero del equipo te ayude cuanto antes, ¿me dejas tu nombre y tu WhatsApp?" → capturó nombre → WhatsApp → email de uno en uno y cerró: "Paso tu caso al equipo y te contactan hoy mismo para ayudarte con el acceso al campus". No intentó resolver el acceso él mismo |
| 5 · % aprobados | ✅ PASA | "Esa información te la confirma mejor un compañero del equipo, ya que no tengo el dato exacto" — no inventó cifra |
| 6 · fuera de tema (deberes) | ✅ PASA | "Solo puedo ayudarte con temas de la oposición a Guardia Civil y dudas sobre la academia… ¿Te ayudo con alguna parte de la oposición?" |
| 7 · journey completo (conversación) | ✅ PASA | Pitch fiel (sin permanencia, temario actualizado, PDF) → cualificó ("¿En qué punto estás con la oposición?") → resolvió duda del temario → capturó nombre → WhatsApp → email de uno en uno → cerró con "asesoría gratuita de unos 10 minutos… ¿te llamamos hoy mismo?" |

**Además:**
- 🟢 **El fallo del despertar NO se reproduce**: en ambas conversaciones el
  bot respondió al PRIMER mensaje (~30-60 s). El "no contestó a los 2
  primeros mensajes" de la 1ª pasada parece cosa del arranque de aquella
  sesión del widget, no del ajuste de response time.
- 🟡 **Sigue el texto puente** "el equipo te escribe hoy por WhatsApp" en el
  cierre (confirmado en real): el nodo **Book Appointment** (subtarea 8)
  sigue sin cablear al calendario `Asesorías 133ª`.
- 🟡 **Textos del sistema del widget en inglés**: "Have a question?", "Enter
  your question below…", "Give us a minute to assign you the best person…".
  Se cambian en la config del Chat Widget en GHL (idioma/textos) — 5 min.
- 🔎 La cualificación pregunta en abierto ("¿en qué punto estás?") sin
  enumerar las 4 opciones del prompt. Funciona bien; solo apuntado.

**Verificación CRM (7-ago, tarde) — resuelta con el equipo:**
- Los 2 contactos SÍ se crearon (Andrés Ferrer, Lucía Prado), pero **el
  trigger *Customer Replied · Live Chat* estaba mal guardado** (el filtro
  escrito por API a ciegas — el riesgo que quedó apuntado arriba). El
  equipo lo corrigió en la UI.
- **Retest tras el arreglo: ✅ VALIDADO end-to-end.** Un mensaje más en el
  chat de Lucía (el widget conserva la identidad del visitante; el chat
  caducado se reabre con su botón "Click here") → oportunidad en
  **Captación/Cualificado** + **los 3 avisos internos** llegados.
  El circuito completo del bot queda probado: chat → contacto → LS02 →
  oportunidad + aviso.

**✅ Re-inscripciones RESUELTAS y VALIDADAS (7-ago, tarde):** el equipo
aplicó **Allow Re-Entry OFF** + un IF de guarda bajo el trigger (cinturón
y tirantes; *Allow multiple opportunities* también OFF). Validación con
conversación nueva de 3 mensajes ("Marta"): el 1º creó contacto +
**una** oportunidad + **una** tanda de avisos; los mensajes 2 y 3 no
re-inscribieron. 

**🟡 Hallazgo derivado del trigger nuevo — nombre de la oportunidad:**
la oportunidad sale como **"Guest Visitor xxxx"** aunque el contacto
luego se llame Marta. No es bug: *Create Opportunity* evalúa
`{{contact.name}}` al crearse, y con el trigger de primer mensaje el
contacto aún es el guest anónimo de Live Chat; el bot captura el nombre
después y GHL no renombra oportunidades. Opciones: (1) aceptarlo,
(2) nombre estático "Lead Bot web", (3) **recomendada**: reordenar LS02 →
aviso interno inmediato → *Wait* 3-5 min → *Create Opportunity* (para
entonces `{{contact.name}}` ya es el real). Decisión del equipo pendiente.

**🟡 Comportamiento a vigilar — el bot calla tras despedirse:** en una
conversación reabierta tras la despedida/cierre por inactividad, el bot
dejó de responder ("¿puedo pagar con tarjeta?" sin contestar). Un lead
real que reabra el chat se queda colgado hasta que alguien lo vea en
Conversations. Revisar el ajuste de fin de sesión del Conversation AI.

## Al terminar

- Borrar los contactos de prueba del CRM con sus oportunidades: los 2 de la
  2ª pasada (Andrés Ferrer, Lucía Prado) y el de la 1ª
  (`pdGcwnrSHJ2J0nwAQKOf`).
- Apuntar en la tarea de ClickUp qué pruebas fallaron y con qué texto
  respondió el bot — los fallos se corrigen retocando el prompt
  correspondiente (Personalidad/Objetivo/Información) de
  `bot_ls02_prompts_2026-08-07.md`.
