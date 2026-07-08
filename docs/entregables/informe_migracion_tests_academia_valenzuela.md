# Informe técnico — Migración del motor de tests (Academia Valenzuela)
**Para:** equipo Omnia (Víctor / Henry / Germán / Oliver) · **Uso interno** · Fecha: junio 2026
**Origen:** investigación en vivo del aula de EvoCampus + verificación de capacidades de GoHighLevel.

---

## 1 · Qué hace HOY el motor de tests de EvoCampus (verificado en vivo)

Es, con diferencia, **la parte más compleja y valiosa** de la plataforma. No es un "quiz": es un simulador de oposición completo.

**Banco de preguntas**
- **7.286 preguntas** organizadas en árbol: 23 temas (Derechos Humanos, Constitucional, Penal, Guardia Civil…) → cada tema con subgrupos: *Preguntas Oficiales*, *Por Apartados*, *Globales*, y sub-temas.
- Bloque adicional de **Simulacros** (mensuales y de progreso, ~500 preguntas).

**Generación de test personalizado** (4 modos)
- **Completo** — todas las preguntas del curso.
- **Parcial** — el alumno elige asignaturas/temas en el árbol y el nº de preguntas (máx. 100).
- **Falladas** — solo preguntas que falló en intentos anteriores. *(adaptativo)*
- **Dudosas** — solo las que marcó como dudosas. *(adaptativo)*
- Opciones extra: "mostrar de una en una con corrección individual" y "no repetir preguntas ya respondidas".

**Durante el examen**
- Navegación por preguntas (001, 002, 003…), contadores en vivo: *contestadas / no contestadas / dudosas / importantes*.
- Cada pregunta se puede marcar como **Dudosa** y como **Importante** (alimentan los modos adaptativos y la página "Preguntas marcadas").
- **Sistema de calificación tipo oposición:** +1 por acierto, **penalización proporcional por fallo** (con tabla), 0 por no contestada. Cronómetro.

**Corrección y feedback**
- Marca la respuesta correcta y muestra una **Explicación** por pregunta (texto justificativo citando artículos legales).

**Impugnar pregunta**
- Botón que abre un formulario para **impugnar** una pregunta → **envía un correo al profesor/director** para que revise si es correcta. (El cliente quiere conservar esto.)

**Otras piezas**
- Página "Preguntas marcadas" (histórico de dudosas/importantes), nº de realizaciones (1/∞), expediente del alumno.

---

## 2 · Qué puede y qué NO puede GoHighLevel (nativo)

GHL **sí** tiene "Quizzes/Assessments" en Membresías/Cursos: preguntas de opción única/múltiple, respuesta correcta, **explicación por pregunta**, nota mínima de aprobado, categorías y feedback dinámico.

GHL **NO** tiene (y esto es lo que define la oposición):
- ❌ Banco de preguntas con **selección aleatoria** por temas.
- ❌ **Generar un test de N preguntas** a partir de temas elegidos.
- ❌ Modos **Falladas / Dudosas** (retest adaptativo).
- ❌ **Penalización proporcional** por fallo con tabla.
- ❌ **Impugnar** pregunta con aviso al profesor.
- ❌ **Ranking / percentiles** frente al resto de alumnos.
- ❌ Marcar pregunta como dudosa/importante y reutilizarla.

**Conclusión:** el quiz nativo de GHL sirve para un test al final de una lección, **no** para replicar un simulador de 7.286 preguntas. Confirmado lo que ya intuíais: *por defecto, no se puede*.

---

## 3 · Opciones de migración

### Opción A — Motor de tests a medida embebido en Omnia CRM ⭐ (la que de verdad replica)
Una **mini-app web** (HTML/JS + base de datos) que vive **dentro** de Omnia CRM (enlace de menú propio / iframe en la membresía) y se conecta por API/webhook.
- Banco de preguntas en BD (p. ej. Supabase/MySQL), con la misma estructura de temas/subgrupos.
- Generador de tests (completo/parcial/falladas/dudosas + nº de preguntas + exclusiones).
- Motor de examen con marcar dudosa/importante, cronómetro y **calificación con penalización**.
- Explicación por pregunta.
- **Impugnar** → webhook → workflow de GHL → email al profesor + tarea/registro en el CRM.
- Ranking y "preguntas marcadas".
- Acceso unificado con el login de Omnia CRM (SSO/token).
- ✅ Replica el 100% · ✅ Queda integrado y "es suyo" · ⚠️ Es el componente **más caro y de más riesgo** del proyecto.

