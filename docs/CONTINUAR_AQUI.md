# CONTINUAR AQUÍ — estado al 10-ago-2026 (sesión 9)

> **EMPIEZA POR AQUÍ ⬇️ — lo de abajo es histórico de sesiones anteriores.**

---

# 🚨 HOY ES LUNES 31 DE AGOSTO · EL CURSO EMPIEZA MAÑANA

Corrección de fechas del 31-ago: varios documentos se escribieron fechados el
**27-ago** y hablaban de «el lunes 1 de septiembre». **El 1 de septiembre es
martes, y hoy —lunes— es el 31.** No quedan días de margen: queda hoy.

## Lo que vence hoy o mañana por la mañana

| Cuándo | Qué | Estado |
|---|---|---|
| ~~Hoy~~ ✅ | ~~Dar acceso a los 17~~ **HECHO el 31-ago por la tarde** — carga masiva verificada: 35 personas × 4 formaciones, cero duplicados. Falta avisarles de que recuperen contraseña | ✅ |
| **Hoy** | **Último día de la oferta de 48 €.** Mañana debería estar a 80: no sube sola | 🔴 |
| **Hoy** | **Congelar la campaña de recuperación** hasta que el conector matricule a ex-alumnos | 🔴 |
| Mañana | Reconfigurar el `#2054` (cuota, prueba, sincronización, «dejar de renovar») | 🟡 |
| Mañana | Landing y bot con el precio nuevo | 🟡 |

Detalle de cada uno en los documentos enlazados más abajo.

---


---

# 📬 27-ago · Paco contesta las tres, y el falso desfase del campus

## Las tres respuestas

| | Lo que dijo | Qué cierra |
|---|---|---|
| **1** | *«esas suscripciones están extintas ya que al ser un curso nuevo hay que hacer una suscripción nueva»* | **Decisión A cerrada.** Confirma lo que dedujimos del censo. Queda ejecutarlo |
| **2** | *«Aproximadamente para la primera quincena del mes de julio»* | **Examen de la 133ª.** Corroborado por la plataforma: las matrículas acaban el **03-jul-2027** |
| **3** | *«El curso de la entrevista va a parte… quien hace la entrevista ya no debe volver a estar en ningún curso, ya que ya habrían aprobado»* | **La exención de los 7 no se arrastra.** Y se resuelve sola: las 45 matrículas de entrevista **caducan el 25-sep** |

Con la 1, **el último bloqueo del modo real es de ejecución, no de decisión**.

## ✅ El desfase 34 / 15 no era un fallo de sincronización

Paco avisó de que había «34 apuntados» en la plataforma y «15» en la web, y
Horacio apuntó a un problema de sincronización. Consultado en directo:

**72 matrículas, pero solo 18 personas.** Cada alumno se matricula en **4
cursos** —Conocimientos, Psicotécnicos, Ortografía, Inglés—, que son las cuatro
formaciones. 18 × 4 = 72. **Es el comportamiento correcto.**

Y 18 personas frente a los 16 pedidos del 25-ago: cuadra con dos ventas más en
dos días. **No falta nadie.**

> La dirección del desfase ya lo descartaba: un fallo de sincronización daría
> **menos** en la plataforma que en la web, no más.

Diagnóstico, lista de los 18 y borrador de respuesta:
`entregables/desfase_campus_web_2026-08-31.md`

---

# ⏰ MARTES 1 DE SEPTIEMBRE — lo que vence

Reunido aquí porque hoy está repartido entre tres documentos. **Enlaces, no
copias**: el detalle vive en su sitio.

| Qué hay que hacer | Detalle |
|---|---|
| **Reconfigurar el producto `#2054`.** No basta con subir la cuota de 48 a 80 €: la prueba gratuita más la sincronización al día 1 dejan huecos de 32 a 60 días según el día de compra | `entregables/precio_lanzamiento_caduca_31ago.md` |
| **Vaciar las «Fechas del precio rebajado»** (6-ago → 31-ago). Están puestas sobre un precio rebajado vacío: no hacen nada y hacen creer que el precio está programado | idem |
| **«Dejar de renovar después de» → las cuotas que lleguen al examen**, en vez de «No parar hasta que se cancele». Sin esto, el ciclo hay que cerrarlo a mano cada año. ✅ **Ya no bloqueado**: examen en la primera quincena de julio de 2027, matrículas del campus hasta el **03-jul-2027** | `entregables/duracion_suscripcion_contradiccion.md` |
| **Actualizar landing y prompt del bot** con el precio nuevo | `entregables/landing_133.html` · `entregables/bot_ls02_prompts_2026-08-07.md` |
| **Llamar a los 32 de la lista de recuperación** — ya desbloqueado. Que quien llame lleve la frase de «¿Olvidó su contraseña?». ⚠️ Solo el canal telefónico: **LS03 sigue en borrador** y su email B2 espera contenido de Paco | `entregables/llamada_2026-08-26_acuerdos.md` |
| **Publicar `RP02 · Dunning impago`** — lleva la fecha en el propio nombre («NO ACTIVAR HASTA 1-SEP») y estaba fuera de toda lista. Inofensivo mientras el plugin siga en DRY-RUN | ver el aviso de abajo |

## ⚠️ El orden real del dunning (RP02)

Inventario de workflows del 26-ago: **RP02 está en borrador** y su nombre dice
«NO ACTIVAR HASTA 1-SEP». Publicarlo mañana es inofensivo: dispara con el tag
`alumno-impago`, que lo pone el espejo de GHL del plugin, y con el plugin en
DRY-RUN nadie lo activa.

**El interruptor de verdad no es RP02: es el DRY-RUN del plugin.** En cuanto se
quite, empiezan a salir avisos de impago — y si las suscripciones paradas siguen
sin cerrarse, los ex-alumnos de la 132ª recibirían correos de «no has pagado»
por un curso que terminó en julio.

> Cadena real: **cerrar las paradas → plugin a modo real → arranca el dunning.**
> Saltarse el primer paso es lo que convierte RP02 en un problema.

### Y una matización sobre la campaña de recuperación

Escribí hoy que «ya se puede llamar a los 32». Cierto **para el teléfono**. Pero
**`LS03 · Re-enganche ex-alumnos 132ª` sigue en borrador** y su email B2 espera
las novedades de la 133ª que Paco lleva semanas sin enviar. La secuencia
automática de correos **no está lista**: son dos canales y solo uno está abierto.

### Estado de los 12 workflows (26-ago, 20:13)

| Publicados | En borrador / sin estado |
|---|---|
| BT01, LS01, LS02, RP04, SP01, **SP02**, SP03 | **LS03** (re-enganche), **RP02** (dunning), RP03 (bienvenida al recuperar), 2 × LEGADO |

**SP02 verificado tras ponerle el filtro *In calendar*:** sigue `published`,
modificado a las 20:12:57. No se despublicó al guardar, que era el riesgo.

Los dos **LEGADO** están en `draft` **desde el 6-ago** — se despublicaron en la
sesión de ese día (ver «Aplicado en esta sesión» más abajo), no están pendientes.
Verificado por API: `updatedAt` sigue en `2026-08-06T22:48`, sin tocar desde
entonces.

> Corrijo lo que escribí antes en este mismo bloque: dije que seguían
> publicados, contradiciendo la tabla de aquí arriba. El mapa
> `mapa_ghl_nomenclatura_casa.md` los daba por publicados desde el 24-jul y ya
> está corregido.

## 📅 Calendario de asesorías: desbloqueado, con dos minas

El horario de Paco era lo único que faltaba y lo dio en la llamada. Verificado
hoy por API el estado real del calendario `Asesorías 133ª`:

