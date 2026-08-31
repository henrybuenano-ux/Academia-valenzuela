# Las 19:00 (hora Argentina) · fin de la oferta de lanzamiento

**31 de agosto de 2026** · 19:00 ART = **medianoche en España** = fin del
«primer mes 48 € hasta el 31 de agosto». Antes de esa hora, no tocar nada: la
oferta sigue viva para quien está en plazo.

Todo lo de abajo es **ejecutar, no decidir**. ~5 minutos.

---

## 1 · El producto `#2054`

`https://academiavalenz.com/wp-admin/post.php?post=2054&action=edit`
→ Datos del producto → **General**

| # | Campo | De → A |
|---|---|---|
| 1 | **Cuota de registro (€)** | 48 → **80** |
| 2 | **Fechas del precio rebajado** (2026-08-06 → 2026-08-31) | **Vaciarlas** (enlace «Cancelar»). Estaban sobre un precio rebajado vacío: no hacen nada y hacen creer que el precio está programado |
| 3 | **Dejar de renovar / caducar después de** | «No parar hasta que se cancele» → **10 meses** |
| 4 | **Actualizar** | |

### Por qué «10 meses»

Alta en septiembre + 10 ciclos mensuales ≈ último cobro hacia el 1-jun/1-jul de
2027 — y el acceso al campus lo corta EvoCampus de todas formas el
**03-jul-2027**. Con esto, el ciclo de cobro se cierra **solo**, y el cierre
manual en lote que hubo que hacer el 24-jun-2026 no se repite en 2027…

> ⚠️ …**para las compras nuevas.** Las **35 suscripciones ya existentes NO
> heredan este cambio**: conservan «no parar hasta que se cancele» y habrá que
> cerrarlas en julio de 2027 (a mano o en lote). Queda anotado aquí para no
> redescubrirlo entonces.

## 2 · La landing

Pegar en GHL la versión nueva de `landing_133.html` (ya actualizada en el repo):

- Barra superior: ya no anuncia los 48 € — ahora «La 133ª ya está en marcha»
- Precio en los 4 sitios: **80 €/mes**, sin oferta
- FAQ «¿Cuánto cuesta?» reescrita

## 3 · El prompt del bot

Pegar el bloque **«VIGENTE desde el 1-sep»** de `bot_ls02_prompts_2026-08-07.md`
en el nodo de precio. Incluye la instrucción de qué decir si alguien pregunta
por la oferta caducada: que terminó el 31, sin prometer descuentos.

## 4 · Verificación (incógnito)

- [ ] El checkout del `#2054` cobra **80 € hoy** (no 48)
- [ ] En la primera compra de mañana, el pedido muestra un **fin previsto**
      razonable (~jun-jul 2027) — confirma el «10 meses»
- [ ] La landing no menciona ni 48 ni el 31 de agosto
- [ ] El bot, preguntado «¿cuánto cuesta?», dice **80 €/mes** y no ofrece nada

## Lo que queda para esta semana (no para esta noche)

La reconfiguración de fondo del producto — quitar la prueba gratuita y
prorratear el primer pago — sigue pendiente de decisión
(`precio_lanzamiento_caduca_31ago.md`). El hueco de cobertura de 32-60 días ya
no corta a nadie (v0.8.1 lo tolera), así que es una decisión comercial sin
urgencia técnica.
