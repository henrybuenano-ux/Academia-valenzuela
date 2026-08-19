# Facturación — respuesta al cliente y plan · 10-ago-2026

> El cliente respondió (10-ago) sobre facturación: eligieron numeración
> `26/08-0001` (año/mes-correlativo), **nunca han emitido una factura**, no
> tienen plantilla y preguntan cómo resolver las ventas que ya están
> entrando. Esto es lo que hay que contestar y hacer.

## 🔴 Lo que bloquea todo: el IVA

**Antes de emitir la primera factura hay que saber si el curso está exento de
IVA.** La enseñanza puede estarlo (art. 20.Uno.9º de la Ley 37/1992) y las
academias de oposiciones suelen encajar, pero depende del caso concreto y
**lo confirma la gestoría, no nosotros**.

Por qué no es un detalle de diseño:
- Si **está exenta** → factura sin IVA, citando el artículo de la exención.
- Si **no lo está** → 21 %, y aparece una decisión comercial: ¿los 80 € pasan
  a ser el total con IVA incluido (base 66,12 €, el alumno paga lo mismo) o se
  añade encima (el alumno pasa a pagar 96,80 €)?
- Y afecta hacia atrás: llevan cobrando desde **noviembre de 2025**.

## La numeración: válida, pero con un agujero en el razonamiento

`26/08-0001` es correcto: es una serie mensual, y las series están permitidas
mientras dentro de cada una la numeración sea correlativa y sin huecos
(art. 6 del RD 1619/2012).

**El fallo está en el motivo.** Dicen que lo hacen para no pisar números
mientras trabajan las facturas anteriores — pero la numeración va por **fecha
de expedición**, no por la de la venta. Si mañana emiten la factura de una
venta de marzo, se expide en agosto → entra igualmente en la serie `26/08` y
vuelve a pisarse con las nuevas.

**Lo que sí les da ese margen: una serie aparte para el atrasado**, p. ej.
`26/ANT-0001`. Nuevas ventas en `26/08-000X`, atrasadas en su propia serie,
sin cruces y mucho más limpio de cuadrar.

*(Nota operativa: la serie mensual obliga a reiniciar el contador cada mes.
Es viable, pero si en algún momento quieren simplificar, una serie anual
`26-0001` da menos trabajo. Su elección es legítima; solo hay que
configurarla bien.)*

## "Ya hay gente comprando": la respuesta es automatizar

Con las suscripciones renovando mensualmente son **~39 facturas al mes** hoy,
y crece con cada alumno de la 133ª. A mano es inviable.

Dato ya verificado en la auditoría de julio: **en la web no hay ningún plugin
ni servicio de facturación** (revisados los 31 plugins activos). Cada venta
que entra ahora mismo no genera factura.

**Propuesta**: instalar un generador de facturas de WooCommerce que emita el
PDF automáticamente en cada pedido, con su numeración, y se lo envíe al
alumno. Resuelve agosto y todas las renovaciones sin trabajo manual.

### A verificar antes de recomendar uno concreto
1. **Reinicio mensual del contador** — es el requisito menos habitual de su
   formato. Muchos plugins reinician anualmente de serie; el mensual puede
   requerir versión de pago o un ajuste. **Hay que comprobarlo antes de
   prometerlo.**
2. **Series múltiples** (para separar el atrasado).
3. **Compatibilidad con WooCommerce Subscriptions** (que facture también las
   renovaciones, no solo el primer pedido).
4. **Camino a VeriFactu** sin rehacerlo (ver abajo).

## VeriFactu: no corre prisa, pero condiciona la elección

No es obligatorio todavía. Con el **RD-ley 15/2025** (BOE 3-dic-2025) los
plazos quedaron en **1-ene-2027** (sociedades) y **1-jul-2027** (autónomos).
Conviene saber cuál les aplica: son seis meses de diferencia.

Lo que sí venció es la otra pata: los **fabricantes de software** debían tener
sus productos adaptados en julio de 2025, así que hoy las soluciones serias
del mercado español ya vienen preparadas.

**Para la decisión de ahora**: no bloquea nada y no hace falta que lo que
instalemos esté certificado hoy. El único criterio es **no elegir un callejón
sin salida** que haya que rehacer entero en 2027.

## Modelo de factura

`plantilla_factura_academia_valenz.html` — imprimible a PDF, con los campos
que exige el art. 6 del RD 1619/2012 (serie y número, fechas de expedición y
operación, datos fiscales de ambas partes, descripción, base, IVA o mención
de exención). Trae **las dos versiones**: exenta y con 21 %.

Sirve para dos cosas: las facturas atrasadas que hagan a mano, y como
referencia de lo que debe generar la automatización.

## ✅ PROBADO EN STAGING (10-ago) — resultados de primera mano

No es una comparativa de folleto: las webs de los fabricantes están
bloqueadas desde nuestro entorno, así que se instaló el candidato en el
staging y se leyeron sus ajustes reales.

**Entorno de prueba** (mejor de lo esperado): staging tiene WooCommerce,
**WooCommerce Subscriptions**, WooPayments y nuestro plugin de EvoCampus
activos, con 3 productos y **286 pedidos** copiados de producción.

