# QA del bot LS02 (subtarea 14) — checklist ejecutable · 7-ago-2026

> El QA conversacional **no se puede automatizar desde el entorno**: el chat
> del widget habla por su propio canal autenticado (el endpoint inbound de la
> API devuelve 401 sin un conversation provider) y la consola de prueba del
> bot vive en la UI. Esta batería la ejecuta una persona chateando —
> por el widget o por el "Test bot" del builder. Lo que SÍ está verificado
> por API ya está marcado abajo.

## ⚠️ Hallazgo previo nº 0 — EL WIDGET NO ESTÁ EN LA WEB PRINCIPAL

Comprobado el 7-ago (HTML con caché rota + contenedor GTM-W4VN9FVG):
**academiavalenz.com no carga ningún script de chat de GHL** (ni
`widgets.leadconnectorhq.com` ni el whitelabel). El único `loader.js` de la
web es el de Trustindex (reseñas).

Posibles explicaciones — verificar cuál es:
1. El widget se instaló **solo en la landing** (`info.academiavalenz.com` —
   no comprobable desde este entorno, la red la bloquea). Si es así, decidir
   si también va en la web principal (el plan original decía WordPress).
2. Se instaló en WordPress pero **una caché/optimizador lo está quitando**
   (Elementor + caché de página). Purga la caché y reprueba.
3. Quedó configurado en GHL pero **el embed no llegó a pegarse** en WP.

**Sin widget visible no hay bot que probar en la web** — resolver esto antes
de la batería.

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

## Al terminar

- Borrar el/los contactos de prueba del CRM (y sus oportunidades).
- Apuntar en la tarea de ClickUp qué pruebas fallaron y con qué texto
  respondió el bot — los fallos se corrigen retocando el prompt
  correspondiente (Personalidad/Objetivo/Información) de
  `bot_ls02_prompts_2026-08-07.md`.
