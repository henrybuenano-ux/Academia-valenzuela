# ¿Hasta cuándo tiene acceso el alumno? — resuelto

**26 de agosto de 2026** · resuelto con datos del censo, no con recuerdos de
llamada

## La respuesta

**El acceso llega hasta el día del examen.** Lo dice el censo de EvoCampus del
10-jul, leyendo las fechas de fin de las matrículas reales:

> **TODAS las matrículas de la 132ª promoción terminan el 10-11 de julio de
> 2026** (fecha del examen). El curso regular va **01-oct-2025 → 10-jul-2026
> (~10 meses de cuota mensual)**; el intensivo, **13-abr → 10-jul**.

Es evidencia directa: fechas de matrícula en la plataforma, no interpretación.

## La contradicción aparente, y por qué se cae

Durante unas horas pareció que tres fuentes decían tres cosas. No es así:

| Fuente | Qué dice | Lectura correcta |
|---|---|---|
| Llamada 26-ago | «hasta el examen, junio o julio» | ✅ **Correcto** |
| Llamada 10-jun | «la mensual dura hasta abril/mayo» | Se refería al **temario**, no al acceso: en abril/mayo se termina de entregar el contenido |
| Producto `#2054` | «No parar hasta que se cancele» | Es un **agujero de configuración**, no una política. Ver abajo |

Las dos primeras son compatibles, y encajan con lo que Paco explicó el 26-ago
sobre liberar el contenido poco a poco como evaluación continua: **el temario se
acaba en abril/mayo y el alumno conserva el acceso para repasar hasta el
examen.**

## Qué es el intensivo

**13-abr → 10-jul.** Tres meses, terminando el mismo día del examen. Producto
aparte, de pago único, descrito en la llamada de descubrimiento como «para gente
externa».

Es decir: **para quien se acuerda dos o tres meses antes del examen y no compró
en octubre.** No es la continuación del curso regular ni algo que un suscriptor
tenga que comprar además — es la vía de entrada tardía.

---

## Consecuencia 1: la decisión A ya no tiene tres opciones

Si el curso de la 132ª terminó el 10-11 de julio, **las suscripciones que se
pararon el 24 de junio no son un pendiente: son el final natural del ciclo**.

Y explica el «regalo» que contó Paco: el último cargo mensual habría cubierto
unos días de julio de un curso que se acababa el día 10. No cobrarlos es lo
lógico.

| | |
|---|---|
| ❌ Como estaba planteado | «¿Cancelar, reactivar o dejar expirar?» — tres opciones, decisión del cliente |
| ✅ Como está ahora | **Cancelar o dejar expirar.** Reactivarlas sería cobrar por un curso terminado. Y venderles la 133ª, que es para lo que sirve la campaña de recuperación |

Sigue siendo una confirmación que le corresponde a Paco, pero ya no es una
disyuntiva: es validar la única acción coherente.

## Consecuencia 2: el ciclo no se cierra solo, y hay que arreglarlo el lunes

El producto `#2054` está en **«Dejar de renovar después de: No parar hasta que se
cancele»**. Con eso, en julio de 2027 volverá a hacer falta que **alguien edite a
mano unas 40 suscripciones** para cerrar el ciclo.

Eso es exactamente lo que ocurrió el **24-jun-2026 desde la cuenta DevOmibu**, y
que costó semanas explicar (`p2_causa_raiz_2026-07-10.md`).

**Arreglo, en la misma pantalla de la reconfiguración del 1-sep:**

> «Dejar de renovar después de» → **el número de cuotas que llegue al examen**,
> en lugar de «No parar hasta que se cancele».

Para la 132ª fueron ~10 cuotas (oct → jul). La 133ª arranca el **1-sep-2026**, así
que serían 10 u 11 según cuándo caiga el examen.

**✅ Dato confirmado por Paco el 27-ago:** *«Aproximadamente para la primera
quincena del mes de julio.»*

Y lo corrobora la propia plataforma: las 72 matrículas de la 133ª en EvoCampus
tienen fecha de fin **03-jul-2027**, todas. El acceso al campus ya está acotado
al examen; lo que falta es que el **cobro** se pare ahí también.

Curso **01-sep-2026 → 03-jul-2027**: unas **10-11 cuotas** según cómo quede la
reconfiguración del 1-sep.

---

## Nota de método

La transcripción del 26-ago **no se guardó**: se pegó en el chat y se compactó.
Esta verificación no salió de ella, sino del censo de EvoCampus — y salió mejor,
porque son datos y no memoria.

> Aun así: **guardar las transcripciones en `docs/` antes de trabajarlas.** Esta
> vez hubo una fuente mejor; la próxima puede no haberla.
