# Plan maestro ordenado — Academia Valenz (Pack completo 4 sistemas)

## Contexto

Presupuesto aceptado (3.200 € + 340 €/mes). El objetivo de este plan es fijar
**el orden de ejecución completo**, qué resuelve cada pieza (plugin, GHL,
externos), y las conexiones entre plataformas — para trabajarlo y luego
mapearlo a ClickUp. Equipo: Oliver (GHL), Henry (accesos WP/hosting), Germán,
Horacio (rol por definir). Ya construido: plugin v0.3.0 (repo + ZIP), base GHL
(7 campos, 5 tags, 2 pipelines, workflow "Espejo" con trigger Inbound Webhook).

## Mapa de conexiones entre plataformas

```
                    academiavalenz.com (WordPress — nuestro terreno)
                    ┌──────────────────────────────────────────┐
  Alumno paga ───►  │ WooCommerce + Subscriptions + Redsys     │
                    │   │ (a) conector oficial Evolmind: ALTA  │──► EvoCampus
                    │   │ (b) NUESTRO PLUGIN v0.3.0:           │    (campus SaaS,
                    │   │     baja/reactivación ──────────────►│──►  API 83208)
                    │   │     conciliación diaria ────────────►│
                    │   └─ (c) webhook espejo ─────────────┐   │
                    └──────────────────────────────────────│───┘
                                                           ▼
                    GoHighLevel sub-cuenta "Academia Valenz" (hBvP7lemQSMibPYcJPEP)
                    │ workflow "Espejo EvoCampus" (Inbound Webhook) → tags
                    │ alumno-impago/activo → pipeline Recobro → dunning WhatsApp/email
                    │ + Fase 3 (bot IA) y Fase 4 (captación) viven 100% aquí
                    ▼
                    Facturación (Fase 2): Woo webhook → SIF certificado VeriFactu
                    (gestoría/Holded/…) → GHL envía factura + archivo trimestral
```

**Qué resuelve el plugin** (única pieza de código a medida): baja por impago,
reactivación al pagar, conciliación diaria, y alimentar a GHL con cada evento.
**Qué NO resuelve**: facturación (Fase 2, otro flujo), captación, bot.

## Orden de ejecución

### Bloque A — Desbloqueos (esta semana, en paralelo, ~2 h + esperas)
| # | Tarea | Quién | Estado |
|---|---|---|---|
| A1 | Pegar aquí la URL del Inbound Webhook (CRM → Automatización → workflow "Espejo EvoCampus" → trigger) | Oliver | Pendiente |
| A2 | Abrir política de red del entorno (añadir api.evolcampus.com, academiavalenz.com) en claude.ai/code → o decidir ruta manual | Equipo | **Decisión** |
| A3 | Solicitar canal WhatsApp a Meta (lead time semanas; lo usa Fase 3) | Oliver | Pendiente |
| A4 | Preguntar a Fran/gestoría: ¿cómo se emiten facturas hoy? ¿herramienta certificada VeriFactu? | Henry/Germán | **Bloquea Fase 2** |
| A5 | Confirmar si existe staging de WordPress o crearlo desde el hosting | Henry | **Decisión** |
| A6 | Preguntar a Paco: ¿corte inmediato en impago u X días de gracia? | Comercial | **Bloquea F1 en real** |

### Bloque B — Fase 1 · Sincronización de acceso (semanas 1–3, ~10–15 h)
1. **B1** Validar API EvoCampus en vivo (solo lecturas: token + getEnrollments)
   — la hago yo si A2 se abre; si no, la hace el equipo con mis comandos curl.
2. **B2** Completar workflow "Espejo" en GHL (yo, por API): pasos tras el
   webhook → upsert contacto, tag según evento, oportunidad en pipeline
   "Recobro impagos", notificación interna. Mensajes de dunning en borrador
   (se activan cuando haya canal email/WhatsApp configurado).
3. **B3** Instalar plugin v0.3.0 en staging (equipo WP): ZIP + 3 constantes en
   wp-config.php + `OMNIA_GHL_WEBHOOK_URL` (de A1). DRY-RUN=true.
4. **B4** Prueba controlada en staging: suscripción de prueba → on-hold →
   revisar log (`omnia-evocampus-sync`) → pegar aquí el log → yo ajusto campos
   de la API si difieren → reactivar → verificar.
5. **B5** Decisión de Paco aplicada (A6): con/sin gracia → config final.
6. **B6** Producción: instalar con DRY-RUN=true 24–48 h → revisar logs y
   conciliación → pasar a real → probar con UN alumno real controlado.
