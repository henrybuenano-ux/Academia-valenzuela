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
// Espejo CRM — workflows "Espejo EvoCampus" en la sub-cuenta Academia Valenz:
define( 'OMNIA_GHL_WEBHOOK_URL_BAJA', 'https://services.leadconnectorhq.com/hooks/hBvP7lemQSMibPYcJPEP/webhook-trigger/vJkOtiVDBtx0TChtWl9U' );
define( 'OMNIA_GHL_WEBHOOK_URL_REACTIVACION', 'https://services.leadconnectorhq.com/hooks/hBvP7lemQSMibPYcJPEP/webhook-trigger/DHGCTUxHMdwjVIMKbSOt' );
```

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

## Seguridad

- Las credenciales viven SOLO en `wp-config.php` (nunca en la base de datos
  ni en este repo).
- `OMNIA_EVO_DRYRUN` es seguro por defecto: si la constante no existe, el
  plugin NO escribe nada en EvoCampus.
