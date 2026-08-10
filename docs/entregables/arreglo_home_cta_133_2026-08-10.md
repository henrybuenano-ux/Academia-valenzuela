# Arreglo de la home: CTA roto + copy de la 132ª · 10-ago-2026

> **Urgencia**: el botón más visible de `academiavalenz.com` devuelve **404**
> y la portada anuncia la promoción que ya terminó. La 133ª arranca el
> **1-sep (22 días)** y su curso ya está a la venta. Esto son 3 minutos de
> edición y es, hoy, lo más rentable del proyecto.

## Alcance: solo la home (verificado)

Barrido de todo el sitio el 10-ago — enlaces al producto muerto y menciones
obsoletas:

| Página | Enlaces al 132 (404) | "132ª" | "2025" |
|---|---|---|---|
| **/** (home) | **1** 🔴 | 2 | 1 |
| /preparacion-de-oposiciones/ | 0 | 0 | 0 |
| /guardia-civil/ | 0 | 0 | 0 |
| /nosotros/ | 0 | 0 | 0 |
| /tienda/ | 0 | 0 | 0 |
| /novedades/ | 0 | 0 | 0 |
| /producto/…-133a-promocion/ | 0 | 0 | 0 |

**Un solo bloque que tocar.** (Nota: `/contacto/` da 404 — el menú no lo
enlaza, así que no urge, pero conviene revisarlo.)

## Dónde está

Home → bloque destacado del curso (Elementor). IDs de elementos cercanos por
si ayuda a localizarlo en el editor: `f5b7a8a`, `df19fdb`, `2902ae9`.
Se reconoce por el H2 "Curso Ingreso Guardia Civil – 132ª Promoción".

## Los 3 cambios

### 1. El botón (lo crítico — quita el 404)

| | |
|---|---|
| Texto | `Apúntate ahora al curso` *(se mantiene)* |
| Enlace **actual** | `https://academiavalenz.com/producto/curso-ingreso-guardia-civil-132-promocion/` → **404** |
| Enlace **nuevo** | `https://academiavalenz.com/producto/curso-ingreso-guardia-civil-133a-promocion/` |

Destino verificado: producto publicado y comprable, suscripción **80 €/mes**
(id de producto `2054`, botón "Añadir al carrito" operativo).

### 2. El copy (texto listo para pegar)

**H2 actual:**
> Curso Ingreso Guardia Civil – 132ª Promoción

**H2 nuevo:**
> Curso Ingreso Guardia Civil – 133ª Promoción

**Párrafo actual:**
> Prepárate para las oposiciones a la Guardia Civil 2025 con nuestro curso
> completo para la 132ª promoción. Formación actualizada, materiales
> exclusivos y tutorías personalizadas con Paco Valenzuela.

**Párrafo nuevo** (quita el "2025" y mete la fecha de arranque, que es el
mejor gancho ahora mismo):
> Prepárate para las oposiciones a la Guardia Civil con nuestro curso
> completo para la 133ª promoción, que arranca el **1 de septiembre**.
> Formación actualizada, materiales exclusivos y tutorías personalizadas con
> Paco Valenzuela. Sin matrícula y sin permanencia.

*(“Sin matrícula y sin permanencia” es cierto y es lo que más repite el bot
como argumento de cierre — conviene que la web diga lo mismo.)*

### 3. Botón secundario → alimenta el embudo

Añadir junto al principal:

| | |
|---|---|
| Texto | `Infórmate sin compromiso` |
| Enlace | `https://info.academiavalenz.com/formacion` |

**Por qué**: el botón principal solo convierte a quien ya está decidido a
pagar 80 €/mes. La landing captura al resto → LS01 los nutre con la secuencia
de 4 emails → oportunidad en Captación. Es la única forma de que el embudo
que llevamos construido desde julio empiece a recibir gente.

## ✅ WIDGET DEL BOT INSTALADO EN LA WEB (10-ago) — subtarea 12 CERRADA

Pegado por el equipo vía **Elementor Pro → Custom Code** (`<body> - End`,
todo el sitio) y purgadas las cachés de WP Fastest Cache y Autoptimize.
Verificación de Claude, end-to-end:

| Check | Resultado |
|---|---|
| Embed en el HTML con los 4 atributos | ✅ (cargado con `defer` al final del body) |
| Burbuja visible en `academiavalenz.com` | ✅ |
| El bot responde al primer mensaje | ✅ *"¡Hola, Nuria! …la 133ª empieza el 1 de septiembre de 2026…"* |
| Contacto creado en el CRM | ✅ |
| Oportunidad en Captación | ✅ **'Nuria Bosch', fuente "Bot web"** |
| Tiempo hasta la oportunidad | **5 min 10 s** (T0 16:40:24 → 16:45:34) — el wait de LS02 clavado |

Contacto y oportunidad de prueba **borrados por id exacto**. El bot capta
ahora también desde la web principal, no solo desde `/formacion`.

🟡 **Pendientes menores confirmados en la prueba**: el saludo del widget sale
en inglés (*"Hi there! Have a question?"*) — se cambia en la config del Chat
Widget en GHL. Y `data-source="WEBSITE"` no llega a la oportunidad, que se
crea con la fuente "Bot web" del paso de LS02: para separar leads de web y
funnel haría falta un campo o etiqueta propia, no basta el atributo.

### Referencia — el embed instalado

```html
<script src="https://widgets.leadconnectorhq.com/loader.js"
        data-resources-url="https://widgets.leadconnectorhq.com/chat-widget/loader.js"
        data-widget-id="6a75e4fae425d99b06dba3bf"
        data-source="WEBSITE"></script>
```

Datos del entorno por si hace falta otra vía: contenedor GTM **GTM-W4VN9FVG**
(activo, con GTM4WP) y **Elementor Pro** disponible. Cachés a purgar tras
cualquier cambio: **WP Fastest Cache** y **Autoptimize**.

<!-- histórico: instrucciones originales -->
- Embed del chat (subtarea 12) — pegar en el `<head>` o por GTM:

```html
<script src="https://widgets.leadconnectorhq.com/loader.js"
        data-resources-url="https://widgets.leadconnectorhq.com/chat-widget/loader.js"
        data-widget-id="6a75e4fae425d99b06dba3bf"></script>
```

*(Es el embed real del funnel. Si se quiere distinguir el origen de los leads
web de los del funnel, se puede añadir `data-source="WEBSITE"`.)*

## Verificación (Claude la hace en 1 minuto tras el cambio)

1. El botón de la home responde **200** y cae en el producto de la 133ª.
2. Ya no queda ningún enlace a `curso-ingreso-guardia-civil-132-promocion`.
3. La home no menciona "132ª" ni "2025".
4. Si se pega el widget: la burbuja del chat aparece en `academiavalenz.com`.
5. Si se añade el botón a la landing: un lead de prueba entra por LS01
   (tag `lead-landing-133` + oportunidad en Captación) y se borra después.

## ✅ ENSAYO COMPLETADO EN STAGING (10-ago, 18:01)

Los 3 cambios se aplicaron y verificaron en
`academiavalenz.com/staging` (portada = página **13**, widgets de Elementor
`fb766bc` titular, `96defd6` párrafo, `3ba7359` botón). Verificación:

| Check | Resultado |
|---|---|
| Enlaces al producto 132 (404) | **0** ✓ |
| Enlaces al producto 133 | **1** ✓ |
| Menciones "132ª" | **0** ✓ |
| Menciones "2025" | **0** ✓ |
| Botón "Apúntate ahora al curso" | → producto 133ª ✓ |

Queda como herramienta reutilizable: **`tools/wp-fix-home-cta.py`**
(DRY-RUN por defecto, backup automático del árbol de Elementor antes de
escribir, `--apply` para ejecutar, `--restore <backup>` para revertir, e
idempotente: si ya está arreglado, no toca nada).

```bash
export WP_BASE=https://academiavalenz.com
export WP_LOGIN=https://academiavalenz.com/av-login
export WP_USER=… WP_PASS=…
python3 tools/wp-fix-home-cta.py            # dry-run
python3 tools/wp-fix-home-cta.py --apply    # aplica + verifica
```

## ✅✅ APLICADO EN PRODUCCIÓN (10-ago, por el equipo) — VERIFICADO

El equipo lo aplicó desde wp-admin de producción. Verificación de Claude:

| Check | Resultado |
|---|---|
| Enlaces al producto 132 (404) | **0** ✅ |
| Enlaces al producto 133 | **1** ✅ |
| Menciones "132ª" | **0** ✅ |
| Menciones "2025" | **0** ✅ |
| Destino del botón | **HTTP 200** → producto de la 133ª ✅ |

Titular en vivo: *"Curso Ingreso Guardia Civil – 133ª Promoción"*.
Párrafo: el equipo optó por **"oposiciones a la Guardia Civil 2026"** en vez
de quitar el año — coherente con la convención anterior (la 132ª, con examen
en julio-2026, se anunciaba como "2025"). Sin la coletilla de la fecha de
arranque ni "sin matrícula y sin permanencia"; se pueden añadir cuando se
quiera, pero **el 404 —lo que costaba dinero— está resuelto**.

### ✅ Remate hecho también (10-ago): los dos "2024" de la home
En la sección de más abajo quedaban dos menciones obsoletas; el equipo las
actualizó y Claude verificó: **0 menciones a "2024"** en la portada.
- Titular (`498ccc0`): "Oposiciones a Guardia Civil **2026**"
- Párrafo (`a8af142`): "…presentarte a las oposiciones a la Guardia Civil en
  **2026**?…", con el resaltado intacto.
Revisión de regresión en la misma pasada: el bloque del curso sigue correcto
(0 enlaces al producto muerto, 1 al de la 133ª, sin "132ª").

**La portada queda limpia de copy obsoleto.**

## 🔴 Nota sobre el acceso a producción desde este entorno

**Claude no puede autenticarse en producción desde este entorno**, y no es
un problema de credenciales: el login de `av-login` rechaza **todos** los
intentos sin mostrar mensaje de error — probado con un usuario inventado y
con uno real, mismo resultado. WordPress siempre muestra error cuando la
contraseña falla, así que algo (firewall del hosting o filtro por IP)
bloquea antes de comprobar nada. La IP de salida de este entorno es un proxy
fuera de España, que es la explicación más probable.

> **Consecuencia práctica**: los cambios en producción los aplica el equipo
> desde su navegador (donde sí funcionan las credenciales de siempre) y
> Claude verifica después por HTTP, que sí es alcanzable. Así se hizo este.
> `tools/wp-fix-home-cta.py` queda disponible por si algún día se habilita
> el acceso, y sigue siendo utilizable contra staging.

> ⚠️ **No usar "push staging → producción" de WP Staging** para llevar este
> cambio: arrastraría toda la copia de julio encima de la web viva. El
> cambio hay que hacerlo directamente en producción.
