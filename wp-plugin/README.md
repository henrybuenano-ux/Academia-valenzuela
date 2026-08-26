# Omnia — EvoCampus Subscription Sync (mini-plugin WordPress)

Corta y reactiva el acceso al campus EvoCampus según el estado de la
suscripción en WooCommerce Subscriptions. Conciliación diaria como red de
seguridad. Espejo opcional de eventos hacia GoHighLevel (tags + dunning).

## Instalación (staging primero, SIEMPRE)

1. Comprimir la carpeta `evocampus-subscription-sync/` en un ZIP
   (o usar el ZIP de la release).
2. wp-admin → Plugins → Añadir nuevo → Subir plugin → activar.
3. En `wp-config.php`, encima de `/* That's all, stop editing! */`:

```php
define( 'OMNIA_EVO_CLIENTID', '83208' );
define( 'OMNIA_EVO_KEY',      '<key del panel EvoCampus>' );
define( 'OMNIA_EVO_DRYRUN',   true );  // ¡empezar SIEMPRE en true!

// ── Espejo CRM · OPCIÓN A (recomendada, v0.6.0): API pública de GHL ──
// El plugin hace upsert del contacto + tags + oportunidad de recobro por sí
// mismo. NO depende del Mapping Reference del webhook (que quedó sin
// configurar → el webhook NO crea el contacto; ver docs/entregables/
// estado_workflows_ghl_2026-07-10.md). Requiere un PIT de la sub-cuenta
// (Settings → Private Integrations, con scopes contacts + opportunities).
define( 'OMNIA_GHL_PIT',         '<pit de la sub-cuenta Academia Valenz>' );
define( 'OMNIA_GHL_LOCATION_ID', 'hBvP7lemQSMibPYcJPEP' );

// ── Espejo CRM · OPCIÓN B (fallback): Inbound Webhook ──
// Solo se usa si NO hay OMNIA_GHL_PIT. Requiere configurar el Mapping
// Reference en la UI de GHL para que el webhook cree el contacto.
define( 'OMNIA_GHL_WEBHOOK_URL_BAJA', 'https://services.leadconnectorhq.com/hooks/hBvP7lemQSMibPYcJPEP/webhook-trigger/vJkOtiVDBtx0TChtWl9U' );
define( 'OMNIA_GHL_WEBHOOK_URL_REACTIVACION', 'https://services.leadconnectorhq.com/hooks/hBvP7lemQSMibPYcJPEP/webhook-trigger/DHGCTUxHMdwjVIMKbSOt' );
```

> ✅ La OPCIÓN A (v0.6.1) está **implementada y validada en staging** con un
> PIT real (upsert + tags + oportunidad, ida y vuelta baja↔reactivación). Para
> validar el espejo en real dejando EvoCampus en simulación, usar
> `OMNIA_GHL_DRYRUN=false` (desacopla ambos DRY-RUN). En staging se deja el
> espejo heredando el DRY-RUN de EvoCampus para no escribir en el CRM real.

4. Ver logs en WooCommerce → Estado → Registros → fuente `omnia-evocampus-sync`.

## Plan de validación en staging (checklist)

- [ ] Activar plugin con DRY-RUN=true y credenciales reales.
- [ ] Forzar una suscripción de prueba a `on-hold` → el log debe mostrar el
      evento, las matrículas encontradas y el `updateEnrollment` que HARÍA.
- [ ] Confirmar contra la doc oficial (PDF) los nombres exactos de:
      campo del token en `/v1/token`, filtro por email en `getEnrollments`,
      y campos `enrollmentid`/`status` de las respuestas. Ajustar el código
      donde hay comentarios "confirmar contra la doc".
- [ ] Lanzar la conciliación manualmente (`wp cron event run
      omnia_evo_daily_reconciliation` con WP-CLI) y revisar desajustes.
- [x] Decisión de negocio (A6, Paco 14-jul-2026): **7 días de cortesía**.
      Aplicada en v0.8.0: `on-hold` ya NO corta (solo avisa a GHL);
      `GRACE_DAYS = 38` (ciclo mensual 31 + 7 de cortesía).
- [x] **v0.8.1** — la cortesía se aplica ahora sobre la fecha de cobro que
      declara cada suscripción (`OMNIA_EVO_COURTESY_DAYS = 7`), no sobre una
      ventana fija. `GRACE_DAYS` queda como regla de reserva. Ver abajo.
- [ ] **Ejecutar la conciliación con v0.8.1 y comparar con el pase anterior:**
      ningún alumno debe cambiar de veredicto salvo los que estén en prueba
      gratuita con cobros sincronizados.
- [ ] **Pulsar «Sembrar veredictos (sin avisar a GHL)»** y confirmar en el log
      que la línea final dice `0 avisos enviados`. Sin esto, el primer pase
      real notifica a GoHighLevel el estado de TODOS los alumnos de golpe.
- [ ] Pasar `OMNIA_EVO_DRYRUN` a `false` en staging → probar baja y
      reactivación reales con UN alumno de prueba → verificar en el campus.
- [ ] Desplegar a producción (de nuevo con DRY-RUN=true 24–48 h, revisar
      logs y conciliación, y entonces activar en real).

