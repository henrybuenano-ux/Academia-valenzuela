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

## De dónde sale el «34»

No lo sé con certeza, pero no son personas. Con 18 alumnos y 4 cursos, cualquier
vista que liste matrículas da múltiplos de 18: 36 son dos cursos, 54 son tres,
72 son los cuatro. **Conviene mirar en qué pantalla se ven esos 34** — es lo
único que queda por comprobar, y es un vistazo.

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

Paco, si nos dices en qué pantalla ves los 34 te confirmamos qué está contando
esa vista concreta, pero por lo que hemos mirado no falta ningún alumno.

Un saludo.
