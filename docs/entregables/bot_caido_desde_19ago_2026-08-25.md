# 🔴 INCIDENTE — El bot lleva caído desde el 19 de agosto

> Detectado el **25-ago-2026** al revisar el pipeline *Captación* por API.
> **Sigue activo ahora mismo.** Siete personas reales han preguntado por el
> curso y han recibido *"Parece que no hay nadie disponible"* en vez de una
> respuesta del bot.

## Por qué importa tanto

- La **oferta de lanzamiento caduca el 31 de agosto**. Estas siete personas son
  exactamente el público al que iba dirigida.
- El **curso empieza el 1 de septiembre**. Es la última semana útil de captación.
- Cuatro de las siete preguntas eran **de compra directa** ("cuánto cuesta",
  "quiero información", "cuándo empieza"). No eran curiosos.
- Y esto es lo que agrava todo: **el bot no es un canal de captación más, es el
  único.** El widget está en toda la web desde el 10-ago, y la home tiene
  exactamente dos salidas: comprar a 48 € o hablar con el chat. Quien no está
  listo para pagar hoy —la mayoría— solo tiene el bot. No hay ningún "déjanos
  tu email y te mandamos información".

  Así que la caída no fue "perdimos unos chats". Fue **la web entera sin
  capacidad de captar un solo lead durante seis días**, en la semana de la
  oferta.

- **Y el repuesto tampoco funciona.** Durante la caída el widget sí pedía los
  datos ("Por favor, deje sus datos de contacto"). **Ninguna de las 7 personas
  los dejó. Cero de siete.** Cuando el bot no conversa, el formulario de reserva
  no convierte: la gente se va.

## La evidencia

Sacado de la API interna de GHL: pipeline `Captación`
(`Zfz0z86Mk3LaxHfK1yYb`), etapa *Cualificado*, y las conversaciones de cada
contacto.

| Fecha | Oportunidad | Lo que escribió el visitante | ¿Respondió el bot? |
|---|---|---|---|
| 11-ago 14:03 | `Guest Visitor kruwt` | "VOSOTROS SON UNA ACADEMIA ONLINE ?" | ✅ sí |
| 17-ago 08:04 | `Guest Visitor hnmea` | "hola, tengo dudas" | ✅ sí |
| 17-ago 14:02 | `Guest Visitor vqsvy` | "hola" | ✅ sí |
| 17-ago 16:18 | `fran` | "hola" | ✅ sí |
| **18-ago 07:22** | `Carlos` | *(prueba nuestra — carlos.santiago@omibu.com)* | ✅ **sí — último OK conocido** |
| **19-ago 18:33** | `Guest Visitor qhmiu` | **"es 100% online?"** | ❌ *"no hay nadie disponible"* |
| **20-ago 12:26** | `Guest Visitor wucvf` | "hola Paquiño 🫡🤫" | ❌ |
| **20-ago 18:57** | `Guest Visitor hqwtf` | **"cuanto cuesta"** | ❌ |
| **22-ago 20:55** | `Guest Visitor ofurq` | **"quiero informacion"** | ❌ |
| **23-ago 14:07** | `Guest Visitor dsllx` | **"cuanto cuesta el temario?"** | ❌ |
| **24-ago 15:47** | `Guest Visitor uhowk` | **"hola ¿cuando empieza el curso?"** | ❌ |
| **25-ago 07:16** | `Guest Visitor bhcrt` | "Buenas, me gustaria saber si la academia es…" | ❌ |

**La ventana del corte está clavada: funcionaba el 18-ago a las 07:22 y estaba
caído el 19-ago a las 18:33.** Algo pasó entre esas dos marcas.

Matiz que conviene tener presente: el último OK conocido (18-ago) es **una prueba
nuestra**. El último visitante real al que el bot atendió fue el del **17-ago a
las 16:18**. Desde entonces, ningún desconocido ha tenido una conversación útil.

*(Comprobado además que hay exactamente **12 conversaciones y 12 oportunidades**
en la sub-cuenta: no hay conversaciones huérfanas que se nos escapen.)*

## Lo que esto corrige de lo que veníamos diciendo

Veníamos repitiendo *"hay 12 leads del bot sin contactar"*, y era una lectura
equivocada en dos sentidos:

1. **Diez de los doce son incontactables.** No tienen teléfono ni email. No es
   que nadie los haya llamado: es que **no hay a dónde llamar**.
2. **De los dos con datos, uno es una prueba nuestra** (`carlos.santiago@omibu.com`,
   17-ago). El otro es `fran`, +34605442972 — conviene confirmar si también es
   interno.

O sea: **el número de leads reales y contactables captados por el bot puede ser
cero.** Es un dato mucho peor que "12 sin contactar", y hay que decirlo tal cual.

### Y explica el misterio de los "Guest Visitor"

Llevábamos semanas dándole vueltas a por qué las oportunidades se siguen
llamando `Guest Visitor xxxxx` pese al *wait* de 5 minutos que se añadió a LS02.
**El wait no está roto.** El bot nunca llegó a preguntar el nombre porque no
estaba respondiendo. La causa era otra desde el principio.

## Qué hay que comprobar (por orden de probabilidad)

1. **¿Se han agotado los créditos de IA de la sub-cuenta?** Es la causa más
   habitual de que un bot de GHL deje de responder de golpe y caiga al mensaje
   de "no hay nadie disponible". Un corte tan limpio en una fecha concreta encaja.
2. **¿Está el agente publicado y activo**, y con el canal *Live Chat* asignado?
3. **¿Se tocó algo entre el 18 y el 19 de agosto?** La ventana es estrecha:
   entre el 18-ago 07:22 y el 19-ago 18:33.
4. **¿Está el bot en modo "solo fuera de horario"** o con alguna condición de
   disponibilidad que se activara entonces?

## Segundo problema, aparte del bot

**LS02 crea la oportunidad aunque la conversación no haya capturado nada.** Por
eso hay diez tarjetas en *Cualificado* que no sirven para nada: ensucian el
pipeline y falsean cualquier métrica de conversión.

Habría que añadir una condición antes de crear la oportunidad: **si no hay
teléfono ni email, no se crea**. Un visitante anónimo que preguntó y se fue no
es una oportunidad cualificada.

Y con eso, limpiar las diez existentes.

## Para la reunión

Esto **no se puede esconder**: si el cliente abre Oportunidades, ve doce tarjetas
con nombres de fantasma. Mejor llevarlo nosotros, y llevarlo con el diagnóstico
hecho y la fecha exacta — que es lo que hay aquí.

El encuadre honesto: *el sistema de captación funciona (está probado end-to-end
el 10-ago), pero el bot se cayó el 19 y nadie se dio cuenta porque no había
alerta. Lo hemos detectado hoy, sabemos qué revisar, y hace falta una alerta para
que no vuelva a pasar en silencio.*

**Lo urgente es levantarlo antes del 1 de septiembre**, que es cuando llega el
grueso del tráfico.

## Lo que este incidente demuestra, más allá del bot

Que la captación del sitio **depende de una sola pieza y no hay red**. Dos cosas
distintas hacen falta:

1. **Una alerta.** Estuvo caído seis días sin que saltara nada. Un workflow que
   avise si pasan 48 h sin conversación del bot vale más que el propio arreglo.
2. **Una segunda vía de captura en la home.** Lo más rentable es enlazar a
   `/formacion`, que reaprovecha LS01 y SP01 —ya construidos y validados— y
   cuesta cero. **Con un parámetro de origen propio**
   (`?utm_source=web&utm_medium=home`), para no mezclar estos leads con los de
   campañas y poder medir cada cosa por separado.