**Candidato probado**: *PDF Invoices & Packing Slips* (WP Overnight) **v1.8.0**,
gratuito. Instalado y activo en staging.

| # | Requisito | Resultado |
|---|---|---|
| 1 | **Reinicio mensual** del contador | ❌ **NO existe.** El único ajuste es una casilla *"Restablecer el número de factura anualmente"* (`reset_number_yearly`). Verificado en el propio formulario |
| 2 | Formato `26/08-0001` | ✅ El prefijo acepta `[invoice_year]` y `[invoice_month]`, más relleno de ceros configurable |
| 3 | **Facturar renovaciones** de suscripción | ✅ **Excelente**: adjunta la factura a 10 correos de renovación distintos (`customer_completed_renewal_order`, `new_renewal_order`, `customer_renewal_invoice`…) |
| 4 | Mención de exención de IVA | ✅ Vía plantilla/textos del documento |
| 5 | Envío automático del PDF al alumno | ✅ Se adjunta al correo de pedido completado |

**Resumen: 4 de 5.** Falla exactamente donde se preveía — y la buena noticia
es que ese requisito no hace falta.

### 🎯 Recomendación: proponerles serie ANUAL en vez de mensual

El formato mensual lo eligieron para tener margen con el atrasado, pero **ese
razonamiento no se sostiene** (la numeración va por fecha de expedición, no de
venta: ya está explicado arriba). Si el motivo no aplica, el formato mensual
solo añade coste y complejidad.

**Propuesta:**
- **Facturas corrientes** → serie anual `26-0001`, generada automáticamente
  por el plugin gratuito, renovaciones incluidas. Coste: **0 €**.
- **Facturas atrasadas** → serie propia `26/ANT-0001`, emitidas a mano con
  `plantilla_factura_academia_valenz.html`. Es un trabajo histórico y puntual;
  no merece pagar un plugin por él.

Legalmente equivalente (las series están permitidas; lo que importa es que
dentro de cada una la numeración sea correlativa), gratis y sin piezas extra.

**Si insisten en el formato mensual**: hay que pasar a una versión de pago
(WebToffee anuncia reinicio mensual; WP Overnight lo tiene en su extensión
Professional). No se ha podido verificar precio ni funcionamiento desde aquí
—sus webs están bloqueadas— así que habría que probarlo antes de comprometerlo.

### Estado del staging
El plugin queda **instalado y activo en staging**, sin configurar. No afecta a
producción. Si molesta, se desactiva desde la lista de plugins.

## Borrador de respuesta al cliente

> Hola,
>
> Genial, con esto podemos avanzar. Te comento tres cosas:
>
> **1. La numeración es correcta, pero os proponemos simplificarla.** El
> formato `26/08-0001` es válido. El punto flojo está en el motivo: la
> numeración va por fecha de emisión, no por la de la venta. Si emitís ahora
> una factura de una venta de marzo, se expide en agosto, así que entraría
> igualmente en la serie de agosto y volvería a pisarse. O sea, el reinicio
> mensual no os da el margen que buscáis.
>
> Lo que sí os lo da es **una serie aparte para las facturas atrasadas**, por
> ejemplo `26/ANT-0001`. Y si el atrasado va por su propia serie, las
> corrientes ya no necesitan el corte mensual: con una **serie anual
> `26-0001`** os vale, es igual de válida legalmente y os simplifica la vida.
>
> Hay un motivo práctico añadido: hemos probado el sistema de facturación
> automática y el reinicio **anual** viene de serie y sale gratis, mientras
> que el mensual obliga a una versión de pago. Si preferís mantener el formato
> mensual se puede, pero conviene saber que tiene coste.
>
> **2. Os pasamos el modelo de factura, pero necesitamos una confirmación de
> vuestra gestoría antes: ¿el curso está exento de IVA?** La formación suele
> estarlo, pero hay que confirmarlo en vuestro caso. Cambia la factura: si
> está exenta hay que indicar el artículo de la exención, y si no lo está hay
> que aplicar el 21 % y decidir si los 80 € pasan a incluirlo o se suma
> encima. Os mandamos la plantilla con las dos versiones para que la gestoría
> vea de qué hablamos.
>
> **3. Sobre la gente que ya está comprando: os proponemos automatizarlo.**
> Con las suscripciones renovando cada mes son unas 39 facturas mensuales
> ahora mismo, y suben con cada alumno de la 133ª. A mano se os hace bola
> enseguida. Podemos instalar en la web un sistema que genere la factura en
> PDF automáticamente con cada pedido, ya con vuestra numeración, y se la
> envíe al alumno.
>
> En cuanto nos confirméis lo del IVA, cerramos el modelo y os pasamos la
> propuesta de la automatización.
>
> Un saludo.

## Pendiente de decisión interna (Omnia)

- ¿Entra la automatización de facturación en el alcance actual o va como
  presupuesto aparte? (Estaba en **Fase 2**, que llevaba meses bloqueada por
  la gestoría.)
- El **atrasado desde noviembre de 2025** — unos 8 meses de pedidos sin
  factura — no es trabajo nuestro, pero conviene que Horacio lo sepa: es
  volumen y es responsabilidad del cliente.
