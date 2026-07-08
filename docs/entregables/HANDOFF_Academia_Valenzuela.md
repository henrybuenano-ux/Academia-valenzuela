# HANDOFF — Proyecto Academia Valenzuela (Omnia)
> Documento de traspaso completo para continuar el trabajo en Claude Code.
> Fecha: junio 2026 · Agencia: **Omnia / ómibu** (Granada) · Usuario: info@omibu.com

---

## 0 · Propósito
Este documento resume TODO lo trabajado en el chat de Cowork sobre el cliente **Academia Valenzuela**: contexto, investigación técnica en vivo, solución diseñada, decisiones comerciales, entregables generados, enlaces, credenciales y tareas pendientes. Sirve para retomar el proyecto en Claude Code con contexto completo.

---

## 1 · Contexto general
- **Agencia:** Omnia (marca comercial también "ómibu"), Granada. Bill in EUR. CRM propio sobre GoHighLevel en `accesocrm.omniainbusiness.com`.
- **Equipo Omnia:** Henry Buenaño, Germán Borrello, Oliver Guerrero (implementación/AI), **Víctor Molina Ángel** (CTO, experto Asana/GHL), **David Alados** (dirección; pidió estimar por horas y sacar salarios medios del puesto).
- **Cliente:** **Academia Valenz** (Academia Valenzuela) — academia ONLINE de preparación de oposiciones a la **Guardia Civil**.
- **Encargo:** resolver dos dolores (revocación de acceso por impago + facturación automática), valorar migración completa a Omnia CRM, y armar propuesta comercial.

---

## 2 · El cliente en detalle
**Datos fiscales/contacto (de su Aviso Legal):**
- Titular: **Francisco Valenzuela Rodríguez** (aka "Paco" / "Fran").
- **NIF/CIF:** 26956058N
- Nombre comercial: **Academia Valenz**
- **Domicilio:** Camino de Ronda, 57, 2ºF · **18004 Granada**
- **Teléfono:** +34 664 678 147 (móvil, sirve para WhatsApp)
- **Emails:** info@academiavalenz.com (general) · valenzuela.oposiciones@gmail.com (legal)
- Web: https://academiavalenz.com (la desarrolló ómibu) · Aula: https://campus.academiavalenz.com
- Redes: x.com/academiavalenz, instagram.com/academiavalenz, instagram.com/pacovalenzuelaopos
- Jurisdicción: Granada.

**Interlocutores:** Paco (director/preparador) y Fran (gestión y pagos). Persona de desarrollo que montó la conexión de pagos: **Jaime** (externo; contactos web: Alberto, Carlos).

**Volumen:** ~386 alumnos (~119 en baja, ~267 activos). Productos: **suscripción mensual (~80 €)** recurrente por tarjeta + **intensivo** (pago único, abril–julio hasta el examen).

**Dolores (del discovery):**
1. **Acceso no se cierra por impago** → revisión manual uno por uno.
2. **Facturación trimestral manual** → "ansiedad" de Paco.
3. Consultas/captación repetitivas (volumen bajo; a futuro).
4. Deseo futuro: **plataforma propia a su gusto**.

---

## 3 · Grabaciones y fuentes (Fathom)
- **Discovery Academia Valenzuela (10-jun):** https://fathom.video/share/pvmoGTnqnHKZuMTooTpwhKUXieK7ofwh
- **Impromptu equipo Omnia (11-jun):** https://fathom.video/share/SPVd4y6mK6Zz21pJRaKhxf5i_YNWkch7
- Aviso legal (fuente de datos fiscales): https://academiavalenz.com/aviso-legal/

---

## 4 · Stack técnico VERIFICADO en vivo (WordPress + EvoCampus)
Revisado dentro del panel de EvoCampus y del wp-admin (11-jun).

**WordPress / WooCommerce (29 plugins activos):**
- **WooCommerce 10.8.1**
- **WooCommerce Subscriptions 8.7.1** (oficial) → motor de recurrencia.
- **Pasarela Unificada de Redsys 1.8.0** (Redsys S.L.) → cobro con tarjeta tokenizada. (También WooPayments 10.8.0 instalado, pero cobran por **Redsys**.)
- **Pluging evolCampus for WooCommerce 3.4** (Evolmind) → conector que hace el **alta** al comprar (mapea Producto → Curso → Grupo). **NO gestiona baja.**
- Otros: TablePress, GTranslate, Elementor/Pro, WPForms, WP Mail SMTP (+Brevo/SendLayer), Yoast, WP Fastest Cache, Autoptimize, Trustindex (reseñas), WPS Hide Login (login en URL personalizada, no /wp-admin).