- **Lo guardado y lo servido no coinciden.** Guardado: 10-14 / 16-20. Servido:
  **08:00–16:45, 36 huecos de 15 min.** Confirma el hallazgo de julio: el
  horario cargado por API está almacenado pero el motor lo ignora. **Hay que
  reescribirlo desde la UI.**
- **Ninguna de las dos es la de Paco** (9-13 / 17-20, 30 min, 24 h).

### 🔴 Dos cosas que hay que arreglar antes de encender nada

1. **Ya existe un segundo calendario** — `Paco Valenzuela's Personal Calendar`.
   La auditoría del 6-ago avisó: *«si se añade otro, ponerle el filtro In
   calendar»* a SP02. Nadie lo hizo. Hoy **una cita en la agenda personal de
   Paco sacaría al contacto de SP01/LS03**. Treinta segundos de arreglo.
2. **No encender el nodo de agenda del bot** hasta que Paco conecte su Google
   Calendar. Sin eso GHL ofrece sus 14 franjas diarias como libres y los leads
   reservan encima de sus clases.

Pasos de UI, valores y checklist de verificación:
`entregables/calendario_asesorias_config.md`

**Dos decisiones abiertas**: cuánto dura la asesoría (el bot dice 10 min, Paco
pidió 30, el calendario sirve 15) y quién la atiende (está en round robin con 3
personas, pero el horario es el de Paco).

## ✅ Resuelto: el acceso llega hasta el examen

El censo de EvoCampus lo dice desde las matrículas reales: **todas las de la 132ª
terminan el 10-11 de julio de 2026**, el día del examen. Curso regular
01-oct-2025 → 10-jul-2026, ~10 cuotas. Intensivo 13-abr → 10-jul.

Lo publicado en la landing y en el bot es **correcto**. El «hasta abril/mayo» de
la llamada de junio se refería al **temario**, no al acceso. Y el intensivo es la
vía de entrada tardía —«para gente externa»—, no la continuación del curso.

Análisis: `entregables/duracion_suscripcion_contradiccion.md`.

### Y desatasca dos cosas

**La decisión A deja de tener tres opciones.** Las suscripciones paradas el
24-jun son el final natural del ciclo de la 132ª: reactivarlas sería cobrar por
un curso terminado. Cancelar o dejar expirar, y venderles la 133ª.

**Falta un arreglo el 1-sep que nadie había visto.** El `#2054` está en «No parar
hasta que se cancele», así que en julio de 2027 volverá a hacer falta editar ~40
suscripciones a mano — que es literalmente lo que pasó el 24-jun desde DevOmibu.
Hay que poner **«Dejar de renovar después de» = las cuotas que lleguen al
examen**. Bloqueado por un dato: **la fecha del examen de la 133ª**.

## 📨 Esperando respuesta de Paco (enviado el 26-ago)

Contestada su pregunta de si había que dar de baja a los ex-alumnos para que
pudieran inscribirse: **no, y sería un error** — perderían historial, facturas y
el vínculo con su matrícula del campus, y no habría arreglado nada.

Borrador y notas internas: `entregables/respuesta_paco_rematriculacion_2026-08-26.md`
Versión publicada: https://claude.ai/code/artifact/1bad64f2-f1fe-4f84-acc5-a9346e7468ca

**Lo que falta y solo puede venir de ellos:** que **Óscar Vargas Balboa** intente
la compra y confirme que llega a la pasarela. Está verificado que el formulario
de acceso aparece y funciona, pero **nadie ha completado una compra entera**
desde el arreglo. Hasta que eso ocurra, el asunto no está cerrado del todo.

---

# 🔴 SESIÓN 9 (10-ago-2026) — EL EMBUDO ESTÁ TERMINADO Y VACÍO

## Estado en una línea
El sistema funciona de punta a punta (validado en la sesión 8) pero **no ha
entrado ni un lead en 17 días**, y la 133ª arranca el **1-sep: quedan 22
días**. El problema ya no es técnico, es de **tráfico**.

## El dato (verificado por dos vías: listado y búsqueda por tag)
| Métrica | Valor |
|---|---|
| Contactos en el CRM | **103** — los 103 de la importación de julio |
| Con `lead-landing-133` (leads de la landing) | **0** |
| Con `lead-bot` (leads del chat) | **0** |
| Oportunidades (open/won/lost/abandoned) | **0** |

**Por qué está vacío**: no hay puerta de entrada — y la que hay está rota
(auditoría de la web, 10-ago, más abajo). El bot vive en `/formacion`, una
página del funnel a la que nadie llega, y las campañas de pago (F4) nunca
arrancaron pese a que el plan las situaba en julio-agosto.

## 🔴🔴 AUDITORÍA DE LA WEB PRINCIPAL — el CTA de la home lleva a un 404

> ⚠️ **Corrección**: una nota anterior decía que la home "menciona la 133ª 7
> veces". **Es falso** — ese conteo salió de un grep que capturó ids de
> Elementor (`73133cf`). La realidad es peor: **la home no menciona la 133ª
> en absoluto**.

Lo verificado hoy en `academiavalenz.com`:

| Hallazgo | Estado |
|---|---|
| Bloque destacado de la home | Vende **"Curso Ingreso Guardia Civil – 132ª Promoción"**, con el texto "oposiciones a la Guardia Civil **2025**" |
| Botón principal "Apúntate ahora al curso" | → `/producto/curso-ingreso-guardia-civil-132-promocion/` → **HTTP 404** |
| Producto de la 133ª | ✅ **existe, publicado y comprable**: `/producto/curso-ingreso-guardia-civil-133a-promocion/`, suscripción **80 €/mes** (precio confirmado en el datalayer: `"price":80`) — ⚠️ *corregido el 25-ago: la estructura real es 80 €/mes + 48 € de cuota de registro + 1 mes de prueba; ver la sección del 25-ago al final*, botón "Añadir al carrito" (`add-to-cart=2054`) |
| Enlace a ese producto desde la home | ❌ ninguno — solo se llega por el menú "Cursos" (tienda) |
| Enlace a la landing `/formacion` | ❌ ninguno |
| Widget del bot en la web | ❌ no está |

**Traducción comercial**: quien entra hoy en la web de Paco y pulsa el botón
más visible **se estrella contra un 404**. El curso nuevo está bien montado y
a la venta, pero escondido a dos clics. Esto explica el embudo vacío mejor
que ninguna otra cosa y es lo más barato de arreglar de todo el proyecto.

### Arreglo propuesto (orden de impacto, todo en WordPress/Elementor)
1. **Repuntar el CTA de la home** al producto de la 133ª (o a `/formacion`).
   Es cambiar una URL: quita el 404 y abre la venta. **Hacer hoy.**
2. **Actualizar el copy del bloque**: "132ª Promoción / oposiciones 2025" →
   133ª, arranca el 1 de septiembre.
3. **Enlazar la landing `/formacion`** desde la home (botón secundario tipo
   "Infórmate sin compromiso") para alimentar LS01 con tráfico orgánico.
4. **Pegar el embed del chat** (está en el checklist de QA) para que el bot
   capte también en la web principal — subtarea 12.
5. Revisar si quedan más enlaces al producto 132 en otras páginas
   (`/preparacion-de-oposiciones/`, `/guardia-civil/`, novedades).

### ✅ ARREGLADO EN PRODUCCIÓN (10-ago) — el 404 ya no existe
Ensayado primero en staging por API de Elementor (4 checks verdes) y
**aplicado por el equipo en producción**, verificado por Claude: 0 enlaces
al producto muerto, 1 al de la 133ª, sin "132ª" ni "2025", y el botón
devuelve **HTTP 200**. Titular en vivo: "Curso Ingreso Guardia Civil – 133ª
Promoción". Ficha completa en
`entregables/arreglo_home_cta_133_2026-08-10.md`.

