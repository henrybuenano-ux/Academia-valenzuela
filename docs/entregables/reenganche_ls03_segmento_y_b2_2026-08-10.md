# LS03 · Re-enganche 132ª → 133ª: segmento + email B2 · 10-ago-2026

> **Por qué ahora**: la 133ª arranca el **1-sep (22 días)** y el embudo de
> captación lleva 17 días vivo con **cero leads** (verificado por API: 0
> contactos con `lead-landing-133` / `lead-bot`, 0 oportunidades). Los 100
> ex-alumnos ya importados son el único activo con tracción inmediata y
> coste cero. Esto es lo que falta para poder lanzarles LS03.

---

## 1. El segmento: 69 contactos

Criterio: **ex-alumnos que pagaron** (suscripción o intensivo), excluyendo
becados y convenio de colegio. Los 69 tienen email válido.

| Sub-segmento | N | Por qué entra |
|---|---|---|
| Suscripción · último estado **baja** | 39 | Núcleo del re-enganche: pagaban 80 €/mes y lo dejaron. ⚠️ Ojo: **32 de ellos figuran como baja porque el lote del 24-jun les paró la suscripción**, no porque impagaran (ver `dryrun_produccion_2026-08-25.md`) |
| Suscripción · último estado **activo** | 19 | Siguen "dentro"; el curso terminó (examen 10-11 jul) y no hay 133ª contratada |
| **Intensivo** (pago único) | 11 | Compraron una vez; perfil distinto pero comprador probado |

### Excluidos a propósito (34)

| Excluidos | N | Motivo |
|---|---|---|
| `becado` + `colegio-173` | 9 | **No se les manda oferta comercial.** Los 7 becados son altas manuales de Paco (ver `respuesta_paco_becados_2026-07-17.md`): mandarles una oferta comercial es un error de relación. Su continuidad la decide Paco. |
| `sin-pago-web` y resto | 25 | Sin rastro de pago en Woo; incluye `info@academiavalenz.com` (la propia academia) y altas manuales |

Los 69 ids están calculados y listos para etiquetar con `reenganche-133`
(hoy ese tag lo tienen **0 contactos**, así que LS03 no ha tocado a nadie).

### ⚠️ Comprobar antes de enviar: los 39 en "baja"
Ese 39 coincide sospechosamente con las **39 suscripciones pausadas en bloque
el 24-jun** (edición masiva Activa → "En espera", ver `p2_causa_raiz_2026-07-10.md`).
Si su "baja" es en realidad **esa pausa administrativa** y no una decisión del
alumno, el email B1 ("¿repetimos?") le llega a gente que cree seguir dada de
alta. **Antes de lanzar**: confirmar en WooCommerce si esos 39 son los mismos
y decidir qué se hace con las suscripciones pausadas (cancelarlas formalmente
antes de escribir, o mencionarlo en el email). Es la decisión de negocio que
ya estaba pendiente y ahora bloquea el envío.

---

## 2. Email B2 — dos versiones

Contexto: B1 ("La 133ª empieza el 1 de septiembre — ¿repetimos?") y B3
("¿Vas a por ella?" con SÍ / LUEGO / NO) ya están escritos y cargados. B2 va
**+4 días después de B1** y su trabajo es dar una razón concreta para volver.

### Versión A — con input de Paco (preferida)

Solo hay que rellenar 3 huecos; el resto está escrito:

> **Asunto:** Qué hay nuevo en la 133ª
>
> Hola {{contact.first_name}},
>
> Te cuento en corto qué cambia en la 133ª respecto al año pasado:
>
> - **[NOVEDAD 1 — p. ej. cambios del temario por la nueva convocatoria]**
> - **[NOVEDAD 2 — p. ej. algo nuevo del campus: simulacros, corrección, tutorías]**
> - **[NOVEDAD 3 — opcional: resultados de la 132ª, si se pueden dar]**
>
> Lo que no cambia: sin matrícula y sin permanencia — si no te encaja, lo
> cancelas tú mismo cuando quieras. Y por volver ahora, **el primer mes te sale
> por 48 € en vez de 80 €**; la oferta acaba el 31 de agosto.
>
> Si tienes dudas de si repetir, responde a este correo y lo vemos, sin
> compromiso.

**Lo que hay que pedirle a Paco, exactamente:** 2-3 frases sobre qué es
distinto este año. No hace falta que redacte nada bonito — con notas sueltas
vale, lo montamos nosotros.

### Versión B — autosuficiente, sin Paco (plan B recomendado si no contesta esta semana)

No afirma ninguna novedad que no podamos verificar: se apoya en lo que ya es
cierto y en lo que de verdad le importa a un **repetidor**.

