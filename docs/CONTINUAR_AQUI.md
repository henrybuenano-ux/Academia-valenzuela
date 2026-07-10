# CONTINUAR AQUÍ — estado al 10-jul-2026 (fin de sesión 1)

> **Act. sesión 2 (10-jul):** red verificada — academiavalenz.com/staging (200 OK)
> y api.evolcampus.com (alcanzable) SÍ están permitidos; **fathom.video NO**
> (bloqueado por la política de red y también vía WebFetch → 403).
> ✅ Punto 4 COMPLETADO por plan B: el equipo pegó las 2 transcripciones en el
> chat; hallazgos extraídos en `docs/LLAMADAS_FATHOM_HALLAZGOS.md` (incluye
> hipótesis para P1 y P2 a contrastar con el censo, y aviso: las campañas de
> septiembre se preparan en julio–agosto según el propio cliente).
> Los puntos 1–3 siguen a la espera de credenciales (sección siguiente).

> Para la nueva sesión de Claude Code: lee este archivo + `PLAN_MAESTRO.md`
> (plan por bloques con registro de progreso) y retoma desde "Siguiente paso".

## Estado en una línea
Fase 1 validada en DRY-RUN en staging; falta ejecutar el CENSO completo de la
conciliación (la página con botón de la v0.5.1 no llegó a probarse bien) y
ahora la red del entorno ya permite academiavalenz.com y api.evolcampus.com
→ Claude puede operar directamente con Playwright/curl.

## Qué hay construido
- `wp-plugin/evocampus-subscription-sync/` v0.5.1 — plugin WP: baja/reactivación
  EvoCampus por API + conciliación diaria POR PEDIDOS (GRACE_DAYS=35, hallazgo:
  los estados de suscripción no reflejan el pago) + espejo GHL + página admin
  "WooCommerce → EvoCampus Sync" con botón y visor de log. DRY-RUN por defecto.
- `wp-plugin/00-omnia-evo-config.php.example` — plantilla del mini-plugin de
  constantes (el real, con la key, está instalado en el staging).
- GHL sub-cuenta "Academia Valenz" (hBvP7lemQSMibPYcJPEP): 7 custom fields,
  5 tags, 2 pipelines (Captación, Recobro impagos), 2 workflows espejo EN
  BORRADOR con Inbound Webhooks (URLs en `wp-plugin/README.md`).
- Staging: https://academiavalenz.com/staging (WP Staging; login = mismas
  credenciales que producción; los plugins ya están instalados allí).

## Credenciales que hay que volver a pegar en la nueva sesión
(El .env local no sobrevive entre sesiones; pedirlas al equipo por chat)
- GHL: token PIT de la sub-cuenta + Firebase refresh token de agencia
  → guardarlas en gohighlevel-cli/.env (gitignorado) como GHL_API_KEY y
  GHL_FIREBASE_REFRESH_TOKEN; instalar el CLI con gohighlevel-cli/install.sh.
- EvoCampus: ClientId 83208 + Key (panel EvoCampus → Config → Complementos → API).
- wp-admin del staging: pedir usuario admin (sugerido: crear usuario temporal
  `omnia-bot` rol Administrador solo en staging).

## Siguiente paso (donde se quedó)
1. Con Playwright (Chromium en /opt/pw-browsers/chromium): login en
   https://academiavalenz.com/staging/wp-admin → verificar que SOLO hay una
   copia del plugin (v0.5.1) activa → WooCommerce → EvoCampus Sync →
   "Ejecutar conciliación ahora" → capturar el censo del log.
2. Validar también la API EvoCampus directo por curl (POST /api/v1/token con
   clientid+key form-encoded; getEnrollments email=...&page=1&regs_per_page=100).
3. Analizar el censo (morosidad real de la 132ª promoción) → preparar demo Paco.
4. Leer con Playwright las transcripciones de las 2 llamadas de Fathom
   (pedido expreso del usuario; requiere que la red permita fathom.video):
   - Discovery (10-jun): https://fathom.video/share/pvmoGTnqnHKZuMTooTpwhKUXieK7ofwh
   - Impromptu equipo (11-jun): https://fathom.video/share/SPVd4y6mK6Zz21pJRaKhxf5i_YNWkch7
   Si Fathom exige login o no renderiza el texto, plan B: el equipo exporta
   la transcripción desde Fathom y la sube al chat.

## Pendientes de negocio (Bloque A del plan)
- P1 ¿Cómo pagan los ~200 alumnos que no están en Woo? (solo hay 60 suscripciones)
- P2 ¿Por qué no hay pedidos de julio? ¿Quién lanza los cobros mensuales?
- A3 Solicitud WhatsApp a Meta (Fase 3, lead time).
- A4 Pregunta VeriFactu a la gestoría (bloquea Fase 2).
- A6 Política de corte con Paco (¿gracia?; GRACE_DAYS del plugin).
- Oliver: Mapping Reference en los 2 triggers GHL (ya hay peticiones reales
  recibidas) + guardar workflows + paso "Create Opportunity" en el de Baja.

## Reglas que siguen vigentes
- NUNCA desactivar DRY-RUN sin pasar el checklist de wp-plugin/README.md.
- Producción no se toca hasta cerrar P1/P2 y A6.
- Secretos solo en .env/wp-config (gitignorados) — nunca commiteados.
