# Email para Horacio · actualizado 25-ago-2026

> Listo para copiar. Pide **4 decisiones**, cada una con recomendación, para que
> se pueda contestar con un sí/no. El detalle largo queda abajo, **fuera del
> email**, por si pregunta.
>
> ⚠️ **Actualizado el 25-ago**: la versión del 10-ago abría diciendo que
> llevábamos 17 días sin un solo lead. Ya no es cierto — hay 12 leads del bot y
> **16 matrículas vendidas**. El arranque cambia, y entra el IVA como decisión
> nueva y urgente.

---

**Asunto:** Academia Valenzuela: 4 cosas que necesito de ti

Hola Horacio,

Contexto en dos líneas: **la 133ª ha arrancado a vender** — 16 matrículas en 11
días y acelerando — y el sistema está desplegado y funcionando. Lo que falta son
decisiones, no desarrollo. Y una corre de verdad.

• **WhatsApp con Meta — urgente por plazos.** El bot le pide el WhatsApp a cada
lead y le promete que le escribimos por ahí… y no tenemos el canal conectado.
Hoy eso se responde a mano desde móviles personales. La aprobación de Meta tarda
semanas y el curso empieza el 1 de septiembre: si no arranca esta semana, no
llega.

• **El IVA, y esta ha pasado a correr.** Las matrículas que están entrando se
cobran **sin repercutir IVA**, sin que la gestoría lo haya confirmado. Si resulta
que el curso no está exento, se debe el 21 % de todo lo vendido hacia atrás. Hoy
son 768 € — poco — pero sube con cada venta y arrastra lo de noviembre de 2025.
Es una llamada a la gestoría.

• **39 suscripciones paradas desde el 24 de junio** → unos 3.100 €/mes sin
facturar. Además bloquea la campaña de re-enganche, porque son las mismas
personas a las que queremos escribir. ¿Las cancelamos? Reactivarlas dispararía
cobros atrasados. Lo decide Paco.

• **Campaña de re-enganche a 69 ex-alumnos: lista para enviar.** Solo falta que
Paco cuente las novedades de la 133ª, y lleva semanas sin contestar. Tengo escrita
una versión que no lo necesita. ¿La lanzo esta semana?

Y dos avisos que no necesitan decisión tuya, solo que lo sepas:

1. **El bot lleva caído desde el 19 de agosto y lo hemos detectado hoy.** Siete
   personas han preguntado por el curso y han recibido "no hay nadie disponible";
   la última, esta mañana. Justo la semana en que caduca la oferta. Lo estamos
   levantando, pero hace falta una alerta: se cayó y nadie se enteró en seis días.
2. **El paso a modo real del plugin no puede ser el 1 de septiembre.** Al
   verificarlo en producción encontramos que habría cortado el acceso a la mayoría
   de los alumnos nuevos a mediados de mes. Lo arreglamos nosotros; lo digo para
   que no se prometa esa fecha.

Lo del re-enganche y el calendario de Paco pasan por hablar con él. Por email no
responde, así que habría que llamarle — su horario de asesorías sigue sin
configurar y el bot está ofreciendo citas a las 8 de la mañana.

Menores, cuando quieras: las campañas de captación que nunca arrancaron y el
botón de la home hacia la landing, que sigue pendiente de que lo aprueben ellos.

Un abrazo,
[tu nombre]

---
---

## Material de apoyo — NO enviar

Para responder si Horacio pregunta, o si prefieres una reunión en vez de un
email.

### Las 39 suscripciones

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

### El re-enganche

- Segmento: **69 ex-alumnos que pagaron** (39 de suscripción dados de baja,
  19 activos, 11 de intensivo), todos con email.
- Excluidos a propósito: los **7 becados** y 2 de convenio con colegio; son
  altas manuales de Paco y no deben recibir una oferta comercial.
- Los 3 emails están escritos. El segundo tiene dos versiones: una con hueco
  para las novedades de Paco y otra que no lo necesita.
- Todo en `reenganche_ls03_segmento_y_b2_2026-08-10.md`.

### El calendario y lo demás que bloquea Paco

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

### El IVA, por si pregunta

- Los pedidos salen con **subtotal 48 €, total 48 € y ninguna línea de
  impuesto**. Verificado en el pedido `#2087`.
- La enseñanza suele estar exenta (art. 20.Uno.9º de la Ley 37/1992) y una
  academia de oposiciones encaja bien, pero **lo confirma la gestoría**.
- Si estuviera exento: no hay nada que corregir, solo hay que citar el artículo
  en la factura.
- Si no lo estuviera: 21 % sobre lo vendido. Y una decisión comercial — ¿el
  precio pasa a incluirlo (48 € → base 39,67 €) o se suma encima (48 € →
  58,08 €)?
- Detalle y plantilla en `facturacion_plan_2026-08-10.md` y
  `plantilla_factura_academia_valenz.html`.

### El corte de septiembre, por si pregunta

- El plugin decide quién tiene acceso por **"días desde el último pago"**, con
  una ventana de 38 días.
- El curso se vendió con **1 mes de prueba y facturación sincronizada al día 1**:
  quien compró el 14-ago no vuelve a pagar hasta el **1-oct**. Son 48 días.
- Resultado: los habría cortado a mediados de septiembre, con tag de impago y
  reclamación de deuda incluidos. **8 seguros y 5 más a suerte, de 16.**
- Lo encontró la pasada en seco, que es exactamente para lo que estaba.
- Se arregla preguntándole a la suscripción en vez de a la fecha del último
  pedido. Detalle en `dryrun_produccion_2026-08-25.md`.

### Por si hace falta defender que el bloqueo no es de ejecución

Hecho, probado y funcionando: bot que conversa, cualifica, capta datos y
**agenda cita**; CRM que reacciona solo (oportunidad + avisos, sin duplicados);
widget del bot instalado en la web y verificado de punta a punta; el botón
principal de la home, que **llevaba a un error 404** desde que se retiró el curso
de la 132ª, arreglado; y el plugin de EvoCampus **desplegado en producción**, con
el censo cuadrando 71 de 71 alumnos en 15 segundos.

Y el dato que mejor lo resume: **el 10-ago se arregló el botón roto de la home y
el 14-ago entró la primera matrícula de la 133ª**, después de casi dos meses sin
un solo pedido. No es prueba de causalidad —el producto ya era comprable— pero la
secuencia está ahí y WooCommerce guarda la atribución de cada venta.
