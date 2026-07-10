# Censo EvoCampus vs WooCommerce — 10-jul-2026 (resuelve P1)

> Datos leídos en vivo de la API de EvoCampus (ClientId 83208), que es el
> **campus de producción** (no el clon de staging). Cruce con los 59 emails de
> suscripción de WooCommerce del censo de conciliación del mismo día.

## La pregunta (P1)

El discovery apuntaba ~267 alumnos activos (de ~386) frente a solo ~60
suscripciones en WooCommerce → "¿cómo pagan los ~200 que no están en Woo?".

## La respuesta: la brecha "~300 vs 60" NO se sostiene

Los números reales del propio campus:

| Fuente | Alumnos |
|---|---|
| EvoCampus — alumnos únicos (todas las matrículas) | **104** |
| EvoCampus — con al menos una matrícula **activa** | **57** |
| WooCommerce — con suscripción | **59** |
| **En ambos** (activo en campus + suscripción Woo) | **38** |
| Activo en campus, **sin** suscripción Woo | **19** |
| Con suscripción Woo, **no** activo en campus (ya cortados) | 21 |

**El campus solo tiene 57 alumnos activos, no ~267.** Está muy cerca de los
59-60 de Woo. No existen ~200 alumnos "fantasma" pagando por fuera: como mucho
hay **19** alumnos activos que no tienen suscripción en WooCommerce (y uno de
ellos es `info@academiavalenz.com`, la cuenta de la propia academia).

Total de matrículas (enrollments) en el campus: **514** (~5 por alumno: temario,
inglés, simulacros, promoción, a veces intensivo).

## Distribución de los 57 activos por grupo

| Alumnos activos | Grupo |
|---:|---|
| 57 | 132 PROMOCIÓN GC INGLÉS (grupo paraguas) |
| 57 | PROMOCIÓN 132 GC (grupo paraguas) |
| 45 | 132 PROMOCIÓN GUARDIA CIVIL |
| 13 | 132 GC INTENSIVO |
| 3 | 173 COLEGIO GUARDIAS JÓVENES |
| 2 | PSICÓLOGO 2025 |

## Los 19 activos en el campus SIN suscripción en Woo (a revisar con Paco)

Son el verdadero P1, pero reducido de ~200 a 19. Hay que preguntar cómo acceden:
¿pago por transferencia/Bizum?, ¿tarjeta guardada fuera de Woo?, ¿intensivo de
pago único que no genera suscripción?, ¿acceso de cortesía/prueba?

```
alejandrocs2509@gmail.com      juanjolirio2003@gmail.com    peto-reala@hotmail.es
anabelgar1515@gmail.com        juncaluam@gmail.com          sanchezbermejo10@gmail.com
charidominguez99@gmail.com     laraloume@hotmail.com        santiml.89@hotmail.com
danielsanchezluc55@gmail.com   marioventapuer@hotmail.com   sergiohuelma_123@hotmail.com
fabioclve@proton.me            mirellacrespo29@gmail.com    xabiif14@gmail.com
gabricasta19@gmail.com         nacholgo@icloud.com
info@academiavalenz.com        (cuenta de la academia)
jmbobis10@gmail.com
```

Pista: los del grupo **132 GC INTENSIVO** (13) probablemente son pago único
(abril–julio), que NO crea una suscripción recurrente en WooCommerce → explica
buena parte de estos 19.

## Preguntas para cerrar P1 del todo

1. **A Paco/Fran:** ¿de dónde sale el número de ~267? ¿Cuenta el intensivo,
   histórico, o alumnos de otra promoción/otro producto?
2. ¿Hay **otra cuenta de EvolMind** además de la 83208? (Si no, 57 activos es
   el número real y el negocio es más pequeño de lo que se creía.)
3. De los 19 sin Woo: ¿cómo pagan? (Confirma si el intensivo va por otra vía.)

## Implicación para el plugin

La conciliación por pedidos de Woo cubre a los 38 que están en ambos sistemas.
Los 19 activos sin suscripción Woo **no los ve el plugin** (no tienen pedidos):
si son intensivos de pago único, habría que tratarlos aparte (p. ej. por fecha
fin de matrícula en EvoCampus, no por suscripción). A decidir con negocio.
