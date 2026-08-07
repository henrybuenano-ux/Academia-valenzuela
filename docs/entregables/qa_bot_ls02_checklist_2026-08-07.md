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
**cero oportunidad en Cualificado y cero aviso al equipo**. Tal como está,
un lead cualificado por el bot se queda huérfano en el CRM.
**Arreglo (bot builder)**: en el final del flujo de AI Capture (o en las
acciones del bot), añadir **"Add Contact Tag: `lead-bot`"** al completar la
captura. Todo lo de después ya está probado y corre solo.

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

## Al terminar

- Borrar el/los contactos de prueba del CRM (y sus oportunidades).
- Apuntar en la tarea de ClickUp qué pruebas fallaron y con qué texto
  respondió el bot — los fallos se corrigen retocando el prompt
  correspondiente (Personalidad/Objetivo/Información) de
  `bot_ls02_prompts_2026-08-07.md`.
