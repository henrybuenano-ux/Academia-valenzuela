# Respuesta a Paco — tratamiento de los becados en el próximo curso (17-jul-2026)

> Paco confirmó (17-jul) que los 7 alumnos manuales son becados y preguntó
> cómo tratarlos en la 133ª. Borrador de respuesta + decisión técnica ya
> aplicada en el plugin v0.8.0.

---

**Borrador para enviar:**

Hola Paco,

¡Gracias por las respuestas! Con esto ya lo tenemos todo: dejamos programado
el arranque del sistema con la 133ª (1 de septiembre) y la política de impago
con **7 días de cortesía con aviso automático**, como quedamos.

Sobre los becados, te proponemos esto para el próximo curso — elige la opción
que te resulte más cómoda:

**Opción A — Como hasta ahora, pero controlado (recomendada por sencillez):**
los sigues dando de alta a mano en el campus y nos pasas la lista de becados
de cada promoción. Nosotros la cargamos en el sistema y así:
- el informe mensual los marca como "becado autorizado" (ya está funcionando),
- si algún día aparece alguien con acceso y sin pago que NO esté en tu lista,
  te saltará como "desconocido — revisar": nadie se cuela sin que lo sepas,
- su acceso caduca solo con la fecha de fin de curso del campus, como ahora.
Para ti no cambia nada: solo mantener la lista al día (un WhatsApp nos vale).

**Opción B — Por la web con "beca 100%":** les creamos un cupón del 100% y se
matriculan por la web como cualquier alumno, sin pagar. Ventajas: entran solos
al campus (sin alta manual), quedan en el CRM con su ficha como el resto, y
si la beca se acaba basta con no renovarles el cupón. Es un pelín más de
gestión al inicio (que cada becado se registre él mismo), pero deja todo el
alumnado en un único sitio.

Nuestra sugerencia: empezar la 133ª con la **Opción A** (cero fricción) y
plantear la B más adelante si el número de becados crece.

Un abrazo.

---

**Nota técnica (ya aplicado en v0.8.0):**
- Constante `OMNIA_EVO_BECADOS_EMAILS` con los 7 becados actuales instalada
  en la config del staging.
- El informe "acceso sin pago" distingue "Becado (autorizado)" de
  "Desconocido — revisar" (verificado en vivo el 17-jul).
- Los becados no tienen suscripción en Woo, así que la conciliación no los
  toca; su acceso se rige por la fecha fin de EvoCampus.
- Si Paco eligiera la Opción B: crear producto/cupón "BECA-133" (100%,
  restringido por email) y quitar esos emails de la constante; pasarían a
  tener rastro en Woo y saldrían del informe automáticamente.
