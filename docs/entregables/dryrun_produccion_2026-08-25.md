# Resultado de la pasada en seco en producción · 25-ago-2026

> El plugin **v0.8.0 quedó desplegado en producción en DRY-RUN** y se ejecutaron
> sus dos informes. **El deploy pasa.** Pero la pasada en seco, cruzada con la
> configuración del producto y con la suscripción `#2088`, ha destapado un fallo
> que **cortaría el acceso a la mayoría de los alumnos nuevos en septiembre**,
> más dos riesgos de facturación.
>
> Nada de esto era visible antes de desplegar. Todo lo de aquí está confirmado
> con datos de producción, no deducido.

## ✅ El deploy pasa

| Check del runbook | Resultado |
|---|---|
| Página carga: "Modo: DRY-RUN · Ventana de pago: 38 días" | ✅ |
| Conciliación sin errores | ✅ **71 de 71 alumnos en 15 s** (sin rozar el corte de 150 s) |
| Informe de acceso sin pago | ✅ 35 filas |
| Sin errores PHP | ✅ |

### El censo cuadra al 100 %

Los **16 "activos"** de la conciliación son exactamente los **16 pedidos de 48 €
del 14 al 25 de agosto**. Comprobado uno a uno:

| Pedido | Fecha | Email del log | Días desde el pago |
|---|---|---|---|
| `#2087` Ana Belén Mendoza | 14-ago | `mendozahervasana@gmail.com` | 11 |
| `#2101` Saray Benítez | 19-ago | `saraybenitezcaracuel@gmail.com` | 6 |
| `#2103` Pablo Suárez | 19-ago | `pablosualop@gmail.com` | 6 |
| `#2105` Manuel León | 19-ago | `mleonguijosa@gmail.com` | 6 |
| `#2107` Pedro Callejo | 19-ago | `pedrocallejo.rc@gmail.com` | 6 |
| `#2109` Félix Manuel Febrero | 19-ago | `felixmanuelfebrero@gmail.com` | 6 |
| `#2112` José Mariano Martínez | 20-ago | `MARIANO2003HERRANZ@GMAIL.COM` | 5 |
| `#2114` Nicolás Díaz | 22-ago | `nicodiaz1995@hotmail.com` | 3 |

*(Los 8 restantes —`#2116` a `#2133`, del 23 al 25 de agosto— quedaron por
encima del tramo de log capturado, pero suman los 16 exactos.)*

Los **55 restantes** son bajas de la 132ª. **16 + 55 = 71.** El plugin lee bien:
el motor de censo, la consulta de último pago pagado y el veredicto funcionan.

---

# 🔴 Hallazgo 1 — El plugin cortaría a 8-13 de los 16 alumnos nuevos en septiembre

**Es el hallazgo que bloquea el modo real.**

## La configuración del producto (correcta)

| Campo | Valor |
|---|---|
| Precio de suscripción | **80 € cada mes** |
| Cuota de registro | **48 €** |
| Prueba gratuita | **1 mes** |
| Ajustar facturación | **día 1º del mes** |
| Dejar de renovar | No parar hasta que se cancele |
| Precio rebajado | *(vacío, con fechas 6-ago → 31-ago puestas)* |

Esto hace **exactamente lo que promete la ficha**: 48 € de entrada cubren
septiembre (40 % de descuento sobre 80 €), y desde octubre son 80 €/mes. El
48 € **no es permanente** y el cliente cobrará bien. La configuración comercial
no tiene ningún problema.

## El fallo es del plugin

Confirmado en la suscripción **`#2088`** (del pedido `#2087`):

| Dato | Valor |
|---|---|
| Fecha de inicio | 14-ago-2026 |
| Fin del periodo de prueba | 14-sep-2026 |
| **Siguiente pago** | **30-sep 22:00** (= 1-oct en hora española) |
| Estado | Activa · 80,00 € / mes |

`reconcile()` decide por **"días desde el último pedido pagado ≤ 38"**.

