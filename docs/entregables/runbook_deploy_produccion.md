# Runbook — Deploy del plugin v0.8.0 en PRODUCCIÓN (tarea del 24-ago, Henry)

> Objetivo: dejar el plugin corriendo en producción con DRY-RUN=true 24-48 h,
> revisar, y pasar a real el 1-sep con la 133ª. Tiempo estimado: 30-45 min
> + observación. Claude puede ejecutar los pasos 2-6 si se le da el OK
> (misma técnica validada en staging); este runbook permite hacerlo a mano.

## Pre-requisitos (verificar ANTES de empezar)
- [ ] Decisión tomada sobre las 39 suscripciones pausadas (tarea del 24-jul).
      Si se cancelan, hacerlo antes del deploy: el primer censo real saldrá limpio.
- [ ] Respuesta de Paco sobre becados registrada (opción A por defecto).
- [ ] Backup/punto de restauración del sitio (el hosting o WP Staging Pro).

## Paso 1 — Acceso
wp-admin de producción: https://academiavalenz.com/av-login (login oculto
con WPS Hide Login; las credenciales de siempre).

## Paso 2 — Limpiar la copia obsoleta
En Plugins hay una **v0.3.1 del plugin INACTIVA** ("EvoCampus ↔ WooCommerce
Subscriptions Sync (Omnia)"). **Borrarla** (no activarla): Plugins → Eliminar.

## Paso 3 — Instalar la v0.8.0
Subir el ZIP del plugin desde el repo (carpeta
`wp-plugin/evocampus-subscription-sync/`, comprimida) o copiar por FTP.
NO ACTIVAR TODAVÍA.

## Paso 4 — Instalar el mini-plugin de configuración
Copiar el `00-omnia-evo-config.php` DEL STAGING (tiene la key de EvoCampus,
el PIT de GHL y ya incluye las constantes nuevas) a producción como plugin
propio (carpeta `00-omnia-evo-config/`). Verificar que contiene:
- `OMNIA_EVO_DRYRUN` → **true** (¡imprescindible!)
- `OMNIA_EVO_GRACE_DAYS` → 38
- `OMNIA_EVO_BECADOS_EMAILS` → los 7 becados
- `OMNIA_GHL_DRYRUN` → decidir: true para simular también el CRM, o false
  para que el espejo GHL trabaje en real desde el DRY-RUN (recomendado:
  **true** las primeras 24 h, luego false).
Activar primero el config, después el plugin principal.

## Paso 5 — Verificación inmediata (5 min)
- [ ] WooCommerce → EvoCampus Sync: la página carga y muestra
      "Modo: DRY-RUN … Ventana de pago: 38 días".
- [ ] Botón "Ejecutar conciliación ahora" → termina sin errores y el log
      lista el censo ("X de X alumnos evaluados").
- [ ] Botón "Generar informe de acceso sin pago" → los 7 becados salen como
      "Becado (autorizado)".
- [ ] Ningún error PHP en pantalla ni en el log de WooCommerce
      (fuente `omnia-evocampus-sync`).

## Paso 6 — Observación 24-48 h
- [ ] La conciliación automática corre sola cada día (ver "Próxima
      conciliación" en la página y el log del día siguiente).
- [ ] Los veredictos son coherentes con la realidad (con la 132ª terminada
      y el campus auto-vaciado, lo esperable es censo pequeño o vacío).
- [ ] Sin errores en logs 2 días seguidos.

## Paso 7 — Paso a REAL (1-sep, con la 133ª ya matriculándose)
- [ ] Cambiar `OMNIA_EVO_DRYRUN` a **false** (y `OMNIA_GHL_DRYRUN` a false).
- [ ] Prueba controlada con UN alumno real/de prueba: forzar su suscripción
      a impago → verificar tag GHL sin corte (cortesía) → dejar agotar la
      ventana o simularla bajando GRACE_DAYS temporalmente → verificar baja
      en EvoCampus → pagar → verificar reactivación. Documentar con capturas.
- [ ] Activar el workflow de dunning en GHL (ya con canal de email operativo).

## Rollback
Cualquier problema: desactivar el plugin principal (el config puede quedarse).
Todo vuelve al estado anterior en el acto — el plugin no altera datos de Woo,
solo llama a la API de EvoCampus (y en DRY-RUN, ni eso).