**Plataforma e-learning:** EvoCampus / **EvolMind** (Zaragoza). Ofrece cursos PDF, clases grabadas, tests interactivos con feedback/ranking.

---

## 5 · API de EvoCampus (documentada + verificada)
- **Base URL:** `https://api.evolcampus.com/api`
- **Auth:** token JWT vía `POST /v1/token` (clientid + key) → cabecera `Authorization: Bearer <token>`.
- **Estado:** complemento API **ACTIVO** en el panel (Configuración › Complementos › API).
- **ClientId:** `83208` · **Key:** en el panel (enmascarada: `fa66…Nfr`).
- **Doc oficial:** PDF "Documentacion_API_evolCampus.pdf" (adjunto en el chat; act. 18-08-2025).

**Endpoints clave:**
| Método | Uso |
|---|---|
| `token` | JWT |
| `newEnrollment` | Alta (lo usa el conector). Devuelve `enrollmentid`; admite `external_id` |
| **`updateEnrollment`** | **status 0=activa · 1=archivada · 2=BAJA · 3=solo lectura** |
| `getEnrollments` | Lista/filtra matrículas (para conciliación) |
| `checkEnrollment` | Estado de un alumno por email/usuario |
| `extendEnrollmentTime` | Amplía fecha fin (intensivo) |
| `getUrlAutologin` / `changePassword` | UX / gestión |

⚠️ **La API NO expone el banco de preguntas de los tests** — solo matrículas.

---

## 6 · Solución de sincronización de acceso (dolor #1)
**Diagnóstico:** las automatizaciones nativas de EvoCampus no tienen condición de pago (el pago vive en WooCommerce/Stripe/Redsys). El conector 3.4 solo hace el alta.

**Solución (implementada como scaffold):** mini-plugin de WordPress que escucha los hooks de **WooCommerce Subscriptions**:
- `woocommerce_subscription_status_on-hold` / `_cancelled` / `_expired` → `updateEnrollment status=2` (baja).
- `woocommerce_subscription_status_active` → `updateEnrollment status=0` (reactivación).
- Token cacheado en transient (~50 min). Conciliación diaria (wp-cron) como red de seguridad.
- **Modo DRY-RUN** por defecto (no toca nada, solo loguea) → constante `OMNIA_EVO_DRYRUN`.
- Credenciales por constantes en `wp-config.php`: `OMNIA_EVO_CLIENTID`, `OMNIA_EVO_KEY`.
- Modelo: 1 suscripción = acceso a todo → opera sobre TODAS las matrículas del email. (Para cortar curso a curso: usar mapeo Producto→Grupo del conector.)

**Pendiente de validar en staging:** nombre real de la option del conector para las credenciales; forma exacta de la respuesta de `getEnrollments`/`updateEnrollment`; decisión de negocio de cortar en `on-hold` o esperar; acceso a entorno de pruebas (Jaime).

---

## 7 · Facturación (dolor #2)
No la hace EvoCampus. Se resuelve en el CRM (GHL): cada pago confirmado en WooCommerce → genera y envía factura + archivo trimestral descargable. Comparte disparador (pago) pero es independiente de la sincronización de acceso.

---

## 8 · Motor de tests (CRÍTICO) — investigación y decisión
**Qué hace EvoCampus (verificado generando un test de prueba):**
- **7.286 preguntas** en árbol: 23 temas → subgrupos (Oficiales / Por Apartados / Globales / sub-temas) + Simulacros (mensuales, de progreso).
- Generación en **4 modos:** Completo, Parcial (por temas), **Falladas**, **Dudosas** (adaptativos). Elegir nº de preguntas y "no repetir respondidas".
- Durante el examen: marcar **Dudosa/Importante**, cronómetro, contadores.
- **Calificación tipo oposición:** +1 acierto, **penalización proporcional** por fallo (con tabla), 0 en blanco.
- **Explicación** por pregunta (cita artículos legales).
- **Impugnar pregunta** → envía correo al profesor/director.
- Página "Preguntas marcadas", nº de realizaciones (1/∞).