- ✅ **Remate hecho**: los dos "Oposiciones a Guardia Civil **2024**" de la
  sección de más abajo pasados a 2026 y verificados. La portada queda sin
  copy obsoleto.
- 📌 **Claude no puede autenticarse en producción desde este entorno**: no
  es cuestión de credenciales — `av-login` rechaza *todo* intento sin
  mensaje de error (probado con usuario inventado y con uno real), señal de
  un firewall/filtro por IP delante de WordPress. **Reparto de trabajo que
  funciona: el equipo aplica desde su navegador, Claude verifica por HTTP.**
- ⚠️ **Nunca** usar "push staging → producción" de WP Staging para llevar
  cambios: arrastraría la copia de julio sobre la web viva (pedidos y
  suscripciones incluidos).
- 🛠️ `tools/wp-fix-home-cta.py` queda disponible (dry-run por defecto,
  backup automático, `--restore`, idempotente) y funciona contra staging.
- 📌 Aprendizaje: `options-reading.php` no expone el select de portada en
  este sitio; la forma fiable de detectarla es la clase `page-id-N` del
  `<body>` de la home.

## Hecho hoy: preparar el re-enganche (el único activo con tracción inmediata)
`docs/entregables/reenganche_ls03_segmento_y_b2_2026-08-10.md` — todo lo que
faltaba para poder lanzar LS03 a los ex-alumnos de la 132ª:
- **Segmento definido: 69 contactos** (39 suscripción en baja + 19 suscripción
  activos + 11 intensivo), todos con email. **Excluidos 9** becados y de
  colegio a propósito: son altas manuales de Paco y no deben recibir oferta
  comercial. Script reproducible con DRY-RUN por defecto:
  `gohighlevel-cli/builders/av-reenganche-segmento.py`.
- **Email B2 escrito en dos versiones**: (A) con 3 huecos para las novedades
  de Paco, y (B) **autosuficiente sin Paco** — apoyada solo en hechos
  verificables (temario actualizado a la nueva convocatoria, ranking real,
  sin permanencia), para no quedarnos bloqueados si no contesta.
- **Fallo encontrado en B1**: su CTA apunta a `academiavalenz.com` (la home,
  que no enlaza el embudo) → el ex-alumno llega y no encuentra dónde
  apuntarse. Cambiar a la landing o al widget de asesoría.
- **Mensaje para Paco** redactado, con la petición concreta de 2 minutos.

## ⛔ Bloqueo de negocio antes de enviar nada
Los **39 "en baja"** cuadran con las **39 suscripciones pausadas en bloque el
24-jun** (`p2_causa_raiz_2026-07-10.md`). Si su baja es esa pausa
administrativa y no una decisión del alumno, B1 ("¿repetimos?") le llega a
gente que cree seguir dada de alta. **Confirmar en WooCommerce y decidir qué
se hace con esas suscripciones antes de lanzar LS03.**

## ⏭️ SIGUIENTE PASO (por impacto, no por comodidad)
1. ~~CTA roto de la home + copy obsoleto + widget del bot~~ ✅ **HECHOS Y
   VERIFICADOS** (10-ago). El bot ya capta desde la web principal: prueba
   end-to-end con oportunidad "Bot web" a los 5 min y 10 s (**subtarea 12
   cerrada**). Del mismo bloque queda **solo el botón "Infórmate sin
   compromiso" a `/formacion`**, que está **⏸️ a la espera de aprobación del
   cliente** (consulta enviada a Horacio).
   🟡 Menores detectados en la prueba: el saludo del widget sale en inglés, y
   `data-source="WEBSITE"` no llega a la oportunidad (se crea con la fuente
   "Bot web" de LS02) → para separar leads de web y funnel hace falta un
   campo o etiqueta propia.
2. **📧 Enviar el email a Horacio** —
   `entregables/email_horacio_decisiones_2026-08-10.md`, listo para copiar.
   Consolida las 6 decisiones del cliente (encabezadas por las 39
   suscripciones paradas, ~3.100 €/mes) y las 5 internas. **Es el paso que
   desbloquea todo lo demás.**
3. **Desbloquear y lanzar LS03**: decisión sobre las 39 pausadas → cerrar B2
   (Paco o versión B) → arreglar CTA de B1 → publicar + activar trigger →
   etiquetar (tanda de prueba primero). Checklist completo en el entregable.
4. **Perseguir a Paco** (17 días): novedades para B2 + horario de asesorías +
   qué hacer con los 7 becados.
5. **24-ago (14 días)**: deploy del plugin a producción — runbook verificado.
6. **1-sep**: modo real del plugin + encender RP02 y RP04.
7. Flecos del bot (10 min): textos del widget en castellano, quitar el texto
   puente del prompt, franjas reales del calendario.

---

# 🟢 SESIÓN 8 (7-ago, tarde) — RED DESBLOQUEADA + QA DEL BOT EJECUTADA POR NAVEGADOR

## Estado en una línea
La política de red por fin se amplió y, tras cazar un bug de TLS que llevaba
desde julio disfrazado de "Chromium no sale por el proxy", **se ejecutó la
batería de QA conversacional completa contra el bot real desde Chromium**:
pruebas 4, 5, 6 y 7 → **todas PASAN** (resultados con textos reales en
`entregables/qa_bot_ls02_checklist_2026-08-07.md`). La subtarea 14 del bot
queda hecha en su parte conversacional; solo falta la verificación CRM.

## 🔑 Aprendizaje clave: cómo usar el navegador en este entorno
El MITM del proxy **no completa el handshake TLS 1.3 de Chromium** (todo
ClientHello → 6 s de silencio → reset; curl sí pasa). El histórico
"ERR_CONNECTION_RESET en el handshake del MITM" de julio era esto. Arreglo:
**lanzar Chromium con `--ssl-version-max=tls1.2`**. Receta que funciona
(Playwright + navegador del entorno):

```python
p.chromium.launch(
    headless=True,
    executable_path="/opt/pw-browsers/chromium-1194/chrome-linux/chrome",
    proxy={"server": "http://127.0.0.1:37203"},   # $HTTPS_PROXY
    args=["--ssl-version-max=tls1.2"],
)
```

Trampas anotadas mientras se depuraba: el `--dump-dom` "con éxito" de antes
era en realidad **la página de error de Chromium** (~186 KB por el CSS
inline — verificar contenido, no tamaño); y `--disable-features=UseMLKEM`
ya no existe en Chrome 141 (para quitar el post-cuántico: política
`PostQuantumKeyAgreementEnabled: false` en
`/etc/chromium/policies/managed/` — con el fix TLS 1.2 no hace falta).

## Red (verificada 7-ago tarde) — actualiza la tabla de la sesión 7
| Dominio | Estado |
|---|---|
| `app.gohighlevel.com` | ✅ 200 (¡por fin! — login con reCAPTCHA, ojo) |
| `api.omniainbusiness.com` | ✅ 200 (el form embed del funnel carga) |
| `info.academiavalenz.com` | ✅ carga entera con el fix TLS |
| `widgets.leadconnectorhq.com` | ✅ (loader del chat OK) |
| `stcdn.leadconnectorhq.com` | ❌ **sigue vetado** — es el CDN estático: el bundle del funnel no carga y por eso el chat no se auto-inyecta; pedir añadirlo para rematar |
| `backend/services.leadconnectorhq.com` | ✅ (como siempre) |

Consecuencia del veto de `stcdn`: para la QA el widget se inyectó **a mano**
con el embed real (script en el checklist de QA; sirve tal cual para la
subtarea 12 de WordPress). El widget en sí funciona perfecto una vez cargado.

