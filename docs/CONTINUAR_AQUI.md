# CONTINUAR AQUÍ — estado al 24-jul-2026 (fin de sesión 6)

> **EMPIEZA POR AQUÍ ⬇️ — lo de abajo es histórico de sesiones anteriores.**

---

# 🟢 SESIÓN 6 (24-jul-2026) — LS01 EN PRODUCCIÓN

## Estado en una línea
**El primer carril del embudo está VIVO**: landing publicada → formulario →
contacto etiquetado con atribución UTM → oportunidad en Captación → secuencia
de 4 emails. Validado end-to-end con datos reales. Todo lo demás está
construido en borrador esperando 3 cosas: el calendario (falta horario de
Paco), los remates de UI de Oliver, y el 1-sep para el modo real del plugin.

## Lo que se hizo hoy

### ✅ LS01 completo y funcionando en producción
- **Landing viva**: https://info.academiavalenz.com/landing (HTML en
  `docs/entregables/landing_133.html`, con el embed real del formulario).
- **Formulario** "Form Landing 133" (id `EIa3gz2I8ndWcPA2we6v`) creado por el
  equipo con Ask AI y depurado en 3 iteraciones.
- **Prueba end-to-end superada**: contacto creado + tag `lead-landing-133` +
  campo "Momento del lead" + los 3 UTM + oportunidad en Captación/Nuevo lead
  con fuente "Landing 133". (Contacto de prueba: germanborrello@gmail.com —
  decidir si se borra.)
- **LS01 y SP01 PUBLICADOS**.

### ✅ Dominios verificados
- **Email**: `mail.academiavalenz.com` con SPF + DKIM (`mx._domainkey`) +
  CNAME + MX de Mailgun. ⚠️ El SPF del dominio raíz (Google Workspace +
  MailChannels) quedó INTACTO — nunca tocarlo. DNS en el panel de Conversalia,
  que bloquea SPF por formulario TXT: hay que usar su botón "SPF" con el
  campo Hostname = `mail`.
- **Funnels**: `info.academiavalenz.com` → `sites.ludicrous.cloud`.

### ✅ Construido por API (7 workflows + infraestructura)
LS01, LS02, LS03, SP01, SP02, RP02, RP03 · 22 tags · 15 custom fields.
Builders reutilizables en `gohighlevel-cli/builders/av-*.py`.
Nomenclatura de casa (LS/SP/AP/RP) aplicada en GHL, ClickUp y el mapa.

### 📌 Aprendizajes técnicos (IMPORTANTES para la próxima sesión)
1. **Editar un workflow por API lo DESPUBLICA** sin avisar. Tras cualquier
   cambio: verificar `status` y republicar (`PUT` con `status: published`).
   Ojo el 1-sep con RP02.
2. **`workflow_goal` se guarda por API pero la UI NO lo dibuja** → se retiró.
   Las salidas (exits) se hacen A MANO con el patrón de casa:
   IF "tiene tag X" → End workflow.
3. **La API interna sí acepta triggers** de `form_submitted` y
   `customer_booked_appointment` (creados con éxito).
4. **No se puede crear formularios ni funnels por API** (probadas 8 rutas).
5. **Red del entorno**: solo `services/backend.leadconnectorhq.com` están
   permitidos. La UI (`app.gohighlevel.com`), el whitelabel
   (`api.omniainbusiness.com`) y la landing publicada estaban bloqueados —
   **el equipo ya los añadió a la política de red el 24-jul, así que en una
   SESIÓN NUEVA deberían funcionar** (los cambios no aplican a sesiones ya
   iniciadas). Verificarlo al empezar: `curl -I https://app.gohighlevel.com/`.
   Aun así, el login de GHL usa reCAPTCHA: no está garantizado que se pueda
   conducir la interfaz por navegador.

## ⏭️ SIGUIENTE PASO al abrir sesión nueva

