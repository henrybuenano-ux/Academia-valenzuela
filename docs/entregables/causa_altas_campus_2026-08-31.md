# La causa: el conector no matricula a quien ya existe en el campus

**31 de agosto de 2026** · causa identificada, correlación perfecta

## El hallazgo

| Grupo | Ya tenían cuenta en EvoCampus |
|---|---|
| **17 que NO entraron** | **17 de 17** — todos de la 132ª |
| **18 que SÍ entraron** | **0 de 18** — ninguno tenía cuenta previa |

No hay excepciones en ninguno de los dos lados.

**El conector `Pluging evolCampus for WooCommerce 3.4` crea el usuario y lo
matricula sin problema. Lo que no sabe hacer es matricular a alguien que ya
existe en la plataforma.**

## Por qué encaja con todo lo demás

- **Por qué es intermitente**: depende de si quien compra es alumno nuevo o
  ex-alumno. No hay nada aleatorio.
- **Por qué ha empeorado tanto** *(8 fallos de las últimas 10 compras)*: el
  **26-ago arreglamos el checkout** y los ex-alumnos de la 132ª pudieron
  comprar por fin. Desde entonces la proporción de ex-alumnos entre los
  compradores se ha disparado, y con ella los fallos.
- **Por qué falla también gente del 19-ago**: los pocos ex-alumnos que
  consiguieron comprar antes del arreglo (los que ya venían logueados) fallaron
  igual.

## 🔴 La consecuencia que corre

**No lancéis la campaña de recuperación hasta que esto esté arreglado.**

Los 32 de esa lista son **todos ex-alumnos de la 132ª**. Con este fallo, cada
uno de ellos pagaría y se quedaría sin acceso. Serían 32 casos como el de Óscar
Vargas, todos a la vez, y en plena semana de arranque del curso.

> Ayer quitamos el muro de la compra y les abrimos la puerta. Detrás hay un
> segundo muro que no habíamos visto. Óscar Vargas los ha atravesado los dos: no
> podía comprar, lo arreglamos, compró — y se quedó fuera del campus.

## Lo que esto descarta

| Hipótesis | Estado |
|---|---|
| Fallo de sincronización general | ❌ funciona perfectamente con alumnos nuevos |
| Estado del pedido (Procesando vs Completado) | ❌ **los 35 están en «Procesando»**, entraran o no |
| Tope de plazas del plan de EvoCampus | ❌ no explicaría la correlación con ex-alumnos |
| Cuota de API o timeouts | ❌ igual |

**Y una buena noticia**: como no es un tope, **la matriculación manual sí va a
funcionar**. Los 17 ya tienen cuenta; solo hay que añadirles las matrículas.

## Qué hacer

### 1 · Dar acceso a los 17 — antes de mañana

Ya tienen usuario en el campus. Hay que añadirles las **4 formaciones** del
grupo `133 PROMOCIÓN GUARDIA CIVIL`, con alta **01-sep-2026** y fin
**03-jul-2027**:

- CONOCIMIENTOS CURSO DE ACCESO GUARDIA CIVIL *(groupid 112)*
- PSICOTÉCNICOS CURSO DE ACCESO GUARDIA CIVIL *(115)*
- ORTOGRAFÍA Y GRAMÁTICA CURSO DE ACCESO GUARDIA CIVIL *(118)*
- INGLÉS CURSO DE ACCESO GUARDIA CIVIL *(121)*

### 2 · Que Jaime o EvolMind arreglen el conector

Con la causa identificada es un encargo concreto: **el alta debe funcionar
cuando el correo ya existe en la plataforma**, matriculando al usuario existente
en vez de intentar crearlo de nuevo.

Merece la pena pedir el log del conector para uno de los 17: dirá el error exacto
(probablemente un «el usuario ya existe» que aborta el proceso entero).

### 3 · Congelar la campaña de recuperación

Hasta que el punto 2 esté hecho y verificado con una compra de prueba **de un
ex-alumno**.

### 4 · Vigilar cada compra nueva

Mientras no esté arreglado, cada venta a un ex-alumno se queda sin acceso. Hay
que cruzar suscripciones contra matrículas a diario.

---

# Aviso para Paco y Horacio — listo para enviar

**Asunto:** Encontrada la causa: el alta falla con los alumnos que repiten

Hola Paco, hola Horacio,

Ya tenemos la causa, y es muy concreta.

**El alta automática funciona con los alumnos nuevos y falla con los que ya
estuvieron en la 132ª.** Sin excepciones: de los 17 que no tienen acceso, los 17
ya tenían cuenta en la plataforma; de los 18 que sí la tienen, ninguno la tenía.

El conector sabe crear un alumno nuevo y matricularlo. Lo que no sabe es
matricular a alguien que ya existe.

Eso explica por qué ha ido a peor justo esta semana: el martes arreglamos el
problema que impedía comprar a los ex-alumnos, empezaron a matricularse, y se
han topado con esto.

**Tres cosas:**

1. **Damos acceso a los 17 a mano** antes de mañana. Al no ser un problema de
   plazas, esto sí funciona.
2. **Habría que pedirle a Jaime que corrija el conector** para que matricule a
   quien ya existe. Con la causa localizada debería ser rápido.
3. **Conviene esperar** a tener eso arreglado antes de llamar a los antiguos
   alumnos para que vuelvan: son todos ex-alumnos y les pasaría lo mismo.

Un saludo.
