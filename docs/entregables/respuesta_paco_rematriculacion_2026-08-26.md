# Respuesta a Paco · la inscripción de los ex-alumnos

> **Listo para copiar.** Contesta al correo de Paco de las 19:28 del 26-ago.
>
> Lo importante no es solo decirle que está resuelto, sino **desactivar su
> propuesta**: pregunta si hay que dar de baja a los alumnos, y si alguien de la
> academia lo hace por su cuenta, borra datos de clientes reales para resolver
> algo que ya no existe.

---

**Asunto:** Re: la inscripción de los alumnos del curso pasado — resuelto

Hola Paco,

Buenas noticias: **está resuelto desde esta tarde**, y no hay que dar de baja a
nadie.

**Qué pasaba.** Comprar una suscripción obliga a tener cuenta en la web. Los
alumnos de la 132ª ya la tenían, así que al escribir su correo la tienda les
decía *«ya existe una cuenta con ese correo»*… y no les ofrecía ningún sitio
donde meter su contraseña. Se quedaban encerrados ahí. Era una casilla sin
activar en la configuración de la tienda, y ya está activada y comprobada.

**Qué tienen que hacer ellos ahora.** En la página de compra, **arriba del
todo**, aparece una línea que dice:

> **¿Ya eres cliente? Haz clic aquí para acceder**

Hay que pinchar ahí y entrar con su correo y su contraseña. Como la mayoría no
se acordará de una contraseña que puso hace meses, justo debajo tienen
**«¿Olvidó su contraseña?»**: les llega un correo y se ponen una nueva en un
minuto.

Merece la pena decírselo por teléfono tal cual, porque esa línea está arriba y
es fácil pasarla por alto.

**Sobre darles de baja: mejor no.** Si les borramos la cuenta pierden su
historial de pedidos, sus facturas y el enlace con su matrícula en el campus. Y
tampoco habría arreglado nada, porque el problema no era que la cuenta
existiera. Cancelar su suscripción antigua de la 132ª tampoco hace falta:
también lo hemos comprobado hoy.

**¿Nos dices algo de Óscar Vargas Balboa?** Que lo intente ahora y nos cuente.
Hemos verificado que el formulario aparece y funciona, pero nadie ha completado
una compra entera desde el arreglo. Si él o cualquier otro se atasca otra vez,
**que nos mande una captura del mensaje exacto** que le salga y lo miramos en el
momento.

Y una consecuencia buena: esto era justo lo que bloqueaba la campaña para
recuperar a los alumnos cuya suscripción se paró en junio. **Ya se les puede
llamar.**

Un saludo.

---

# Notas de apoyo — NO enviar

### Por qué la pregunta de Paco es peligrosa

«Dar de baja» admite dos lecturas y las dos hacen daño:

| Lectura | Consecuencia |
|---|---|
| **Borrar la cuenta de usuario** | Se pierde historial de pedidos, facturas emitidas y el vínculo con la matrícula de EvoCampus. Irreversible |
| **Cancelar la suscripción de la 132ª** | Innecesario. El único ajuste que podría haberlo exigido —«Limitar suscripción» del `#2054`— ya está en «No limitar» |

Ninguna de las dos habría arreglado el problema real, que era la falta del
formulario de acceso en la página de compra.

### El diagnóstico técnico, por si pregunta

Producción tenía **activado** el pago como invitado y **desactivado** «Activar
el inicio de sesión durante el pago». La ayuda de WooCommerce lo explica en una
línea: *«adquirir una suscripción requiere tener una cuenta»*. Así que el
ex-alumno entraba como invitado, WooCommerce le exigía cuenta, intentaba crearla
con su email, ya existía, le mandaba a iniciar sesión — y no había formulario de
login en esa página. Callejón sin salida.

Detalle completo en `llamada_2026-08-26_acuerdos.md`.

### Lo que está verificado y lo que no

| | |
|---|---|
| El aviso y el formulario aparecen en la página de compra | ✅ comprobado en incógnito |
| Existe el enlace de contraseña olvidada | ✅ |
| El límite de suscripción del `#2054` ya no bloquea | ✅ puesto en «No limitar» |
| **Una compra completa de extremo a extremo** | ❌ **nadie la ha hecho todavía** |

Por eso el email pide que Óscar lo intente, en vez de dar el asunto por cerrado.

### Ojo al probarlo

El aviso **no se muestra a quien ya tiene la sesión iniciada**. Si alguien de la
academia lo prueba con el wp-admin abierto en otra pestaña, no lo verá y creerá
que sigue roto. Nos pasó a nosotros: hacen falta dos intentos hasta caer.
