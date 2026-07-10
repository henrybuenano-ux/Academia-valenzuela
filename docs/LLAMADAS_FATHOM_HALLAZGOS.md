# Hallazgos de las 2 llamadas de Fathom (transcripciones pegadas en chat, 10-jul-2026)

> Fuente: transcripciones exportadas por el equipo (plan B; fathom.video está
> bloqueado por la red del entorno). Nota de calidad: la transcripción de la
> llamada del 11-jun tiene tramos corruptos (texto pseudo-islandés) y la mitad
> final es charla interna de Ómibu sin relación con Academia Valenzuela; aquí
> se recoge solo lo relevante para el proyecto.

## Llamada 1 — Discovery con el cliente (10-jun, 19 min)
Participantes: Paco Valenzuela (dueño), Francisco "Fran" Valenzuela (pagos/admin),
Víctor, Henry, Germán, Oliver (Ómibu).

### Hechos confirmados por el cliente
1. **Dolor nº 1: la facturación trimestral**. Hoy no se emiten facturas de
   forma sistemática; al cierre del trimestre Fran revisa los pagos en
   WooCommerce a mano para la declaración fiscal. Paco: "odio el final del
   trimestre". → Justifica Fase 2 y su prioridad tras Fase 1.
2. **El alta ya es automática**: al comprar, el conector oficial de Evolmind
   llama a la plataforma y EvoCampus envía al alumno sus credenciales
   (lo implementó Jaime, el desarrollador de la web). **La baja NO existe**:
   cuando dejan de pagar, el acceso sigue abierto y Fran comprueba uno a uno
   quién no ha pagado. → Exactamente lo que resuelve nuestro plugin.
3. **Cobro**: suscripción con tarjeta, cargo automático mensual en la fecha
   de aniversario (ej.: paga el 11-feb → se recarga el 11-mar). El alumno
   puede cancelar su suscripción él mismo desde la pasarela de pago.
4. **Dos productos**:
   - Suscripción mensual (~80 €/mes) — el curso "normal", dura hasta abril/mayo.
   - **Curso intensivo: PAGO ÚNICO** (abril–junio, para gente externa), acceso
     a todo el contenido, **se cierra el día del examen**. El examen es en
     **julio**.
5. **Ciclo académico y campañas**: el curso ya terminó (a fecha 10-jun);
   las campañas de captación están paradas y, según Fran, "lo suyo sería
   empezar las campañas para septiembre" trabajándolas en **julio y agosto**.
6. **Bot IA / setter**: el volumen de llamadas/consultas es bajo ahora mismo;
   se acordó que el agente tiene sentido ligado al arranque de campañas
   (setter que atienda y dirija a compra), no como soporte suelto.
7. A futuro (fuera de alcance actual): a Paco le gustaría una plataforma
   propia en lugar de EvoCampus "más adelante".

## Llamada 2 — Interna Ómibu (11-jun, 84 min; ~20 min relevantes)
Participantes: Víctor, Henry, Germán, Oliver.

### Hechos confirmados explorando WP y EvoCampus
1. Localizado el **plugin oficial "Evol Campus" en WordPress**: su única
   función es vincular productos Woo y mandar la notificación de compra
   (alta) a la plataforma. Config = ClientID + Key. No contempla bajas.
2. En EvoCampus → **Seguimiento y automatizaciones**: las condiciones
   disponibles son tiempo en curso, progreso, acceso, encuesta… **ninguna
   basada en pago**. Sí existe la acción "cambiar la matrícula a baja",
   pero sin trigger de pago no sirve. → Confirma que la baja por impago
   debe venir de fuera (nuestro plugin vía API), como se construyó.
3. **Censo EvoCampus a 11-jun: 386 alumnos totales, 119 de baja → ~267
   activos.** (Este es el origen del dato "~267 activos" que no cuadra con
   las 60 suscripciones de Woo — el hallazgo abierto P1.)
4. Se descargó la documentación de la API de EvoCampus (está en el Drive
   compartido) — base sobre la que se construyó el plugin v0.5.x.
5. Quedó pendiente preguntar a soporte de EvolCampus por la revocación de
   acceso por impago (superado: la API getEnrollments/matrícula-baja ya
   está validada en DRY-RUN).
6. El resto de la llamada es organización interna de Ómibu en Asana
   (plantillas, portafolios, medición de horas) — útil solo como referencia
   para el Bloque F (mapeo a ClickUp/gestor de tareas).

## Implicaciones para los pendientes del plan

- **P2 (¿por qué no hay pedidos de julio?) — hipótesis fuerte**: el ciclo
  académico terminó (curso hasta abril/mayo, examen en julio, intensivo
  cerrado el día del examen, campañas paradas desde fin de curso). Es
  plausible que las cancelaciones masivas de mayo/junio y la ausencia de
  pedidos de julio sean estacionales y no un fallo de cobros. **Verificar
  con el censo**: fechas de último pedido por alumno concentradas en
  abril–junio apoyarían la hipótesis.
- **P1 (~200 activos en EvoCampus sin suscripción en Woo) — hipótesis a
  contrastar con el censo**:
  a) alumnos del intensivo = pago único → pedido suelto, nunca suscripción;
  b) alumnos dados de alta manualmente antes de implementarse los pagos web;
  c) **bajas nunca ejecutadas** (dejaron de pagar y nadie les cortó el
  acceso — el problema que motiva el proyecto). El censo por pedidos del
  plugin distingue (a) de (c) por la existencia y fecha de pedidos pagados.
- **A6 (política de corte)**: nada decidido en las llamadas; sigue pendiente
  con Paco. Dato útil: el cargo es mensual por aniversario → GRACE_DAYS=35
  cubre un ciclo completo.
- **Fase 4 (captación)**: según el propio cliente las campañas para la
  convocatoria de septiembre se preparan en **julio–agosto, o sea YA**.
  Conviene señalárselo a Paco en la demo: adelantar E1/D1 (landing +
  canal de envío) aunque Fase 2 vaya en paralelo.
- **Fase 2 (facturación)**: el discovery confirma que hoy no se emite
  factura alguna automáticamente; refuerza A4 (pregunta VeriFactu a la
  gestoría) como bloqueante real.
