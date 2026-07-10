# CONTINUAR AQUÍ — estado al 10-jul-2026 (fin de sesión 1; verificación sesión 2)

> Para la nueva sesión de Claude Code: lee este archivo + `PLAN_MAESTRO.md`
> (plan por bloques con registro de progreso) y retoma desde "Siguiente paso".

## Sesión 2 (10-jul, tarde) — CENSO EJECUTADO ✅
- ✅ Red confirmada (A2 operativa) y wp-admin del staging accesible con el
  usuario DevOmibu (credenciales por chat; NO commiteadas).
- ✅ Diagnóstico del "plugin muerto" de la sesión 1: en staging una inclusión
  temprana ajena define la clase sin arrancarla y la carga normal chocaba con
  el guard → sin cron, sin menú, sin avisos (causa raíz del incluidor fantasma
  aún sin identificar; active_plugins/mu-plugins/wp-config limpios).
- ✅ Plugin **v0.5.2** desplegado en staging vía editor de plugins:
  1) guard con "arranque de rescate" (si la clase existe sin iniciar → init),
  2) fix fatal `self::CRON_HOOK` sin definir (la página admin daba 500 —
     por esto "no llegó a probarse" en sesión 1),
  3) fix paginación de la recolección (`paged` ignorado → censo incompleto,
     49 de 59 alumnos).
- ✅ **CENSO COMPLETO ejecutado desde la página con botón (DRY-RUN)**:
  59 alumnos · 22 al corriente · 37 en baja de pago. Cruce con API EvoCampus:
  **16 impagados con acceso activo HOY**, de ellos 3 morosos reales
  (63/68/**144** días) usando el campus esta misma semana.
  → `docs/entregables/censo_conciliacion_2026-07-10.md`
- ✅ API EvoCampus validada por curl (token + getEnrollments; ClientId 83208,
  key en el mini-plugin de config del staging).
- ✅ Evidencia P2: no hay cobros de julio (13 alumnos "frontera" 36-40 días +
  activos en 33-35). Si nadie lanza las renovaciones, en 1-2 semanas todo el
  censo cae en baja. SIGUE BLOQUEANDO B6.
- ⚠️ GHL BLOQUEADO por infraestructura (no por credenciales): con el login de
  agencia (victor.molina@omibu.com, location hBvP7lemQSMibPYcJPEP) no se puede
  autenticar desde este entorno por DOS motivos independientes:
  (a) el login por API rechaza (`Invalid email or password` en el backend;
      Firebase `PASSWORD_LOGIN_DISABLED`) — GHL exige reCAPTCHA, así que el
      login headless NO funciona ni con credenciales correctas;
  (b) los dominios de la app (app.gohighlevel.com y el whitelabel
      accesocrm.omniainbusiness.com) están BLOQUEADOS por la política de red
      del entorno (proxy → 403 al CONNECT); no se puede conducir el navegador.
  → Para desbloquear el trabajo sobre los workflows espejo hace falta UNA de:
    1) el **Firebase refresh token** sacado con la extensión Chrome del CLI
       (gohighlevel-cli/README.md §Step 1) desde un navegador con sesión
       iniciada, pegado en el chat → guardar como GHL_FIREBASE_REFRESH_TOKEN
       (habilita la API interna: crear/editar/guardar workflows);
    2) o un **PIT** de la sub-cuenta Academia Valenz (solo API pública:
       lecturas, contactos, oportunidades — NO edita workflows).
  Material listo para cuando llegue el token: las 51 peticiones DRY-RUN del
  censo de hoy son ejemplos reales de payload para el Mapping Reference.
- CLI gohighlevel-cli instalado y funcionando (.venv + .env gitignorado).

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

## Siguiente paso (act. sesión 2)
~~1-3: censo + validación API + análisis~~ ✅ HECHOS (ver arriba y
`docs/entregables/censo_conciliacion_2026-07-10.md`). Ahora:
1. Llevar el censo a la reunión con Paco/Fran: los 3 morosos con acceso
   (63/68/144 días) venden la Fase 1 solos. Presionar P1 (dónde pagan los
   otros ~200) y P2 (quién lanza los cobros de julio) — ambos bloquean B6.
2. Con credenciales GHL de la sub-cuenta: completar lo de Oliver (Mapping
   Reference en los 2 triggers, guardar workflows, paso Create Opportunity
   en Baja) — las 51 peticiones DRY-RUN de hoy ya dan ejemplos de payload.
3. Cazar al "incluidor fantasma" del staging (pedir a Henry acceso SSH/FTP o
   al panel del hosting; grep -r de 'evocampus-subscription-sync' fuera de
   wp-content/plugins). La v0.5.2 lo neutraliza, pero mejor entenderlo antes
   de producción (B6).
4. Nota Playwright: Chromium no puede salir por el proxy del entorno
   (ERR_CONNECTION_RESET en el handshake TLS del MITM). Todo el trabajo
   wp-admin se hizo con requests/HTTP puro — funciona perfectamente; usarlo
   también en la próxima sesión.

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