7. **B7** ✅ Hito: demo a Paco (impago corta acceso solo, pago lo devuelve).

### Bloque C — Fase 2 · Facturación (semanas 3–4, ~10–16 h) — tras A4
1. **C1** Diseño del flujo según respuesta de la gestoría (2 escenarios:
   ya tienen SIF con API → integrar; no tienen → proponer Holded/Quipu).
2. **C2** Webhook de WooCommerce (pago confirmado) → emisor de factura.
3. **C3** GHL: registrar factura en el contacto + envío al alumno + carpeta
   trimestral para Fran + aviso trimestral automático.
4. **C4** ✅ Hito: un cobro real genera y envía su factura sin tocar nada.

### Bloque D — Fase 3 · Agente IA Setter (semanas 4–5, ~12–16 h) — tras B7
1. **D1** Fundaciones restantes GHL: dominio de envío email (DNS), conectar
   WhatsApp (aprobado en A3), calendario de asesorías.
2. **D2** Importar ~386 alumnos desde WooCommerce (CSV → contactos con tags
   activo/baja) — también alimenta el dunning de F1 y campañas de F4.
3. **D3** Knowledge base con contenido de Paco + configurar Conversation AI
   + widget web + handoff a humano + pipeline Captación conectado.
4. **D4** ✅ Hito: el bot responde, cualifica y agenda en WhatsApp y web.

### Bloque E — Fase 4 · Captación (semanas 5–6, ~10–14 h)
1. **E1** Landing/funnel + formularios + tracking (píxel en WordPress).
2. **E2** Secuencias de nurturing (email/WhatsApp) + reporting.
3. **E3** Primera campaña con Paco (oferta/creativos del cliente).
4. **E4** ✅ Hito: primer lead entra, es atendido por el bot y queda medido.

### Bloque F — Cierre (semana 6)
Mapear todo a ClickUp (con responsables y horas reales consumidas), traspaso
al servicio mensual (340 €/mes: monitorización conciliación, ajustes bot,
soporte), y demo final.

## Reglas de dependencia (lo que NO se puede saltar
- Nada pasa a real sin pasar por DRY-RUN/staging primero (B3→B4→B6).
- Fase 2 no se diseña hasta la respuesta de la gestoría (A4).
- El dunning de F1 no se activa hasta tener canal de envío (D1 puede adelantarse si urge).
- A3 (WhatsApp) se lanza YA aunque sea de Fase 3, por el lead time de Meta.

## Verificación end-to-end (criterio de "hecho" por fase)
- **F1**: alumno de prueba impaga → pierde acceso al campus en <1 min, aparece
  con tag alumno-impago y oportunidad en Recobro en GHL → paga → recupera
  acceso. Conciliación nocturna sin desajustes 3 días seguidos.
- **F2**: pedido real → factura legal emitida y enviada + registrada en GHL.
- **F3**: conversación de prueba en WhatsApp → bot responde con datos reales
  de la academia y agenda cita en el calendario.
- **F4**: lead de prueba desde la landing → contacto + oportunidad + secuencia
  disparada + métricas visibles.

## Decisiones abiertas embebidas (se resuelven en Bloque A)
A2 (red del entorno), A4 (VeriFactu/gestoría), A5 (staging), A6 (política de
corte), y el rol de Horacio (asignación de tareas WP vs GHL).


---

## REGISTRO DE PROGRESO (act. 10-jul-2026)

