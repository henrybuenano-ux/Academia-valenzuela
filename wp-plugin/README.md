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
- [ ] Decisión de negocio: ¿cortar en `on-hold` o dar días de gracia?
      (si hay gracia: quitar `on-hold` de la lista de estados de corte).
- [ ] Pasar `OMNIA_EVO_DRYRUN` a `false` en staging → probar baja y
      reactivación reales con UN alumno de prueba → verificar en el campus.
- [ ] Desplegar a producción (de nuevo con DRY-RUN=true 24–48 h, revisar
      logs y conciliación, y entonces activar en real).

## Comportamiento (v0.4+)

**Señal principal — conciliación diaria por PEDIDOS** (hallazgo 10-jul-2026:
en esta tienda el estado de la suscripción no refleja el pago):

| Situación del alumno | Acción EvoCampus |
|---|---|
| Último pedido PAGADO hace ≤ `OMNIA_EVO_GRACE_DAYS` (35) | `status=0` (activo) |
| Último pedido pagado hace más de la ventana (o nunca) | `status=2` (baja) |

La conciliación corre a diario (cron 04:30) y loguea el censo completo
("email — último pago hace N días → veredicto"). El espejo a GHL solo se
notifica cuando el veredicto de un alumno CAMBIA. Prueba manual:
`/wp-admin/?omnia_evo_reconcile_now=1` (solo administradores).

**Señal de refuerzo — hooks de estado** (por si el flujo de estados se
corrige en el futuro):

| Evento WooCommerce Subscriptions | Acción EvoCampus |
|---|---|
| `on-hold` / `cancelled` / `expired` | `updateEnrollment status=2` (baja) |
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