De 14-ago a 1-oct hay **48 días**. Día 38 = 21-sep → **corte el 22-sep**, nueve
días antes de que al alumno le toque pagar.

El hueco lo abre la sincronización: la prueba termina el **14-sep** pero el cobro
no llega hasta el **30**. Hay 16 días en los que el alumno ya no está en prueba y
todavía no ha pagado. El plugin corta justo ahí.

## Alcance: quién cae y cuándo

Con la sincronización, **los 16 cobran el 1-oct**. Lo que cambia es su fecha de
compra:

| Compra | Pedidos | Día 38 | Resultado |
|---|---|---|---|
| 14-ago | `#2087` | 21-sep | ❌ **corte 22-sep** |
| 19-ago | `#2101` `#2103` `#2105` `#2107` `#2109` | 26-sep | ❌ **corte 27-sep** |
| 20-ago | `#2112` | 27-sep | ❌ **corte 28-sep** |
| 22-ago | `#2114` | 29-sep | ❌ **corte 30-sep** |
| 23-ago | `#2116` | 30-sep | ⚠️ empate el 1-oct |
| 24-ago | `#2118` `#2120` `#2122` `#2124` | 1-oct | ⚠️ empate |
| 25-ago | `#2127` `#2131` `#2133` | 2-oct | ✅ a salvo |

**8 cortes seguros y 5 a suerte**, según si el cron diario pasa antes o después
del cobro del 1-oct.

Y no es solo perder el acceso al campus: cada uno se lleva también el tag
`alumno-impago`, una **oportunidad de recobro** y la **secuencia de dunning**.
Alumnos nuevos, en su primer mes, con el curso recién empezado, reclamándoles una
deuda que no tienen.

## No es del lanzamiento: es estructural

Después del 1-oct todos renuevan el día 1, y de 1-oct a 1-nov hay 31 días —
dentro de ventana, estable. **Pero cada alta nueva vuelve a caer en el agujero.**

Quien compre el **2 de septiembre**: prueba hasta el 2-oct, primer día 1
posterior el **1-nov**. Son **60 días**. Corte el 11-oct.

Mientras existan prueba gratuita + sincronización al día 1, el plugin cortará a
todo el que compre a principios de mes. Cuanto más temprano en el mes, peor.

## Causa raíz

**"Días desde el último pago" es la pregunta equivocada.** Sirve para una
suscripción normal que cobra cada 30 días, pero no cuando hay periodo de prueba
o facturación sincronizada: el hueco entre pagos deja de ser el ciclo.

Hay que preguntarle a la propia suscripción. WooCommerce Subscriptions ya lo
sabe y el plugin no se lo pregunta:

- `$sub->get_date('next_payment')` — si está en el futuro, el alumno está al
  corriente por definición.
- `$sub->get_date('trial_end')` — si sigue en prueba, tiene acceso legítimo.
- `$sub->get_status()` — `active` / `pending-cancel` mantienen acceso.

**El arreglo** (v0.8.1, ver abajo): si hay `next_payment` en el futuro o sigue en
periodo de prueba → **activo**. La ventana de 38 días queda como red de seguridad
para quien de verdad caducó, que es para lo que se diseñó.

---

# 🔴 Hallazgo 2 — El primer pase real dispara 55 avisos de golpe

`reconcile()` solo avisa a GHL cuando el veredicto **cambia** respecto a la
opción `omnia_evo_verdicts`. Pero esa opción **no se persiste en DRY-RUN**:

```php
// En DRY-RUN no se persisten veredictos: el primer pase real
// notificará a GHL el estado inicial de todos los alumnos.
if ( ! OMNIA_EVO_DRYRUN ) {
    update_option( 'omnia_evo_verdicts', $verdicts, false );
}
```

Es deliberado, pero se diseñó suponiendo un censo limpio. Con la opción vacía, la
primera pasada real notifica a los 71 de golpe: **55 tags `alumno-impago` + 55
oportunidades de recobro**, y con ellas las secuencias de dunning.

