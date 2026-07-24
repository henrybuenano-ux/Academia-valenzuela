# Secuencias de nurturing 133ª — copy listo para cargar (E2, Oliver)

> Dos secuencias: (A) lead nuevo de landing/campaña y (B) re-enganche de
> ex-alumnos de la 132ª (los 39 con tag `ultimo-estado-baja` ya importados).
> Canal: email desde el día 1 (D1); WhatsApp se suma cuando Meta apruebe.
> Salida de ambas: agenda asesoría, compra, o pide que no le escriban.

## Secuencia A — Lead nuevo (trigger: tag `lead-landing-133` o `lead-frio` del bot)

**A1 · Inmediato — Email "Bienvenida + qué sigue"**
Asunto: Tu plaza en la 133ª: por dónde empezar
Hola {{first_name}}, gracias por tu interés en la 133ª promoción (arrancamos
el 1 de septiembre). Dos caminos, elige el tuyo: si lo tienes claro,
matricúlate aquí {{enlace}} y tienes acceso al campus desde el primer día;
si prefieres resolver dudas antes, reserva una asesoría gratuita de 10
minutos {{enlace calendario}}. Un abrazo, Academia Valenzuela.

**A2 · Día 2 — Email "El método" (valor, no venta)**
Asunto: Así se prepara una oposición que aprueba
Qué nos diferencia: temario siempre actualizado, clases grabadas para ir a tu
ritmo y —lo que más engancha a nuestros alumnos— los test con ranking real:
sabes en todo momento en qué puesto estás frente al resto de la promoción,
como el día del examen. {{CTA: Ver cómo funciona el campus → asesoría}}

**A3 · Día 4 — Email "Objeciones" (responder lo que frena)**
Asunto: "¿Y si no llego?" — la duda de todos los que empiezan
Las tres dudas que más escuchamos: ¿voy tarde? (no: el campus completo está
disponible desde el día 1), ¿me ata? (no: 80 €/mes sin permanencia, cancelas
tú mismo en un clic), ¿podré compaginarlo? (las clases son grabadas — tú
pones el horario). ¿Alguna más? Respóndeme y te contesto personalmente.
{{CTA asesoría}}

**A4 · Día 7 — Email "Última llamada de la secuencia"**
Asunto: Cerramos tu hueco en la 133ª
{{first_name}}, no queremos ser pesados: este es el último correo de esta
serie. Si la Guardia Civil sigue en tu cabeza, la 133ª empieza el 1 de
septiembre y este es el enlace {{matrícula}}. Si prefieres que te llamemos
y verlo en 10 minutos: {{calendario}}. Y si no es tu momento, todo bien —
aquí estaremos. 💪

→ Sin respuesta tras A4: tag `lead-frio-133` (audiencia para remarketing de
la campaña E3). Cualquier respuesta → notificación interna + humano.

## Secuencia B — Re-enganche ex-alumnos 132ª (trigger manual: segmento `promo-132` + `ultimo-estado-baja`)

**B1 · Al lanzar — Email "Te esperamos en la 133ª"**
Asunto: La 133ª empieza el 1 de septiembre — ¿repetimos?
Hola {{first_name}}, fuiste parte de la 132ª y sabemos lo que cuesta llegar
hasta el examen. Si vas a por la siguiente convocatoria, la 133ª arranca el
1 de septiembre con el temario actualizado y todo lo que ya conoces del
campus. Tu cuenta sigue ahí: te suscribes de nuevo y recuperas el ritmo
desde el día 1. {{CTA: Reservar mi plaza}}

**B2 · +4 días — Email "Lo que cambia este año"**
Asunto: Qué hay nuevo en la 133ª
[Rellenar con 2-3 novedades reales que cuente Paco: cambios de convocatoria,
contenido nuevo, mejoras del campus. NO inventar.] Si tienes dudas de si
repetir, respóndeme y lo vemos — sin compromiso.

**B3 · +8 días — Email "Cierre honesto"**
Asunto: ¿Vas a por ella?
Sin rodeos: ¿te apuntamos a la 133ª? Responde SÍ y te mandamos el enlace,
responde LUEGO y te avisamos más adelante, o NO y no te escribimos más
sobre esta convocatoria. Así de fácil.

→ "NO" o sin respuesta: tag `no-133` (excluir de más envíos de esta camada).

## Notas de implementación
- Cadencias con envío en horario 10-20 h, zona Madrid.
- Toda respuesta de un contacto pausa su secuencia (condición estándar) y
  notifica al equipo.
- B2 necesita input de Paco (novedades de la 133ª) — pedirlo junto con el
  material de la landing; B1 y B3 pueden cargarse ya.
- Reporting mínimo: aperturas por email + agendas generadas por secuencia
  (fuente en la oportunidad: "Nurturing A" / "Re-enganche B").
