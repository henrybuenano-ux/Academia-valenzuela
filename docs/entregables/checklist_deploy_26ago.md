# Checklist de deploy — plugin v0.8.0 a producción · para el 26-ago

> Versión condensada del runbook, para tener abierta mientras se ejecuta.
> Tiempo: 30-45 min. **Todo en DRY-RUN: el plugin no modifica nada.**
> Runbook completo: `runbook_deploy_produccion.md`.

## ⛔ Lo primero: lo que NO es esto

**No se toca el botón "push" de WP Staging.** Eso machacaría la web viva con
la copia de julio y se llevaría por delante pedidos y suscripciones. Esto es
subir un plugin, nada más.

## Antes de empezar (5 min)

- [ ] **Backup / punto de restauración** del sitio (hosting o WP Staging Pro).
- [ ] Tener a mano el ZIP: `dist/evocampus-subscription-sync-v0.8.0.zip`.
- [ ] Abrir el `00-omnia-evo-config.php` **del staging** — hace falta copiarlo
      tal cual (lleva la key de EvoCampus y el PIT de GHL; no está en el repo
      por seguridad).

## Los pasos

**1. Entrar** → `https://academiavalenz.com/av-login` (credenciales de siempre).

**2. Borrar la copia vieja** → Plugins: existe una **v0.3.1 INACTIVA**
("EvoCampus ↔ WooCommerce Subscriptions Sync (Omnia)"). **Eliminarla, no
activarla.**

**3. Subir la v0.8.0** → Plugins → Añadir nuevo → Subir plugin → el ZIP.
**NO activar todavía.**

**4. Subir el mini-plugin de config** (copiado del staging) como carpeta
`00-omnia-evo-config/`. Verificar antes de activar:
- [ ] `OMNIA_EVO_DRYRUN` = **true** ← imprescindible
- [ ] `OMNIA_EVO_GRACE_DAYS` = **38**
- [ ] `OMNIA_EVO_BECADOS_EMAILS` = los 7 becados
- [ ] `OMNIA_GHL_DRYRUN` = **true** las primeras 24 h

**Activar primero el config, después el plugin principal.**

**5. Verificar en el momento (5 min)** → WooCommerce → EvoCampus Sync:
- [ ] La página carga y dice **"Modo: DRY-RUN … Ventana de pago: 38 días"**
- [ ] "Ejecutar conciliación ahora" → termina sin errores y lista el censo
- [ ] "Generar informe de acceso sin pago" → los 7 becados salen como
      **"Becado (autorizado)"**
- [ ] Sin errores PHP en pantalla ni en el log (fuente `omnia-evocampus-sync`)

**6. Avisar a Claude** → verifico por HTTP y reviso el log al día siguiente.

## Rollback

Desactivar el plugin principal. Todo vuelve al instante: no altera datos de
WooCommerce y en DRY-RUN ni siquiera llama a EvoCampus.

## ⚠️ Para la reunión: qué NO prometer

**El paso a modo real del 1-sep no se puede confirmar todavía.** Le faltan dos
prerequisitos que no dependen de nosotros:
1. La decisión sobre las **39 suscripciones pausadas** — sin ella, el primer
   censo real arranca sucio.
2. La confirmación de **Paco sobre los becados** (por defecto, opción A).

Lo que sí se puede decir: el plugin queda desplegado y observándose esta
semana, y el modo real se activa en cuanto se cierren esos dos puntos.

## Contexto útil para la reunión

- El embudo lleva **12 leads reales del bot** desde el 11-ago (0 antes).
- **Ninguno ha sido contactado todavía** — si el cliente abre el CRM, lo verá.
- Web arreglada (el botón principal daba 404) y bot captando también desde
  la web principal.
