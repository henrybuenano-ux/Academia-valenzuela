# Email para Horacio · 10-ago-2026

> Listo para copiar. Pide **3 decisiones**, cada una con recomendación, para
> que se pueda contestar con un sí/no. El resto de pendientes va en una sola
> línea al final. El detalle largo queda abajo, **fuera del email**, por si
> pregunta.

---

**Asunto:** Academia Valenzuela: 4 cosas que necesito de ti

Hola Horacio,

Contexto en dos líneas: el sistema está listo y funcionando, pero llevamos 17
días sin un solo lead y la 133ª arranca en 22 días. Lo que falta son
decisiones, no desarrollo.

• **WhatsApp con Meta — urgente por plazos.** El bot le pide el WhatsApp a
cada lead y le promete que le escribimos por ahí… y no tenemos el canal
conectado. Hoy eso se responde a mano desde móviles personales. La aprobación
de Meta tarda semanas y la 133ª es el 1 de septiembre: si no arranca esta
semana, no llega.

• **39 suscripciones paradas desde el 24 de junio** → unos 3.100 €/mes sin
facturar. Además bloquea la campaña de re-enganche, porque son las mismas
personas a las que queremos escribir. ¿Las cancelamos? Reactivarlas
dispararía cobros atrasados. Lo decide Paco.

• **Campaña de re-enganche a 69 ex-alumnos: lista para enviar.** Solo falta
que Paco cuente las novedades de la 133ª y lleva 17 días sin contestar. Tengo
escrita una versión que no lo necesita. ¿La lanzo esta semana?

• **Horario de asesorías de Paco.** El bot ya agenda citas de verdad, pero el
calendario ofrece los huecos por defecto: de 8:00 a 16:45. Un interesado
puede reservar a las 8 de la mañana y no encontrar a nadie. Son diez minutos
de configuración en cuanto nos dé sus franjas.

Los tres últimos pasan por hablar con Paco. Por email no responde, así que
habría que llamarle.

Menores, cuando quieras: fecha del paso a producción del plugin (ClickUp dice
6 de agosto, el runbook 24), las campañas de captación que nunca arrancaron y
la gestoría para facturación.

Un abrazo,
[tu nombre]

---
---

## Material de apoyo — NO enviar

Para responder si Horacio pregunta, o si prefieres una reunión en vez de un
email.

### Sobre el punto 1 — las 39 suscripciones

- El **24-jun** una edición en lote pasó las suscripciones de *Activa* a
  *En espera*. Figura hecha desde la cuenta **DeVOmibu**.
- WooCommerce no cobra las suscripciones en espera → **sin un solo pedido
  desde el 19-jun**. 39 × ~80 € ≈ **3.100 €/mes**.
- Pudo ser una pausa deliberada antes del examen (10-11 jul), tiene lógica.
- Las tres salidas: **cancelar** (limpio), **reactivar** (ojo: WooCommerce
  intentará el cobro vencido — habría que hacerlo de uno en uno) o **dejar
  expirar**.
- Verificado en `p2_causa_raiz_2026-07-10.md` (notas de las suscripciones
  #1815 y #1818, contadores de producción).
- *Si prefieres no citar la cuenta desde la que se hizo, se puede decir "las
  suscripciones quedaron en espera desde el 24 de junio" — la decisión no
  depende de quién lo hizo.*

### Sobre el punto 2 — el re-enganche

- Segmento: **69 ex-alumnos que pagaron** (39 de suscripción dados de baja,
  19 activos, 11 de intensivo), todos con email.
- Excluidos a propósito: los **7 becados** y 2 de convenio con colegio; son
  altas manuales de Paco y no deben recibir una oferta comercial.
- Los 3 emails están escritos. El segundo tiene dos versiones: una con hueco
  para las novedades de Paco y otra que no lo necesita.
- Todo en `reenganche_ls03_segmento_y_b2_2026-08-10.md`.

### Sobre el punto 3 — el calendario y lo demás que bloquea Paco

**El dato del calendario, verificado hoy** (`Asesorías 133ª`,
`HV3DnVxagoNduXbNx0UG`): sirve **36 huecos diarios de 08:00 a 16:45**, L-V,
citas de 15 min. Es el **horario por defecto** de GoHighLevel, no el de Paco.
- En julio se intentó cargar por API un horario provisional (10-14 / 16-20),
  pero el motor de huecos lo ignoró y siguió sirviendo el de por defecto:
  hay que meterlo **desde la UI** (Availability → editar → guardar reescribe
  el formato bien). Son 10 minutos en cuanto tengamos sus franjas.
- El riesgo ya es real: el bot ofreció citas a las 8:00 en una prueba, y
  desde hoy el bot está también en la web.
- Dueño provisional del calendario: **German**. Cambiar a Paco cuando tenga
  usuario en la plataforma.

**Lo demás que sigue bloqueado por él:**
1. Novedades de la 133ª (para el email del punto 2).
2. Los 7 becados: opción A o B (propuesta enviada el 17-jul, sin respuesta).
3. Añadir el botón "Infórmate sin compromiso" en la home hacia la landing.

### Por si hace falta defender que el bloqueo no es de ejecución

Hecho, probado y funcionando: bot que conversa, cualifica, capta datos y
**agenda cita**; CRM que reacciona solo (oportunidad + avisos, sin
duplicados); widget del bot instalado hoy en la web y verificado de punta a
punta; y el botón principal de la home, que **llevaba a un error 404** desde
que se retiró el curso de la 132ª, arreglado hoy junto con el texto obsoleto.
