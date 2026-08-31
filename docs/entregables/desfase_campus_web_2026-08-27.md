# El desfase campus / web: no hay fallo de sincronización

**27 de agosto de 2026** · consultado en directo contra la API de EvoCampus
Respuesta al correo de Paco de las 6:55 y al de Horacio de las 8:47.

## La respuesta en una línea

**No falta nadie: sobran filas.** Cada alumno se matricula en **cuatro cursos**
—las cuatro formaciones— así que **18 personas generan 72 matrículas**. Lo que
se está contando son filas, no alumnos.

## Los números reales

Consultado hoy `getEnrollments` (614 matrículas en toda la plataforma), grupo
`133 PROMOCIÓN GUARDIA CIVIL`:

| | |
|---|---|
| Matrículas | **72** |
| Personas distintas | **18** |
| Matrículas por persona | **4, exactamente, en las 18** |
| Estado | **72 de 72 activas** |
| Vigencia | **01-sep-2026 → 03-jul-2027**, idéntica en todas |

Y las cuatro son las cuatro formaciones, una por curso:

| groupid | Curso | Matriculados |
|---|---|---|
| 112 | CONOCIMIENTOS CURSO DE ACCESO GUARDIA CIVIL | 18 |
| 115 | PSICOTÉCNICOS CURSO DE ACCESO GUARDIA CIVIL | 18 |
| 118 | ORTOGRAFÍA Y GRAMÁTICA CURSO DE ACCESO GUARDIA CIVIL | 18 |
| 121 | INGLÉS CURSO DE ACCESO GUARDIA CIVIL | 18 |

**Las 18 personas tienen las 4, sin excepción.** Es el comportamiento correcto:
el conector matricula en las cuatro formaciones que incluye el curso completo.

## Por qué no puede ser sincronización

Horacio apuntó a un problema de sincronización. La dirección del desfase lo
descarta:

> Si fallara la sincronización habría **menos** en la plataforma que en la web
> —la compra no llegaría al campus—. Aquí hay **más**.

Y al mirarlo de cerca ni siquiera hay «más»: hay **18 personas** en el campus
frente a los **~15-16 pedidos** de la web. El 25 de agosto contamos **16 pedidos**
en producción; dos días después hay 18 matriculados. **Cuadra.**

## De dónde sale el «34»: no del campus

**Ningún corte de EvoCampus da 34.** Comprobado:

| Corte | Valor |
|---|---|
| Personas en toda la plataforma | 140 |
| Personas con alguna matrícula activa | 63 |
| Matrículas activas | 117 |
| **Personas en la 133ª** | **18** |
| Matrículas en la 133ª | 72 |
| Personas en ENTREVISTA 2026 | 45 |

Y los cuatro cursos **se comparten entre promociones**, lo que sí explica que
las pantallas de curso den números raros:

| Curso | Total | 132ª | **133ª (activas)** | 132 Intensivo |
|---|---|---|---|---|
| CONOCIMIENTOS | 109 | 78 | **18** | 13 |
| INGLÉS | 107 | 76 | **18** | 13 |
| ORTOGRAFÍA Y GRAMÁTICA | 105 | 74 | **18** | 13 |
| PSICOTÉCNICOS | 106 | 75 | **18** | 13 |

Una vista de curso sin filtrar da ~107; filtrando activos, 18. **Nunca 34.**

### La hipótesis que encaja: viene de WooCommerce

Cada compra deja **dos registros**: un **pedido** y una **suscripción**.

> **16 pedidos + 18 suscripciones = 34.**

El 25-ago contamos 16 pedidos en producción; hoy hay 18 alumnos en el campus.
Si una pantalla o un recuento suma las dos cosas, sale exactamente 34.

**No lo he podido verificar**: el `wp-login.php` de producción devuelve 404 para
nosotros, así que no puedo contar los registros de WooCommerce desde aquí.

### Lo que hay que mirar para cerrarlo (10 segundos, con acceso)

