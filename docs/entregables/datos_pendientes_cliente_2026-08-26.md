# Lo que necesitamos del cliente · 26-ago-2026

> Todo lo que hoy está parado esperando un dato o una decisión suya. Cada línea
> dice **qué desbloquea**, para que se entienda por qué lo pedimos.
>
> Separado a propósito en **datos** (nos los pasan y ya) y **decisiones** (hay
> que pensarlas). Los datos se pueden recoger en una llamada de diez minutos;
> las decisiones no.
>
> ## ✅ ACTUALIZADO tras la llamada del 26-ago
>
> La llamada con Paco cerró **seis** de estos pendientes. Lo tachado abajo ya no
> hay que pedirlo. Acta completa en `llamada_2026-08-26_acuerdos.md`.
>
> | Resuelto | Valor |
> |---|---|
> | Nombre fiscal | **Francisco Valenzuela Rodríguez** |
> | Domicilio fiscal | **Camino de Ronda 57, 2º F · 18004 Granada** |
> | IVA | Exento — *de palabra, falta el respaldo escrito de la gestoría* |
> | Horario de asesorías | **9:00-13:00 y 17:00-20:00** · 30 min · 24 h de antelación |
> | Curso de entrevista | Altas manuales de Paco, con otra forma de pago. Quiere que queden fuera de facturación y avisos |
> | Aterrizaje de campañas | Acordado moverlas a la landing con formulario |
>
> **Y entraron dos nuevos, los dos con fecha:**
>
> 1. **Ex-alumnos de la 132ª no pueden re-matricularse** — al meter su email, la
>    tienda no les deja terminar la compra. **Causa confirmada y arreglada en
>    producción** el mismo 26-ago: faltaba la casilla «Activar el inicio de
>    sesión durante el pago». Queda pendiente **la compra de prueba** que lo
>    confirme antes de llamar a nadie.
> 2. **El precio de lanzamiento no caduca solo.** Las fechas del producto están
>    puestas sobre un campo vacío y no hacen nada; los 48 € son cuota de
>    registro, fija. **El 1-sep hay que subirla a mano.** Detalle en
>    `precio_lanzamiento_caduca_31ago.md`.

---

# 🔴 URGENTE — tiene fecha

## 1 · Datos fiscales de Paco → desbloquea TODA la facturación

Tenemos el NIF (**26956058N**, persona física) porque estaba en el plan. Nos
faltan dos campos que son **obligatorios por ley** en una factura
(art. 6 del RD 1619/2012):

- [x] ~~Nombre y apellidos fiscales completos~~ → **Francisco Valenzuela Rodríguez**
- [x] ~~Domicilio fiscal~~ → **Camino de Ronda 57, 2º F · 18004 Granada**

Sin esto no se puede emitir ni una factura, ni de las nuevas ni de las
atrasadas. Es lo más barato de conseguir y lo que más bloquea.

## 2 · ¿El curso está exento de IVA? → lo confirma la gestoría, no nosotros

- [~] Paco confirmó de palabra que está exento. **Sigue faltando el respaldo por escrito de la gestoría** antes de emitir a volumen

**Ya se está vendiendo sin repercutir IVA.** Si resulta que no está exento, se
debe el 21 % de todo lo vendido, hacia atrás. Hoy son ~768 € de las 16
matrículas nuevas, más lo acumulado desde noviembre de 2025. Poco dinero
todavía: por eso conviene cerrarlo ahora y no en enero.

## 3 · WhatsApp → la aprobación de Meta tarda semanas y el curso empieza el 1-sep

- [x] ~~Un número de teléfono~~ → tienen un móvil dedicado a la academia, **sin WhatsApp Business**. Sirve
- [ ] **Acceso a su Facebook Business Manager** (o que nos añadan)
- [ ] **Datos para la verificación de empresa** de Meta: nombre legal,
      dirección y web

El bot le pide el WhatsApp a cada lead y le promete que le escribimos por ahí.
Hoy eso se contesta a mano desde móviles personales. Si el trámite no arranca
esta semana, no llega al arranque del curso.

---

# 🟡 Bloquean trabajo ya hecho

## 4 · Horario real de asesorías de Paco

- [x] ~~Franjas horarias~~ → **9:00-13:00 y 17:00-20:00**, citas de 30 min, 24 h de antelación mínima

El calendario está sirviendo el horario **por defecto de GoHighLevel: 36 huecos
de 08:00 a 16:45, de lunes a viernes**. Un interesado puede reservar a las 8 de
la mañana y no encontrar a nadie. Son diez minutos de configuración en cuanto
tengamos las franjas.

## 5 · Novedades de la 133ª

- [ ] **2-3 frases** sobre qué cambia respecto a la 132ª (temario, campus,
      simulacros, resultados si se pueden dar)

Es lo único que falta para lanzar la campaña de re-enganche a 69 ex-alumnos,
que lleva escrita desde el 10 de agosto. *(Tenemos una versión alternativa que
no lo necesita, por si no llega.)*

## 6 · El curso de entrevista

- [x] ~~¿Se cobra aparte?~~ → altas manuales de Paco para la entrevista, con otra forma de pago. Quiere que queden fuera de facturación y de avisos

Hay **30 alumnos** del grupo `ENTREVISTA 2026 PROMOCIÓN 132GC` con acceso
activo al campus, conectándose esta misma semana, y **sin ningún registro en la
tienda**: ni pedido, ni usuario, ni suscripción. Son altas manuales posteriores
al examen de julio. No hay riesgo técnico —el sistema no los toca— pero
conviene saber si es lo previsto.

