# P2 — Causa raíz de "no hay cobros de julio" (10-jul-2026, sesión 3)

## Conclusión en una línea
**El 24-jun-2026 a las 14:05, el usuario `DeVOmibu` cambió en lote las
suscripciones de "Activa" a "En espera" EN PRODUCCIÓN.** WooCommerce
Subscriptions no procesa renovaciones de suscripciones en espera, así que
desde ese día no se genera ningún cobro: la facturación recurrente lleva
congelada ~2,5 semanas (≈ 39 suscripciones × ~80 € ≈ **3.100 €/mes parados**).

## Evidencia (producción, SOLO LECTURA — no se modificó nada)
Verificado con el usuario DevOmibu vía wp-admin de producción (login oculto
con WPS Hide Login, slug `av-login`):

1. **Contadores de suscripciones idénticos al clon**: 60 en total ·
   39 "En espera" · 21 "Canceladas" · **0 Activas**. (Descarta que el clon
   estuviera desactualizado.)
2. **Último pedido de la tienda: 19-jun-2026** (286 pedidos, 276 procesando,
   9 fallidos, 1 cancelado). Ni un pedido de renovación desde entonces.
3. **Notas de suscripciones** (muestra):
   - #1815: «El estado de la suscripción cambió por **edición en lote**:
     Estado cambió de Activa a En espera. **24 de junio de 2026 a las 14:05
     por DeVOmibu**». Historial previo sano: renovó normal el 11-jun
     (pedido #1987), pago completado; próximo pago programado 11-jul.
   - #1818: misma nota de edición en lote 24-jun 14:05 por DeVOmibu.
     Renovación previa normal el 9-jun.
   - #1803 (contraste): cancelación genuina del propio alumno en marzo —
     el patrón "en lote" NO aparece en las canceladas antiguas.
4. **Los ajustes de WCS son correctos** (descartado fallo de configuración):
   pagos automáticos activados (`turn_off_automatic_payments = no`),
   renovación manual no aceptada (`accept_manual_renewals = no`),
   reintentos de cobro desactivados (`enable_retry = no`).

## Interpretación
- No es un fallo técnico de Redsys/WooPayments ni del cron: fue una **acción
  manual deliberada o accidental** con la cuenta DevOmibu.
- Encaja temporalmente con la hipótesis fin-de-curso de la sesión 2: todas
  las matrículas de la 132ª promoción terminan el 10-11-jul (examen). Es
  plausible que alguien pausara los cobros a propósito para **no cobrar
  julio a alumnos cuyo curso termina** — una decisión de negocio razonable…
  si fue consciente. También es la fecha aproximada de creación del staging:
  cabe la posibilidad de que se hiciera **creyendo estar en el clon**.
- Consecuencia adicional: como el plugin concilia POR PEDIDOS, si esto no se
  aclara, todo el censo irá cayendo a "baja" por ventana de pago vencida
  aunque el alumno jamás decidiera irse.

## Qué hay que hacer (equipo — no lo hace Claude)
1. **Preguntar a Henry/equipo**: ¿quién hizo la edición en lote del 24-jun y
   con qué intención? (¿pausa de verano deliberada? ¿confusión con staging?)
2. Si fue **deliberada** (no cobrar julio por fin de curso): perfecto —
   documentarlo como política de fin de promoción y definir el calendario de
   reactivación para la 133ª (otoño). El plugin debe entrar en real CON la
   nueva promoción.
3. Si fue **accidental**: planificar la reactivación CONTROLADA:
   - Reactivar UNA suscripción primero y observar: al pasar a "Activa", WCS
     puede intentar el cobro de la renovación vencida inmediatamente.
   - Decidir ANTES si se quiere cobrar el mes(es) atrasado(s) o mover la
     fecha de próximo pago (con el examen ya pasado, cobrar julio entero a
     todos puede generar quejas justificadas).
   - Solo después, reactivar el resto en lote.
4. En cualquiera de los dos casos: la respuesta define A6 (política de corte)
   y la fecha real de B6 (paso a producción del plugin).

## Nota de método
Todo el acceso a producción fue de lectura (GETs de listados, una ficha de
suscripción y options.php). No se ejecutó la conciliación ni ningún cambio
fuera del staging.
