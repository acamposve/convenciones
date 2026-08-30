# Transcripción de Reunión - Proyecto ConvencionesIA

**Participantes:**
- **Alex Campos** (Desarrollador / Lider técnico)
- **Luis Villegas** (Colaborador / Especialista de Dominio)

---

## 📄 Transcripción del Diálogo

**Luis:** Okay. Logró ver lo que le pasé?

**Alex:** Eh, ¿qué cosa no?

**Luis:** El link para que viese la página.

**Alex:** No, no, ¿cuándo lo pasaste, cuándo? ¿Hoy?

**Luis:** El mensaje anterior.

**Alex:** Ah no, porque como estaba ahorita aprovechando que llegó el agua me puse a fregar y ahorita cuando abrí la computadora me di cuenta que estaba en línea.

**Luis:** Bueno, no importa. No importa. ¿Qué sucede con... qué es lo que tiene el... la aplicación actual? Se puede cargar un contrato, la IA lee el contrato, lo separa por cláusulas y se puede hacer una comparación con otro contrato que yo le indique, ¿sí? Todo eso es por IA. Ahora, toda la parte de tablas de configuración, eso se hace en un momentico. Yo lo que quiero es hacer la parte complicada, que es la lectura y clasificación de documentos, que ya está, ¿sí?

**Alex:** Okay.

**Luis:** Ahora, ¿cuál sería la parte que es más vital para el proyecto?

**Alex:** ¿Me preguntas?

**Luis:** Sí.

**Alex:** Sí, la parte vital es... bueno, son dos ambientes. El primero, la lista de contratos incorporados en la página o en la plataforma: contratos bancarios, ¿verdad?, del país o de la región donde está la gente que le interesa. Por ejemplo, los contratos de Argentina, los de Venezuela. La lista de los contratos tal cual como está el documento. Si tiene errores, se publica con errores, lo que sea. Cosa que no es común que tenga errores, pero se publica tal cual. Y la otra parte es el comparativo entre cláusulas. Por ejemplo, yo trabajo en el Banco de Venezuela, voy a discutir contrato con el sindicato, pero quiero saber los contratos vigentes del sector bancario. Entonces yo bajo cuatro o cinco bancos que compiten conmigo, ¿verdad?, y comparo las cláusulas. Digo, agarro las vacaciones: ¿cuánto está pagando el Banco de Venezuela, Mercantil, Banesco? Y me compara las cláusulas. Esas cláusulas las puedo comparar con el texto completo o con un resumen de lo más importante. Yo hacía resúmenes y debajo de los resúmenes había un despliegue que te desplegaba toda la cláusula original, para que vieras realmente... Si tú quieres ser práctico dices: "Mira, en vacaciones quiero saber que el Banco de Venezuela paga un bono de un mes anual y da 15 días hábiles de vacaciones", solamente eso. Y en la parte de abajo de esa casilla desplegaba la cláusula completa porque viene toda la literatura y todo aquello. Pero al que le interesa verlo rápido, lo ve porque ya se lo resumí. No sé si la IA puede hacer un resumen, pero siempre es bueno que si la IA lo hace, uno lo va monitoreando un poco porque tampoco la IA está tan perfecta, ¿okay?

**Luis:** Sí, claro. Esto lo que tiene es: yo agarro y separo, me organizo por cláusulas. Pero para que eso quede tal cual, yo solicité un botón después de la revisión humana que diga "Aprobado". Entonces toda esa segmentación que hace la IA tiene que ser validada por un humano.

**Alex:** Ajá, sí, correcto. Okay.

**Luis:** Bueno, por defecto todos los documentos están privados, es únicamente para la persona que lo vaya a ver. Pero si yo quiero que eso sea público, que mañana llega el abogado tal o llega la empresa tal que contrata los servicios y quiere ver todos estos documentos, ¿se puede o no se puede, sí? Eso también es algo humano. ¿Qué otra parte sería bueno que fuésemos incluyendo aparte de esto que acabamos de conversar?