## Lo hecho hoy
1. **QA conversacional del bot (subtarea 14): 4/4 PASAN** — detalle y
   transcripciones en el checklist. Hallazgos menores: los textos de sistema
   del widget están en inglés ("Have a question?"…, se cambia en la config
   del widget), y el cierre sigue con el puente "te escribimos hoy" porque
   la subtarea 8 (Book Appointment) sigue pendiente.
2. **El fallo del despertar de la 1ª pasada NO se reproduce**: el bot
   contestó al primer mensaje en ambas conversaciones.
3. **Trigger de LS02 arreglado y VALIDADO end-to-end** (misma tarde): los
   contactos del QA se crearon pero el filtro del trigger *Customer
   Replied·Live Chat* estaba mal guardado (el formato a ciegas del API —
   riesgo que ya estaba apuntado). El equipo lo corrigió en la UI y el
   retest desde el chat de Lucía metió la oportunidad en
   **Captación/Cualificado** y entregó **los 3 avisos**. El circuito del
   bot (chat → contacto → oportunidad → aviso) queda probado entero.

> **Act. 7-ago noche:** re-entry OFF + IF de guarda aplicados por el equipo
> y **validados**. Y aplicada la **opción 3 del nombre de oportunidad**:
> LS02 = IF → aviso inmediato → **Wait 5 min** → Create Opportunity (ya con
> el nombre real) → Add Tag. Validado en real ("Diego Fuentes" a los 5 min).
> Todos los contactos de prueba borrados por ID.

### 📌 Trampa GORDA de la API descubierta (7-ago noche) — apuntar junto a la del PUT
**Los triggers tienen `targetActionId`: el paso EXACTO del workflow por el
que entra la inscripción.** Si cambias la cabeza del grafo (insertar/mover
el primer paso), los triggers siguen entrando por el paso viejo — y NINGÚN
save del workflow (API o UI) los repunta. Síntoma: editas el grafo y el
comportamiento no cambia jamás (nos costó 6 conversaciones de prueba).
Arreglo: `PUT /workflow/{loc}/trigger/{id}` con `targetActionId` = nuevo
primer paso. Corolario: el IF de guarda no se ejecutaba hasta este arreglo
(la entrada lo esquivaba); el candado real era `allowMultiple=false`.

### ⚠️ Credenciales del entorno: son de OTRO workspace
Las env vars `GHL_*` que trae el entorno (location `DjVejJurmfmaPhDlDkBg`)
**no son de Academia Valenz** — no usarlas. El token bueno se pega en chat
→ `gohighlevel-cli/.env` (pisar las env vars al ejecutar: `set -a; . .env`).
Con cabecera **`Version: 2021-07-28`** la API interna también sirve
contactos y oportunidades (search/delete) — ver sesión 8 del historial.

> **Act. 21:49:** filtro *Reply Channel = Live Chat* repuesto por el equipo
> y smoke test final ✅ (oportunidad "Alba Serrat" a los 5 min exactos).
> **LS02 queda CERRADO y validado de conversación a CRM.**

### ✅ AGENDAMIENTO POR EL BOT PROBADO (7-ago, 22:00) — ¡la subtarea 8 ya estaba cableada!
En la conversación de prueba el bot **ofreció huecos reales** del calendario
`Asesorías 133ª` ("lunes 10-ago 8:00/8:15/8:30/8:45" — sirve el horario por
defecto: el quirk de las franjas sigue pendiente) y al aceptar **creó la
cita de verdad** (verificada por API, autoconfirmada, asignada a German) →
**SP02 disparó solo**: tag `asesoria-agendada` + oportunidad en Agendado.
Bonus validado: el candado *Allow multiple opportunities OFF* hizo que el
Create de LS02 (que vencía después) NO duplicara — el lead quedó con UNA
oportunidad, la de Agendado. **El embudo del bot funciona de conversación a
cita.** Cita y contacto de prueba borrados por API.

