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
- [x] La página carga y dice **"Modo: DRY-RUN … Ventana de pago: 38 días"**
- [x] "Ejecutar conciliación ahora" → **71 de 71 alumnos en 15 s**, sin errores
- [x] "Generar informe de acceso sin pago" → 35 filas, etiquetado correcto
- [x] Sin errores PHP en pantalla ni en el log

> ⚠️ **El check de los becados estaba mal formulado.** Decía "los 7 becados salen
> como *Becado (autorizado)*", pero solo pueden salir los que **no tengan ningún
> rastro en la tienda**: `has_woo_footprint()` descarta antes a quien tenga
> usuario WP o algún pedido. Salen **2 de 7** y es el comportamiento correcto.
> Lo que hay que comprobar es que **los que aparecen estén bien etiquetados** — y
> lo están.

**6. Avisar a Claude** → ✅ hecho el 25-ago. Resultados y hallazgos completos en
**`dryrun_produccion_2026-08-25.md`**.

## Rollback

Desactivar el plugin principal. Todo vuelve al instante: no altera datos de
WooCommerce y en DRY-RUN ni siquiera llama a EvoCampus.

## ✅ ESTADO 25-ago: desplegado y verificado

El plugin está **activo en producción en DRY-RUN** y sus dos informes se han
ejecutado. **El deploy pasa** y el censo cuadra al 100 % contra los pedidos.

Pero la pasada en seco ha destapado **un fallo que cortaría el acceso a 8-13 de
los 16 alumnos nuevos en septiembre**. Todo el detalle, con la evidencia, en
**`dryrun_produccion_2026-08-25.md`**.

---

## 🎤 Guion para la reunión del 26-ago

### Lo que se enseña

- Plugin **desplegado y observándose** en producción.
- Censo **perfecto**: 71 de 71 alumnos en 15 s, cuadrado uno a uno con los
  pedidos de WooCommerce.
- **La 133ª está vendiendo**: 16 matrículas en 11 días y acelerando (4 el 24-ago,
  3 el 25), con atribución de origen.
- Web arreglada — el botón principal daba 404 — y bot captando también desde la
  web principal.
- Y el argumento que lo une todo: **el modo seco ya ha evitado un corte masivo**.
  Es exactamente para lo que servía.

### Las tres preguntas

1. **¿La gestoría ha confirmado la exención de IVA?** Ya se está vendiendo sin
   repercutirlo (768 € expuestos hoy, y creciendo). Es la que más corre.
2. **¿El curso de entrevista se cobra aparte o va incluido?** Hay 30 alumnos del
   grupo `ENTREVISTA 2026` con acceso activo y sin ningún registro en la tienda.
   *Pregunta neutra, no acusación.*
3. **¿Qué se hace con las 39 suscripciones pausadas** desde el 24-jun?
4. **¿Cuándo arrancan las campañas?** La landing, LS01 y la secuencia de 4
   emails están hechos y validados desde julio, y **no han recibido un solo lead
   real** porque nunca se lanzó ninguna campaña. No falta desarrollo: falta
   tráfico. *(Y de paso: el botón de la home hacia `/formacion` da una segunda
   vía de captura, que es justo lo que faltó durante la caída del bot — pero con
   parámetro de origen propio para no mezclar con la atribución de campañas.)*

### ⚠️ Qué NO prometer

**El paso a modo real del 1-sep no se puede confirmar.** Ahora son cuatro
bloqueos, y **dos son nuestros**:

| Bloqueo | Depende de |
|---|---|
| 🔴 El corte de septiembre | **Nosotros** — parche v0.8.1 |
| 🔴 Los 55 avisos de golpe al CRM | **Nosotros** — parche v0.8.1 |
| 🟡 Las 39 suscripciones pausadas | Paco |
| 🟡 Confirmación de los becados | Paco |

Lo que sí se puede decir: el plugin queda desplegado y observándose, y el modo
real se activa en cuanto estén el parche y las dos decisiones.

### 🔴 Y algo que sí es nuestro, y corre HOY

**El bot está dando el precio equivocado.** Su prompt dice "80 €/mes" mientras la
web ofrece **48 € el primer mes hasta el 31 de agosto**. O sea: el bot cita un
precio un 67 % más alto que la oferta vigente, pierde el mejor argumento de
cierre que hay ahora mismo —un descuento con fecha— y contradice a la web delante
del lead.

El bot no falla: su prompt le prohíbe inventarse precios y obedece. **Está mal el
prompt.** Texto corregido listo para pegar en `bot_ls02_prompts_2026-08-07.md`.
Son cinco minutos en GHL y quedan **6 días de oferta**.

*(Y anotar en el calendario: el 1 de septiembre hay que quitarlo, o el bot
ofrecerá un descuento caducado.)*

### La urgencia que no es nuestra

- **La oferta caduca el 31 de agosto — en 6 días.**
- 🔴 **El bot lleva caído desde el 19-ago.** Siete personas han preguntado por el
  curso —"cuánto cuesta", "quiero información", "cuándo empieza"— y han recibido
  *"no hay nadie disponible"*. La última, hoy. Ver
  **`bot_caido_desde_19ago_2026-08-25.md`**.
- Y las "12 oportunidades del bot" no son 12 leads: **10 no tienen teléfono ni
  email**, y de los 2 que sí, uno es una prueba nuestra. Si abren el CRM verán
  doce tarjetas llamadas *Guest Visitor*. Hay que llevarlo nosotros, con el
  diagnóstico hecho.

### Dos apuntes menores, por si salen

- El campo **"Precio rebajado"** del producto está **vacío pero con fechas
  puestas** (6-ago → 31-ago). No hace nada, pero conviene limpiarlo.
- El WordPress está en huso horario de **Buenos Aires**. Afecta a las fechas de
  las facturas (ver `facturacion_plan_2026-08-10.md`).