## 7 · Campañas: qué hay corriendo y dónde

- [ ] **Qué plataformas** (Google Ads, Meta, otras) y con qué presupuesto
- [ ] **Acceso a las cuentas de anuncios**, o a quien las lleve

De los 16 pedidos, **ninguno aparece como campaña**: 10 "Directo", 3 "Orgánico:
Google", 3 "Fuente: Google". Un 63 % de tráfico directo es anormalmente alto
para quien invierte en publicidad. O las campañas no están etiquetadas, o son
de Meta (cuyo navegador interno borra el origen). **Hoy no se puede saber qué
devuelve la inversión en anuncios.** Se arregla en una tarde: auto-etiquetado
en Google Ads y UTMs en los enlaces de Meta.

---

# ⚪ Accesos técnicos

## 8 · GoHighLevel

- [ ] **Un PIT** (Private Integration Token) de la sub-cuenta

Hoy trabajamos con un token de Firebase que caduca y hay que renovar a mano.
Con un PIT las consultas son estables. *(El que teníamos apunta a otra
sub-cuenta.)*

- [ ] **¿Están contratados los créditos de IA? ¿Se agotaron?**

Es la primera sospecha de por qué el bot se cayó del 19 al 25 de agosto.

- [ ] **¿Quién apagó el bot y por qué?**

GHL guarda registro de actividad de la sub-cuenta. Si fue un despiste se
corrige hablando; si fue un cambio de configuración, puede repetirse.

## 9 · WordPress

- [ ] **Que desbloqueen nuestra IP** en el firewall del hosting, o confirmar
      que seguimos con el sistema actual (ellos aplican, nosotros verificamos)

El login de producción rechaza **todos** los intentos sin mostrar error — probado
con usuario inventado y con uno real, mismo resultado. No es cuestión de
credenciales: algo bloquea antes de comprobarlas.

- [ ] **Permiso para cambiar el huso horario del sitio**

Está en **`America/Buenos_Aires`**, cinco horas por detrás de Madrid. Afecta a
la fecha de expedición de las facturas, que es un campo obligatorio.

---

# Decisiones (no son datos, pero van en la misma conversación)

| # | Decisión | Por qué corre |
|---|---|---|
| A | **Las 39 suscripciones pausadas** desde el 24-jun: ¿cancelar, reactivar o dejar expirar? | Son ~3.100 €/mes sin facturar, y **bloquean el paso del plugin a modo real**: sin decidirlo, el primer censo real arranca sucio |
| B | **Los 7 becados**: opción A o B (propuesta enviada el 17-jul, sin respuesta) | Segundo bloqueo del modo real |
| C | **Numeración de facturas**: ¿anual `26-0001` o mensual `26/08-0001`? | La mensual obliga a plugin de pago, y el huso horario la rompería en los cambios de mes. Recomendamos anual |
| D | **El atrasado desde nov-2025**: ¿quién emite esas facturas? | No es trabajo nuestro, pero es volumen y es su responsabilidad |
| E | **Dónde aterriza el tráfico de campañas** | Hoy va a la home, que solo sirve al que ya viene decidido. El que aún compara no tiene dónde dejar sus datos |
| F | **El botón "Infórmate sin compromiso"** en la home hacia la landing | Es la segunda vía de captura que faltó durante la caída del bot |
| ~~G~~ ✅ | ~~**¿Los 48 € son solo de lanzamiento?**~~ **Resuelta sin preguntar**: la landing y el bot dicen «primer mes 48 € en vez de 80», «40 % de descuento» y «sin matrícula» — es el primer mes rebajado, no una matrícula. Lo que queda no es una decisión de precio sino **de configuración**, y es nuestra: ver `precio_lanzamiento_caduca_31ago.md` |

---

## ⏰ Con fecha, y no dependen del cliente

| Cuándo | Qué | Quién |
|---|---|---|
| ~~Ya~~ ✅ | Marcar «Activar el inicio de sesión durante el pago» en Cuentas y privacidad | Hecho 26-ago |
| ~~Ya~~ ✅ | «Limitar suscripción» del `#2054` (pestaña **Avanzado**) → «No limitar» | Hecho 26-ago |
| ~~Ya~~ ✅ | Verificado que el formulario de acceso aparece en la página de compra | Hecho 26-ago |
| **Antes de llamar** | Añadir al guion la instrucción de «¿Olvidó su contraseña?» | Quien llame |
| **1-sep** | **Reconfigurar el `#2054`** — no basta con subir la cuota de registro: la combinación prueba + sincronización deja huecos de hasta 60 días. Opciones y recomendación en `precio_lanzamiento_caduca_31ago.md` | Equipo (wp-admin) |
| **1-sep** | Actualizar landing y prompt del bot con el precio nuevo | Omnia |

✅ **Ya se puede llamar a los 32 de la lista de recuperación** — el muro está
caído. Con la instrucción de la contraseña olvidada en el guion.

---

## Lo que NO hay que pedirles

Por si alguien lo pregunta en la reunión, esto **ya está resuelto** y no
necesita nada de ellos:

- El plugin de EvoCampus está **desplegado en producción** y verificado
  (71 de 71 alumnos en 15 s, censo cuadrado con los pedidos).
- La web está limpia: el botón principal daba **404** y está arreglado, junto
  con el copy obsoleto de la 132ª.
- El bot **está levantado** y ya da el precio nuevo.
- La landing y el bot anuncian los **48 € hasta el 31 de agosto**.
- Los pedidos ya capturan **DNI, dirección y teléfono**: la facturación
  automática tiene todo lo que exige la ley sin pedirle nada al alumno.
