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

## Diagnóstico · confirmado en producción el 26-ago

Henry abrió el wp-admin de producción y comprobó los ajustes. **No coinciden con
staging**, y el diagnóstico real resulta más simple:

| Casilla (WooCommerce → Cuentas y privacidad) | Staging (julio) | **Producción** |
|---|---|---|
| Activar el pago como invitado | off | **ON** |
| Activar el inicio de sesión durante el pago | off | **off** ❌ |

La causa la explica el propio WooCommerce, en la ayuda bajo la primera casilla:
*«Permite a los clientes finalizar la compra sin crear una cuenta. Observa que
adquirir una suscripción requiere tener una cuenta.»*

**La secuencia del muro:**

1. El ex-alumno llega al checkout como invitado — se lo permiten.
2. Pero el producto es una **suscripción**, así que WooCommerce le exige cuenta.
3. Intenta crearla con su email → ya existe → *«Ya existe una cuenta registrada
   con tu dirección de correo electrónico. Por favor, inicia sesión.»*
4. Y **no hay formulario de inicio de sesión en esa página**, porque la segunda
   casilla está desmarcada.

Callejón sin salida. Es literalmente lo que describió Paco: *«al meter su correo
electrónico, le dice la plataforma que ya está»*. Y explica por qué los del
intensivo sí pudieron: pago único, sin cuenta obligatoria. Y por qué alguno lo
resolvió con un segundo correo: era la única salida que le dejaba la tienda.

**El «segundo sospechoso», descartado como causa.** El `#2054` sí tenía
**«Limitar a una suscripción activa»** (confirmado en producción), pero ese
límite es **por producto**: impide comprar el `#2054` a quien ya tenga una
suscripción a *ese mismo* `#2054`. Los ex-alumnos vienen de la 132ª, otro
producto con otro ID — no les afectaba.

El síntoma tampoco encaja: cuando el límite salta, WooCommerce **retira el botón
de «Añadir al carrito»** y avisa de que ya se tiene una suscripción. Paco
describió el error al meter el correo, que es el fallo de la casilla de login.

Corrijo lo que escribí antes: dije que bloquearía «por partida doble» y era una
suposición, no un hecho.

## Los arreglos

**1 · Marcar «Activar el inicio de sesión durante el pago»** ✅ *la causa*
WooCommerce → Ajustes → Cuentas y privacidad. **El pago como invitado se queda
como está** — desactivarlo no arregla nada y rompe otras compras.

**2 · «Limitar suscripción» del `#2054` → «No limitar»** *(higiene, no la causa)*
Ficha del producto, pestaña **Avanzado** — no General: en las versiones recientes
de WooCommerce Subscriptions el campo vive ahí. Estaba en «Limitar a una
suscripción activa».

No bloqueaba a los de la 132ª (ver arriba), pero **es una trampa hacia adelante**:
la 133ª corre mes a mes hasta el examen, y a quien le falle un cobro la
suscripción pasa a *En espera*. Con el límite puesto no podría volver a comprar
el mismo producto — ni él, ni la academia por él. Justo la gente que se quiere
recuperar es la que se quedaría fuera.

Treinta segundos cada uno. **Los aplica el equipo** — nuestra IP sigue bloqueada
en el wp-admin de producción.

## Verificado el 26-ago

Comprobado el HTML de `/finalizar-compra/` con un carrito de invitado. El aviso
existe y el formulario está completo:

> **«¿Ya eres cliente? Haz clic aquí para acceder»**
> → Correo electrónico · Contraseña · [Acceder] · Recuérdame · **¿Olvidó su contraseña?**

**El muro está caído.** Quien ya tiene cuenta puede iniciar sesión desde la
propia página de compra y terminar el pedido.

**Dónde aparece** (esto confundió en la primera prueba): es una línea gris **en
lo alto de la página**, en la zona de avisos, encima del formulario de
facturación. Sale **al cargar**, no al escribir el correo, y hay que **pincharla**
para que se despliegue el formulario.

### ⚠️ La fricción que queda: la contraseña

Los 32 de la lista de recuperación **no van a recordar su contraseña** — la
pusieron hace meses. Llegan al formulario y se atascan ahí.

El enlace **«¿Olvidó su contraseña?»** está en el mismo sitio, así que hay
salida, pero **hay que decírselo por teléfono**. Guion para la llamada:

> *«Entra en la página de compra. Arriba del todo verás "¿Ya eres cliente? Haz
> clic aquí para acceder". Si no te acuerdas de la contraseña, justo debajo
> tienes "¿Olvidó su contraseña?" y te llega un correo para ponerla nueva.»*

Sin esa frase, se pierden en el mismo punto en el que se perdían antes.

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