**Alex:** Okay, te explico un poquito antes varias cosas previo. Primero, los contratos colectivos o convenciones colectivas, que antiguamente salían en librito y ahora están digitalizados, esos contratos son documentos públicos. Yo lo certifiqué con un abogado aquí laboralista que fue viceministro del Trabajo y es amigo mío. Y le dije: "Coño, tengo una duda. Esos contratos que están depositados en la Inspectoría del Trabajo de cada una de las empresas, una vez que lo firman, lo homologan y ya tiene carácter legal, ¿yo lo puedo publicar?". Me dice: "Sí, porque eso es un documento público". Entonces por ahí estamos tranquilos, ¿okay? Eso en el caso de Venezuela, me imagino que en Uruguay y en Argentina es lo mismo, me imagino. Entonces, esa es la primera cosa, o sea que con tranquilidad podemos publicar todos esos contratos. Lo segundo es meterlo en esa base de datos que vengan por orden alfabético con un buscador: "Contrato del Banco de Venezuela", "Contrato de Sidor", "Contrato de tal cosa", y tú bajas el que tú quieras. Quieres ver el contrato completo tal cual está en el original. Y luego viene la segunda etapa, que es la parte de las comparaciones, ¿okay? Y hasta ahí llega. Existe otro módulo que lo tenía o lo tengo, mejor dicho, en Excel —nunca lo metí en la web— que tendría que actualizarlo, que es el cálculo de los costos de los contratos colectivos y los distintos escenarios. Me explico: cuando yo me siento con un sindicato, el sindicato me hace primero una propuesta o un pedido a la empresa, y esos pedidos son muy altos. Son pedidos de que "bueno, yo quiero el 200% de aumento salarial, quiero duplicar el bono vacacional y de 4 meses de utilidades quiero 8", todo eso. Eso es lo que piden los sindicatos. Cuando yo recibo eso, una vez que ellos lo introducen en la Inspectoría, ¿okay?, que ya tiene carácter legal, yo establezco, de acuerdo al presupuesto de la empresa, cuáles son los montos a donde yo puedo llegar en cada cláusula. Ya esta es la parte económica, y hago distintos escenarios. Yo digo: "Okay, en juguete te puedo dar un juguete adicional por cada hijo; en becas escolares por cada hijo en vez de ser 100 bolívares lo puedo llevar hasta 120 (el sindicato me pidió 500)". Entonces yo voy haciendo escenarios. Y esos escenarios, te lo voy a poner en estos términos: imagínate tú que ese módulo de negociación es como una bitácora en donde yo me reúno con el sindicato; por un lado, como si fuera una hoja de Excel, en una columna yo coloco las cláusulas que yo voy a discutir con el sindicato y le pongo los montos. El sindicato en la reunión aprueba una, la otra la rechaza, pero la difiere para la próxima reunión. Entonces yo coloco fechas de próximas reuniones. Ya eso es una dinámica que la voy llevando como si fueran columnas de Excel, ¿okay? Entonces bueno, las aprobadas pasan a ser aprobadas, y las que están diferidas van para la próxima reunión con fecha, hora y lugar, ¿okay? Ese es el módulo. Y cuando hablo de cálculo de costo, entonces yo tengo eso en Excel, eso te lo puedo enviar, pero ameritaría como una reunión aparte. Yo diría que para comernos la torta por pedacitos estaríamos en el módulo que tú estás trabajando ahorita.

**Luis:** Sí, sí, claro. Esto yo ahorita... o sea, por eso es que quería grabar, porque esto ahorita yo lo subo a la IA, la IA revisa todo el video, empieza a sacar todas las tareas, todas las modificaciones que tenemos y la pongo a trabajar con eso.

**Alex:** ¿Qué IA tienes ahí? ¿ChatGPT o...?

**Luis:** Claude. De Anthropic.

**Alex:** Trabaja con comparativos y trabaja con todo este tipo de cosas.

**Luis:** Con todo eso. Esto es lo que yo uso en mi trabajo y me pagué una suscripción para mi trabajo personal.

**Alex:** Okay. Entonces fíjate, tú me hablaste de la parte profesional que uno tiene que leer. Por ejemplo, si tú obtienes los contratos de... ¿tú estás en Uruguay, no?

**Luis:** Sí.

