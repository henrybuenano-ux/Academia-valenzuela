# Calendario de asesorías — configurarlo de una vez

**26 de agosto de 2026** · verificado hoy contra la API de GoHighLevel
Calendario `Asesorías 133ª` · `HV3DnVxagoNduXbNx0UG` · slug `asesorias-133`

## Lo que descubre la verificación

El documento del 10-ago decía que el calendario servía el horario por defecto.
**Es cierto, y además ahora sabemos por qué**: lo guardado y lo que sirve el
motor **no coinciden**.

| | Guardado en la configuración | Lo que sirve el motor de huecos |
|---|---|---|
| Franjas | Lun-Vie **10:00–14:00** y **16:00–20:00** | **08:00 → 16:45**, sin parón |
| Huecos por día | — | **36** |
| Duración | 15 min | 15 min |

Comprobado pidiendo los huecos libres del 27 y 28 de agosto: los dos días
devuelven 36 huecos que empiezan a las **08:00** y acaban a las **16:45**.

**Confirma el hallazgo de julio**: el horario que se cargó por API está
almacenado pero el motor lo ignora. **Hay que reescribirlo desde la UI** — al
guardar desde el panel, GHL reescribe el formato de una forma que el motor sí
respeta. No merece la pena volver a intentarlo por API.

### Y ninguna de las dos es la de Paco

| | Franjas | Duración | Antelación mínima |
|---|---|---|---|
| **Lo que pidió Paco** (llamada 26-ago) | **9:00–13:00** y **17:00–20:00** | **30 min** | **24 h** |
| Guardado | 10:00–14:00 y 16:00–20:00 | 15 min | **1 hora** |
| Servido | 08:00–16:45 | 15 min | 1 hora |

Las franjas guardadas van **una hora corridas** respecto a las suyas, en la
mañana y en la tarde.

---

## 🔴 Hallazgo nuevo: ya hay un segundo calendario

Existe **`Paco Valenzuela's Personal Calendar`** (`Qja7BkVIzSOMK3rCEvW7`),
activo, Lun-Vie 08:00–17:00, huecos de 30 min.

Eso rompe un supuesto que dejamos escrito en la auditoría del 6-ago:

> *«el trigger no filtra por calendario — correcto mientras solo exista el de
> Asesorías; **si se añade otro, ponerle el filtro In calendar**»*

**Ya se añadió otro.** `SP02 · Asesoría agendada` dispara con cualquier cita de
cualquier calendario, así que **una reserva en la agenda personal de Paco
etiquetaría al contacto como `asesoria-agendada` y lo sacaría de SP01/LS03**.

→ Hay que ponerle a SP02 el filtro **In calendar = Asesorías 133ª**. Es un
campo en el trigger, treinta segundos, y conviene hacerlo antes que nada.

---

## Dos decisiones antes de tocar

### 1 · ¿Cuánto dura la asesoría? Hay tres números vivos

| Dónde | Duración |
|---|---|
| El prompt del bot (dos veces) | **10 minutos** |
| La descripción del propio calendario | «10-15 minutos» |
| Lo que pidió Paco en la llamada | **30 minutos** |

**Recomiendo 30**, que es lo que pidió el dueño de la agenda, y cambiar el copy
del bot para que coincida. Prometer 10 minutos y que al reservar salga un hueco
de 30 es una sorpresa en el peor momento.

*Contraargumento honesto:* «10 minutos» es un sí mucho más fácil para un lead
frío. Si se prefiere la baja fricción, se pone el calendario en 15 y el bot en
15 — pero entonces hay que decírselo a Paco, porque él pidió 30.

### 2 · ¿Quién atiende las asesorías?

El calendario está en **round robin con 3 personas** del equipo. Paco dio **sus**
horarios, no los del equipo.

- Si las asesorías las va a dar **Paco**: dejarlo solo a él y usar sus franjas.
- Si las da **el equipo**: sus franjas no son el criterio correcto, y hay que
  usar las del equipo.

**No lo decido yo.** Pero aplicar el horario de Paco a un round robin de tres es
lo peor de las dos opciones.

---

## Los pasos (UI de GoHighLevel, ~10 min)

`Calendarios → Ajustes de calendario → Asesorías 133ª → Editar`

Los nombres de los campos varían un poco según la versión del panel; van por
valor, no por pestaña exacta.

| Campo | Poner |
|---|---|
| **Duración de la cita** (*Meeting Duration*) | **30 min** — o 15, según la decisión 1 |
| **Intervalo** (*Meeting Interval*) | **30 min** — igual que la duración |
| **Horario semanal** (*Weekly Available Hours*) | Lun-Vie · **09:00–13:00** y **17:00–20:00** *(dos franjas por día)* |
| **Antelación mínima** (*Minimum Scheduling Notice*) | **24 horas** |
| **Zona horaria** | **Europe/Madrid** — verificar, la API no la devuelve |
| **Descripción** | Quitar «HORARIO PROVISIONAL — ajustar con el de Paco» y el «10-15 minutos» |

**Guardar desde el panel aunque algún valor ya parezca correcto.** El guardado
desde la UI es lo que reescribe el formato que el motor respeta; ese es el
objetivo del ejercicio.

---

## Verificar que ha funcionado (esto es lo importante)

Que el panel muestre los valores nuevos **no basta** — ya estaban guardados y el
motor los ignoraba. Hay que mirar los huecos reales:

Abrir el enlace público del calendario y comprobar, para un día laborable:

- [ ] **No aparece ningún hueco a las 08:00** ← la señal de que el cambio prendió
- [ ] El primer hueco del día es a las **09:00**
- [ ] Hay un **parón entre las 13:00 y las 17:00**
- [ ] El último hueco empieza a las **19:30**
- [ ] Los huecos van de **media hora en media hora**, no de cuarto en cuarto
- [ ] **No hay huecos en las próximas 24 h**
- [ ] Salen **14 huecos al día**, no 36

Si sigue ofreciendo las 08:00, el guardado desde la UI tampoco ha prendido y hay
que abrir incidencia con el soporte de GHL. Avisadme y lo vuelvo a medir por API.

---

## 🔴 Lo que NO hay que encender todavía

**El nodo de agenda del bot (subtarea 8 de LS02).** Hoy el bot no reserva: usa la
frase puente *«el equipo te escribe hoy mismo»*.

Sin el **Google Calendar de Paco conectado**, GHL no sabe qué tiene ocupado y
ofrecerá las 14 franjas del día como si estuvieran libres. Los leads reservarían
encima de sus clases y reuniones.

Hoy el fallo es «nadie reserva». Si se enciende antes de conectar su calendario,
el fallo pasa a ser «Paco con la agenda pisada» — peor, y justo con el lead que
más interesa.

**La llamada corta con Paco para conectar su Google Calendar deja de ser un
pendiente menor: es lo que abre la captación por calendario.**

## Orden recomendado

1. Filtro *In calendar* en SP02 — **ya**, es una mina abierta
2. Configurar el calendario desde la UI y **verificar los huecos**
3. Llamada de 15 min con Paco → conectar su Google Calendar
4. Encender el nodo de agenda en LS02 y quitar la frase puente
5. Primera reserva de prueba → confirmar que SP02 dispara y aplica
   `asesoria-agendada`
