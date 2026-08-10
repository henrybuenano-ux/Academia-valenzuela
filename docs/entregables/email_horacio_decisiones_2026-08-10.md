# Email para Horacio — decisiones pendientes de Academia Valenzuela · 10-ago-2026

> Listo para copiar y enviar. Revisar antes de mandarlo: hay un punto
> sensible (el nº 1) descrito con el dato tal cual está documentado.
> Fuentes de cada afirmación al final, por si pregunta.

---

**Asunto:** Academia Valenzuela — 6 decisiones bloqueadas y la 133ª a 22 días

Hola Horacio,

Te resumo dónde estamos y qué necesito que se decida, porque lo que queda ya
no es trabajo técnico.

**Estado en tres líneas:**

- El sistema está **terminado y validado de punta a punta**: el bot conversa,
  cualifica, captura datos y **agenda cita**; la cita dispara sola el CRM
  (oportunidad + avisos al equipo). Probado con conversaciones reales.
- El embudo lleva **17 días sin recibir un solo lead**. En el CRM hay 103
  contactos y los 103 son la importación de julio: cero de la landing, cero
  del bot, cero oportunidades.
- La **133ª promoción arranca el 1 de septiembre: quedan 22 días.**

El motor funciona; lo que falta es gasolina y seis decisiones que no dependen
de nosotros.

---

## 1. Lo más urgente: 39 suscripciones paradas desde junio

El **24 de junio** una edición en lote pasó las suscripciones de *Activa* a
*En espera* (la edición figura hecha desde la cuenta **DeVOmibu**).
WooCommerce no cobra las suscripciones en espera, así que **la facturación
lleva parada desde entonces**: 39 suscripciones × ~80 € ≈ **3.100 €/mes**.
Desde el 19 de junio no hay ni un pedido nuevo.

Puede que fuera una pausa deliberada antes del examen (10-11 de julio) y que
tenga toda la lógica. Pero **hay que decidir qué se hace antes de la 133ª**:

- **Cancelarlas** formalmente (dejar limpio y que quien vuelva se suscriba de
  nuevo),
- **reactivarlas** —ojo, al reactivar WooCommerce intentará el cobro vencido:
  habría que hacerlo de forma controlada, empezando por un alumno—,
- o **dejarlas expirar** y no tocar nada.

Además **bloquea la campaña de re-enganche**: son exactamente las mismas
personas a las que queremos escribir. Si les mandamos un "¿repetimos?" a
gente que cree seguir dada de alta, queda fatal.

## 2. Las otras cinco decisiones del cliente

| | Decisión | Por qué corre prisa |
|---|---|---|
| 2 | **Novedades de la 133ª** para el segundo email del re-enganche. Con 2-3 apuntes de Paco vale, ya lo redactamos nosotros | Bloquea la campaña a 69 ex-alumnos |
| 3 | **Horario de asesorías de Paco** | El calendario está sirviendo el horario por defecto: el bot llegó a ofrecer citas **a las 8 de la mañana** |
| 4 | **Los 7 becados en la 133ª**: opción A o B (les mandamos la propuesta el 17 de julio) | Los hemos dejado fuera del re-enganche a propósito; sin decisión se quedan fuera |
| 5 | **Botón "Infórmate sin compromiso"** en la home, hacia la landing | Hoy la web solo convierte a quien ya está dispuesto a pagar; sin ese botón el embudo no recibe a nadie |
| 6 | **Gestoría / VeriFactu** | No urge (los plazos se movieron a 2027) pero bloquea el diseño de la fase de facturación |

**Aviso importante: Paco no responde desde el 24 de julio, hace 17 días.** Es
el cuello de botella de cuatro de estos seis puntos. ¿Quién lo persigue y por
qué vía? Si por email no funciona, quizá toque llamada o WhatsApp.

## 3. Lo que necesito decidir contigo (interno)

1. **¿Lanzamos el re-enganche sin esperar a Paco?** Tenemos escrita una
   versión del email que no necesita sus novedades: se apoya solo en cosas
   verificables (temario actualizado a la nueva convocatoria, test con
   ranking, sin permanencia). **Mi recomendación es lanzarla**: a 22 días de
   la 133ª, esperar cuesta más que enviar.
2. **Fecha del paso a producción del plugin**: ClickUp lo fecha el 6 de
   agosto (ya vencido) y el runbook el 24. Hay que unificar y confirmar que
   lo ejecuta Henry.
3. **Las campañas de captación nunca arrancaron**, aunque el plan las situaba
   en julio-agosto. Esta es la causa de fondo del embudo vacío: ¿hay
   presupuesto, responsable y fecha?
4. **El tablero de ClickUp está desincronizado**: fechas vencidas y tareas mal
   marcadas (la landing figura "por hacer" llevando viva desde el 24 de julio).
5. **WhatsApp con Meta** sigue sin arrancar y tiene un plazo de aprobación
   largo.

## 4. Para que quede claro dónde NO está el bloqueo

Todo esto está hecho, probado y funcionando:

- Bot web: conversa, cualifica, captura nombre/WhatsApp/email y **agenda
  cita** en el calendario. Batería de pruebas superada.
- CRM: la cita dispara etiqueta + oportunidad; los leads del chat entran solos
  en Captación con aviso al equipo; sin duplicados.
- Web: arreglado hoy el botón principal de la home, que **llevaba a un error
  404** desde que se retiró el curso de la 132ª, y quitado el texto obsoleto.
- Re-enganche: segmento de **69 ex-alumnos** ya calculado (excluyendo becados)
  y los tres emails escritos.

En cuanto tengamos las decisiones, la ejecución es de horas, no de días.

Un abrazo,
[tu nombre]

---

## Notas de respaldo (no enviar — por si Horacio pregunta)

| Afirmación | Dónde está verificado |
|---|---|
| Pausa del 24-jun y ~3.100 €/mes | `p2_causa_raiz_2026-07-10.md` (notas de las suscripciones #1815 y #1818, contadores de producción) |
| 0 leads / 0 oportunidades | Consulta al CRM del 10-ago por dos vías: listado completo y búsqueda por etiqueta |
| Bot validado de conversación a cita | `qa_bot_ls02_checklist_2026-08-07.md` + pruebas del 7-ago con cita real creada |
| Botón de la home en 404 y arreglo | `arreglo_home_cta_133_2026-08-10.md` |
| Segmento de 69 y emails | `reenganche_ls03_segmento_y_b2_2026-08-10.md` |
| Propuesta de becados del 17-jul | `respuesta_paco_becados_2026-07-17.md` |
| VeriFactu aplazado a 2027 | RD-ley 15/2025 (BOE 3-dic-2025), recogido en `PLAN_MAESTRO.md` |

**Si prefieres suavizar el punto 1**: se puede describir como "las
suscripciones quedaron en espera desde el 24 de junio" sin citar la cuenta
desde la que se hizo la edición. El dato está documentado por si hace falta,
pero la decisión que importa (cancelar / reactivar / dejar expirar) no
depende de quién lo hizo.
