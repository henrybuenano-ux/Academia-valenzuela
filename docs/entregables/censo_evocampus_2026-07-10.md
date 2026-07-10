# Censo EvoCampus vs WooCommerce — 10-jul-2026 (resuelve P1)

> Datos leídos en vivo de la API de EvoCampus (ClientId 83208), que es el
> **campus de producción** (no el clon de staging). Cruce con los 59 emails de
> suscripción de WooCommerce del censo de conciliación del mismo día.

## La pregunta (P1)

El discovery apuntaba ~267 alumnos activos (de ~386) frente a solo ~60
suscripciones en WooCommerce → "¿cómo pagan los ~200 que no están en Woo?".

## La respuesta: el "~267" era el TOTAL histórico, no los activos

**Confirmado con el panel de EvoCampus** (captura del cliente, 10-jul-2026):

| Alumnos (panel) | | Matrículas (panel) | |
|---|---:|---|---:|
| **Activos** | **57** | Activas | 344 |
| No activos | 192 | Bajas | 161 |
| Archivados | 4 | Archivadas | 9 |
| **Ver todos** | **253** | **Ver todas** | **514** |

**La clave: el campus tiene 57 alumnos ACTIVOS y 253 en TOTAL (histórico).** El
"~267 activos / ~386 total" del discovery era en realidad el acumulado histórico
de alumnos que han pasado por el campus, no la foto actual. **Paco confundió el
total con los activos.** No hay ~200 alumnos fantasma pagando por fuera.

Cruce de los 57 activos con WooCommerce:

| Fuente | Alumnos |
|---|---|
| EvoCampus — con al menos una matrícula **activa** (panel-confirmado) | **57** |
| WooCommerce — con suscripción | 59 |
| **En ambos** (activo en campus + suscripción Woo) | 38 |
| Activo en campus, **sin** suscripción Woo | 19 |
| Con suscripción Woo, **no** activo en campus (ya cortados) | 21 |

Total de matrículas (enrollments) en el campus: **514** (coincide con el panel;
~6 por alumno activo: temario, inglés, simulacros, promoción, a veces intensivo).

## Distribución de los 57 activos por grupo

| Alumnos activos | Grupo |
|---:|---|
| 57 | 132 PROMOCIÓN GC INGLÉS (grupo paraguas) |
| 57 | PROMOCIÓN 132 GC (grupo paraguas) |
| 45 | 132 PROMOCIÓN GUARDIA CIVIL |
| 13 | 132 GC INTENSIVO |
| 3 | 173 COLEGIO GUARDIAS JÓVENES |
| 2 | PSICÓLOGO 2025 |

## Los 19 activos en el campus SIN suscripción en Woo (a revisar con Paco)

Son el verdadero P1, pero reducido de ~200 a 19. Hay que preguntar cómo acceden:
¿pago por transferencia/Bizum?, ¿tarjeta guardada fuera de Woo?, ¿intensivo de
pago único que no genera suscripción?, ¿acceso de cortesía/prueba?

```
alejandrocs2509@gmail.com      juanjolirio2003@gmail.com    peto-reala@hotmail.es
anabelgar1515@gmail.com        juncaluam@gmail.com          sanchezbermejo10@gmail.com
charidominguez99@gmail.com     laraloume@hotmail.com        santiml.89@hotmail.com
danielsanchezluc55@gmail.com   marioventapuer@hotmail.com   sergiohuelma_123@hotmail.com
fabioclve@proton.me            mirellacrespo29@gmail.com    xabiif14@gmail.com
gabricasta19@gmail.com         nacholgo@icloud.com
info@academiavalenz.com        (cuenta de la academia)
jmbobis10@gmail.com
```

Pista: los del grupo **132 GC INTENSIVO** (13) probablemente son pago único
(abril–julio), que NO crea una suscripción recurrente en WooCommerce → explica
buena parte de estos 19.

## Retrato de los 7 sin pago en Woo (detalle de matrícula EvoCampus)

Panel de EvoCampus (`campus.academiavalenz.com`) NO accesible desde el entorno
(bloqueado por la política de red, 403 al CONNECT). Datos vía API:

| Alumno | Tipo | Inicio→Fin | Estado | Última conexión |
|---|---|---|---|---|
| danielsanchezluc55 | Intensivo | 13-abr → 10-jul | activa | 08-jul |
| mirellacrespo29 | Intensivo | 13-abr → 10-jul | activa | 25-jun |
| juanjolirio2003 | Regular | 01-oct-25 → 10-jul | activa | 01-jul |
| juncaluam | Regular | 01-oct-25 → 10-jul | activa | 23-abr |
| marioventapuer | Regular | 01-oct-25 → 10-jul | activa | 28-may |
| santiml.89 | Regular (+PSICÓLOGO 2025 baja) | 01-oct-25 → 10-jul | activa | 05-jul |
| sergiohuelma_123 | Regular | 01-oct-25 → 10-jul | activa | 01-jul |

