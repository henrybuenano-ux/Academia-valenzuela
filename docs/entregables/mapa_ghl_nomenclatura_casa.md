# Academia Valenzuela — Mapa GHL en nomenclatura estándar (act. 24-jul-2026)

> Re-mapeo del sistema construido a la nomenclatura de casa: LS (Lead Sources),
> SP (Sales Pipeline), AP (Alumno activo — gestión de acceso), RP (Recovery /
> Recobro de impagos). PS (reviews) no aplica en este alcance.
> ✔ Nombres YA aplicados en la sub-cuenta GHL y en ClickUp (24-jul).
> Estado: ✅ funcionando · 🟡 construido en borrador · ⚪ especificado · 🔴 espera externa

## MAPA VISUAL (pegar en Miro/Canva — bloques por color)

```
[LEAD SOURCES — AZUL]
├─ LS01: Formulario Landing 133ª ⚪ (form especificado · workflows 🟡)
│  ├─ Trigger: Form submitted → aplica tag lead-landing-133
│  ├─ Campos ocultos: UTM Source/Medium/Campaign → custom fields
│  ├─ Action: Crear oportunidad → Captación / Nuevo lead (fuente "Landing 133")
│  └─ Encadena: SP01 (nurturing) en paralelo
├─ LS02: Chat widget — Bot IA Setter ⚪ (14 subtareas especificadas)
│  ├─ Trigger: Chat Initiated (widget web)
│  ├─ Bot: responde con KB → cualifica (Situación oposición) → captura datos
│  ├─ Decision: ¿cualificado? → aplica tag lead-bot
│  ├─ Action: Crear oportunidad → Captación / Cualificado (fuente "Bot web")
│  └─ Action: Book Appointment → calendario Asesorías (pendiente D1)
├─ LS03: Campaña re-enganche ex-132ª 🟡 (workflow en borrador)
│  ├─ Trigger: tag reenganche-133 (se aplica manualmente al segmento
│  │           promo-132 + ultimo-estado-baja — 39 contactos ya importados)
│  ├─ Emails: B1 día 0 → B2 día 4 [PENDIENTE contenido Paco] → B3 día 8
│  └─ Salida: responde NO → tag no-133 (no se insiste)
└─ LS04: WhatsApp post-formulario 🔴 (espera aprobación Meta)
   ├─ Trigger: envío de LS01 → Send WhatsApp (plantilla aprobada)
   └─ Lead responde → Bot IA toma la conversación (modo identificado:
      NO re-pide datos, saluda por nombre)

[SALES PIPELINE — VERDE]  Pipeline "Captación":
  Nuevo lead → Contactado → Cualificado → Agendado → Alta alumno | Perdido
├─ SP01: Nurturing lead nuevo 🟡 (workflow en borrador)
│  ├─ Trigger: tag lead-landing-133
│  ├─ Emails: A1 día 0 → A2 día 2 → A3 día 4 → A4 día 7
│  ├─ Goal Event (remate UI): asesoria-agendada / matriculado-133 → END
│  └─ Sin respuesta → tag lead-frio-133 (audiencia remarketing)
├─ SP02: Asesoría agendada ⚪ (config al crear el calendario)
│  ├─ Trigger: Customer Booked Appointment (calendario Asesorías)
│  ├─ Action: tag asesoria-agendada (corta SP01/LS03 por goal event)
│  └─ Action: mover oportunidad → Agendado
└─ SP03: Cierre a alumno (MANUAL equipo + auto al pagar)
   ├─ Se matricula en la web → alta Woo → tag matriculado-133
   └─ Action: mover oportunidad → Alta alumno · entra en AP

[ALUMNO ACTIVO — ROJO]  (gestión de acceso al campus)
├─ AP01: Alta automática en campus ✅ (conector oficial Evolmind)
│  └─ Compra en Woo → alta EvoCampus + envío de credenciales
├─ AP02: Sync de acceso por pago 🟡 (plugin v0.8.0 · DRY-RUN staging · prod 24-ago · real 1-sep)
│  ├─ Conciliación nocturna POR PEDIDOS (ventana 38 días = ciclo 31 + 7 cortesía)
│  ├─ Cancela/expira → baja INMEDIATA en campus
│  ├─ Paga tras corte → reactivación automática (status=0)
│  └─ Espejo GHL por API: tags + oportunidades (sin webhooks)
└─ AP03: Informe mensual "acceso sin pago" 🟡 (v0.8.0)
   └─ Becados (whitelist 7) = autorizados · desconocidos = alerta a revisar

[RECOVERY / RECOBRO — MORADO]  Pipeline "Recobro impagos":
  Impago detectado → Avisado → Recuperado | Baja definitiva
├─ RP01: Impago detectado 🟡 (plugin, SIN corte — 7 días de cortesía)
│  ├─ Trigger: falla el cobro → suscripción on-hold
│  └─ Action: tag alumno-impago + oportunidad → Impago detectado
├─ RP02: Dunning — avisos automáticos 🟡 (workflow en borrador · NO ACTIVAR HASTA 1-SEP)
│  ├─ Trigger: tag alumno-impago
│  ├─ Emails: día 0 amable → día 3 recordatorio → día 6 último aviso
│  ├─ (El corte lo ejecuta AP02 al agotar la ventana) → email post-corte día 8
│  └─ Goal Event (remate UI): alumno-recuperado / alumno-activo → END
└─ RP03: Recuperación 🟡
   ├─ Trigger: tag alumno-recuperado (lo pone el plugin al cobrar)
   └─ Email "Bienvenida de vuelta" + oportunidad → Recuperado
```

