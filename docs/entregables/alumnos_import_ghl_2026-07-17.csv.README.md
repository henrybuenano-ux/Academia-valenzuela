# CSV de importación GHL — alumnos_import_ghl_2026-07-17.csv

- **103 contactos** (todo el histórico de EvoCampus, excluida la cuenta interna
  info@academiavalenz.com), con nombre, apellidos, teléfono (+34 normalizado;
  7 sin teléfono) y tags de segmentación.
- Fuente: API EvoCampus (14-jul) cruzada con el censo Woo (10-jul).
- **Tags**: `importado-jul26` (todos) · `promo-132` (89) · `colegio-173` (3) ·
  `pago-suscripcion` (59, con `ultimo-estado-activo/baja` al cierre del curso) ·
  `pago-intensivo` (11) · `becado` (7) · `sin-pago-web` (26 — OJO: en su
  mayoría alumnos históricos de antes de que los pagos fueran por la web,
  p. ej. grupos "PSICOLOGO 2025"; no son morosos).
- **Uso**: D2 (importar a GHL) + campaña de re-enganche para la 133ª — el
  segmento `promo-132` + `ultimo-estado-baja` (39) es el público caliente
  natural de la campaña de septiembre.
- Importar en GHL: Contacts → Import, mapear columnas tal cual; los tags se
  crean solos si no existen.