## Y 32 de esas 55 no deben nada

| Antigüedad del último pago | Cuántos | Qué son |
|---|---|---|
| **67-86 días** (31-may → 19-jun) | **32** | 🔴 El lote que se pasó a *En espera* el **24-jun**. No impagaron: **la academia dejó de cobrarles** |
| **> 100 días** | 23 | Bajas reales de la 132ª |

Reclamarles una deuda a esos 32 sería un error caro. Además **son las mismas
personas del segmento de re-enganche de LS03**: recibirían a la vez un "vuelve a
la 133ª" y un "nos debes dinero".

---

# 🟡 Hallazgo 3 — Ya se vende sin IVA, sin confirmación de la gestoría

El pedido `#2087`: **subtotal 48 €, total 48 €, sin ninguna línea de impuesto**,
y el metadato `is_vat_exempt: no`.

O sea: **están facturando de facto como exentos** mientras la pregunta sigue
abierta desde el 10-ago. Ahora tiene consecuencia numérica: si la gestoría dice
que el curso **no** está exento, deben el 21 % de todo lo vendido, **hacia atrás**.

- Expuesto hoy: 16 × 48 € = **768 €** → ≈ **133 € de IVA**.
- Crece con cada venta, y está el atrasado desde **noviembre de 2025**.

Es poco dinero todavía. Justo por eso conviene resolverlo ahora y no en enero.

> ⚠️ El "Impuesto IVA de ES (21,00 %): −0,20 €" que aparece en el pedido es el
> IVA de la **comisión de WooPayments**, no del curso. No cuenta.

---

# 🟡 Hallazgo 4 — El sitio está en huso horario de Buenos Aires

La suscripción `#2088` remata con: `Zona horaria: America/Buenos_Aires`.

Un WordPress de una academia española configurado en huso argentino: **5 horas de
desfase con Madrid**. Es la razón de que el siguiente pago se muestre como
"30-sep 22:00" cuando en España ya es 1-oct.

**Para facturación importa de verdad.** La *fecha de expedición* es campo
obligatorio (art. 6 del RD 1619/2012) y el cliente ha elegido **serie mensual**
(`26/08-0001`). Una venta del 1 de octubre a las 02:00 de Madrid, el sitio la
fecha el **30 de septiembre** → esa factura entraría en la serie de septiembre
estando ya en octubre. En cada cambio de mes, la numeración se puede romper.

Es un argumento más —y muy concreto— para la **serie anual** que ya se les
propone en `facturacion_plan_2026-08-10.md`.

---

# 🟢 Hallazgo 5 — La 133ª está vendiendo

**16 pedidos en 11 días y acelerando**: 4 el 24-ago, 3 el 25. Antes de eso,
**cero pedidos desde el 19-jun**. Con atribución de origen: `Orgánico: Google`,
`Fuente: Google`, `Directo`.

Cronología, sin sobrevender: el botón principal de la home dejó de dar **404** el
**10-ago**; el primer pedido de la 133ª es del **14-ago**. El producto ya era
comprable antes de arreglar el botón, así que **esto no prueba causalidad** —
pero es el dato a poner sobre la mesa, y la atribución de WooCommerce permite
comprobarlo en serio.

## Bonus: la facturación automática tiene todo lo que necesita

El pedido `#2087` captura **DNI** (`billing_dni: 21027747C`), segundo apellido
(`billing_last_name_2`), dirección completa y teléfono. Son todos los campos que
exige el art. 6 del RD 1619/2012 **sin pedirle nada más al alumno**. El
generador de facturas puede emitir con esto tal cual.

---

# 🟡 Hallazgo 6 — 30 alumnos "ENTREVISTA 2026" sin rastro en la tienda

De las 35 filas del informe de acceso sin pago:

| Situación | Cuántos |
|---|---|
| Becado (autorizado) | 2 |
| `info@academiavalenz.com` (cuenta de la propia academia) | 1 |
| Otros sueltos | 2 |
| **Grupo `ENTREVISTA 2026 PROMOCIÓN 132GC`** | **30** |