**GHL nativo NO lo replica** (quizzes básicos: opción única/múltiple + explicación + aprobado/suspenso; sin banco aleatorio, adaptativos, impugnar, penalización ni ranking).

**Coste de replicarlo a medida:** ~170–190 h solo el motor (≈ 9.400–10.500 € a 55 €/h). Con tests, la migración completa se iría a **17.000–19.000 €** (no 8.800 €).

**Riesgo:** extraer las 7.286 preguntas → confirmar con Jaime/EvolMind si hay export.

**DECISIÓN FINAL:** no se puede migrar todo *menos* los tests (es todo o nada) y la migración completa hoy no compensa → **se descarta la Opción C (migración)**. El presupuesto queda solo con los **pasos 1–4** (conexiones sobre EvoCampus), sin migrar.

Detalle completo en `informe_migracion_tests_academia_valenzuela.md`.

---

## 9 · Comercial: presupuesto y precios
Modelo de precios derivado de la tabla estándar de Omnia (skill `ghl-cotizador`, en USD; en la propuesta se presentó en € 1:1). Tarifa de desarrollo a medida: **55 €/h** (1 día = 8 h).

**Los 4 sistemas (ruta "conectar", la vigente):**
| Sistema | Implementación | Cuota/mes |
|---|---|---|
| 1 · Sincronización de acceso | 1.100 € | 90 € |
| 2 · Facturación automática | 700 € | 70 € |
| 3 · Agente IA Setter | 850 € | 130 € |
| 4 · Captación con campañas | 1.000 € | 100 € |
| **À la carte (los 4)** | **3.650 €** | **390 €** |
| **Pack completo** | **3.200 €** | **340 €** |
| Entrada (1+2, los 2 dolores) | 1.800 € | 160 € |

- **Financiación:** implementación diluida en 6 cuotas (variable `FIN_MESES` en el HTML), sin pago inicial.
- **Costes de terceros (consumo):** WhatsApp **30 €/mes** (reselling), email, IA del bot, comisión pasarela (Stripe ~1,5%+0,25 € / Redsys según banco). Estimado ~**30–60 €/mes + IVA** con monedero recargable.
- **Mapa de Valor + ROI** (auto-calculado en el HTML): default coste laboral 31.800 €/trabajador, 2 trabajadores, escenario medio 18% → ahorro ~11.448 €/año → ROI ~7 meses. (Admin/back-office España ≈ 23.000–25.000 € brutos + SS.)
- **Opción C (descartada):** era migración completa 8.800 € + 350 €/mes; retirada del presupuesto.

**Pendiente:** conectar **links de pago** reales al botón (hoy `PAYMENT_LINK='#'`).

---

## 10 · Datos para crear la sub-cuenta en GHL
(Formulario `accesocrm.omniainbusiness.com/accounts/add`)
| Campo | Valor |
|---|---|
| First Name | Francisco (Paco) |
| Last Name | Valenzuela Rodríguez |
| Email | info@academiavalenz.com |
| Business Name | Academia Valenz |
| Business Niche | Education / Formación |
| Business Phone | +34 664 678 147 |
| Address | Camino de Ronda, 57, 2ºF |
| City | Granada |
| State | Granada |
| Country | España (Spain) |
| Zip | 18004 |
| Website | https://academiavalenz.com |
| Time Zone | (GMT+01:00) Europe/Madrid |
| Tipo de cuenta | This is my client's account |
| NIF (facturación) | 26956058N |

---

