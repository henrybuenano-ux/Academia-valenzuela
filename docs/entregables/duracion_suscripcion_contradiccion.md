# ¿Hasta cuándo tiene acceso el alumno? Tres fuentes, tres respuestas

**26 de agosto de 2026** · pendiente de resolver con Paco **antes de que alguien
compre creyendo otra cosa**

## El problema

Hoy se ha publicado en la landing y en el prompt del bot que **la suscripción
corre hasta el examen**. Al verificarlo aparece que las fuentes no coinciden.

| Fuente | Qué dice | Fecha |
|---|---|---|
| **Llamada con Paco** (acta) | La suscripción corre mes a mes **hasta el examen**, que cae en **junio o julio** | 26-ago-2026 |
| **Llamada de descubrimiento**, con Paco **y Fran** (el que lleva los pagos) | La suscripción mensual «dura hasta **abril/mayo**». El **intensivo** es un producto aparte de **pago único** (abril–junio) que «se cierra el día del examen». El examen es en **julio** | 10-jun-2026 |
| **El producto `#2054` en producción** | «Dejar de renovar después de: **No parar hasta que se cancele**» — sin fecha de fin | verificado 26-ago |

Tres lecturas incompatibles: **acaba en abril/mayo**, **acaba con el examen**, o
**no acaba nunca**.

Y el mes del examen tampoco cuadra: una fuente dice **julio**, la otra **junio o
julio**.

## Por qué corre

**Ya está publicado.** Si la suscripción termina en abril/mayo y el tramo final
hasta el examen exige comprar el intensivo aparte, le estamos prometiendo a cada
lead algo que la academia no vende. Se enterarían en abril, con el examen encima
y después de haber pagado ocho meses.

Afecta a:

- `entregables/landing_133.html`
- `entregables/bot_ls02_prompts_2026-08-07.md`
- La ficha del producto `#2054`, que hay que reconfigurar el 1-sep de todas
  formas (`precio_lanzamiento_caduca_31ago.md`)

## Límite de esta verificación

La transcripción de la llamada del 26-ago **no está guardada en el repositorio**:
se pegó en el chat y esa parte de la conversación ya se ha compactado. Lo de
arriba está contrastado contra **el acta**, no contra la fuente primaria.

La única cita literal que se conservó de ese tramo es la de la liberación del
contenido — *«porque si le das todo de golpe hay algunos que se saturan»* — no la
de la fecha de fin. **La fecha de fin es interpretación, no transcripción.**

> Para la próxima: guardar la transcripción en `docs/` antes de trabajarla. Una
> cita que no se puede releer no sirve para verificar nada.

## La pregunta para Paco

Concreta, para que no se pueda responder de forma ambigua:

> El alumno que se suscribe en septiembre y paga todos los meses, **¿hasta qué
> mes tiene acceso al campus?**
>
> - ¿Hasta abril/mayo, cuando se acaba el temario?
> - ¿O hasta el día del examen?
>
> Y si es hasta abril/mayo: **el intensivo de abril–junio, ¿se paga aparte?**
> ¿Un suscriptor que lleva ocho meses pagando tiene que comprarlo también?

De la respuesta salen tres cosas: qué dice la web, qué dice el bot, y cómo se
configura la fecha de fin del producto el 1 de septiembre.

## Hasta que conteste

**No cambiar nada todavía.** El texto publicado hoy es el más generoso de los
tres, así que el riesgo es de promesa excesiva, no de venta perdida. Pero no
conviene que pasen semanas: cuanta más gente compre con la promesa de «hasta el
examen», más caro sale corregirla.