| Pantalla | Debería dar |
|---|---|
| WooCommerce → **Pedidos**, del producto de la 133ª | ~16-18 |
| WooCommerce → **Suscripciones**, del producto de la 133ª | 18 |
| EvoCampus → alumnos de la 133ª | **18 personas** |

Si pedidos + suscripciones da 34, el asunto está cerrado y la respuesta a Paco
es que está sumando dos listas del mismo pedido.

## Lo que sí conviene revisar

- **Contrastar los 18 con los pedidos de WooCommerce.** Abajo va la lista. Si
  los 18 tienen pedido, no hay nada que hacer. Si aparece alguno sin pedido, es
  una matriculación manual y se decide qué hacer con ella.
- **Que las altas nuevas sigan llegando.** Lo anterior dice que las de hasta hoy
  están bien, no que el conector vaya a seguir funcionando. La compra de prueba
  que quedó pendiente ayer sirve también para esto.

## Los 18 matriculados en la 133ª

Para cruzar contra los pedidos de la tienda en una sola pasada.

| # | Alumno | Correo |
|---|---|---|
| 1 | Aldo Scarzella | `aldo.scarz@icloud.com` |
| 2 | Alejandro Arroyo | `alejandroarroyo92@gmail.com` |
| 3 | Alfonso Gámez | `alfonsogamezg@gmail.com` |
| 4 | Ana Belén Mendoza | `mendozahervasana@gmail.com` |
| 5 | Andrés Ruiz | `andresruizrubio5@gmail.com` |
| 6 | Angel Guijarro | `angel.guijarrog@gmail.com` |
| 7 | Antonio Rubio | `arubioruiz95@gmail.com` |
| 8 | Daniel Ramos | `cmarrajo@gmail.com` |
| 9 | FRANCISCO BORJA DIAZ | `borjadiaz2004@gmail.com` |
| 10 | JOSE MARIANO MARTINEZ | `MARIANO2003HERRANZ@GMAIL.COM` |
| 11 | Manuel León | `mleonguijosa@gmail.com` |
| 12 | Manuel Ángel Verdugo | `mavitaa06@gmail.com` |
| 13 | María Ortiz | `mariaortizrodriguez400@gmail.com` |
| 14 | Nicolás Díaz | `nicodiaz1995@hotmail.com` |
| 15 | Rodolfo Rusillo | `rusillop@gmail.com` |
| 16 | Saray Benítez | `saraybenitezcaracuel@gmail.com` |
| 17 | Violeta Mañas | `violetamanasfdez@gmail.com` |
| 18 | Álvaro Morales | `alvaromh95@hotmail.com` |

*(Consulta de solo lectura. No se ha modificado ninguna matrícula.)*

---

# Borrador de respuesta — listo para enviar

**Asunto:** Re: alumnos apuntados al curso — está bien, son 18

Hola Paco, hola Horacio,

Lo hemos comprobado consultando directamente la plataforma y **no hay ningún
problema de sincronización**: las altas están llegando bien.

Lo que pasa es que **cada alumno se matricula en cuatro cursos**, no en uno —
Conocimientos, Psicotécnicos, Ortografía y Gramática, e Inglés, que son las
cuatro formaciones que incluye el curso completo. Así que **18 alumnos generan
72 matrículas** en la plataforma. El número que se ve en la pantalla son
matrículas, no personas.

**Ahora mismo hay 18 alumnos matriculados en la 133ª**, todos activos y con
acceso del 1 de septiembre al 3 de julio. En la web nos salían 16 pedidos el
lunes, así que cuadra con los dos de estos dos días.

Sobre los 34: no salen del campus por ningún lado —lo hemos comprobado corte por
corte—. Lo más probable es que sea una suma de la tienda, donde **cada compra
deja dos registros, un pedido y una suscripción**. Con 16 pedidos y 18
suscripciones salen justo esos 34.

Paco, si nos dices en qué pantalla ves los 34 te lo confirmamos en un momento,
pero por lo que hemos mirado **no falta ningún alumno**.

Un saludo.
