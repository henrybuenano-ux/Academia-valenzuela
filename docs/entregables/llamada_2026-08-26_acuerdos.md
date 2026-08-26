# Llamada con Paco · 26-ago-2026 · acuerdos y acciones

> 43 minutos con Paco Valenzuela, Horacio, Germán y Oliver.
> **Cerró seis pendientes que llevaban semanas abiertos** y destapó un fallo que
> está costando matrículas ahora mismo.
> Grabación: `https://fathom.video/share/Czxtx44ywhF1Tezx98rTU8jpQ3tq8pCs`

---

# 🔴 URGENTE — ex-alumnos que quieren volver y no pueden comprar

**Lo que contó Paco:** chavales de la 132ª que no aprobaron quieren re-matricularse
y aprovechar el descuento de septiembre. Al meter su correo, *«le dice la
plataforma que ya está»* y no pueden completar la compra. Caso concreto que dio:
**Óscar Vargas Balboa**.

Detalles que estrechan el diagnóstico:
- Los del **intensivo sí pudieron** (era pago único, no suscripción).
- Uno lo resolvió **creando otro usuario con otro correo** — exactamente lo que
  no queremos: cuenta duplicada, historial partido y acceso doble al campus.

**Esto es dinero perdido hoy**, con la oferta caducando el 31 de agosto, y sobre
el público que más fácil convierte: gente que ya estudió con ellos.

## Diagnóstico

Comprobado en staging (copia de julio — **confirmar en producción**):

| Ajuste de WooCommerce | Estado |
|---|---|
| `enable_guest_checkout` — comprar sin cuenta | **off** |
| `enable_checkout_login_reminder` — iniciar sesión en el checkout | **off** |
| `enable_signup_and_login_from_checkout` | **off** |

**Las tres apagadas a la vez dejan al ex-alumno sin salida**: no puede comprar
como invitado porque su email ya existe, y tampoco puede iniciar sesión desde la
página de compra. El que sí pudo comprar es quien ya venía logueado.

**Segundo sospechoso, independiente:** el producto `#1374` (la 132ª en staging)
tiene **«limitar suscripción» = _una activa_**. WooCommerce Subscriptions cuenta
también las **En espera** para ese límite. Si el producto de la 133ª (`#2054`,
creado después de la copia y no visible desde aquí) heredó el ajuste, bloquearía
por partida doble.

## Los dos arreglos

**1 · Activar «Permitir a los clientes iniciar sesión durante la compra»**
WooCommerce → Ajustes → Cuentas y privacidad. Es el cambio mínimo: desbloquea
sin tocar la política de cuentas y sin generar duplicados.

**2 · Revisar «Limitar suscripción» en el producto `#2054`**
Pestaña General. Si está en «una activa», ponerlo en **«Sin límite»**: quien
terminó la 132ª tiene todo el derecho a comprar la 133ª.

Treinta segundos cada uno. **Los aplica el equipo** — nuestra IP sigue bloqueada
en el wp-admin de producción.

**Verificación:** intentar una compra con un email que ya tenga cuenta y
comprobar que llega hasta la pasarela.

---

# Lo que la llamada resolvió

| Pendiente | Resultado |
|---|---|
| **Nombre fiscal** | **Francisco Valenzuela Rodríguez** |
| **NIF** | `26956058N` |
| **Domicilio fiscal** | **Camino de Ronda 57, 2º F · 18004 Granada** |
| **IVA** | Paco: *«para una academia de formación está exento»* — ⚠️ ver abajo |
| **Horario de asesorías** | **9:00-13:00 y 17:00-20:00** · citas de **30 min** · **24 h** de antelación mínima |
| **Usuario del CRM** | **gestion@academiavalenz.com** — invitación enviada |
| **Número para WhatsApp** | Tienen un móvil dedicado a la academia, **sin WhatsApp Business**. Sirve para la API |
| **Los 7 becados** | Confirmado: los matriculó Paco a mano para la entrevista, con otra forma de pago |
| **Campañas** | Acordado moverlas a la landing con formulario. Paco se lo pasa a quien las lleva |

## Sobre la factura: va a nombre del hijo

