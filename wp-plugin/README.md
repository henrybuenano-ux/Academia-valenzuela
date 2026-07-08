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
// Opcional — espejo CRM (Inbound Webhook de un workflow GHL):
// define( 'OMNIA_GHL_WEBHOOK_URL', 'https://services.leadconnectorhq.com/hooks/...' );
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

## Comportamiento

| Evento WooCommerce Subscriptions | Acción EvoCampus |
|---|---|
| `on-hold` / `cancelled` / `expired` | `updateEnrollment status=2` (baja) en TODAS las matrículas del email |
| `active` (reactivación) | `updateEnrollment status=0` (activa) |
| Cron diario 04:30 | Concilia estado Woo vs matrícula y corrige desajustes |

Modelo: 1 suscripción = acceso a todo → se opera por email sobre todas las
matrículas. Para granularidad curso a curso, usar el mapeo Producto→Grupo
del conector oficial de Evolmind.

## Seguridad

- Las credenciales viven SOLO en `wp-config.php` (nunca en la base de datos
  ni en este repo).
- `OMNIA_EVO_DRYRUN` es seguro por defecto: si la constante no existe, el
  plugin NO escribe nada en EvoCampus.
