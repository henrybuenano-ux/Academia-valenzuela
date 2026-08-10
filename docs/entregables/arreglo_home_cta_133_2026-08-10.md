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

## 🔴 BLOQUEO: producción sigue rota

Las credenciales **`DevOmibu` son de STAGING**: en producción ese usuario
**no existe** (el login de `av-login` rechaza sin mensaje de error, probado
dos veces con cabeceras de navegador). Es decir: **el 404 que ven los
visitantes reales sigue ahí**.

Hace falta un **usuario administrador de producción**. Con él, el arreglo es
un solo comando (arriba) y tarda 30 segundos, ensayo ya hecho. Alternativa:
lo aplica el equipo a mano con esta ficha y Claude verifica después.

> ⚠️ **No usar "push staging → producción" de WP Staging** para llevar este
> cambio: arrastraría toda la copia de julio encima de la web viva. El
> cambio hay que hacerlo directamente en producción.