### Opción B — Híbrido: EvoCampus solo para tests, el resto a Omnia CRM ⭐ (la más realista para empezar)
Migrar a Omnia CRM **todo menos los tests** (pagos, facturación, accesos, comunicación, CRM, captación, agente) y **mantener EvoCampus únicamente como motor de exámenes** durante una primera fase.
- ✅ Rápido y de bajo riesgo · ✅ No tocamos lo más delicado · ✅ Resuelve ya los dolores reales (acceso + facturación).
- ⚠️ Siguen pagando una licencia parcial de EvoCampus · no es 100% "propio" todavía.
- Es el puente natural hacia la Opción A en una fase 2.

### Opción C — Herramienta de quizzes/LMS de terceros integrada
Conectar un LMS/quiz externo especializado a GHL.
- ⚠️ Reintroduce una **dependencia de terceros** (el mismo problema que EvoCampus) y rara vez cubre impugnaciones + falladas/dudosas + penalización. **No recomendada.**

---

## 4 · Recomendación

1. **Cerrar ya** con el cliente lo que le quita el dolor y es de bajo riesgo: **acceso + facturación** (sobre EvoCampus o como entrada del presupuesto).
2. Plantear la migración completa como **Opción B (híbrido)**: todo a Omnia CRM **manteniendo EvoCampus solo para los tests** en la primera fase.
3. Posicionar el **motor de tests a medida (Opción A)** como **Fase 2 / módulo premium** — es el que convierte la plataforma en 100% propia, pero hay que presupuestarlo aparte y con su propio riesgo.

> Mensaje honesto para el equipo: **no prometáis "migración completa a Omnia" como si el motor de tests fuera trivial.** Es el corazón del negocio del cliente y construirlo bien es un proyecto en sí mismo.

---

## 5 · Impacto en la Opción C (alcance / horas / precio)

La estimación previa de la Opción C (160 h / 8.800 €) incluía **24 h** para "tests", lo cual es **muy insuficiente** si hay que construir el motor completo. Estimación realista **solo del motor de tests a medida**:

| Bloque del motor de tests | Horas est. |
|---|---|
| Diseño + base de datos del banco de preguntas | 16 h |
| Importación/migración de las 7.286 preguntas (según export disponible) | 24–40 h |
| Generador de tests (completo/parcial/falladas/dudosas + nº + exclusiones) | 30 h |
| Motor de examen (UI, navegación, dudosa/importante, cronómetro) | 24 h |
| Calificación con penalización proporcional + tabla | 10 h |
| Impugnar → webhook → GHL → email/tarea | 12 h |
| Preguntas marcadas, históricos y ranking | 20 h |
| Integración/SSO con Omnia CRM (embed + auth) | 16 h |
| QA y pruebas en paralelo | 20 h |
| **Total solo motor de tests** | **≈ 170–190 h** |

A 55 €/h ≈ **9.400–10.500 € adicionales**. Es decir, **el motor de tests prácticamente duplica** el coste de la migración. La Opción C "de verdad" (con tests) estaría en el orden de **17.000–19.000 €**, no 8.800 €.

→ **Acción:** o se re-cotiza la Opción C incluyendo el motor (premium), o se vende el **híbrido (B)** ahora y el motor como fase 2.

---

## 6 · Riesgo / dependencia CRÍTICA: exportar las 7.286 preguntas

La API de EvoCampus que tenemos documentada solo expone **matrículas**, no el **banco de preguntas**. Para migrar los tests necesitamos sacar las 7.286 preguntas con sus respuestas, explicaciones y estructura de temas. Vías posibles, a confirmar:
- **Pedir a EvolMind un export** del banco (lo ideal; preguntar a Jaime/soporte).
- Export propio si el panel de profesor lo permite (a verificar con acceso de profesor).
- Scraping controlado (lento, frágil, último recurso).

**Sin una vía de exportación limpia, la migración del motor de tests se complica muchísimo.** Es la primera pregunta a resolver antes de comprometer la Opción A.

---

## 7 · Qué decir al cliente en la llamada
- *"El sistema de tests es lo mejor que tenéis y lo respetamos: lo podemos replicar entero en vuestra plataforma — generación por temas, falladas, dudosas, penalización, impugnar, todo."*
- *"Por su complejidad lo hacemos en dos pasos: primero os quitamos el dolor del acceso y la facturación, y el motor de tests lo migramos en una segunda fase, bien hecho, para que sea 100% vuestro."*
- Pregunta clave a Paco/Jaime: *"¿EvoCampus os permite exportar el banco de preguntas?"* — su respuesta marca el plazo y el precio.