Matiz importante que salió en la llamada. **«Academia Valenz» no es el emisor
fiscal**: el NIF está a nombre de **Francisco Valenzuela Rodríguez**, hijo de
Paco, que es quien figura de alta como autónomo.

La factura va **a su nombre**, y «Academia Valenz» entra como **logo**, no como
razón social. Van a pasar el pack de logos; el actual tiene fondo verdoso y hay
que adaptarlo a fondo blanco.

## Los 7 becados: era exactamente lo que pedía

Paco lo explicó sin saber que ya estaba construido: quiere poder becar a alguien
*«que no entrara dentro del sistema de pago… exentos del tema de facturación,
del tema de los avisos»*, con una etiqueta tipo «becado».

Es literalmente lo que hace `OMNIA_EVO_BECADOS_EMAILS` desde julio. **Pendiente
cerrado sin trabajo nuevo** — solo confirmar con él la lista de los 7.

---

# ⚠️ El IVA no queda cerrado

Paco respondió *«sí, está exento»* a una pregunta directa en una llamada. **Eso
no es la confirmación de la gestoría.**

Sigue siendo la palabra del cliente sobre un asunto en el que, si se equivoca, se
debe el **21 % de todo lo vendido hacia atrás** — hoy poco, pero creciendo con
cada matrícula y arrastrando lo de noviembre de 2025.

**Se avanza con la exención** —es lo razonable y lo más probable en formación—
pero queda anotado como **riesgo asumido, no como cerrado**. Hay que pedir el
respaldo por escrito de la gestoría antes de emitir a volumen.

*(Nota operativa: el plugin de facturas ofrece un listado de decretos de
exención y no incluye el art. 20.Uno.9º que citamos. Se pone como texto libre.)*

---

# 🔄 Corrección: la pausa de junio no fue un error

En `suscripciones_pausadas_2026-08-26.md` escribimos que a esas personas se les
dejó de cobrar por **«un error administrativo»**. **Es falso**, y Paco lo aclaró:

> *«Esos que les paramos el cobro fue porque como eran simplemente unos 11 días
> del mes de julio, quisimos tener el detalle hacia ellos de que esos 11 días no
> los pagaran, es decir, como regalo.»*

Fue **deliberado y generoso**. Y cambia el argumento de recuperación entero:

| | |
|---|---|
| ❌ Lo que decíamos | «Se paró tu suscripción por un problema nuestro, ¿la retomamos?» |
| ✅ Lo correcto | «Te regalamos el final de julio. ¿Retomamos en septiembre?» |

Mucho mejor posición para llamar. Corregido en el documento de las suscripciones.

*(Paco, con humor resignado: «poquita gente lo agradeció».)*

---

# Contenido que falta en la web y en el bot

Paco lo pidió expresamente: le preguntan por Instagram qué incluye «el curso
completo», porque el nombre no lo dice. **Son cuatro formaciones, no una:**

1. **Conocimientos** *(la más extensa)*
2. **Ortografía y gramática**
3. **Psicotécnicos**
4. **Inglés**

Y un dato que no estaba escrito en ningún sitio: **la suscripción corre mes a mes
hasta el examen**, que cae en **junio o julio**. El contenido se libera poco a
poco, como evaluación continua, «porque si le das todo de golpe hay algunos que
se saturan».

Hay que meterlo en la ficha del producto, en la landing y en el bot.

---

# Quién hace qué

| Quién | Qué |
|---|---|
| **Equipo (wp-admin)** | Los dos arreglos de la re-matriculación. **Es lo que corre.** |
| **Henry** | Conexión de WhatsApp, ~15 min. ⚠️ Ojo con el cobro: el número **no puede quedar facturado a la agencia**, la tarjeta la ponen ellos |
| **Paco** | Acceso al Business Manager de Facebook y a Google My Business · una llamada corta para conectar su Google Calendar estando él logueado |
| **Nosotros** | Calendario con sus horarios · las 4 formaciones en web y bot · logo en la factura |

## Sin resolver

**Quién apagó el bot el 18 de agosto.** Se preguntó en la llamada y nadie lo
sabía. Se mencionó que hubo pruebas con Carlos, sin confirmar. Sigue abierto — y
sigue siendo el argumento de que **hace falta una alerta**, no más vigilancia.