- 2 son intensivo con acceso pero sin pedido Woo (¿transferencia/alta manual?).
- 5 son de la promoción regular (curso completo desde octubre) sin ningún
  rastro de pago en Woo → los verdaderos casos a aclarar con Paco.

## P2 (cobros de julio): hipótesis fuerte con los datos

**TODAS las matrículas de la 132ª promoción terminan el 10-11 de julio de 2026**
(fecha del examen). El curso regular va 01-oct-2025 → 10-jul-2026 (~10 meses de
cuota mensual); el intensivo, 13-abr → 10-jul. Es decir, **el curso está
acabando justo ahora**.

Esto explica de forma natural **por qué no hay pedidos de julio**: no es que la
facturación esté rota, es que la promoción anual se cierra y el último cargo
mensual fue en junio. Los 22 "al corriente" del censo pagaron hace 20-35 días
(su cuota de junio); en julio ya no hay nada que cobrar de esta promoción.

**Implicación para el paso a producción (B6):** conviene NO activar el corte
por impago justo al final del curso (todos caerían en "baja" por no haber cargo
de julio, cuando en realidad han terminado el curso pagado). El plugin debería
entrar en real con la **133ª promoción** (nuevo ciclo en otoño), o bien
respetar la fecha fin de matrícula de EvoCampus. A confirmar con Paco el
calendario de la nueva promoción.

## Estado de P1 (RESUELTO)

- ✅ ¿De dónde sale el ~267? → Era el TOTAL histórico de alumnos (panel: 253),
  no los activos. Los activos reales son **57** (panel-confirmado).
- ✅ Tamaño real del negocio activo: **57 alumnos**, de los que **49 pagan por
  Woo** (38 suscripción + 11 intensivo). El negocio recurrente es más pequeño
  de lo que sugería el ~267.
- ⏳ Queda menor: de los **7** sin pago en Woo, ¿cómo acceden? (2 intensivo,
  5 promoción regular). Pregunta para Paco, pero no bloquea.

## Los 19 "sin Woo", desglosados por pedidos (consulta a WooCommerce)

Se revisaron los **pedidos** (no solo suscripciones) de esos 19 en Woo:

- **11 tienen un pedido de pago único del INTENSIVO** — "Intensivo Ingreso
  Guardia Civil 132ª promoción" (270–295 €, estado *processing*, marzo–abril
  2026). Pagan por Woo, pero con un pedido único, no una suscripción → por eso
  el censo (que miró suscripciones) no los contó.
- **8 sin NINGÚN pedido en Woo** — de ellos uno es `info@academiavalenz.com`
  (cuenta de la academia) → **7 alumnos reales** sin rastro de pago en Woo:
  danielsanchezluc55, juanjolirio2003, juncaluam, marioventapuer,
  mirellacrespo29, santiml.89, sergiohuelma_123. (¿acceso de cortesía/prueba?,
  ¿beca?, ¿pago fuera de Woo?, ¿alumnos antiguos con acceso vivo?)

### Balance final de los 57 activos

| Cómo pagan | Alumnos |
|---|---|
| Suscripción recurrente en Woo | 38 |
| Pedido único de intensivo en Woo | 11 |
| **Pagan por Woo (total)** | **49 (86%)** |
| Sin rastro de pago en Woo (1 es la academia) | 8 → **7 reales** |

**Conclusión: no hay ~200 alumnos fantasma. 49 de 57 activos pagan por Woo;
quedan 7 por aclarar con Paco.** El número ~267 no lo respalda ningún dato.

## Implicación para el plugin

La conciliación arma la lista de alumnos a partir de **suscripciones**
(`wcs_get_subscriptions`), así que los **11 alumnos de intensivo (pago único)
son invisibles para ella** — nunca se evalúan. Para el intensivo, el acceso
debe regirse por la **fecha fin de matrícula en EvoCampus** (`enroll.end`,
abril→julio hasta el examen), no por pago recurrente: expira solo. Opciones:
1. Dejar el intensivo fuera del plugin (expira por fecha fin en EvoCampus). Es
   lo más simple y probablemente correcto.
2. Si se quiere cubrir también, añadir un segundo pase de conciliación que
   recorra los pedidos de intensivo (producto único) y controle acceso por
   fecha fin, no por recurrencia.
A decidir con negocio (Paco). Los 7 sin pago en Woo se revisan aparte.
