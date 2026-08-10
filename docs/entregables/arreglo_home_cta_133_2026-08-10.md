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

## Después de esto (mismo bloque de trabajo, 5 min más)

- **Embed del chat en la web** (subtarea 12) — pegar en el `<head>` o por GTM:

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

## Bloqueo

Falta **usuario y contraseña de wp-admin de producción** (login oculto con
WPS Hide Login → `https://academiavalenz.com/av-login`). Con eso Claude
aplica los 3 cambios y los verifica en la misma sesión. Alternativa: lo hace
el equipo con esta ficha y Claude verifica después.
