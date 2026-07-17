# Secuencias de dunning — alineadas con la política de 7 días de cortesía (A6)

> Para Oliver: copy listo para cargar en GHL (workflow disparado por el tag
> `alumno-impago` que pone el plugin al fallar el cobro). Canales: email desde
> el día 0; WhatsApp cuando Meta apruebe el canal (A3). Tono cercano, España.
> El corte real lo ejecuta el plugin al agotar la ventana de 38 días
> (≈ día 7 tras el fallo de cobro); estas secuencias avisan ANTES.

## Disparador y salidas

- **Entra**: contacto recibe tag `alumno-impago` (el plugin lo pone en el
  momento del fallo de cobro, sin cortar el acceso).
- **Sale** (cancelar secuencia): recibe tag `alumno-recuperado` o
  `alumno-activo` (pagó → el plugin lo re-etiqueta), o responde pidiendo ayuda
  (pasar a humano: notificación interna + pausa de la automatización).
- Oportunidad en pipeline "Recobro impagos" ya la crea el plugin; el workflow
  solo debe moverla de etapa si se configura así.

## Día 0 — Aviso amable (a las ~2 h del fallo, horario 10-20 h)

**Email — Asunto:** No hemos podido pasar tu cuota de este mes

Hola {{contact.first_name}},

Te escribimos de Academia Valenzuela: hoy no hemos podido cobrar tu cuota
mensual (suele ser por caducidad de la tarjeta o un rechazo puntual del
banco). **Tu acceso al campus sigue activo** — tienes 7 días para
regularizarlo sin que cambie nada.

Puedes actualizar tu tarjeta o repetir el pago aquí:
{{enlace a Mi cuenta → Suscripción}}

Si ya lo has resuelto o crees que es un error, responde a este correo y lo
miramos. ¡Seguimos!

**WhatsApp:** Hola {{first_name}} 👋 Soy de Academia Valenzuela. No hemos
podido pasar tu cuota de este mes (suele ser cosa de la tarjeta). Tranquilo:
tu acceso sigue activo y tienes 7 días para arreglarlo desde tu cuenta:
{{enlace}}. Si necesitas ayuda, respóndeme por aquí 🙂

## Día 3 — Recordatorio

**Email — Asunto:** Recordatorio: tu cuota sigue pendiente (quedan 4 días)

Hola {{contact.first_name}},

Hace 3 días no pudimos cobrar tu mensualidad y sigue pendiente. Tu acceso al
campus continúa activo, pero **el jueves que viene se pausará
automáticamente** si no se regulariza.

Se arregla en 1 minuto desde aquí: {{enlace}}.

¿Problemas con el pago o quieres comentarnos algo? Responde a este correo y
te echamos una mano.

**WhatsApp:** Hola {{first_name}}, un recordatorio rápido: tu cuota sigue
pendiente y en 4 días el campus se pausa automáticamente. Lo arreglas en un
minuto aquí: {{enlace}} 💪

## Día 6 — Último aviso (24 h antes del corte)

**Email — Asunto:** Último aviso: mañana se pausa tu acceso al campus

Hola {{contact.first_name}},

No queremos que pierdas el ritmo de la preparación: **mañana se pausará tu
acceso al campus** porque tu cuota lleva 6 días pendiente.

Regularízalo hoy y no notarás ningún cambio: {{enlace}}.

Y si estás pasando un momento complicado o tienes dudas sobre continuar,
respóndenos — preferimos hablarlo contigo antes que cortar sin más.

**WhatsApp:** {{first_name}}, último aviso 😕 mañana se pausa tu acceso al
campus por la cuota pendiente. Hoy todavía estás a tiempo: {{enlace}}. Si
prefieres hablarlo, respóndeme por aquí.

## Día 7+ — Corte ejecutado (lo hace el plugin; mensaje post-corte)

**Email — Asunto:** Tu acceso al campus está en pausa (recuperarlo es fácil)

Hola {{contact.first_name}},

Tu acceso al campus quedó en pausa por la cuota pendiente. La buena noticia:
**todo tu progreso está guardado** (test, estadísticas, posición en el
ranking) y se reactiva solo en cuanto el pago se complete: {{enlace}}.

Si has decidido no continuar, dínoslo y cerramos tu suscripción sin más
cargos. Y si es un tema económico puntual, respóndenos — vemos opciones.

**WhatsApp:** Hola {{first_name}}, tu acceso quedó en pausa por la cuota
pendiente. Tu progreso está guardado y se reactiva solo al completar el
pago: {{enlace}}. Si decides no seguir, dímelo y lo cerramos sin más cargos.

## Al pagar (en cualquier momento) — tag `alumno-recuperado`

**Email — Asunto:** ¡Todo en orden! Tu acceso sigue/vuelve a estar activo

Hola {{contact.first_name}},

Pago recibido ✅ Tu acceso al campus {{está activo como siempre / se ha
reactivado con todo tu progreso intacto}}. Gracias por resolverlo rápido —
¡a por la 133ª!

## Notas de implementación (Oliver)

- Esperas: 2 h → 3 días → 3 días → (corte por plugin) → mensaje post-corte
  al recibir el tag `alumno-baja`... **ojo**: el plugin en conciliación
  etiqueta el corte como cambio de veredicto a `baja`; usar ese evento para
  el mensaje del día 7 en lugar de un delay fijo, así nunca se desincroniza.
- Condición de salida en cada paso: si tiene `alumno-recuperado`/`alumno-activo`, fin.
- El enlace de pago es la página "Mi cuenta → Suscripciones" de
  academiavalenz.com (el alumno gestiona la tarjeta ahí — confirmado en el
  discovery).
- No activar la rama WhatsApp hasta que A3 (Meta) esté aprobado; la de email
  necesita D1 (dominio de envío) — por eso D1 vence el 28-jul.