### ✅ BT 01 · Momento del Lead ARREGLADO Y VALIDADO (7-ago, 22:30)
El workflow del equipo (lee el CF texto "Momento Lead bot" que escribe el
bot → pone la opción del select "Momento del lead") mandaba TODO por la
rama None. Tres causas, las tres corregidas por API:
1. **Mapeo descuadrado**: 3 de 4 ramas ponían la opción equivocada.
2. **Frases exactas vs texto libre**: el bot a veces escribe el valor
   canónico ("quiero empezar") y a veces el texto del lead ("estoy
   empezando de cero"). Ahora cada rama matchea por raíces de palabra
   (empez/cero/pensando/informaci/plante…).
3. **Carrera**: con escrituras del bot, el IF se evaluaba antes de poder
   leer el valor → **Wait 2 min** insertado entre trigger e IF (con el
   trigger repuntado al Wait, lección targetActionId).
Validación final: texto libre por API → 2 min → "Empezar con la 133ª" ✓.

**✅ Remate (23:13): reestructurado a 4 ramas = 4 opciones del select**
(propuesta del equipo). La rama redundante "lo estoy pensando" se
reconvirtió en **"ya preparandola"** (prepar / por mi cuenta / otra
academia / me presenté…) y sus keywords de duda se fusionaron en
"planteandomelo". Todo por atributos (sin tocar el grafo). Doble
validación quirúrgica en verde: "ya me estoy preparando por mi cuenta" →
**Ya preparándola** ✓ y "me lo estoy pensando todavía" →
**Planteándomelo** ✓, ambos a los 2 min exactos. BT 01 cerrado: las 4
respuestas del bot caen cada una en su opción.

### 📌 Más lecciones de API (para el manual de trampas)
- **Condiciones de if_else**: los `segments` de una rama se combinan con
  **AND**; el OR va DENTRO de un segmento (`operator: "or"` + N
  conditions). Meter keywords como segmentos separados = AND accidental.
- **Un paso Wait creado por API SÍ ejecuta** (probado en BT 01 y LS02).
- Escribir custom fields: `PUT /contacts/{id}` con
  `{"customFields": [{"id": ..., "field_value": ...}]}` (otros formatos → 422).
- El GET de contacto devuelve los CF a veces en `customField` y a veces en
  `customFields` — leer AMBAS claves.
- Borrar citas: `DELETE /calendars/events/appointments/{id}`.

## ⏭️ SIGUIENTE PASO
1. **Horario real del calendario** `Asesorías 133ª`: sigue sirviendo el
   default 08:00-16:45 (el bot ofreció las 8:00 de la mañana) — meter las
   franjas reales de Paco en Availability cuando las dé.
2. **Textos del widget en castellano** (config del Chat Widget: "Have a
   question?", mensajes de sistema).
3. **Quitar el texto puente** del prompt Objetivo del bot ("el equipo te
   escribe hoy") ahora que Book Appointment funciona — que ofrezca cita
   siempre.
4. **Token de Firebase fresco** → `gohighlevel-cli/.env` (no sobrevive entre
   sesiones) para trabajo de GHL por API en la próxima sesión.
5. (Opcional) pedir `stcdn.leadconnectorhq.com` en la allowlist para que las
   páginas del funnel carguen enteras sin inyección manual.

---

### ✅ BOT CREADO POR EL EQUIPO + LS02 PUBLICADO Y PROBADO (7-ago)
El equipo montó el bot en Conversation AI (con los prompts de
`bot_ls02_prompts_2026-08-07.md`). A continuación se publicó **LS02** por
API (receta segura; trigger `lead-bot` activo) y se probó con contacto de
prueba: tag `lead-bot` → oportunidad en **Captación/Cualificado** con
fuente "Bot web" + aviso interno a los 3 ✓. Prueba limpiada al momento.
**Los 7 carriles del embudo quedan operativos.** Restan del bot: widget en
WordPress (subtarea 12) y la batería de QA conversacional (subtarea 14) —
la parte de conversación solo se puede probar chateando.

# 🟠 SESIÓN 7 (6-ago-2026) — AUDITORÍA REAL DE LOS WORKFLOWS

## Estado en una línea
Con el token de Firebase se ha auditado **la definición guardada de los 7
workflows** (no el plan: lo que hay en la sub-cuenta) y han salido **dos
fallos reales que nadie había detectado**: SP02 tiene el trigger equivocado y
los avisos internos no tienen destinatario. Informe completo en
`docs/entregables/auditoria_workflows_ghl_2026-08-06.md`.

> ⚠️ **Corrección de fechas**: la sesión 6 **no** terminó el 24-jul. El 24-jul
> es cuando se crearon los workflows en GHL; las ediciones de la sesión 6
> están selladas el 6-ago 22:05–22:12 y su commit a las 22:33, cinco minutos
> antes del primer commit de esta sesión. No hubo parón de 13 días.

## Verificado hoy (esto es lo nuevo)

### ❌ La red NO se amplió — corrige el punto 5 de la sesión 6
Se dio por hecho que el equipo había añadido los dominios el 24-jul. **No es
así.** Comprobado con `curl` y con el estado del proxy (`403 al CONNECT`):

| Dominio | Estado |
|---|---|
| `app.gohighlevel.com` | ❌ 403 (bloqueado por política) |
| `api.omniainbusiness.com` (whitelabel) | ❌ 403 |
| `info.academiavalenz.com` (landing publicada) | ❌ 403 |
| `sites.ludicrous.cloud` | ❌ no resuelve/alcanza |
| `widgets.leadconnectorhq.com` (scripts del chat) | ❌ vetado (confirmado 7-ago) |
| `services/backend.leadconnectorhq.com` | ✅ alcanzables |
| `academiavalenz.com` + `/staging` | ✅ 200 |
| `api.evolcampus.com` | ✅ alcanzable |

→ **Consecuencia**: no se puede auditar visualmente la UI de GHL ni ver la
landing publicada. Sigue pendiente confirmar si el nodo `internal_notification`
se dibuja en LS01/LS02 (el mismo problema que tuvo `workflow_goal`).
→ **Acción**: pedir al equipo que añada esos dominios a la política de red
del entorno. Los cambios **no aplican a sesiones ya abiertas** — hay que abrir
sesión nueva después.
→ **Para poder probar el bot desde el navegador del entorno** (Chromium +
Playwright ya instalados), la lista mínima es:
`info.academiavalenz.com` + `widgets.leadconnectorhq.com` (y opcionalmente
`app.gohighlevel.com` para conducir la UI, aunque su login lleva reCAPTCHA).
Con esas dos primeras, Claude puede abrir /formacion, chatear la batería de
QA completa contra el bot real y verificar el CRM en el mismo turno.

### ✅ Credenciales recibidas — API interna operativa
El equipo pegó el `GHL_FIREBASE_REFRESH_TOKEN` en el chat (guardado en
`gohighlevel-cli/.env`, gitignorado). Confirmado que `securetoken.googleapis.com`
(refresco) y `backend.leadconnectorhq.com` (API interna) **sí** son
alcanzables: se puede trabajar por API aunque la UI siga vetada.
Falta el PIT (`GHL_API_KEY`) para lecturas por API pública.

### 🔴 Hallazgos de la auditoría (lo importante de esta sesión)
1. **SP02 tiene el trigger equivocado**: dispara con el `form_submission` del
   formulario de la **landing** (el mismo que LS01), no con una cita agendada.
   Está en draft con el trigger inactivo, así que hoy no hace daño — pero
   **publicarlo tal cual etiquetaría como "asesoría agendada" a todo lead de
   la landing**. NO PUBLICAR SP02 hasta rehacer el trigger.
2. **Los avisos internos no llegan a nadie**: el nodo `internal_notification`
   sí está guardado en LS01/LS02 (pregunta de la sesión 6 respondida), pero
   con `recipients: []` + `assigned_user: true` y ningún paso que asigne
   usuario → destinatario vacío. Falta decidir quién debe recibirlos.
3. **Cero salidas IF en los 7 workflows**: los remates de UI nunca se
   hicieron. Crítico en **SP01, que está publicado y corriendo**: un lead que
   ya agendó sigue recibiendo "A4 Última llamada".
4. **Los 2 workflows LEGADO siguen publicados** (despublicar antes del 1-sep).
5. LS01 arrastra un trigger redundante y autorreferente (riesgo bajo:
   `allowMultiple=false` lo neutraliza).

### ✅ Verificación pre-deploy del plugin (hecha en frío, sin credenciales)
Contrastado el runbook contra el código de `evocampus-subscription-sync.php`
v0.8.0: **concuerdan**. `OMNIA_EVO_GRACE_DAYS = 38` (ciclo 31 + 7 días de
cortesía, política A6 de Paco). Detalle tranquilizador: si no se define
`OMNIA_GHL_DRYRUN`, **hereda** `OMNIA_EVO_DRYRUN` — o sea, un olvido en el
paso 4 del runbook no dispara escrituras reales en el CRM. El runbook no
tiene huecos; se puede ejecutar tal cual.

### ⚠️ El tablero de ClickUp está desincronizado y vencido
- **Fechas vencidas** (hoy 6-ago): LS01 landing (-9d), SP02 (-9d), Setup
  email+calendario (-7d), remates SP01/LS03 (-7d), gestoría (-6d), bot LS02
  (-3d), RP02-RP03 (-3d), campaña LS03 (-2d).
- **Descuadre de fechas**: ClickUp fecha el deploy a producción **hoy 6-ago**,
  pero el runbook y el plan dicen **24-ago**. Hay que unificar.
- **Tarea mal marcada**: "01 · LS01 · Montar landing + formulario" sigue en
  *to do* cuando LS01 está VIVO y validado desde el 24-jul. En cambio SP02
  figura *complete* cuando está en borrador esperando el calendario.
- Lo que sí queda con margen real: AP02 modo real (28-ago), demo a Paco
  (31-ago/4-sep), cierre (7-sep).

### ✅ Aplicado en esta sesión
- **Los 2 LEGADO despublicados** (pasados a `draft`, pasos intactos).
- **SP03 · Salida de secuencias creado en DRAFT**
  (`da930cd6-cb80-4a73-8675-2ce56b55a112`): triggers por `asesoria-agendada`
  / `matriculado-133` / `no-133` → saca al contacto de SP01 y LS03. Resuelve
  el problema de "A4 Última llamada" **sin tocar SP01**.
- **Trigger redundante de LS01**: ya no está (lo quitó el equipo en paralelo).
- Integridad verificada: los 9 workflows conservan todos sus pasos.

### ⚠️ Trampa de la API descubierta (apuntar antes de tocar nada)
`PUT /workflow/{loc}/{id}` **reemplaza la definición entera**: un PUT con solo
`{name, version, status}` **borra todos los pasos**. Pasó con el LEGADO
Baja/Impago (0 pasos; restaurado desde el `fileUrl` de la versión anterior,
que sigue siendo descargable). **Todo PUT debe llevar
`workflowData: {"templates": [...]}`.**

### ✅ SP03 PUBLICADO (7-ago) — problema de "A4 Última llamada" resuelto
Verificado en la UI (se dibuja entero) y publicado por el equipo, con los 3
triggers activos. Aprendizaje API: la UI lee `workflow_id` (array), no
`workflows` — los pasos `remove_from_workflow` de futuros builders deben
llevar **ambos** campos para no salir con aviso naranja.

### ✅ SP02 ARREGLADO Y PUBLICADO (7-ago, por el equipo en la UI)
El trigger equivocado (`form_submission` del formulario de la landing) fue
sustituido por `customer_appointment` (*Customer Booked Appointment*),
activo. SP02 quedó **publicado**: hoy no puede dispararse (no existe el
calendario), y cuando exista la cadena queda cableada sola:
cita → SP02 etiqueta `asesoria-agendada` → SP03 saca al lead de SP01/LS03.
Matiz apuntado: el trigger no filtra por calendario — con un solo calendario
es correcto; si se añade otro (p. ej. tutorías), añadirle el filtro
*In calendar = Asesorías*.

### ✅ Avisos internos con destinatario (7-ago)
`recipients` de los pasos `internal_notification` de LS01 y LS02 relleno con
los 3 del equipo (german.borrello@, henry.buenano@, oliver.guerrero@omibu.com)
y `assigned_user` desactivado. LS01 siguió `published` tras el PUT (llevaba
`status: published` explícito + `workflowData` completo — la receta segura).
**Vistazo de UI recomendado**: si el paso muestra aviso naranja (como pasó en
SP03 con `workflow_id`), reseleccionar los 3 usuarios en el panel y guardar.
Con la lección aprendida: la prueba definitiva será el próximo lead real de
la landing — deben llegar 3 emails de aviso.

### ✅ Avances del 7-ago (tarde)
1. **RP04 · Salida de dunning creado en draft**
   (`bdbe63e2-1186-4377-8307-fc65dfb550ce`): triggers `alumno-recuperado` +
   `alumno-activo` → saca de RP02. Mismo patrón que SP03, ya con
   `workflow_id` incluido (sin aviso naranja). **Publicar el 1-sep junto a
   RP02** — sin esto, un alumno que paga seguiría recibiendo el dunning.
2. **Prueba end-to-end ejecutada** con contacto real de prueba
   (german.borrello@omibu.com, id `XQn1u2fdsGX5KKSM9YbQ`): inscrito en LS01
   por API → tag `lead-landing-133` ✓, oportunidad "Prueba Claude E2E" en
   Captación/Nuevo lead con fuente "Landing 133" ✓, aviso interno disparado
   hacia los 3 ✓ (verificar buzones), y el tag debió inscribirlo en SP01
   (verificable: email A1 en el buzón). Después se aplicó `asesoria-agendada`
   → SP03 debió sacarlo de SP01 (verificar en Execution logs de SP03/SP01).
   **Nota**: el envío del formulario público no se pudo simular (Cloudflare
   bloquea el endpoint desde este entorno); el disparo por formulario quedó
   validado con datos reales el 24-jul y no se ha tocado.
   **✅ PRUEBA CONFIRMADA por el equipo**: llegaron los 3 avisos internos y
   el email A1 de SP01 — el arreglo de destinatarios funciona y el tag
   inscribe en SP01. **Limpieza hecha**: borrados los 2 contactos de prueba
   con sus oportunidades (el de hoy y el de la sesión 6,
   germanborrello@gmail.com). El CRM queda sin datos de prueba.
3. **Prompts del bot LS02 escritos** (subtareas 4-6 de ClickUp listas para
   pegar): `docs/entregables/bot_ls02_prompts_2026-08-07.md`. Incluye
   ajustes del bot, reglas de handoff, datos reales con los `[PENDIENTE]`
   marcados, y notas para las subtareas 7, 8, 11, 13 y 14. De las 14
   subtareas del bot, solo la 8 (Book Appointment) espera el calendario.

### ✅ CALENDARIO DE ASESORÍAS CREADO (7-ago, provisional)
**"Asesorías 133ª"** (`HV3DnVxagoNduXbNx0UG`), activo, creado por API con
horario provisional a petición del equipo ("cualquier horario, luego lo
corregimos"):
- Citas de **15 min**, solo **L-V**, autoconfirmación, reagendar/cancelar
  permitido, reservas hasta 30 días vista.
- Dueño provisional: **German** (cambiar a Paco cuando tenga usuario).
- Slug del widget: `asesorias-133` (URL patrón:
  `https://api.omniainbusiness.com/widget/booking/HV3DnVxagoNduXbNx0UG`).
- ⚠️ **Las franjas horarias no se aplicaron**: los bloques 10-14/16-20 se
  guardaron en `openHours` pero el motor de huecos sirve el horario por
  defecto (08:00-16:45). Corregirlo en la UI al meter el horario real de
  Paco (Availability → editar → guardar reescribe el formato bien).

**Lo que desbloquea**:
- **SP02 PROBADO EN REAL (7-ago)** ✅: cita de prueba agendada por API
  (lunes 10-ago 10:00, id `bwrsdSziKmnoaq2XWCip`, contacto
  `mCvZ8vuUIQ5KdzbOJ65M` / german.borrello@omibu.com). SP02 disparó solo:
  tag `asesoria-agendada` + oportunidad en **Captación/Agendado** con
  fuente "Asesoría agendada"; el tag encadenó SP03. Todo el circuito de
  conversión validado. **Limpieza pendiente**: borrar cita + contacto +
  oportunidad cuando el equipo lo vea (la cita se ve en el calendario de
  la UI; German debió recibir la confirmación por email).
- **Subtarea 8 del bot (Book Appointment)** ya tiene calendario que apuntar
  → las 14 subtareas del bot quedan desbloqueadas.
- La landing puede enlazar "RESERVAR ASESORÍA GRATUITA" al widget.
- Del Setup solo queda WhatsApp (Meta) — email ✓, calendario ✓ (provisional).

### ⚠️ Edición concurrente
Durante la sesión alguien del equipo estaba editando la cuenta a la vez
(LS01 cambió a las 22:43 bajo la cuenta de German Borrello). Coordinarse
antes de escribir por API para no pisarse.

## ⏭️ SIGUIENTE PASO — 3 desbloqueos, por orden de impacto

1. **Token de Firebase fresco** (extensión Chrome del CLI, 10 s) + PIT →
   `gohighlevel-cli/.env`. Sin esto no hay trabajo de GHL posible.
2. **Ampliar la política de red** con `app.gohighlevel.com`,
   `api.omniainbusiness.com` e `info.academiavalenz.com`, y **abrir sesión
   nueva** después.
3. **Perseguir a Paco** — el mensaje lleva 13 días listo y sin respuesta en
   `docs/entregables/mensaje_paco_pendientes_2026-07-24.md`: su horario de
   asesorías es lo que desbloquea calendario → SP02 → cierre de Setup. Es
   ahora la ruta crítica más larga.

Con esos tres, el orden de ataque es: calendario+SP02 → remates de UI
(salidas IF, mensaje de gracias, despublicar los 2 LEGADO) → deploy del
plugin en producción → 1-sep modo real + RP02/RP03 con la 133ª.

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
- **Landing viva**: https://info.academiavalenz.com/formacion — ⚠️ URL cambiada el 7-ago: el step /landing ya no existe (HTML en
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

---

# 📌 SESIÓN 25-ago-2026 — Deploy en producción verificado (DRY-RUN)

## Lo que se hizo

El equipo desplegó el plugin **v0.8.0 en producción** junto al mini-plugin de
config `00-omnia-evo-config/`, y ejecutó los dos informes. **El deploy pasa.**

| Check | Resultado |
|---|---|
| "Modo: DRY-RUN · Ventana de pago: 38 días" | ✅ |
| Conciliación | ✅ **71 de 71 alumnos en 15 s**, sin errores |
| Informe de acceso sin pago | ✅ 35 filas |
| Censo cuadrado contra pedidos de Woo | ✅ 16 activos = los 16 pedidos de 48 € |

**Entregable completo con toda la evidencia:
`docs/entregables/dryrun_produccion_2026-08-25.md`.**

## 🔴 Los dos bloqueos NUESTROS del modo real

### 1. El plugin cortaría a 8-13 de los 16 alumnos nuevos en septiembre

El producto se vendió como **80 €/mes + 48 € de cuota de registro + 1 mes de
prueba + facturación sincronizada al día 1**. Confirmado en la suscripción
`#2088`: compra 14-ago, fin de prueba 14-sep, **siguiente pago 1-oct**.

`reconcile()` mide **"días desde el último pedido pagado ≤ 38"**. De 14-ago a
1-oct hay **48 días** → **corte el 22-sep**, nueve días antes de que le toque
pagar, con tag `alumno-impago` + oportunidad de recobro + dunning.

**Es estructural, no del lanzamiento**: quien compre el 2-sep tiene 60 días hasta
su primer cobro (1-nov). Mientras haya prueba + sincronización, el plugin cortará
a quien compre a principios de mes.

**Arreglo (v0.8.1)**: preguntarle a la suscripción, no a la fecha del último
pedido. Si `next_payment` está en el futuro o sigue en prueba → activo. La
ventana de 38 días queda como red de seguridad.

### 2. El primer pase real dispara 55 avisos de golpe

`omnia_evo_verdicts` no se persiste en DRY-RUN (deliberado), así que la primera
pasada real notifica a los 71: **55 tags de impago + 55 oportunidades de
recobro**. Y **32 de esas 55** llevan 67-86 días sin pagar porque el lote del
**24-jun** les paró la suscripción — no impagaron.

**Arreglo (v0.8.1)**: persistir veredictos también en DRY-RUN, con botón
"Olvidar veredictos" para forzar el aviso completo si alguna vez se quiere.

> ⚠️ **Hasta que esté el parche, seguir en DRY-RUN.** La mitigación de dejar
> `OMNIA_GHL_DRYRUN = true` con `OMNIA_EVO_DRYRUN = false` sirve para el bloqueo
> 2 pero **NO para el 1**: el corte del campus sería real.

## 🟡 Otros hallazgos

- **IVA**: los pedidos salen sin ninguna línea de impuesto (`#2087`: subtotal
  48 €, total 48 €, `is_vat_exempt: no`). Se vende como exento sin confirmación
  de la gestoría. Expuesto hoy: 768 € (≈133 € de IVA) + el atrasado desde
  nov-2025. **Ha pasado a ser urgente.**
- **Huso horario**: el WordPress está en `America/Buenos_Aires`, 5 h por detrás
  de Madrid. Rompe la serie **mensual** de facturación en los cambios de mes
  (una venta del 1-oct a las 02:00 de Madrid se fecha el 30-sep). Argumento
  adicional para la serie anual.
- **30 alumnos `ENTREVISTA 2026 PROMOCIÓN 132GC`** con acceso activo y cero
  registro en Woo, conectándose esta semana. Altas manuales posteriores al examen
  de julio. **Sin riesgo técnico** (la conciliación no los ve, el informe es de
  solo lectura). Decidido: **pregunta neutra** en la reunión, no hallazgo de fuga.
- **Becados 2 de 7 es correcto**: `has_woo_footprint()` descarta antes a quien
  tenga usuario WP o pedido. El check del runbook estaba mal formulado.
  Añadido `info@academiavalenz.com` al `.example` de autorizados.
- 🟢 **La 133ª vende**: 16 pedidos del 14 al 25-ago, acelerando (4 el 24, 3 el
  25), tras **cero pedidos desde el 19-jun**. El 404 de la home se arregló el
  10-ago y la primera venta es del 14-ago — secuencia, no causalidad probada.
- 🟢 Los pedidos capturan **DNI, segundo apellido, dirección y teléfono**: la
  facturación automática tiene todo lo del art. 6 sin pedir nada más.

## Trampa de API / entorno aprendida hoy

La ficha pública del producto **no muestra el precio de WooCommerce**: cero
`class="price"`, cero `woocommerce-Price-amount`. El "~~80€~~ 48€*/mes" es texto
de Elementor. Para saber el precio real hay que mirar la pantalla de datos del
producto o un pedido — **no fiarse del HTML de la ficha**.

## Próximos pasos

1. **Reunión con el cliente (26-ago)** — guion en
   `docs/entregables/checklist_deploy_26ago.md`. Tres preguntas: IVA, curso de
   entrevista, 39 suscripciones. **No prometer el 1-sep para modo real.**
2. **Parche v0.8.1** con los dos arreglos, probado en staging, antes del modo
   real.
3. 🔴 **Corregir el precio del bot en GHL — corre.** Su prompt dice "80 €/mes"
   mientras la web ofrece **48 € el primer mes hasta el 31-ago**. Cita un precio
   un 67 % más alto que la oferta y contradice a la web. Texto listo para pegar
   en `bot_ls02_prompts_2026-08-07.md`. **Y quitarlo el 1-sep**, o ofrecerá un
   descuento caducado.
4. **Enviar el email a Horacio** (`email_horacio_decisiones_2026-08-10.md`,
   actualizado hoy) y la respuesta de facturación al cliente.
5. 🔴 **EL BOT ESTÁ CAÍDO desde el 19-ago** — siete conversaciones perdidas, la
   última hoy. Ventana del corte: entre el 18-ago 07:22 y el 19-ago 18:33.
   Primero mirar los **créditos de IA** de la sub-cuenta. Y las "12 oportunidades"
   no son 12 leads: 10 son incontactables y 1 es prueba nuestra. Todo en
   `docs/entregables/bot_caido_desde_19ago_2026-08-25.md`.
6. Menor: limpiar el campo "Precio rebajado" del producto (vacío pero con fechas
   6-ago → 31-ago) y corregir el huso horario del sitio.

## Reglas que siguen vigentes
- NUNCA desactivar DRY-RUN sin pasar el checklist de `wp-plugin/README.md` —
  y ahora, además, **sin el parche v0.8.1**.
- Secretos solo en .env/wp-config (gitignorados) — nunca commiteados.
- Borrar contactos de prueba **por id exacto, nunca por búsqueda de nombre**.
- **Nunca** usar el "push staging → producción" de WP Staging.

---

# 📌 LLAMADA CON PACO — 26-ago-2026

Acta completa: **`docs/entregables/llamada_2026-08-26_acuerdos.md`**
Grabación: `https://fathom.video/share/Czxtx44ywhF1Tezx98rTU8jpQ3tq8pCs`

## 🔴 Lo que corre: no pueden re-matricularse

Ex-alumnos de la 132ª que quieren volver **no pueden completar la compra**: al
meter su email, la tienda dice que ya existe cuenta y les corta. Caso concreto
dado por Paco: **Óscar Vargas Balboa**. Uno lo resolvió creando **otro usuario
con otro correo** — cuenta duplicada y acceso doble al campus.

**Diagnóstico — confirmado en producción** (Henry, 26-ago). Producción no
coincidía con staging; la causa es más simple:

| Casilla · Cuentas y privacidad | Staging | **Producción** |
|---|---|---|
| Activar el pago como invitado | off | **ON** |
| Activar el inicio de sesión durante el pago | off | **off** ❌ |

La ayuda de WooCommerce lo dice: *«adquirir una suscripción requiere tener una
cuenta»*. Así que el ex-alumno entra como invitado, WooCommerce le exige cuenta,
la crea con su email, ya existe, le manda a iniciar sesión — y no hay formulario
de login en el checkout. Sin salida.

**Arreglos, los dos aplicados en producción el 26-ago:**

1. ✅ **La causa:** marcada «Activar el inicio de sesión durante el pago».
   El pago como invitado se queda como está.
2. ✅ **Higiene:** «Limitar suscripción» del `#2054` (pestaña **Avanzado**, no
   General) pasa de «una activa» a **«No limitar»**.

**Corrección sobre el punto 2:** escribí que bloqueaba «por partida doble» a los
ex-alumnos. No es cierto — el límite es **por producto**, y los de la 132ª vienen
de otro producto. Tampoco encaja el síntoma: el límite retira el botón de
«Añadir al carrito», no da el error del correo. Se apaga igualmente porque a
quien le falle un cobro en la 133ª le impediría volver a comprarla.

✅ **Verificado el 26-ago**, en el HTML y en el navegador en incógnito: el aviso
«¿Ya eres cliente? Haz clic aquí para acceder» sale en su propio recuadro encima
del formulario, con acceso y enlace de contraseña olvidada. **El muro está
caído.**

**Para probarlo hay que estar deslogueado**: con sesión iniciada el aviso no se
renderiza, y eso costó dos intentos. Un wp-admin abierto en otra pestaña basta
para falsear la prueba.

⚠️ **La fricción que queda:** los 32 no recordarán su contraseña. El enlace
«¿Olvidó su contraseña?» está ahí, pero **hay que decírselo en la llamada**, o se
atascan en el mismo punto. Guion en el acta.

## 🔴 El precio de lanzamiento no caduca solo

Revisando el `#2054` en producción: **«Precio rebajado» está vacío** pero
«Fechas del precio rebajado» tiene 6-ago → 31-ago. Esa programación no aplica
nada. Los 48 € están puestos como **cuota de registro**, un campo fijo sin
fechas.

**El 1 de septiembre no sube nada solo.** Pero al mirarlo salió algo peor que el
precio.

### 🔴 El hueco de pagos llega a 60 días, no a 36

Prueba gratuita de 1 mes **+** sincronización al día 1: WCS aplica la prueba y
luego salta al **siguiente** día 1. Quien compre el 2-sep no paga nada hasta el
**1-nov** — sesenta días.

La ventana de gracia del conciliador son **38 días** (`OMNIA_EVO_GRACE`). Ese
alumno saldría como **baja** con la suscripción impecable, y en modo real
perdería el acceso al campus.

**Esto convierte el parche v0.8.1 en bloqueante para quitar el DRY-RUN**, no en
una mejora conveniente.

### ✅ v0.8.1 escrito y probado (26-ago)

**Corrijo lo que escribí arriba hace unas horas:** dije que el veredicto tenía
que venir del *estado de la suscripción*. **Es incorrecto para esta tienda**, y
el propio plugin lo documenta desde julio — el estado no refleja el pago, las
suscripciones viven «en espera» mientras se cobra por Redsys. Basarse en él
daría de baja en bloque a alumnos que están pagando.

Lo que se ha hecho es más pequeño y más seguro: **la ventana se deriva del
calendario de cada suscripción**.

1. Si la suscripción declara fecha de próximo cobro, manda esa fecha más los 7
   días de cortesía de A6 (`OMNIA_EVO_COURTESY_DAYS`).
2. Si no la declara (impago, cancelada, expirada), sigue la regla vieja por días
   desde el último pedido pagado (`GRACE_DAYS = 38`) — que es la que da de baja
   correctamente a las 39 de junio.

**Probado aquí**, 16 casos en verde sin necesidad de WordPress:

```
php wp-plugin/tests/test-verdict.php
```

Cambian 5 veredictos respecto a v0.8.0, todos revisados: los tres de prueba
gratuita con cobro sincronizado pasan de baja a **activo** (el arreglo), el
cobro vencido hace 8 días pasa a **baja** (cortesía exacta de 7 en vez de 7-8
según el mes), y quien nunca pagó pero tiene cobro previsto futuro pasa a
**activo** (altas manuales y pruebas sin cuota de entrada).

**Incluye también la siembra de veredictos**, que era el otro bloqueo: hasta
ahora en DRY-RUN no se guardaban, así que el primer pase real habría avisado a
GHL de los ~55 alumnos de golpe. Botón nuevo: «Sembrar veredictos (sin avisar a
GHL)».

### ✅ Probado en staging el 26-ago

Plugin actualizado de 0.8.0 a 0.8.1 en `academiavalenz.com/staging` y validado.
Parte completo: `entregables/staging_v081_2026-08-26.md`.

- **Sin regresión:** 59 de 59 alumnos mantienen su veredicto.
- **La rama nueva decide en 40 de 59 casos reales.** Corrijo una suposición mía:
  WooCommerce **no borra** la fecha de próximo cobro al pasar a *En espera*, la
  conserva. La rama del calendario es el camino normal, no la excepción.
- **Fecha dentro de la ventana → `activo`**, verificado con datos reales
  subiendo temporalmente una copia con la cortesía en 90 días, sin manipular
  ninguna suscripción.
- **Siembra:** `59 sembrados, 0 avisos enviados`, y la pasada siguiente da
  **0 avisos a GHL**. El estallido de ~55 notificaciones queda eliminado.

### 🔴 Aviso que salió de la prueba

`OMNIA_GHL_DRYRUN` es independiente de `OMNIA_EVO_DRYRUN`, y **no hay
GoHighLevel de staging**: el PIT y los webhooks del staging apuntan al CRM de
producción. En staging esa constante no está definida, así que hereda el
DRY-RUN de EvoCampus y estábamos a salvo — pero el día que alguien ponga
`OMNIA_EVO_DRYRUN = false` ahí, **el espejo se activa contra el CRM real en el
mismo movimiento**.

v0.8.1 muestra ahora los dos modos en la cabecera de la página, con aviso en
rojo si el espejo está en vivo.

Corrijo lo que escribí antes: dije «36 días, pasa por dos». Era mirando solo el
caso del 26 de agosto.

Detalle, tabla de fechas y las tres opciones de reconfiguración:
`entregables/precio_lanzamiento_caduca_31ago.md`.

## ✅ Seis pendientes cerrados

| | |
|---|---|
| Nombre fiscal | **Francisco Valenzuela Rodríguez** (hijo de Paco, el autónomo) |
| NIF | `26956058N` |
| Domicilio fiscal | **Camino de Ronda 57, 2º F · 18004 Granada** |
| Horario asesorías | **9:00-13:00 y 17:00-20:00** · 30 min · 24 h de antelación |
| Usuario CRM | **gestion@academiavalenz.com** — invitación enviada |
| Becados | Confirmado: altas manuales de Paco. Es justo lo que ya hace el plugin |

**La factura va a nombre de Francisco**, no de «Academia Valenz», que entra solo
como logo. Van a pasar el pack de logos (el actual tiene fondo verdoso).

## ⚠️ El IVA no está cerrado

Paco dijo de palabra que está exento y con eso avanzamos, **pero no es la
gestoría**. Si se equivoca son el 21 % de todo lo vendido hacia atrás. Queda
como **riesgo asumido**: pedir el respaldo escrito antes de emitir a volumen.

## 🔄 Corrección: la pausa de junio fue un regalo, no un error

Escribimos que a los 32 se les dejó de cobrar por «un error administrativo».
**Falso.** Paco: *«eran simplemente unos 11 días del mes de julio, quisimos
tener el detalle de que no los pagaran, como regalo»*. Corregido en
`suscripciones_pausadas_2026-08-26.md`. Cambia el guion de la llamada de
recuperación: no hay nada que disculpar.

## Contenido nuevo para web y bot

**El curso completo son CUATRO formaciones**, y la web no lo dice — por eso
preguntan por Instagram: conocimientos (la más extensa), ortografía y gramática,
psicotécnicos e inglés. Y **la suscripción corre mes a mes hasta el examen**
(junio o julio), con el temario liberado poco a poco. Ya metido en el prompt del
bot y en la landing.

## Pendiente de otros

- **Henry**: conexión de WhatsApp (~15 min). ⚠️ El número **no puede quedar
  facturado a la agencia** — la tarjeta la ponen ellos.
- **Paco**: Business Manager de Facebook · Google My Business · una llamada corta
  para conectar su Google Calendar estando él logueado.
- **Sin resolver**: quién apagó el bot el 18-ago. Nadie lo sabía en la llamada.