## TAB 2 — WORKFLOWS (estado real en la sub-cuenta)

| Código | Nombre en GHL | Trigger | Objetivo | Estado |
|---|---|---|---|---|
| LS01 | LS01 · Lead de landing → Captación | tag `lead-landing-133` | Oportunidad en Nuevo lead, fuente Landing 133 | 🟡 borrador |
| LS02 | LS02 · Lead del bot → Captación | tag `lead-bot` | Oportunidad en Cualificado, fuente Bot web | 🟡 borrador |
| LS03 | LS03 · Re-enganche ex-alumnos 132ª | tag `reenganche-133` | 3 emails de re-matrícula → `no-133` | 🟡 borrador |
| LS04 | (futuro) WhatsApp post-form | envío de LS01 | Conversación IA con lead identificado | 🔴 Meta |
| SP01 | SP01 · Nurturing lead nuevo 133ª | tag `lead-landing-133` | 4 emails → asesoría/matrícula → `lead-frio-133` | 🟡 borrador |
| SP02 | (por crear con el calendario) | booking Asesorías | tag `asesoria-agendada` + mover a Agendado | ⚪ |
| RP02 | RP02 · Dunning impago — NO ACTIVAR HASTA 1-SEP | tag `alumno-impago` | Avisos 0/3/6 + post-corte 8 | 🟡 borrador |
| RP03 | RP03 · Bienvenida al recuperar el pago | tag `alumno-recuperado` | Confirmación + corta RP02 | 🟡 borrador |
| — | LEGADO · Espejo Baja/Impago + Reactivación | Inbound Webhook | Renombrados como LEGADO (24-jul); pendiente despublicar | ✅ publicados |

## TAB 3 — CUSTOM FIELDS (11)

| Campo | Tipo | Origen/uso |
|---|---|---|
| Fecha alta / Fecha baja | DATE | Plugin F1 |
| Estado matrícula | DROPDOWN | Plugin F1 |
| NIF | TEXT | Facturación futura |
| Woo Subscription ID | TEXT | Plugin F1 |
| EvoCampus Enrollment IDs | TEXT | Plugin F1 |
| Producto | DROPDOWN | Plugin F1 |
| UTM Source / Medium / Campaign | TEXT ×3 | Form LS01 (ocultos) |
| Situación oposición | DROPDOWN (4 opc.) | Form LS01 + captura bot LS02 |

## TAB 4 — FORMS | TAB 5 — CALENDARS | TAB 6 — CUSTOM VALUES

| Elemento | Detalle | Estado |
|---|---|---|
| Form "Landing 133" | 4 campos visibles + 3 UTM ocultos · On Submit: tag `lead-landing-133` (receta clic a clic en ClickUp) | ⚪ |
| Calendario "Asesorías" | 10-15 min · decidir quién atiende y horario · su enlace sustituye `{{CALENDARIO_ASESORIAS}}` en SP01/LS03 y el Book del bot | ⚪ **único pendiente de Setup** |
| Dominio de email `mail.academiavalenz.com` | SPF + DKIM (mx._domainkey) + CNAME email.mail + MX mailgun · raíz Google intacta | ✅ **verificado 24-jul** |
| Dominio de funnels `info.academiavalenz.com` | CNAME → sites.ludicrous.cloud | ✅ creado |
| Custom value enlace matrícula | https://academiavalenz.com/ | ✅ |
| Custom value Mi cuenta (pagos) | https://academiavalenz.com/mi-cuenta/ | ✅ |

## TAB 7 — ACTION ITEMS (remates para activar)

- [x] ✅ DNS email verificado (24-jul) — canal operativo, desbloquea SP01/LS03
- [ ] Crear calendario Asesorías → pegar enlace en SP01/LS03/bot → y montar SP02
- [ ] Goal Events en SP01, LS03, RP02 (UI, 2 min c/u) — crítico en RP02
- [ ] Internal Notification en LS01 y LS02 (UI)
- [ ] Form LS01 (receta en ClickUp) + landing (copy listo) → activar LS01+SP01
- [ ] Bot LS02: 14 subtareas + QA (incluye modo identificado) → Auto-Pilot
- [ ] LS03: completar email B2 con novedades de Paco → lanzar campaña
- [ ] Despublicar los 2 workflows espejo legado
- [ ] 1-SEP: activar RP02/RP03 junto con el modo real del plugin (AP02)

## Resumen de alcance

**Workflows**: 4 LS · 3 SP · 3 AP (plugin, fuera de GHL) · 3 RP → 8 ya construidos/funcionando, 2 por crear (SP02, LS04), resto config/manual. **Pendiente de construir en GHL**: form, landing, bot, calendario. **Horas estimadas restantes lado GHL**: ~12-16 h (Oliver, sprint 28-jul → 3-ago).
