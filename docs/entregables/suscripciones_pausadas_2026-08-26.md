# Las suscripciones pausadas · quién es quién · 26-ago-2026

> ⚠️ **Corrección previa.** Veníamos hablando de «las 39 suscripciones
> pausadas» y buscando esa lista en el CSV de julio, donde hay exactamente
> 39 contactos con la etiqueta `ultimo-estado-baja`. **Son listas distintas y
> coincide el número por casualidad.**

## Por qué no son la misma lista

Al cruzar el CSV de julio con la conciliación de producción de anoche:

| | |
|---|---|
| Afectados por la pausa (último pago entre el 31-may y el 19-jun) | **32** |
| Contactos con `ultimo-estado-baja` en el CSV de julio | 39 |
| **En ambas listas** | **13** |

Los **19** afectados por la pausa que no están entre esos 39 llevan todos la
etiqueta **`ultimo-estado-activo`**. Y es coherente: a esas personas se les
estaba cobrando con normalidad hasta el 19 de junio, así que cuando se hizo el
import de julio su suscripción figuraba como viva.

Los 39 con `ultimo-estado-baja` son en su mayoría bajas de **109 a 243 días**,
muy anteriores al 24 de junio. Son ex-alumnos reales — el público correcto para
la campaña de re-enganche — pero **no son a quienes la academia dejó de cobrar**.

> 🔴 **Consecuencia práctica:** si se lleva a la reunión el listado de 39 como
> «las suscripciones pausadas», se estaría proponiendo cancelar a 26 personas
> que no tienen nada que ver con la pausa, y dejando fuera a 19 que sí.

## Dónde está la lista buena de verdad

En **WooCommerce → Suscripciones → filtrar por estado «En espera»**. Es la
única fuente autorizada y son treinta segundos. Nosotros no podemos consultarla:
el wp-admin de producción bloquea nuestra IP.

Lo de abajo es **la mejor reconstrucción posible desde fuera**, a partir de la
conciliación en seco que corrió anoche contra los datos reales de producción.
Sirve para trabajar, pero antes de cancelar nada conviene contrastarlo con el
filtro de WooCommerce.

*(El informe de julio contaba 39 «En espera» y 21 «Canceladas». Hoy salen 32 en
la ventana de la pausa. La diferencia de siete puede ser gente que ya se dio de
baja, que volvió a pagar, o cambios posteriores. Otra razón para mirar el filtro.)*

---

# Los 32 a los que la academia dejó de cobrar

Último pago entre el **31 de mayo y el 19 de junio**. La edición en lote que las
pasó a *En espera* fue el **24 de junio**. **No impagaron.**

| # | Nombre | Email | Teléfono | Último pago |
|---|---|---|---|---|
| 1 | Cristian Alonso Lavega | lavega_94@hotmail.com | 34680980056 | hace 67 días |
| 2 | Sergio Aguilera Ramírez | sergioaguileraramirez17@gmail.com | +34600863331 | hace 71 días |
| 3 | Andrea Sánchez Justicia | andreasanchezjusticia@gmail.com | +34657320608 | hace 73 días |
| 4 | Ana Ruiz Rueda | anaruizrueda8@gmail.com | +34657579548 | hace 75 días |
| 5 | Manuel Rodríguez Fernández | manuel.rodriguez16102007@gmail.com | +34671198799 | hace 77 días |
| 6 | Juan Sánchez Villarejo | js8076324@gmail.com | 34692109895 | hace 77 días |
| 7 | Mostafa Abdeslam | Musta_18@hotmail.es | +34677805546 | hace 77 días |
| 8 | Borja Barranco Sánchez | barrancofborja86@gmail.com | +34603010594 | hace 80 días |
| 9 | Andrea Fuentes Rivera | andreafur6@gmail.com | +34722432085 | hace 80 días |
| 10 | Rosa María Morales Ortega | rosajimena78@gmail.com | +34674964350 | hace 80 días |
| 11 | Elena Hurtado Justicia | elenahurjus@gmail.com | +34651777750 | hace 80 días |
| 12 | David Flores | davidmotril11@gmail.com | +34678097010 | hace 81 días |
| 13 | Nazareth Martínez López | mlnazareth2005@gmail.com | +34697970936 | hace 81 días |
| 14 | Rudy Lozano Rueda | rudylozanorueda@gmail.com | +34646859705 | hace 81 días |
| 15 | Jose Manuel Nuñez Carrasco | josemanuel070488@gmail.com | +34622096743 | hace 81 días |
| 16 | Antonio Miguel Cantarero Gámez | antoniocantarerogamez@gmail.com | — | hace 81 días |
| 17 | Samuel García Ávila | samuelgarciaavila@gmail.com | +34658797945 | hace 81 días |
| 18 | Victor Abad Lama | victorabad695@gmail.com | +34689519634 | hace 81 días |
| 19 | Jaime Romero Marquez | jaimerome150@gmail.com | +34635243266 | hace 81 días |
| 20 | Anaraida Nievas Santiago | anaraidanievassantiago30@gmail.com | +34617962948 | hace 82 días |
| 21 | Rafael Luis Castillo Osuna | rafaelluiscastillo20@gmail.com | +34658933279 | hace 82 días |
| 22 | Estela Romero Abolafia | estelaromero.ab@gmail.com | +34676626004 | hace 82 días |
| 23 | José López García | trompocambil@gmail.com | +34645230940 | hace 82 días |
| 24 | Isabel Del Río Hervás | isabeldelrio97@gmail.com | +34669096245 | hace 83 días |
| 25 | Cristobal Valenzuela Guerrero | cris.atleta89@gmail.com | +34625229041 | hace 83 días |
| 26 | Samuel Duro Latorre | samdl2003@icloud.com | +34603488220 | hace 83 días |
| 27 | Rafael Alberto Luque | rafaluque1995@hotmail.com | +34616170650 | hace 84 días |
| 28 | Cristina Parra Morata | Morata__8@hotmail.com | +34610383954 | hace 84 días |
| 29 | Oscar Vargas Balboa | oscarbelmez@gmail.com | 34677000043 | hace 85 días |
| 30 | Natalia Amador Delgado | nataliaamador97@gmail.com | +34637128906 | hace 85 días |
| 31 | Blanca Mena | blancamenaalbarran@hotmail.com | +34637247616 | hace 86 días |
| 32 | Marta Sánchez De Pablo Fernández | martasdpf@gmail.com | +34654428038 | hace 86 días |

