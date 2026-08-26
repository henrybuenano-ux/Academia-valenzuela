# El producto de la 133ª, después del 31 de agosto

**26 de agosto de 2026** · producto `#2054`
Detectado al revisar la ficha del producto en producción, y ampliado tras una
pregunta de Henry que destapó un problema mayor.

## Primero, la duda directa

> *«Si no lo cambiamos, en octubre va a cobrar 48 + 80?»*

**No.** La «cuota de registro» de WooCommerce se cobra **una sola vez**, en la
compra, y no se repite nunca. El checkout lo dice: *«48,00 € que se deben pagar
hoy»* · *«Total periódico: 80,00 € EUR / mes»*. En octubre son **80 €**.

## Y el 48 no es matrícula

Queda resuelta la **decisión G** («¿los 48 € son solo de lanzamiento?»). Lo que
ya está publicado no deja margen:

| Dónde | Qué dice |
|---|---|
| Landing, barra superior | «**Primer mes 48 €** en vez de 80 € · solo hasta el 31 de agosto» |
| Landing, FAQ | «un **40 % de descuento** sobre la cuota habitual» |
| Landing, «Te suscribes» | «**Sin matrícula** y sin permanencia» |
| Prompt del bot | «80 €/mes. **Sin matrícula** y sin permanencia» |

Es el **primer mes rebajado**. «Cuota de registro» es solo el campo de
WooCommerce que se usó como vehículo técnico: con la prueba gratuita de un mes,
el cobro recurrente no arranca hasta octubre, así que el importe de entrada
tenía que ir por ahí.

**Poner una matrícula ahora contradiría lo prometido a todos los leads.**

---

## 🔴 El problema de verdad: la cobertura varía según el día de compra

Configuración actual: 80 €/mes · cuota de registro 48 € · **prueba gratuita
1 mes** · **sincronizar al día 1º del mes**.

WooCommerce Subscriptions aplica la prueba y **después** sincroniza al siguiente
día 1. La documentación oficial lo ejemplifica: *«since the free trial ended on
February 3rd (after the 1st), the first synchronized payment is the first of the
following month, or March 1st»*.

| Compra | Fin de prueba | 1ª renovación | **Días cubiertos por un pago** |
|---|---|---|---|
| 26-ago | 26-sep | 1-oct | 36 |
| **2-sep** | 2-oct | **1-nov** | **60** |
| 15-sep | 15-oct | 1-nov | 47 |
| 30-sep | 30-oct | 1-nov | 32 |

*(El caso del 26-ago está verificado en el checkout de producción. El resto se
deriva de la regla documentada.)*

### Por qué importa

**Comercial.** Quien compra el día 2 paga lo mismo que quien compra el 30 y
recibe **casi el doble de tiempo**. No es una promoción, es un accidente de
configuración.

**Técnica, y más grave.** El conciliador da por buena una suscripción si el
último pago tiene menos de **38 días** (`OMNIA_EVO_GRACE`). Un hueco de 60 días
lo supera con holgura: el plugin marcaría como **baja** a alguien con la
suscripción impecable, y en modo real **le cortaría el acceso al campus**.

> Corrijo lo que dije antes: caractericé este hueco como «36 días, pasa por dos».
> Eso era mirando solo el caso del 26 de agosto. **Puede llegar a 60.**

---

## Qué hacer el 1 de septiembre

### ❌ Lo que NO basta

Cambiar la cuota de registro de 48 a 80 y nada más. Restaura el precio, pero
mantiene intacta la cobertura variable de 32 a 60 días y el riesgo para el
plugin.

### ✅ Recomendado: quitar la prueba gratuita y prorratear

1. **Prueba gratuita → 0**
2. **Cuota de registro → 0**
3. Mantener la sincronización al día 1
4. En **WooCommerce → Ajustes → Suscripciones**, activar **«Prorate First
   Renewal»** (aparece al activar la sincronización)

Resultado: quien compre el 5 de septiembre paga hoy la parte proporcional hasta
el 1 de octubre (unos 69 €) y después 80 € cada día 1. **Todos alineados al día
1, ningún hueco mayor de 31 días, y cada uno paga exactamente por los días que
recibe.**

⚠️ **«Prorate First Renewal» es un ajuste de toda la tienda**, no del producto.
Hoy no hay conflicto porque la 132ª está terminada, pero conviene saberlo.

⚠️ El importe de entrada deja de ser un número redondo. La landing tendrá que
decir «80 €/mes, el primer pago proporcional a los días que quedan de mes» en
lugar de una cifra fija.

### Alternativa más simple: quitar la sincronización

Cobro de 80 € en la compra y cada mes desde esa fecha. Hueco siempre de ~30
días, sin ajustes de tienda y con importe redondo. Se pierde el cobro unificado
el día 1, que para contabilidad es cómodo.

> Nota: en el plan preliminar recomendé esta opción. Al confirmar que WooCommerce
> sí ofrece prorrateo nativo, la de arriba es mejor: conserva el día 1 y además
> es más justa.

---

## Resumen de acciones

| Cuándo | Qué | Quién |
|---|---|---|
| **1-sep** | Reconfigurar el `#2054` según la opción elegida | Equipo (wp-admin) |
| **1-sep** | Vaciar «Fechas del precio rebajado» (6-ago → 31-ago): no hacen nada y hacen creer que el precio está programado | Equipo |
| **1-sep** | Actualizar landing y prompt del bot con el precio y la fórmula nuevos | Omnia |
| **Antes del modo real** | Parche v0.8.1 del plugin: veredicto por estado de la suscripción, no por días desde el último pago | Omnia |

## Fuentes

- [Guide to Synchronized Renewals](https://woocommerce.com/document/subscriptions/renewal-synchronisation/)
- [Creating a Synchronized Subscription Product](https://woocommerce.com/document/subscriptions/renewal-synchronisation/creating-a-synchronized-subscription-product/)
- [Subscriptions General Settings](https://woocommerce.com/document/subscriptions/store-manager-guide/)