> **Asunto:** Tu temario de la 132ª ya no te sirve (y eso es lo importante)
>
> Hola {{contact.first_name}},
>
> Si vuelves a presentarte, lo primero que necesitas es material al día: cada
> convocatoria mueve cosas y estudiar con los apuntes del año pasado es la
> forma más fácil de perder puntos tontos. En el campus el temario ya está
> actualizado a la convocatoria vigente y lo tienes en PDF descargable desde
> el primer día.
>
> Lo segundo, y para ti que ya sabes cómo es el examen: los test con
> **ranking real** entre opositores. No es un "te ha salido un 7", es saber
> en qué puesto estarías hoy — que es justo lo que decide una plaza.
>
> Y como el año pasado: sin matrícula y sin permanencia, cancelas tú cuando
> quieras, así que volver no te compromete a nada. Con una diferencia a tu favor:
> **el primer mes son 48 € en vez de 80 €** si te decides antes del 31 de agosto.
>
> ¿Lo hablamos 10 minutos antes de decidir? Reserva cuando te venga bien:
> **[ENLACE ASESORÍA]**
>
> Y si prefieres ir directo, aquí tienes toda la info de la 133ª:
> **[ENLACE LANDING]**

**Enlaces a usar:**
- Asesoría: `https://api.omniainbusiness.com/widget/booking/HV3DnVxagoNduXbNx0UG`
  (⚠️ el calendario sirve todavía el horario por defecto 08:00-16:45 — meter
  las franjas reales antes de mandar el email, o los ex-alumnos verán huecos
  a las 8 de la mañana)
- Landing: `https://info.academiavalenz.com/formacion`

### 🔴 Fallo detectado en B1 (arreglar antes de publicar)

B1 termina con *"Reserva tu plaza: https://academiavalenz.com/"* — la **home**,
que no enlaza el embudo por ningún sitio (comprobado: menciona la "133ª" 7
veces y ni un enlace a `/formacion`). El ex-alumno llega, no encuentra dónde
apuntarse y se va. **Cambiar el CTA de B1** a la landing o al widget de
asesoría, igual que en B2/B3.

---

## 3. Checklist para lanzar LS03 (cuando se apruebe)

1. [ ] Decidir qué pasa con las 39 suscripciones pausadas (bloquea el envío).
2. [ ] Cerrar B2: novedades de Paco (versión A) **o** aprobar la versión B.
3. [ ] Corregir el CTA de B1 (hoy apunta a la home).
4. [ ] Meter el horario real en el calendario si se usa el enlace de asesoría.
5. [ ] Publicar LS03 (hoy `status: None`, nunca publicado) y **activar su
       trigger** (`reenganche-133`, hoy inactivo).
6. [ ] Etiquetar los 69 con `reenganche-133` — **esto dispara los envíos**.
       Recomendado: empezar por una tanda de prueba de 3-5 contactos del
       equipo, verificar que llegan los 3 emails y luego el resto.
7. [ ] Vigilar que SP03 saca de LS03 a quien agende (ya cableado y probado).

---

## 4. Mensaje para Paco (listo para enviar)

> Hola Paco,
>
> La 133ª arranca el 1 de septiembre y tenemos preparada la campaña para
> avisar a los alumnos de la 132ª (69 personas). Nos falta una cosa tuya, y
> es de 2 minutos:
>
> **¿Qué cambia este año respecto al curso pasado?** Con 2 o 3 apuntes me
> vale (cambios del temario por la nueva convocatoria, algo nuevo del campus,
> lo que se te ocurra). No hace falta que lo redactes: me mandas las notas
> por WhatsApp y yo lo monto.
>
> Si esta semana no te viene bien, lo lanzamos igual con un texto que ya
> tenemos preparado — pero si nos das las novedades reales, el correo
> convierte bastante más.
>
> Aprovecho para recordarte lo otro que quedaba pendiente: **tu horario para
> las asesorías** (para que los alumnos puedan reservar llamada contigo) y
> qué hacemos con los **7 becados** en esta promoción.
>
> Un abrazo.


---

## 🔴 Nota 25-ago — la oferta caduca el 31 de agosto

Los textos de arriba se han actualizado con el precio real: **48 € el primer mes
en vez de 80 €**, para quien se matricule **antes del 31 de agosto** (40 % de
descuento; desde octubre, 80 €/mes normales).

Eso le da a esta campaña algo que no tenía cuando se escribió: **una fecha
límite**. Un correo de re-enganche con descuento y caducidad a seis días
convierte mucho mejor que uno sin ella.

**Pero también la pone en el reloj.** Si se lanza después del 31, hay que quitar
la oferta de los dos correos o estaremos prometiendo un descuento muerto. Y si se
lanza ahora, tiene que salir **ya**: los correos necesitan margen para que a
alguien le dé tiempo a decidirse.

⚠️ **Antes de lanzar, comprobar el estado de las 39 suscripciones.** Si se
reactivan en vez de cancelarse, esas personas ya son alumnos y no deben recibir
una oferta de captación.