1. **Pedir token de Firebase fresco** (extensión Chrome del CLI, 10 s) →
   guardarlo en `gohighlevel-cli/.env` (gitignorado) como
   `GHL_FIREBASE_REFRESH_TOKEN`. El PIT y el location ID van en el mismo .env.
2. **Verificar si la red ya permite la UI** (ver punto 5 de arriba). Si sí:
   intentar auditar visualmente los workflows y la landing.
3. **Pendiente de confirmar visualmente**: ¿se dibuja el nodo
   `internal_notification` en LS01 y LS02? Si no aparece (como pasó con el
   goal), quitarlo por API y añadirlo a mano.
4. **Remates de UI pendientes** (Oliver): salidas IF en SP01/LS03/RP02,
   mensaje de gracias del formulario en castellano, y despublicar los 2
   workflows LEGADO.
5. **Perseguir a Paco** — `docs/entregables/mensaje_paco_pendientes_2026-07-24.md`
   tiene el mensaje listo con las 4 preguntas: su email personal + horario de
   asesorías (desbloquea calendario → SP02 → cierra Setup), las 39
   suscripciones pausadas, y la gestoría.

## Estado de cada pieza (24-jul)
| Pieza | Estado |
|---|---|
| LS01 landing+form+workflow | ✅ VIVO y validado |
| SP01 nurturing | ✅ publicado (falta su salida IF) |
| SP02 asesoría agendada | 🟡 borrador (espera calendario) |
| LS03 re-enganche 39 ex-alumnos | 🟡 borrador (falta email B2 de Paco) |
| RP02/RP03 dunning | 🟡 borrador — **NO ACTIVAR HASTA 1-SEP** |
| LS02 bot | ⚪ 14 subtareas especificadas en ClickUp |
| Calendario Asesorías | 🔴 espera horario de Paco |
| Plugin v0.8.0 (AP02) | 🟡 staging DRY-RUN · prod 24-ago · real 1-sep |
| Facturación (Fase 2) | 🔴 espera gestoría (VeriFactu → 2027, sin urgencia legal) |
| WhatsApp (LS04) | 🔴 espera Meta |

---

# Histórico de sesiones anteriores

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
- ✅ GHL DESBLOQUEADO con el Firebase refresh token (API interna). Auditados
  los 2 workflows espejo → `docs/entregables/estado_workflows_ghl_2026-07-10.md`:
  Create Opportunity YA existe (Recobro impagos/Impago detectado), workflows
  publicados, PERO los triggers **no tienen Mapping Reference** → la sub-cuenta
  tiene **0 contactos** pese a 51+ disparos: el espejo está publicado pero
  INOPERATIVO (el webhook no crea el contacto). Downstream verificado OK
  (crear contacto+tag+oportunidad → 201). ✅ RESUELTO por vía A (validado):
  · Vía A (plugin → API pública, robusta): IMPLEMENTADA en plugin **v0.6.0**
    en plugin **v0.6.1**: upsert contacto + tags de estado + oportunidad de
    recobro. PIT instalado en el config del staging. VALIDADO extremo a extremo
    (baja → contacto+alumno-impago+oportunidad Recobro; reactivación →
    alumno-activo/recuperado; contacto de prueba borrado). OMNIA_GHL_DRYRUN
    desacopla el espejo del DRY-RUN de EvoCampus; en staging hereda DRY-RUN
    (no escribe en el CRM real). En producción, OMNIA_EVO_DRYRUN=false lo activa.
  · Vía B (mapeo en la UI de GHL) queda como alternativa innecesaria,
    documentada en `docs/entregables/instrucciones_mapping_ghl_oliver.md`.
- Nota: los id_token de GHL caducan ~1 h; usar el helper de scratchpad que
  reacuña por expiración (o el CLI). El PIT del entorno sigue siendo de otra
  location (403 en API pública contra Academia Valenz).

## Estado al cierre de la sesión 2 (10-jul, noche)
- Email a Paco ENVIADO (7 manuales + fecha 133ª + política de corte). ⏳ A la
  espera de respuesta — sus 3 respuestas desbloquean el paso a producción (B6).