- ✅ A1: URLs de los 2 webhooks recibidas y documentadas; peticiones de ejemplo enviadas (Mapping Reference disponible).
- ✅ A5: staging creado con WP Staging → https://academiavalenz.com/staging (BD prefijo wpstg0_, correos/cron/Woo aislados; WCS y WooPayments en modo seguro).
- ✅ B2: workflows espejo creados en borrador (Baja/Impago + Reactivación) con triggers Inbound Webhook.
- ✅ B3: plugin v0.3.1 + config "00-omnia-evo-config" instalados y activos en staging (aviso DRY-RUN visible).
- ✅ B4: PRUEBA DRY-RUN SUPERADA (10-jul 14:19 UTC, suscripción #1818, manuel.rodriguez16102007@gmail.com):
    token OK · getEnrollments OK (6 matrículas: 2937/3045/3177/3315/4027/4285) · ambos flujos (status=0 y status=2) disparan.
    Observación: el filtro active=true/false parece ignorado por la API (mismas 6 matrículas en ambas consultas). Inofensivo (idempotente); verificar en la primera prueba real.
- ⚠️ HALLAZGO ABIERTO: en el clon, WooCommerce Subscriptions muestra 60 suscripciones (39 en espera, 21 canceladas, 0 ACTIVAS). No cuadra con ~267 alumnos activos. Verificar contadores en PRODUCCIÓN y, si coincide, investigar cómo se refleja realmente el pago mensual antes de B6.
- Pendientes Bloque A: A3 (WhatsApp Meta), A4 (VeriFactu/gestoría), A6 (política de corte con Paco). Oliver: Mapping Reference + guardar workflows + paso Create Opportunity.

### Sesión 2 (10-jul-2026, tarde)
- ✅ A2 CERRADA: la red del entorno ya permite academiavalenz.com y api.evolcampus.com; wp-admin staging operado por HTTP (Chromium no atraviesa el proxy MITM — usar requests).
- ✅ B1 CERRADA: API EvoCampus validada en vivo por curl (token + getEnrollments; campos `person.enrollmentid` / `enroll.enrollmentstatus` confirmados; filtro `active` ignorado por la API — vigilar en primera prueba real).
- ✅ Plugin v0.5.2 en staging: fix fatal `CRON_HOOK` (la página admin daba 500), fix paginación del censo (`paged` ignorado por WCS → faltaban 10 alumnos), y "arranque de rescate" en el guard (en staging una inclusión temprana ajena definía la clase sin arrancarla → plugin muerto; causa raíz pendiente, la v0.5.2 la neutraliza).
- ✅ CENSO DRY-RUN COMPLETO (59/59 alumnos, 64 s, 0 errores, webhooks GHL 200): 22 al corriente · 37 en baja → cruce EvoCampus: **16 impagados con acceso activo, 3 morosos reales (63/68/144 días) usando el campus esta semana**. Detalle: `docs/entregables/censo_conciliacion_2026-07-10.md`.
- ⚠️ Refuerzo P2 (bloquea B6): 0 pedidos en julio; 13 alumnos "frontera" (36-40 días) + activos en 33-35 → nadie ha lanzado los cobros de julio.
- ⚠️ GHL pendiente de credenciales de la sub-cuenta (las del entorno remoto son de otra location: PIT 403 / Firebase 401).
- ✅ GHL espejo RESUELTO (sesión 2, más tarde): plugin v0.6.1 hace el espejo por API pública (upsert contacto+tags+oportunidad), validado extremo a extremo con PIT real en staging. Antes estaba publicado pero inoperativo (el Inbound Webhook no creaba el contacto). Ver `docs/entregables/estado_workflows_ghl_2026-07-10.md`.
- ✅ P1 RESUELTO (panel EvoCampus confirma): **57 alumnos activos**, 253 en total (histórico). El ~267 del discovery era el TOTAL histórico, no los activos. De los 57: 38 con suscripción Woo + 11 con pedido único de intensivo = **49 pagan por Woo (86%)**; quedan **7** sin rastro (1 es la academia). La brecha "~300 vs 60" no existe. Falta a Paco: de dónde sale el 267 y cómo pagan esos 7 (2 intensivo, 5 promoción regular). Panel EvoCampus no accesible (bloqueo de red). Ver `docs/entregables/censo_evocampus_2026-07-10.md`.
- ✅ P2 con hipótesis fuerte: **todas las matrículas de la 132ª terminan el 10-11-jul-2026 (examen)** → el curso se acaba ahora, por eso no hay cobros de julio (el último fue junio). No es facturación rota. IMPLICACIÓN B6: no activar el corte por impago al final del curso; entrar en real con la 133ª promoción (otoño) o respetar la fecha fin de EvoCampus. Confirmar calendario con Paco.
- ⚠️ Plugin no cubre el INTENSIVO (pago único): la conciliación es por suscripciones. Decidir si se deja expirar por fecha fin (simple) o se añade un 2º pase por producto intensivo.
- ✅ Plugin v0.7.0: informe mensual de "acceso sin pago" (alumnos activos en EvoCampus sin registro en la tienda = matriculaciones manuales). Solo lectura, tabla en la página admin + cron mensual. Validado: detecta los 7 manuales + cuenta academia.
- ✅ BLOQUE F ADELANTADO — plan mapeado a ClickUp (espacio Omnia → carpeta "Academia Valenzuela", 6 listas estilo casa): 00 Fase 1 ✅ (7 tareas completadas) · 01 Paso a producción (7 tareas, responsables asignados: Germán email Paco, Henry gestoría+fantasma+deploy, Oliver WhatsApp) · 02 Facturación (4) · 03 Bot IA (5) · 04 Captación (4) · 05 Cierre y mensual (3). Email a Paco redactado (`docs/entregables/mensaje_paco_3preguntas.md`).

### Sesión 3 (10-jul-2026, noche) — ramas reconciliadas
- ✅ Transcripciones Fathom recibidas por chat (plan B; fathom.video bloqueado por red) y analizadas → `docs/LLAMADAS_FATHOM_HALLAZGOS.md`. Confirman: alta automática por conector oficial, baja inexistente, intensivo = pago único con cierre el día del examen, y que las campañas de la convocatoria de septiembre se preparan en julio–agosto (→ valorar adelantar E1/D1).
- ✅ Censo re-ejecutado desde la página admin con v0.7.0 (59/59 en 13 s, 0 errores, DRY-RUN).
- ✅ **P2 CAUSA RAÍZ ENCONTRADA (verificado en PRODUCCIÓN, solo lectura)**: el 24-jun-2026 14:05 una **edición en lote del usuario DeVOmibu pasó las suscripciones de Activa → "En espera"** (notas de las suscripciones #1815 y #1818; contadores prod = clon: 60/39 en espera/21 canceladas/0 activas; último pedido: 19-jun; ajustes WCS correctos: pagos automáticos ON, renovación manual OFF). WCS no cobra suscripciones en espera → **facturación congelada desde el 24-jun (~39×80 € ≈ 3.100 €/mes)**. Encaja con la hipótesis fin-de-curso: pudo ser una pausa deliberada antes del examen (10-11 jul) — confirmar con Henry/equipo quién y por qué, y decidir plan de reactivación controlada. Detalle: `docs/entregables/p2_causa_raiz_2026-07-10.md`.
- ✅ Radiografía de facturación en PRODUCCIÓN (10-jul, solo lectura): NO existe ningún plugin/servicio de facturación (31 plugins revisados); cobros por DOS pasarelas activas (Redsys unificada v1.8 + WooPayments v10.8) — el emisor de Fase 2 debe cubrir ambas. VeriFactu: plazos APLAZADOS por RD-ley 15/2025 (BOE 3-dic-2025) → 1-ene-2027 (sociedades) / 1-jul-2027 (autónomos). Hay margen, pero A4 sigue bloqueando el diseño de Fase 2 (¿emite la gestoría con software certificado?). [Corrección 10-jul: una nota anterior decía "plazo vencido" con las fechas de 2026 — era información desactualizada.]
- ✅ Auditoría de pasarelas en PRODUCCIÓN (10-jul, solo lectura): aunque hay DOS plugins de pasarela activos, **todos los cobros reales van por WooPayments (Stripe)**. Muestreo de pedidos desde el primero de la tienda (#1544, 25-nov-2025) hasta el último (#1992, 19-jun-2026): todos "Pago a través de Tarjeta (pi_…)" — transacciones Stripe. El plugin de Redsys (redsyspur) está activo (menú de diagnóstico presente) pero SIN ningún pedido observado: legado/apagado en el checkout. Implicación Fase 2: el emisor de facturas solo necesita cubrir WooPayments; valorar retirar Redsys para simplificar (decisión del equipo).
- Evaluación "¿y si Stripe?" (10-jul): Stripe Invoicing NO es un SIF certificado VeriFactu (sin encadenamiento/QR/envío AEAT) → no sustituye al emisor de Fase 2, solo cambiaría la pasarela de cobro. WooPayments (ya activa) está construida sobre Stripe. Contras de migrar: re-tokenización de tarjetas de los suscriptores + comisiones (~1,5% + 0,25 €/cargo vs TPV Redsys negociado con el banco). Pros: Smart Retries, actualización automática de tarjetas, portal del cliente (reduce churn involuntario). Si se quisiera, el momento natural sería el arranque de la 133ª promoción; la factura legal seguiría necesitando SIF (gestoría/Holded/Quipu o conector tipo Finseed).
- ⚠️ En producción está subido nuestro plugin v0.3.1 INACTIVO (regla respetada). Antes de B6: sustituirlo por la v0.7.0 de staging, no activar el v0.3.1.
- Nota operativa: login de producción oculto con WPS Hide Login → slug `av-login` (solo lectura desde el entorno; producción NO se toca).
