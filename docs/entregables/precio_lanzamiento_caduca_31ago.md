# El precio de lanzamiento no caduca solo

**26 de agosto de 2026** · producto `#2054` — 133ª promoción
Detectado al revisar la ficha del producto en producción.

## El problema

La landing y el bot anuncian que **el primer mes a 48 € vale hasta el 31 de
agosto**. El producto no está configurado para que eso ocurra.

Configuración real del `#2054` (pestaña General):

| Campo | Valor |
|---|---|
| Precio de suscripción | **80 €** cada mes |
| Cuota de registro | **48 €** |
| Prueba gratuita | 1 mes |
| Ajustar facturación | día 1º del mes |
| **Precio rebajado** | *(vacío)* |
| **Fechas del precio rebajado** | **2026-08-06 → 2026-08-31** |

Las fechas están puestas sobre un precio rebajado **que no existe**. Sin importe
en «Precio rebajado», la programación no tiene nada que aplicar ni que retirar:
**no hace absolutamente nada**.

La oferta de 48 € está implementada como **cuota de registro**, que es un campo
fijo, sin fechas y sin caducidad.

## La consecuencia

**El 1 de septiembre no sube nada.** Sin que alguien entre a mano, la 133ª se
sigue vendiendo con 48 € de entrada indefinidamente — en octubre, en noviembre —
mientras la web y el bot siguen diciendo que la oferta terminó el 31 de agosto.

Es la peor combinación de las dos: se pierde el margen *y* se pierde la urgencia
como argumento de venta, porque quien pruebe descubre que la oferta seguía ahí.

## La acción

**El 1 de septiembre**, en la ficha del producto → Datos del producto → General:

- **Cuota de registro: 48 → 80**

`https://academiavalenz.com/wp-admin/post.php?post=2054&action=edit`

De paso, **vaciar las «Fechas del precio rebajado»**: no hacen nada y hacen creer
que el precio está programado. Mejor que no estén a que engañen.

## Si se quiere que sea automático

WooCommerce no programa la cuota de registro. Las dos salidas reales:

1. **Dos productos**, uno de lanzamiento y otro normal, y cambiar el enlace de la
   landing el día 1. Más trabajo, pero deja rastro y no depende de que nadie se
   acuerde.
2. **Recordatorio en calendario** para el 1-sep. Es lo que hay hoy, y depende de
   una persona.

Para una oferta puntual el recordatorio basta. Si las promociones se van a
repetir cada promoción, conviene la opción 1.