- Plan mapeado a ClickUp (Omnia → Academia Valenzuela, 6 listas, 30 tareas,
  responsables asignados). La tarea del email ya está completada.
- Próximo paso al volver: si Paco respondió → aplicar política de corte en la
  config, deploy a producción con DRY-RUN=true (checklist wp-plugin/README.md)
  y agendar demo. Si no → seguir con lo no bloqueado (Fase 3: D1 canal email).

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
`docs/entregables/censo_conciliacion_2026-07-10.md`). ✅ Transcripciones
Fathom recibidas por chat y analizadas → `docs/LLAMADAS_FATHOM_HALLAZGOS.md`.
Ahora:
1. **P2 RESUELTO — actuar con el equipo**: la causa de que no haya cobros es
   una **edición en lote del 24-jun-2026 14:05 (usuario DeVOmibu) que pasó
   las suscripciones de Activa → "En espera" EN PRODUCCIÓN**. WCS no cobra
   suscripciones en espera → facturación congelada desde entonces
   (~39 subs × ~80 € ≈ 3.100 €/mes parados). Ver
   `docs/entregables/p2_causa_raiz_2026-07-10.md`. Preguntar a Henry/equipo
   quién y por qué (¿confusión con el staging, creado por esas fechas?) y
   planificar la reactivación CONTROLADA (1 alumno primero: al reactivar,
   WCS intentará el cobro vencido).
2. Llevar el censo a la reunión con Paco/Fran: los 3 morosos con acceso
   (63/68/144 días) venden la Fase 1 solos.
3. Con credenciales GHL de la sub-cuenta: completar lo de Oliver (Mapping
   Reference en los 2 triggers, guardar workflows, paso Create Opportunity
   en Baja) — las 51 peticiones DRY-RUN de hoy ya dan ejemplos de payload.
4. Cazar al "incluidor fantasma" del staging (pedir a Henry acceso SSH/FTP o
   al panel del hosting; grep -r de 'evocampus-subscription-sync' fuera de
   wp-content/plugins). La v0.5.2 lo neutraliza, pero mejor entenderlo antes
   de producción (B6).
5. Nota Playwright: Chromium no puede salir por el proxy del entorno
   (ERR_CONNECTION_RESET en el handshake TLS del MITM). Todo el trabajo
   wp-admin se hizo con requests/HTTP puro — funciona perfectamente; usarlo
   también en la próxima sesión. Login de PRODUCCIÓN (solo lectura): el
   wp-login está oculto con WPS Hide Login → slug `av-login`.

## Pendientes de negocio (Bloque A del plan)
- ✅ P1 CERRADO: 57 activos reales en EvoCampus (no ~267); 38 suscripción +
  11-12 intensivo (pago único) + 7 matriculación manual sin rastro en Woo
  (uno es info@academiavalenz.com). Falta solo la respuesta de Paco sobre
  los 7 manuales.
- ✅ P2 CERRADO (causa raíz): edición en lote 24-jun Activa→En espera en
  producción por DeVOmibu → renovaciones congeladas (último pedido: 19-jun).
  Falta la decisión de negocio: quién/por qué + plan de reactivación.
- A3 Solicitud WhatsApp a Meta (Fase 3, lead time).
- A4 Pregunta VeriFactu a la gestoría (bloquea Fase 2).
- A6 Política de corte con Paco (¿gracia?; GRACE_DAYS del plugin).
- Oliver: Mapping Reference en los 2 triggers GHL (ya hay peticiones reales
  recibidas) + guardar workflows + paso "Create Opportunity" en el de Baja.

## Reglas que siguen vigentes
- NUNCA desactivar DRY-RUN sin pasar el checklist de wp-plugin/README.md.
- Producción no se toca hasta cerrar P1/P2 y A6.
- Secretos solo en .env/wp-config (gitignorados) — nunca commiteados.
