# Guion de la demo a Paco (B7 — prevista 4-sep, sirve también para una demo previa en staging)

> Duración objetivo: 15 min. Idea fuerza: "esto que te enseño ya no te va a
> volver a pasar". Cada bloque abre con un dato real de la 132ª.

## 1. El problema, con sus números (3 min)
- "En la 132ª detectamos **16 alumnos con acceso al campus sin estar al
  corriente de pago**. Tres de ellos llevaban 63, 68 y **144 días** sin pagar
  y siguieron usando el campus hasta la última semana del curso."
- 144 días ≈ 4,7 meses × 80 € ≈ **376 € en un solo alumno**. Con los tres:
  ~730 €. Eso es lo que el sistema evita a partir de ahora, solo.
- "Y lo detectamos nosotros mirando los datos — nadie en la academia tenía
  forma razonable de verlo. Ese es exactamente el trabajo que desaparece."

## 2. La demo en vivo (7 min) — con un alumno de prueba
1. **Impago**: forzamos el fallo de cobro de la suscripción de prueba →
   enseñar que en el CRM aparece al instante con la etiqueta `alumno-impago`
   y una tarjeta en el pipeline "Recobro impagos". El alumno recibe el
   primer aviso amable. **Su acceso sigue activo** (los 7 días de cortesía
   que decidiste).
2. **Avisos**: enseñar la secuencia (día 0, día 3, día 6) — "esto se envía
   solo; tú no haces nada".
3. **Corte**: (simulado adelantando la ventana) → el campus le pone la
   matrícula de baja **automáticamente**, sin tocar nada. Enseñar el log:
   "cada noche revisa a todos los alumnos, uno a uno".
4. **Paga → vuelve**: completamos el pago → acceso reactivado solo, etiqueta
   `alumno-recuperado`, mensaje de bienvenida. Progreso intacto.
5. **Informe de becados**: enseñar la tabla — tus 7 becados salen como
   "autorizados"; si algún día alguien aparece con acceso y sin pago que no
   esté en tu lista, sale como "Desconocido — revisar". Nadie se cuela.

## 3. Lo que esto significa para la 133ª (3 min)
- Desde el 1-sep: alta automática al pagar (como ya tenías) + **baja y
  reactivación automáticas** (lo nuevo) + avisos de cobro automáticos +
  todos los alumnos fichados en el CRM.
- "Fran deja de comprobar pagos uno a uno; tú dejas de perseguir morosos."

## 4. Siguiente pieza y cierre (2 min)
- Facturación (Fase 2): "cada cobro generará su factura legal y a final de
  trimestre tendrás la carpeta lista para la gestoría" — estado según A4.
- Captación: campañas ya en marcha para llenar la 133ª + bot setter después.
- Cerrar con la pregunta de servicio mensual (340 €/mes): monitorización de
  esta maquinaria + ajustes del bot + soporte.

## Preparación previa (checklist del equipo)
- [ ] Suscripción de prueba creada en producción (o staging si la demo es
      previa al go-live) con email nuestro.
- [ ] Workflow de dunning activo con los mensajes cargados.
- [ ] Pantallas listas: wp-admin (página EvoCampus Sync), GHL (pipeline
      Recobro + contacto), campus EvoCampus (matrícula del alumno de prueba).
- [ ] Los números del bloque 1 salen de
      `docs/entregables/censo_conciliacion_2026-07-10.md`.
