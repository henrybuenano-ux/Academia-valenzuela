# Plan de implementación — Academia Valenz (Pack completo)
> Borrador interno para iterar ANTES de mapear a ClickUp.
> Presupuesto aceptado: **Pack completo (4 sistemas) — 3.200 € implementación + 340 €/mes**
> (https://presupuestos.omniainbusiness.com/academia_valenzuela-977978)
> Sub-cuenta GHL: `hBvP7lemQSMibPYcJPEP` ("Academia Valenz") · Agencia Omnia `e9Wavfr6i9YX8qJYixY2`

---

## Criterio general: qué vive en GHL y qué es externo

Regla de oro del proyecto: **el dinero y el acceso al aula viven fuera de GHL**
(WooCommerce/Redsys y EvoCampus respectivamente). GHL es la capa de CRM,
comunicación y orquestación. Todo lo que toque cobro o matrícula se hace
externo (WordPress/EvoCampus) y se *refleja* en GHL vía webhooks.

| Sistema | Núcleo | GHL | Externo |
|---|---|---|---|
| 1 · Sincronización de acceso | **Externo** | Espejo: tags, alertas, dunning | Mini-plugin WP → API EvoCampus |
| 2 · Facturación automática | **Mixto** (ver VeriFactu) | Orquestación + envío + CRM | Emisión legal de factura (SIF certificado) |
| 3 · Agente IA Setter | **GHL** | Conversation AI, WhatsApp, calendario | Aprobación Meta, KB de Paco, embed web |
| 4 · Captación con campañas | **GHL** | Funnels, forms, email, pipeline, reporting | Cuentas de ads, píxel, DNS, creativos |

---

## Sistema 1 · Sincronización de acceso (1.100 € / 90 €/mes)

**Externo (WordPress del cliente) — el corte real:**
- Mini-plugin `evocampus-subscription-sync.php` (scaffold ya diseñado, §6 del handoff):
  - Hooks WooCommerce Subscriptions: `on-hold`/`cancelled`/`expired` → `updateEnrollment status=2` (baja); `active` → `status=0` (reactivación).
  - DRY-RUN por defecto (`OMNIA_EVO_DRYRUN`), credenciales en `wp-config.php`.
  - Conciliación diaria (wp-cron) con `getEnrollments` como red de seguridad.
- **No puede hacerse desde GHL**: GHL no ve los cobros de Redsys ni tiene conector con EvoCampus.

**GHL — el espejo (recomendado desde el día 1, alimenta el Sistema 3):**
- El mismo plugin (o WP Webhooks) dispara un webhook a GHL → trigger *Inbound Webhook* en un workflow.
- El workflow: actualiza tag del contacto (`alumno-activo` / `alumno-impago` / `alumno-baja`), notifica al equipo, crea tarea de seguimiento y lanza secuencia de dunning (WhatsApp/email al alumno: "tu pago ha fallado, regulariza aquí").

**Validar en staging (reunión con Jaime — GATE del sistema):**
- Option real del conector evolCampus (credenciales), forma exacta de respuesta de `getEnrollments`/`updateEnrollment`, acceso a staging, y export del banco de preguntas (para el futuro).

---

## Sistema 2 · Facturación automática (700 € / 70 €/mes)

**⚠️ Punto de decisión previo — VeriFactu (RD 1007/2023):**
Desde el **1 de julio de 2026** (ya en vigor) los autónomos están obligados a
emitir factura desde un SIF certificado VeriFactu o desde el sistema de la
AEAT. Paco factura como persona física (NIF 26956058N). **GHL Invoices NO es
un SIF certificado** → GHL no puede ser el emisor legal de las facturas.

**Alcance propuesto (a validar con Paco/gestoría):**
- Emisión legal: herramienta certificada VeriFactu (Holded, Quipu, FacturaDirecta…) o la propia gestoría. Averiguar **cómo facturan hoy** — puede que la gestoría ya emita y solo falte automatizar el flujo de datos.
- Trigger: pago confirmado en WooCommerce (webhook nativo de Woo `order.updated`/`subscription renewal`).
- Orquestación: webhook → herramienta de facturación (API) → factura emitida → GHL registra el evento en el contacto y envía la factura al alumno (email/WhatsApp).
- Archivo trimestral descargable: export de la herramienta de facturación o carpeta compartida alimentada por el flujo (externo, script simple).

**En GHL:** contacto con NIF (custom field), historial de facturas, envío y aviso trimestral a Fran con el archivo listo.

---

## Sistema 3 · Agente IA Setter (850 € / 130 €/mes)

**GHL (casi todo nativo):**
- Conversation AI (bot) con knowledge base de la academia (temarios, precios, funcionamiento del campus, convocatorias GC).
- Canal WhatsApp (reselling 30 €/mes) + chat widget web + IG DM si se conecta.
- Calendario de llamadas/asesorías + pipeline de leads con etapas (Nuevo → Cualificado → Agendado → Alta).
- Handoff a humano (Paco/Fran) con notificación cuando el bot no resuelve.

**Externo:**
- Aprobación WhatsApp Business (Meta) — **iniciar en semana 1, tiene lead time**.
- Contenido de la KB (lo aporta Paco), embed del widget en WordPress.

---

## Sistema 4 · Captación con campañas (1.000 € / 100 €/mes)

**GHL:** landing/funnel de captación, formularios, secuencias de email/WhatsApp de nurturing, pipeline, reporting de campañas, tracking UTM.

**Externo:** cuentas Meta/Google Ads del cliente, píxel/eventos en WordPress, subdominios y DNS (funnel + dominio de envío de email), creativos y oferta (con Paco: masterclass, test vocacional GC, etc.).

---

## Fase 0 · Fundaciones (transversal, dentro del setup del pack)

**GHL:**
- Usuarios del equipo Omnia + accesos de Paco/Fran.
- Custom fields: `estado_matricula`, `evo_external_id`, `nif`, `producto` (suscripción/intensivo), `fecha_alta`, `fecha_baja`.
- Taxonomía de tags y pipeline base.
- Dominio de envío de email (DNS) + número/canal WhatsApp.
- **Importación de ~386 alumnos** desde WooCommerce (CSV): decidir campos mínimos y si se importan también los ~119 en baja (recomendado: sí, con tag `alumno-baja`, para campañas de recuperación).

**Externo:** key completa de la API EvoCampus (ClientId 83208, está en el panel), acceso a staging de WordPress, webhook de salida en Woo.

---

## Cronograma borrador (a iterar)

| Semana | Hito |
|---|---|
| 1 | Fase 0 completa + **reunión técnica con Jaime** (gate) + solicitud WhatsApp Meta |
| 2–3 | Sistema 1 en staging (DRY-RUN) → validación → producción + espejo en GHL |
| 3–4 | Sistema 2 (tras decisión gestoría/VeriFactu) |
| 4–5 | Sistema 3 (bot + KB + calendario) |
| 5–6 | Sistema 4 (funnel + campañas) + medición y cierre de implementación |

---

## Decisiones abiertas — iterar ANTES de subir a ClickUp

1. **Política de corte** (Sistema 1): ¿baja inmediata en `on-hold` o periodo de gracia X días con reintentos de Woo? (definir con Paco).
2. **Emisor legal de facturas** (Sistema 2): ¿gestoría, herramienta certificada nueva, o ya existe una? → condiciona todo el alcance del sistema. Preguntar a Fran/gestoría.
3. **Espejo Woo→GHL** del Sistema 1: ¿día 1 (recomendado) o segunda fase?
4. **Importación inicial**: ¿incluir los 119 en baja? ¿qué campos históricos?
5. **Responsables por tarea** (Oliver / Víctor / Jaime / externo) y horas estimadas por bloque — necesario para la vista de carga en ClickUp (petición de David).
6. **Orden de despliegue**: propuesto 1→2→3→4 (dolores primero); ¿adelantar el 3 si WhatsApp se aprueba rápido?

## Estructura ClickUp propuesta (cuando lo anterior esté cerrado)

- 1 Lista por sistema (+1 de Fase 0) · tareas = bloques de arriba · subtareas = checklist técnico.
- Campos: responsable, horas estimadas, entorno (GHL / WordPress / EvoCampus / Terceros), dependencia, estado (Backlog → En curso → Validación staging → Producción).
- Hitos: gate Jaime, DRY-RUN OK, primera factura automática, primer lead del bot, primera campaña live.
