# Censo de conciliación — staging, 10-jul-2026 (DRY-RUN, plugin v0.5.2)

> Ejecutado desde la página "WooCommerce → EvoCampus Sync" del staging
> (https://academiavalenz.com/staging), ventana de pago 35 días, y cruzado
> contra la API de EvoCampus en vivo (`getEnrollments`, solo lecturas).
> 59 de 59 alumnos evaluados en 64 s, sin errores.

## Resumen ejecutivo

| Métrica | Valor |
|---|---|
| Alumnos únicos en WooCommerce (con suscripción) | **59** |
| Al corriente (último pago ≤ 35 días) | **22** |
| En baja de pago según la ventana | **37** |
| — de ellos, "frontera" (36–40 días) | 13 |
| — de ellos, morosos claros (63–197 días) | 24 |
| **Impagados que HOY conservan acceso activo al campus** | **16 de 37** |

**Los 3 hallazgos que valen la demo a Paco:**

1. **Fuga real de acceso**: 3 morosos claros siguen dentro del campus y lo
   usan — `rubenjimenezmolina95@…` (63 días sin pagar, conectado HOY),
   `smartosperez13@…` (68 días, conectado el 8-jul) y `barrachinaten@…`
   (**144 días** sin pagar, conectado el 17-jun). A ~80 €/mes, solo estos
   tres suponen ≈ 720 € ya no cobrados, y la fuga es silenciosa: nadie la ve
   sin revisar uno a uno. El plugin la corta en el primer pase real.
2. **El corte manual funciona… tarde y con huecos**: los otros 21 morosos de
   más de 66 días sí están dados de baja en el campus (alguien los cortó a
   mano), pero los 3 anteriores se escaparon. Es exactamente el dolor #1 del
   discovery.
3. **El cobro de julio no se ha lanzado** (evidencia para P2): los 13
   "frontera" (36–40 días) y la mayoría de los 22 activos (33–35 días)
   pagaron por última vez a primeros de junio. A 10 de julio no existe ningún
   pedido de julio. Si los cobros mensuales no corren solos, en 1–2 semanas
   TODO el censo caería en "baja". **Bloquea B6**: hay que saber quién/qué
   lanza las renovaciones antes de activar el modo real.

**P1 sigue abierto**: solo hay 59 alumnos con suscripción en Woo frente a
~267 activos que declara la academia. Los ~200 restantes no pagan por
WooCommerce (¿transferencia?, ¿tarjeta guardada fuera?, ¿otra tienda?).

## Censo completo (59)

### Al corriente — 22 (último pago ≤ 35 días)

| Email | Último pago |
|---|---|
| lavega_94@hotmail.com | hace 20 días |
| sergioaguileraramirez17@gmail.com | hace 25 días |
| andreasanchezjusticia@gmail.com | hace 26 días |
| anaruizrueda8@gmail.com | hace 28 días |
| manuel.rodriguez16102007@gmail.com | hace 30 días |
| js8076324@gmail.com | hace 30 días |
| Musta_18@hotmail.es | hace 31 días |
| andreafur6@gmail.com | hace 33 días |
| rosajimena78@gmail.com | hace 33 días |
| davidmotril11@gmail.com | hace 34 días |
| barrancofborja86@gmail.com | hace 34 días |
| mlnazareth2005@gmail.com | hace 34 días |
| elenahurjus@gmail.com | hace 34 días |
| rudylozanorueda@gmail.com | hace 35 días |
| josemanuel070488@gmail.com | hace 35 días |
| antoniocantarerogamez@gmail.com | hace 35 días |
| samuelgarciaavila@gmail.com | hace 35 días |
| victorabad695@gmail.com | hace 35 días |
| jaimerome150@gmail.com | hace 35 días |
| anaraidanievassantiago30@gmail.com | hace 35 días |
| rafaelluiscastillo20@gmail.com | hace 35 días |
| pablosualop@gmail.com | hace 35 días |

### En baja de pago — 37, cruzados con EvoCampus

`Activas` = matrículas con `enrollmentstatus=0` hoy en el campus (de 6-7
matrículas por alumno: temario, inglés, simulacros…). Grupos: 132ª promoción
GC + inglés + promociones anteriores.

| Días sin pagar | Email | Matrículas activas HOY | Última conexión al campus |
|---:|---|---:|---|
| 36 | estelaromero.ab@gmail.com | 6 | 17-jun 13:04 |
| 36 | trompocambil@gmail.com | 6 | **10-jul 17:41** |
| 36 | isabeldelrio97@gmail.com | 6 | **10-jul 16:54** |
| 37 | rafaluque1995@hotmail.com | 6 | 09-jul 10:45 |
| 37 | cris.atleta89@gmail.com | 6 | **10-jul 11:03** |
| 37 | samdl2003@icloud.com | 6 | **10-jul 14:49** |
| 38 | Morata__8@hotmail.com | 6 | 09-jul 15:19 |
| 39 | blancamenaalbarran@hotmail.com | 6 | 03-jul 20:11 |
| 39 | oscarbelmez@gmail.com | 6 | **10-jul 09:33** |
| 39 | pedrocallejo.rc@gmail.com | 6 | 09-jul 16:38 |
| 39 | nataliaamador97@gmail.com | 6 | 09-jul 19:46 |
| 39 | elisabet_rd@hotmail.es | 6 | **10-jul 12:58** |
| 40 | martasdpf@gmail.com | 6 | 09-jul 17:55 |
| 63 | **rubenjimenezmolina95@gmail.com** | **6** | **10-jul 08:57** |
| 66 | Jesusbenifaio@hotmail.com | 0 | 22-abr |
| 68 | **smartosperez13@gmail.com** | **6** | 08-jul 14:48 |
| 70 | franciscojruiz89@gmail.com | 0 | 01-may |
| 95 | miriamperezmarcos@gmail.com | 0 | 09-jun |
| 98 | jorgektm200@hotmail.com | 0 | 02-feb |
| 99 | frankasso@icloud.com | 0 | 30-abr |
| 125 | evelynbenaventesanchez@gmail.com | 0 | 17-mar |
| 127 | castillocarreteromarta@gmail.com | 0 | 09-mar |
| 127 | huelmamaria18@gmail.com | 0 | 06-abr |
| 128 | sergiodesecundinomar@gmail.com | 0 | 06-abr |
| 129 | 2305fmdt@gmail.com | 0 | 08-may |
| 129 | montesjc.97@gmail.com | 0 | 15-mar |
| 129 | felixmanuelfebrero@gmail.com | 0 | 15-dic-25 |
| 130 | robertogarciasoriano13@gmail.com | 0 | 04-oct-25 |
| 133 | zmalovelondon@gmail.com | 0 | 11-mar |
| 144 | **barrachinaten@gmail.com** | **6** | 17-jun 14:14 |
| 155 | fermrtnz1806@gmail.com | 0 | 20-abr |
| 159 | dquesada016@gmail.com | 0 | 05-ene |
| 163 | david658051391martinez@gmail.com | 0 | 23-feb |
| 166 | cristianotero68@gmail.com | 0 | 24-feb |
| 182 | tania1987romero@gmail.com | 0 | 08-feb |
| 185 | lauramatillasraya@gmail.com | 0 | 30-ene |
| 197 | mendoza.david14@gmail.com | 0 | 02-dic-25 |

## Validaciones técnicas de esta ejecución

- Plugin v0.5.2 en staging: página de administración operativa (bug
  `CRON_HOOK` corregido), conciliación desde el botón, 352 líneas de log,
  0 errores, todos los webhooks GHL → HTTP 200 (51 disparos DRY-RUN).
- API EvoCampus validada también por curl directo: `POST /v1/token`
  (form-encoded) OK · `getEnrollments` con `email/page/regs_per_page` OK.
  Confirmado que `person.enrollmentid` y `enroll.enrollmentstatus` son los
  campos correctos. El filtro `active=true/false` sigue pareciendo ignorado
  por la API (devuelve todas las matrículas) — inofensivo en DRY-RUN;
  verificar el efecto de `updateEnrollment` en la primera prueba real.
- Bug de paginación corregido: `wcs_get_subscriptions` con `paged` devolvía
  siempre la primera página (49 alumnos en vez de 59); ahora se recolecta
  todo en una consulta.
- Anomalía de arranque en staging (aún sin causa raíz): una inclusión
  temprana ajena define la clase del plugin sin arrancarla, y la carga
  normal chocaba con el guard → el plugin quedaba muerto (sin cron, sin
  menú). La v0.5.2 lo detecta y arranca la clase existente (rescate).
