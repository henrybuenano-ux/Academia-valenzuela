# Censo EvoCampus vs WooCommerce — 10-jul-2026 (resuelve P1)

> Datos leídos en vivo de la API de EvoCampus (ClientId 83208), que es el
> **campus de producción** (no el clon de staging). Cruce con los 59 emails de
> suscripción de WooCommerce del censo de conciliación del mismo día.

## La pregunta (P1)

El discovery apuntaba ~267 alumnos activos (de ~386) frente a solo ~60
suscripciones en WooCommerce → "¿cómo pagan los ~200 que no están en Woo?".

## La respuesta: la brecha "~300 vs 60" NO se sostiene

Los números reales del propio campus:

| Fuente | Alumnos |
|---|---|
| EvoCampus — alumnos únicos (todas las matrículas) | **104** |
| EvoCampus — con al menos una matrícula **activa** | **57** |
| WooCommerce — con suscripción | **59** |
| **En ambos** (activo en campus + suscripción Woo) | **38** |
| Activo en campus, **sin** suscripción Woo | **19** |
| Con suscripción Woo, **no** activo en campus (ya cortados) | 21 |

**El campus solo tiene 57 alumnos activos, no ~267.** Está muy cerca de los
59-60 de Woo. No existen ~200 alumnos "fantasma" pagando por fuera: como mucho
hay **19** alumnos activos que no tienen suscripción en WooCommerce (y uno de
ellos es `info@academiavalenz.com`, la cuenta de la propia academia).

Total de matrículas (enrollments) en el campus: **514** (~5 por alumno: temario,
inglés, simulacros, promoción, a veces intensivo).

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

## Preguntas para cerrar P1 del todo

1. **A Paco/Fran:** ¿de dónde sale el número de ~267? ¿Cuenta el intensivo,
   histórico, o alumnos de otra promoción/otro producto?
2. ¿Hay **otra cuenta de EvolMind** además de la 83208? (Si no, 57 activos es
   el número real y el negocio es más pequeño de lo que se creía.)
3. De los 19 sin Woo: ¿cómo pagan? (Confirma si el intensivo va por otra vía.)

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