## La ventana de pago (v0.8.1)

**El problema.** Hasta v0.8.0 un alumno estaba al corriente si su último pedido
pagado tenía como mucho **38 días** (31 del ciclo + 7 de cortesía). Esa constante
asume que se cobra una vez al mes. El producto de la 133ª tiene **prueba gratuita
de 1 mes + cobros sincronizados al día 1**, y WooCommerce aplica la prueba y
*después* salta al siguiente día 1: quien compre el 2 de septiembre no paga nada
hasta el **1 de noviembre**. Sesenta días. La regla de 38 lo habría dado de baja
y, en modo real, le habría cortado el acceso al campus.

**El arreglo.** La ventana se deriva del calendario de cada suscripción:

1. **Si la suscripción declara fecha de próximo cobro**, esa fecha manda: al
   corriente mientras no se pase de ella más `OMNIA_EVO_COURTESY_DAYS` (7).
2. **Si no la declara** (impago, cancelada, expirada), se mantiene la regla
   histórica: al corriente si el último pedido pagado tiene ≤ `GRACE_DAYS`.

**Lo que NO se cambió, y es deliberado:** el veredicto sigue sin mirar el
*estado* de la suscripción. En esta tienda el estado no refleja el pago
(hallazgo 10-jul-2026), y basarse en él daría de baja en bloque a alumnos que
están pagando.

### Qué veredictos cambian

| Caso | v0.8.0 | v0.8.1 |
|---|---|---|
| En prueba gratuita con cobro sincronizado lejano | baja ❌ | **activo** ✅ |
| Cobro previsto vencido hace 8 días | activo | **baja** |
| Sin haber pagado nunca, pero con cobro previsto futuro | baja | **activo** |
| Todo lo demás | *sin cambios* | *sin cambios* |

La segunda fila es un endurecimiento menor y buscado: antes la cortesía real
variaba entre 7 y 8 días según la longitud del mes; ahora son 7 exactos, que es
lo que dice la política A6.

La tercera cubre altas manuales y pruebas gratuitas sin cuota de entrada. Es
correcto —tienen derecho de acceso— pero conviene saberlo.

### Probar la lógica sin WordPress

```
php wp-plugin/tests/test-verdict.php
```

16 casos, incluida la comparación de qué veredictos cambian respecto a v0.8.0.
Devuelve código 1 si algo falla, así que sirve para CI.

## Comportamiento (v0.4+)

**Señal principal — conciliación diaria por PEDIDOS** (hallazgo 10-jul-2026:
en esta tienda el estado de la suscripción no refleja el pago):

| Situación del alumno | Acción EvoCampus |
|---|---|
| Último pedido PAGADO hace ≤ `OMNIA_EVO_GRACE_DAYS` (38 = ciclo 31 + 7 cortesía) | `status=0` (activo) |
| Último pedido pagado hace más de la ventana (o nunca) | `status=2` (baja) |

La conciliación corre a diario (cron 04:30) y loguea el censo completo
("email — último pago hace N días → veredicto"). El espejo a GHL solo se
notifica cuando el veredicto de un alumno CAMBIA. Prueba manual:
`/wp-admin/?omnia_evo_reconcile_now=1` (solo administradores).

**Señal de refuerzo — hooks de estado** (por si el flujo de estados se
corrige en el futuro):

| Evento WooCommerce Subscriptions | Acción EvoCampus |
|---|---|
| `on-hold` (impago) | **No corta** (v0.8.0, política A6): solo espeja a GHL (tag impago + oportunidad recobro → arranca el aviso). El corte llega por conciliación al agotar la ventana. |
| `cancelled` / `expired` | `updateEnrollment status=2` (baja inmediata) |
| `active` (reactivación) | `updateEnrollment status=0` (activa) |

Modelo: 1 suscripción = acceso a todo → se opera por email sobre todas las
matrículas. Para granularidad curso a curso, usar el mapeo Producto→Grupo
del conector oficial de Evolmind.

## Informe de "acceso sin pago" (v0.7.0)

Además de la conciliación, el plugin genera un **informe mensual de solo
lectura** que lista los alumnos **activos en EvoCampus que NO tienen ningún
registro en la tienda** (ni usuario WP, ni pedido, ni suscripción) — es decir,
matriculaciones **manuales** hechas directamente en el campus.

- **No corta el acceso a nadie**: solo informa. Esos alumnos no tienen pago que
  vigilar y su acceso caduca por la fecha fin de EvoCampus.
- Sirve para que la academia los revise a mano (becas, cortesía, pagos por
  transferencia, o posible fuga de ingresos).
- Se ve en **WooCommerce → EvoCampus Sync** (tabla "Acceso sin pago") y hay un
  botón "Generar informe de acceso sin pago" para lanzarlo bajo demanda.
- Corre solo una vez al mes por wp-cron. Recomendación de negocio: matricular
  siempre por la tienda web para que el sistema automático cubra al 100 %.

## Seguridad

- Las credenciales viven SOLO en `wp-config.php` (nunca en la base de datos
  ni en este repo).
- `OMNIA_EVO_DRYRUN` es seguro por defecto: si la constante no existe, el
  plugin NO escribe nada en EvoCampus.