**Alex:** Okay. Tú tienes los contratos de Uruguay, tú los me montas, yo los veo, porque eso está allí, eso está escrito en español, con cifras, con todo. Entonces yo te puedo hacer el resumen de cada una, ¿okay?, que es la que se montaría, o no sé si la IA hace el resumen. Y si hace el resumen la IA, entonces yo lo valido, porque esto tiene una parte jurídica. Hay que cuidar muy bien las palabras, los términos, que sean muy apegados a lo que dice el contrato original, sin ponerle ni quitarle nada, porque ese es uno de los errores que uno puede cometer: "Bueno, aquí dice tal cosa, pero ese término no me gusta". No, eso no tiene nada que ver, yo no puedo influir ni cambiar absolutamente nada. Lo que sí es importante que yo sí puedo hacer es que si la IA me hace un resumen, yo puedo decir "bueno, esta parte no es tan importante", porque lo medular para un ejecutivo es ver lo que rápidamente le sale. Porque por ejemplo, lo que te decía de vacaciones: ¿qué le interesa a una persona en primera instancia cuando va a discutir un contrato? ¿Cuántos días de vacaciones y cuánto es el monto del bono? ¡Más nada! ¿Para qué le vas a poner más cosas? Porque los pones a perder tiempo, y eso es lo que busca un ejecutivo: el ejecutivo lo que quiere es cosas prácticas. Ahora, si en el argumento hay un elemento adicional, ¿verdad?, que te diga a lo mejor: "Cuando el trabajador tiene dos años el monto se incrementa automáticamente", ah, ese es un elemento importante que está en el contrato que yo debo validar, o sea, yo debo decir sí, esto va en el resumen. Y lo vamos monitoreando. Y esa la misma técnica que yo aplicaría en Venezuela la aplico en Uruguay, en Argentina... Tengo un sobrino en Argentina que trabaja en una corporación de empresas y puedo obtener información de los contratos a través de él, ¿okay?

**Luis:** De acuerdo, de acuerdo. Bueno, vamos a darle comida ahorita a esto y en base a lo que logre hoy, que lo podemos ver en la semana, para reunirnos la próxima semana.

**Alex:** Okay, entonces con lo que hemos hablado es suficiente. ¿Alguna duda?

**Luis:** Sí. No, no, yo con esto alimento la inteligencia artificial y vamos a ver qué nos dice. Ahorita envíeme por WhatsApp su correo para enviarle lo que me dice de los cambios que voy a estar haciendo esta semana.

**Alex:** Okay. Otra cosa importante es cómo conseguirías los contratos en Uruguay, porque me hablaste de que allá eso es un palo, ¿no? ¿Cómo los conseguirías?

**Luis:** Sí, sí, lo tengo. Ahorita voy a ponerme a averiguar todo eso.

**Alex:** Perfecto. Bueno, cualquier cosa seguimos en contacto.

**Luis:** Seguro. Páseme por WhatsApp su correo.

**Alex:** Ya te lo paso. Un abrazote, saludos. Dale, Alex, éxito.

**Luis:** Chao.

---

## 📌 Resumen de Puntos Clave para Referencia Rápida

1. **Estado del Sistema Actual (`ConvencionesIA`):**
   - Ingesta e indexación de contratos mediante IA funcional.
   - Segmentación automática por cláusulas.
   - Flujo de validación humana: se implementa un botón de **"Aprobado"** tras la revisión para confirmar la segmentación/clasificación realizada por la IA.
   - Privacidad por defecto (documentos privados), con opción de cambiar estado a público para clientes/abogados.

2. **Funcionalidades Principales & Requerimientos de Dominio:**
   - **Repositorio/Directorio Público:** Biblioteca de contratos colectivos organizados alfabéticamente y buscables por entidad/empresa/sector.
   - **Comparador de Cláusulas:** Módulo para seleccionar y comparar cláusulas específicas entre múltiples empresas/bancos del mismo sector (ej. Beneficios de vacaciones entre distintos bancos).
   - **Resúmenes Ejecutivos de Cláusulas:** Vistas de resumen rápido de puntos clave (ej. días de vacaciones, monto de bonos) con un desplegable para expandir el texto completo original de la cláusula.

3. **Próximos Pasos & Tareas:**
   - **Revisión Legal / Curaduría:** Alex validará y ajustará los resúmenes y la clasificación de la IA para asegurar fidelidad exacta a la terminología legal original.
   - **Fuente de Datos (Uruguay / Argentina / Venezuela):** Luis investigará la extracción/obtención de contratos colectivos públicos en Uruguay. Alex revisará opciones de obtención mediante contactos en Argentina y Venezuela.
   - **Módulo Futuro (Económico / Costeo):** Modelado en Excel para simulación de escenarios de costo en negociación colectiva (a abordar en etapas posteriores).
