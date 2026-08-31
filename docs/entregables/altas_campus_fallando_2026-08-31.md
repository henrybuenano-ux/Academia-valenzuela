# 🔴 17 alumnos han pagado y no tienen acceso al campus

**31 de agosto de 2026** · el curso empieza **el martes 1 de septiembre**
Contrastado el listado de suscripciones de WooCommerce contra `getEnrollments`
de EvoCampus.

## El dato

| | |
|---|---|
| Suscripciones **activas** de la 133ª en WooCommerce | **35** |
| Personas matriculadas en la 133ª en EvoCampus | **18** |
| **Han pagado y no tienen acceso** | **17** |

**Paco tenía razón.** Su «34 apuntados y 15 en la web» describía esto: 35
suscripciones frente a 18 matrículas.

## Corrección de lo que escribimos esta mañana

En `desfase_campus_web_2026-08-31.md` concluimos que no faltaba nadie. **Es
falso.** El error: se compararon los 18 del campus contra los **16 pedidos del
25-ago** —una cifra de dos días antes— y se dio el cuadre por bueno. Nunca se
verificó cuántas suscripciones había hoy.

Se confirmó una hipótesis con un dato caducado en vez de ir a buscar el actual.
Ese documento queda **superado por este**.

Lo que sí sigue siendo cierto de aquel análisis: cada alumno se matricula en las
4 formaciones (18 × 4 = 72 matrículas), y eso explica por qué el número de
matrículas nunca iba a cuadrar con el de pedidos.

## Los 17 sin acceso

| Suscripción | Alumno | Compró |
|---|---|---|
| #2179 | Rudy Lozano | hace 4 h |
| #2174 | Andrea Fuentes | hace 17 h |
| #2170 | Elena Hurtado | hace 21 h |
| #2168 | Jose Manuel Bobis | hace 1 día |
| #2166 | Blanca Mena | hace 1 día |
| #2165 | Juan Antonio Acosta | hace 2 días |
| **#2163** | **Oscar Vargas** | hace 2 días |
| #2161 | Jaime Romero | hace 2 días |
| #2153 | Cristóbal Valenzuela | hace 4 días |
| #2149 | Mirella Crespo | hace 5 días |
| #2147 | Rafael Luis Castillo | hace 5 días |
| #2145 | Rafael Alberto Luque | hace 5 días |
| #2138 | Alejandro Crespo Suarez | hace 5 días |
| #2128 | Elisabet Rivera | hace 6 días |
| #2110 | Félix Manuel Febrero | 19-ago |
| #2108 | Pedro Callejo Albarrán | 19-ago |
| #2104 | Pablo Suárez López | 19-ago |

**Óscar Vargas está en la lista.** Es el caso que Paco reportó el 26-ago por no
poder inscribirse; compró tras el arreglo del checkout y se ha quedado sin
acceso. Conviene resolverlo antes de contestarle nada más sobre él.

## El fallo es intermitente y va a peor

| Tramo | Altas fallidas |
|---|---|
| Primeras 25 suscripciones (#2088 → #2157) | 9 de 25 · **36 %** |
| **Últimas 10 (#2161 → #2179)** | **8 de 10 · 80 %** |

No hay corte limpio por fecha: el **19-ago** entraron dos altas (#2102, #2106) y
fallaron tres (#2104, #2108, #2110) el mismo día.

Un fallo intermitente que empeora con el volumen apunta a **límite de plazas del
plan de EvoCampus, cuota de la API o timeouts** — no a un ajuste mal puesto, que
fallaría siempre.

> Dato para contrastar: hoy hay **117 matrículas activas** en toda la
> plataforma (72 de la 133ª + 45 de entrevista). Si el plan tiene un tope de
> alumnos o de matrículas, merece la pena mirarlo primero: es la explicación que
> encaja con «funciona a veces y cada vez menos».

## Qué hacer, por orden

### 1 · Dar acceso a los 17, antes de mañana

Es lo que corre. Han pagado y el curso empieza en cuatro días. Se puede hacer a
mano en el campus, o reprocesando los pedidos si el conector lo permite.

**Ojo al hacerlo:** cada alumno necesita las **4 formaciones**
(Conocimientos, Psicotécnicos, Ortografía y Gramática, Inglés), con alta
**01-sep-2026** y fin **03-jul-2027**, que es lo que tienen los 18 que sí
entraron.

### 2 · Averiguar por qué falla

El conector es `Pluging evolCampus for woocommerce`, de Jaime. Por orden de
probabilidad:

- **Tope de plazas / matrículas del plan de EvoCampus.** Lo primero a mirar.
- Cuota o límite de peticiones de la API.
- Timeout en la llamada de alta, con el pedido completándose igualmente.
- Log del conector en producción, si lo tiene.

### 3 · Vigilarlo mientras dure

Hasta que esté resuelto, **contrastar cada día** suscripciones activas contra
matriculados. Con el volumen actual son dos consultas.

## Lo que esto NO es

- **No es el arreglo del checkout de ayer.** Ese permitía comprar; estos han
  comprado. Son dos cosas distintas y la de ayer funciona.
- **No es el plugin de conciliación de Omnia.** Ese da de baja a quien no paga;
  no matricula a nadie. Está en DRY-RUN y no ha tocado nada.

---

# Aviso para Paco y Horacio — listo para enviar

**Asunto:** Tenías razón: 17 alumnos han pagado y no tienen acceso

Hola Paco, hola Horacio,

Rectifico lo que dije esta mañana: **tenías razón, Paco.** Hemos cruzado las
suscripciones de la tienda con las matrículas de la plataforma y **faltan 17
alumnos**: han pagado y no tienen acceso al campus.

Son 35 suscripciones activas en la tienda y 18 alumnos matriculados. Os pasamos
la lista con nombre y número de suscripción.

**Entre ellos está Óscar Vargas**, el que no podía inscribirse: consiguió
comprar y se ha quedado fuera.

Lo urgente es **darles acceso antes de mañana**, que empieza el curso. Cada uno
necesita las cuatro formaciones, con alta el 1 de septiembre y fin el 3 de
julio, igual que los 18 que sí entraron.

En paralelo miramos por qué falla el alta automática. No es constante: falla a
ratos y cada vez más —de las últimas 10 compras, 8 no llegaron—, lo que suele
apuntar a un tope de plazas del plan de la plataforma o a un límite de la API.
Empezamos por ahí.

Un saludo.