**Los 32 tienen teléfono menos uno.** Es un dato que cambia lo que se puede
hacer con ellos: no es una lista para un email masivo, es una lista para llamar.

## Qué son estas personas, exactamente

> 🔄 **Corregido el 26-ago tras hablar con Paco.** Aquí decíamos que se les dejó
> de cobrar por *«un error administrativo»*. **No lo fue.** Fue deliberado:
>
> *«Como eran simplemente unos 11 días del mes de julio, quisimos tener el
> detalle hacia ellos de que esos 11 días no los pagaran, es decir, como
> regalo.»*
>
> El curso terminaba a los 11 días de julio y la academia decidió no cobrar ese
> tramo. Un gesto, no un fallo.

Gente que **estaba pagando religiosamente** y a la que la academia **le regaló
el final del curso**. No se fueron: se les dejó de cobrar a propósito.

Eso los convierte en el mejor público posible para la 133ª, y con un argumento
mucho más cómodo del que teníamos:

| | |
|---|---|
| ❌ Lo que decíamos antes | «Se paró tu suscripción por un problema nuestro, ¿la retomamos?» |
| ✅ Lo correcto | «Te regalamos el final de julio. ¿Retomamos en septiembre?» |

No hay nada que disculpar: se llama para ofrecer, no para reparar.

*(Paco, con humor resignado: «poquita gente lo agradeció».)*

## ⚠️ Y algo que hay que arreglar ANTES de llamarles

Varios de estos ex-alumnos **ya han intentado volver y no han podido**: al meter
su email, la tienda les dice que ya existe una cuenta y no les deja terminar la
compra. Está diagnosticado en `llamada_2026-08-26_acuerdos.md`.

**Llamar a 32 personas para que se encuentren un muro es peor que no llamar.**
Primero se arregla el checkout, después se llama.

## Y la trampa que hay que evitar

Si el plugin pasara a modo real hoy, **a estas 32 personas se les etiquetaría
como impagados y se les abriría una ficha de recobro**, con sus correos de
reclamación. A gente a la que se dejó de cobrar. Es el bloqueo número uno para
encender el sistema, y está detallado en `dryrun_produccion_2026-08-25.md`.

---

# Los 39 del CSV de julio (`ultimo-estado-baja`)

Esta es la lista que **sí** vale para la campaña de re-enganche: ex-alumnos que
pagaron y lo dejaron. De ellos, **13 coinciden** con la lista de arriba (dejaron
de pagar justo en la ventana de la pausa) y **23 son bajas anteriores**, de 109
a 243 días.

**Tres ya han vuelto solos a la 133ª, sin campaña ninguna:**

| Nombre | Email | Confirmación |
|---|---|---|
| Félix Manuel Febrero Vega | felixmanuelfebrero@gmail.com | ✅ por email — pagó hace 6 días (pedido `#2109`) |
| Pedro Callejo Albarrán | pedrocallejo.rc@gmail.com | ✅ por email — pagó hace 6 días (pedido `#2107`) |
| Elisabet Rivera Diaz | elisabet_rd@hotmail.es | ⚠️ probable — el pedido `#2127` (25-ago) es de una «Elisabet Rivera», pero **coincide solo el nombre**, no lo hemos verificado por email |

Que tres del segmento hayan vuelto por su cuenta antes de mandarles nada es el
mejor argumento que hay para lanzar la campaña de re-enganche: **el público
convierte incluso sin que le hablemos**.

> 📌 Sobre la tercera fila: no la damos por confirmada. La regla en este
> proyecto —después de borrar tres contactos reales por buscar por nombre— es
> **identificar siempre por email exacto, nunca por nombre**.

## De dónde sale cada cosa

- **Los 32**: conciliación en seco del plugin en producción, 25-ago 21:15.
  Días desde el último pedido pagado (estados *processing* y *completed*).
- **Los 39 y los datos de contacto**: `alumnos_import_ghl_2026-07-17.csv`,
  filtrando `pago-suscripcion` + `ultimo-estado-baja`.
- **La fecha y la causa de la pausa**: `p2_causa_raiz_2026-07-10.md` — edición
  en lote del **24-jun-2026 a las 14:05**.