## 11 · Entregables generados (carpeta de salida)
1. **`presupuesto_academia_valenzuela.html`** — Propuesta comercial cliente (marca Omnia): diagnóstico, 4 sistemas, calculadora con financiación + botón de pago, costes de terceros, Mapa de Valor + ROI, cronograma. *Sin Opción C.* Sin sello de borrador (aprobada para enviar).
2. **`Omnia_Solucion_EvoCampus_AcademiaValenzuela.pdf`** — Documento técnico para Víctor/equipo: stack confirmado, endpoints, diagrama de flujo (alta/baja/reactivación/conciliación), estados de matrícula, checklist Jaime.
3. **`evocampus-subscription-sync.php`** + **`evocampus-subscription-sync.zip`** — Mini-plugin de WordPress (scaffold) para baja/reactivación vía API. DRY-RUN por defecto.
4. **`guion_venta_academia_valenzuela.md`** — Guion de venta + objeciones + cierres + estrategia Opción C + respuesta a "si la plataforma es mía, ¿por qué pago?".
5. **`informe_migracion_tests_academia_valenzuela.md`** — Informe del motor de tests y por qué se descarta la migración.
6. **`Documentacion_API_evolCampus.pdf`** — Manual oficial de la API de EvoCampus (subido por el cliente/usuario).
7. **`HANDOFF_Academia_Valenzuela.md`** — este documento.

*(También se redactó un correo interno para Víctor y David explicando la decisión sobre los tests — está en el historial del chat, en español neutro.)*

---

## 12 · Enlaces útiles
- Web cliente: https://academiavalenz.com · Aviso legal: https://academiavalenz.com/aviso-legal/
- Aula EvoCampus (login propio): https://campus.academiavalenz.com
- wp-admin: https://academiavalenz.com/wp-admin/ · Conector: `options-general.php?page=evolcampus` · Plugins: `plugins.php` *(login por URL personalizada — WPS Hide Login)*
- API EvoCampus: https://api.evolcampus.com/api · Proveedor: https://www.evolmind.com
- GHL Omnia: https://accesocrm.omniainbusiness.com
- Grabaciones Fathom: ver §3.
- Referencias de precios de terceros: WhatsApp (Meta) developers.facebook.com/…/whatsapp/pricing · GHL WhatsApp/SMS/Email/AI pricing (help.gohighlevel.com).

⚠️ **Las URLs del panel de EvoCampus y del wp-admin llevan tokens de sesión** que caducan. En Claude Code habrá que abrirlas con sesión iniciada de nuevo.

---

## 13 · Credenciales / IDs (manejar con cuidado)
- EvoCampus API: **ClientId 83208**, Key en el panel (Config › Complementos › API). No pegar la key completa en documentos que circulen.
- Cliente NIF: 26956058N.
- Constantes del mini-plugin: `OMNIA_EVO_CLIENTID`, `OMNIA_EVO_KEY`, `OMNIA_EVO_DRYRUN`.

---

## 14 · Pendientes / próximos pasos
1. **Crear la sub-cuenta** en GHL con los datos de §10.
2. **Links de pago** reales → conectarlos al botón del presupuesto (`PAYMENT_LINK`).
3. **Reunión técnica con Jaime:** confirmar eventos de WooCommerce Subscriptions, option del conector, acceso a **staging**, y sobre todo **si se puede exportar el banco de preguntas** (clave para cualquier migración futura).
4. **Validar el mini-plugin en staging** (DRY-RUN → real) y verificar forma de respuesta de la API.
5. **Presentar el presupuesto** a Paco (David preguntó cuándo). Guion listo.
6. Definir política de gracia/reintentos en WooCommerce Subscriptions.
7. (Futuro) Si quieren plataforma propia → retomar migración completa como proyecto aparte con los números reales (§8).

---

## 15 · Notas para continuar en Claude Code
- El código relevante es el **mini-plugin PHP** (`evocampus-subscription-sync.php`): ahí es donde se iterará (mapeo de credenciales, nombres de campos de la API confirmados en staging, lógica de conciliación, tests).
- El **presupuesto HTML** es autocontenido (marca Omnia; sin dependencias externas salvo Google Fonts). Variables de negocio en el `<script>`: precios por sistema (`data-setup`/`data-fee`), `PACK_SETUP`/`PACK_FEE`, `FIN_MESES`, `PAYMENT_LINK`, y defaults del Mapa de Valor.
- Para regenerar PDFs: en el sandbox había `reportlab` + `matplotlib` + `poppler` (pdftoppm) — sin red para pip.
- Skills de Omnia disponibles en Cowork usadas: `generador-presupuestos` (marca), `ghl-cotizador` (tabla de precios + script), `pdf`. La plantilla golden de presupuestos NO estaba en la skill; el HTML se construyó fiel al sistema de diseño documentado.
- Idioma: la propuesta al cliente va en **español de España**; los internos, en **español neutro** (última preferencia del usuario).
