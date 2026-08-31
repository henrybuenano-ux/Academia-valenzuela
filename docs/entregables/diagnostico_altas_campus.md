# Por qué fallan las altas en el campus — guía de diagnóstico

**27 de agosto de 2026** · para resolver antes del lunes
Contexto: 35 suscripciones activas, 18 matriculados, **17 sin acceso**
(`altas_campus_fallando_2026-08-27.md`)

## Empieza por aquí: el estado de los pedidos

**Es una columna, y descarta o confirma la causa más probable.**

El producto de la 133ª está marcado **Virtual** pero **no Descargable**. Con esa
combinación **WooCommerce no completa los pedidos solo**: se quedan en
*Procesando*. Ya nos encontramos esto al montar la facturación.

El conector `Pluging evolCampus for WooCommerce 3.4` hace el alta al comprar,
pero **casi todos estos conectores se enganchan a un estado concreto del
pedido** — normalmente *Completado*.

### La comprobación

`WooCommerce → Pedidos`, y mirar el **estado** de estos dos grupos:

| Grupo | Pedidos |
|---|---|
| **Entraron en el campus** | #2088, #2102, #2106, #2113, #2115, #2117, #2119, #2121, #2123, #2126, #2132, #2134, #2141, #2151, #2155, #2157, #2172, #2177 |
| **NO entraron** | #2104, #2108, #2110, #2128, #2138, #2145, #2147, #2149, #2153, #2161, #2163, #2165, #2166, #2168, #2170, #2174, #2179 |

*(Los números son de suscripción; el pedido asociado suele llevar el número
contiguo.)*

**Si los que entraron están en «Completado» y los que no en «Procesando», ya
está: esa es la causa.** Y el arreglo es de configuración, no de datos.

### Si se confirma, el arreglo

Dos caminos, y el segundo es mejor:

1. **Completar los 17 pedidos a mano.** Dispara el alta, resuelve hoy y no
   arregla el futuro.
2. **Que el conector matricule también en «Procesando»**, o que el producto se
   marque *Descargable* para que WooCommerce complete solo. Así deja de pasar.

⚠️ **Ojo con marcar Descargable**: cambia también el correo que recibe el
cliente y puede afectar a la factura, que hoy se adjunta al correo de pedido
completado. Mejor mirar primero si el conector tiene la opción del estado.

---

## Si el estado de los pedidos no lo explica

### 2 · Ajustes del conector

`Ajustes → evolCampus`, o la pestaña del producto. Comprobar:

- **En qué estado del pedido** dispara el alta.
- **El mapeo Producto → Curso → Grupo.** Los que entraron tienen las 4
  formaciones con grupo `133 PROMOCIÓN GUARDIA CIVIL`, alta **01-sep-2026** y
  fin **03-jul-2027**. Si el mapeo apunta a un grupo que se llenó o cerró, el
  alta falla.
- Si tiene **log propio**, mirarlo directamente: ahí saldrá el error real.

### 3 · Límite del plan de EvoCampus

Panel de EvoCampus → plan / licencias / alumnos contratados.

Contrastar contra lo que hay hoy:

| | |
|---|---|
| Matrículas activas en toda la plataforma | **117** |
| Personas distintas con algo activo | **63** |
| Personas distintas en la plataforma | **140** |

Si el plan tiene tope y anda cerca, es la explicación de «funciona a veces y
cada vez menos». **Y si es esto, ninguna vía manual va a funcionar** hasta
ampliarlo: ni el panel, ni la API, ni completar los pedidos.

### 4 · Log de errores del servidor

Buscar en el log de PHP de producción llamadas a `api.evolcampus.com` con
timeout, error 429 (cuota) o 500, en las fechas de los pedidos que fallaron.

---

## El patrón, por si ayuda a descartar

| Tramo | Fallos |
|---|---|
| Primeras 25 suscripciones | 9 de 25 · 36 % |
| Últimas 10 | 8 de 10 · 80 % |

- **No hay corte por fecha**: el 19-ago entraron dos y fallaron tres.
- **No es «lo reciente falla»**: #2172 y #2177, de hace horas, sí entraron.
- Es **intermitente y empeora con el volumen**.

Eso descarta un ajuste sin más —fallaría siempre— y encaja tanto con el estado
del pedido (si alguien lo completa a mano de forma irregular) como con un tope
que se libera y se vuelve a llenar.

## Cuando sepamos la causa

- **Es el estado del pedido** → completar los 17 y arreglar el disparador.
- **Es el mapeo** → corregirlo y reprocesar.
- **Es el tope del plan** → hablar con EvolMind y ampliar. Nada más funcionará
  hasta entonces.
- **Es cuota o timeout** → dar de alta a los 17 espaciados, y hablar con
  EvolMind sobre el límite.

**En los cuatro casos, los 17 necesitan las 4 formaciones** con alta 01-sep-2026
y fin 03-jul-2027, igual que los 18 que sí entraron.