Los 30 tienen acceso activo, **se conectan esta misma semana** (varios el mismo
25-ago) y **cero registro en WooCommerce**: ni pedido, ni usuario, ni suscripción.
No estaban en el censo de julio, que detectó solo 7 casos. Son **altas manuales
posteriores al examen** del 10-11 de julio.

**No hay riesgo técnico**: la conciliación solo recorre suscripciones de Woo, así
que nunca los toca, y el informe es de solo lectura (*"No se corta el acceso; es
una lista para revisar a mano"*).

**Decidido**: se lleva a la reunión como **pregunta neutra** —*"¿el curso de
entrevista se cobra aparte o va incluido?"*— y no como hallazgo de fuga de
ingresos. Si se cobra aparte, la conversación se abre sola.

## Los becados salen 2 de 7, y está bien

El check del runbook estaba mal formulado, no el plugin. La constante
`OMNIA_EVO_BECADOS_EMAILS` tiene los 7, y el etiquetado funciona: los 2 que
aparecen salen correctamente como *"Becado (autorizado)"*.

Los otros 5 no aparecen porque `has_woo_footprint()` los descarta **antes** de
llegar al informe: si tienen usuario WP o algún pedido, no son "acceso sin pago"
por definición. El informe solo lista a quien no tiene ningún rastro.

**Sí falta una cosa**: `info@academiavalenz.com` sale como *"Desconocido —
revisar"*. Es la cuenta de la propia academia y debe estar en la lista de
autorizados.

---

# Qué hay que arreglar antes del modo real

## Parche v0.8.1 — dos cambios, con pasada por staging

**1. Veredicto por estado de la suscripción, no por fecha del último pago.**
Si `next_payment` está en el futuro o el alumno sigue en periodo de prueba →
activo. La ventana de 38 días se queda como red de seguridad. Resuelve el
hallazgo 1 de raíz: hoy y para cada alta futura.

**2. Sembrar veredictos.** Persistir `omnia_evo_verdicts` también en DRY-RUN,
con un botón *"Olvidar veredictos"* por si alguna vez se quiere forzar un aviso
completo. Resuelve el hallazgo 2.

## Mitigación parcial que ya existe (cero código)

`OMNIA_EVO_DRYRUN = false` dejando **`OMNIA_GHL_DRYRUN = true`**: el campus corta
de verdad y el CRM sigue simulado. Está confirmado en el código —`ghl_dryrun()`
es independiente— y sirve para el hallazgo 2.

⚠️ **Pero no basta para el hallazgo 1**: en ese modo el corte del campus es real,
así que los 8-13 alumnos se quedarían igual sin acceso en septiembre.

> **Hasta que esté el parche, lo seguro es seguir en DRY-RUN.**

## Qué queda bloqueado del 1-sep

| Bloqueo | Depende de |
|---|---|
| 🔴 El corte de septiembre (hallazgo 1) | **Nosotros** — parche v0.8.1 |
| 🔴 Los 55 avisos de golpe (hallazgo 2) | **Nosotros** — parche v0.8.1 |
| 🟡 Qué hacer con las 39 suscripciones pausadas | Paco |
| 🟡 Confirmación de los becados (por defecto, opción A) | Paco |

---

## Cómo se reprodujo todo esto

Sin acceso a wp-admin de producción (la IP de este entorno está bloqueada), la
evidencia salió de: los dos informes del plugin pegados por el equipo, la lista
de pedidos de WooCommerce, la pantalla de datos del producto, el pedido `#2087`,
el panel "Programar" de la suscripción `#2088`, y una lectura por HTTP de la
ficha pública del producto (HTTP 200) que confirmó que el bloque de compra **no
muestra precio ni condiciones de renovación**: cero `class="price"`, cero
`woocommerce-Price-amount`. El "80€ 48€*/mes" de la página es texto de Elementor,
no el precio de WooCommerce.
