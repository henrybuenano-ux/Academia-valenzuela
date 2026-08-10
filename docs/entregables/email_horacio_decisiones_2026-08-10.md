# Email para Horacio · 10-ago-2026

> Listo para copiar. Pide **3 decisiones**, cada una con recomendación, para
> que se pueda contestar con un sí/no. El resto de pendientes va en una sola
> línea al final. El detalle largo queda abajo, **fuera del email**, por si
> pregunta.

---

**Asunto:** Academia Valenzuela: necesito 3 decisiones tuyas

Hola Horacio,

El sistema está terminado y funcionando: el bot conversa, capta y agenda
citas, y todo entra solo en el CRM. El problema es otro: **llevamos 17 días
sin un solo lead** y la 133ª arranca **el 1 de septiembre, en 22 días**.

Lo que falta no es técnico, son decisiones. Te pido tres:

**1. ¿Qué hacemos con las 39 suscripciones paradas?**
Están "en espera" desde el 24 de junio, así que la facturación lleva parada
desde entonces: unos **3.100 €/mes**. Además bloquea la campaña de
re-enganche, porque son exactamente las mismas personas a las que queremos
escribir.
→ *Lo más limpio me parece cancelarlas formalmente: el curso terminó y quien
vuelva se suscribe a la 133ª. Reactivarlas dispararía cobros atrasados. Pero
esto lo tiene que decidir Paco.*

**2. ¿Lanzamos la campaña de re-enganche sin esperar a Paco?**
Tenemos los tres emails escritos y el listado de **69 ex-alumnos** preparado.
Solo falta que Paco nos cuente las novedades de la 133ª… y lleva **17 días
sin contestar**. Tengo escrita una versión del email que no necesita nada
suyo.
→ *Mi recomendación: lanzarla esta semana con esa versión. A 22 días de que
empiece el curso, esperar nos cuesta más que enviar.*

**3. ¿Quién habla con Paco, y por qué vía?**
Sigue bloqueando cuatro cosas: las novedades del punto anterior, su horario
para las asesorías, qué hacemos con los 7 becados y un cambio en la web. Por
email no está funcionando; quizá toque llamada.

Con esas tres respuestas, en un par de días lo tenemos en marcha.

**Otros temas que hay que cerrar pero que hoy no me bloquean:** la fecha del
paso a producción del plugin (ClickUp dice 6 de agosto, el runbook dice 24),
las campañas de captación que nunca llegaron a arrancar, WhatsApp con Meta y
la gestoría para el tema de facturación. Los vemos cuando quieras.

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

### Sobre el punto 3 — lo que bloquea Paco

1. Novedades de la 133ª (para el email B2).
2. Su horario de asesorías → hoy el calendario ofrece huecos por defecto y el
   bot llegó a proponer citas **a las 8 de la mañana**.
3. Los 7 becados: opción A o B (propuesta enviada el 17-jul, sin respuesta).
4. Añadir el botón "Infórmate sin compromiso" en la home hacia la landing.

### Por si hace falta defender que el bloqueo no es de ejecución

Hecho, probado y funcionando: bot que conversa, cualifica, capta datos y
**agenda cita**; CRM que reacciona solo (oportunidad + avisos, sin
duplicados); widget del bot instalado hoy en la web y verificado de punta a
punta; y el botón principal de la home, que **llevaba a un error 404** desde
que se retiró el curso de la 132ª, arreglado hoy junto con el texto obsoleto.
