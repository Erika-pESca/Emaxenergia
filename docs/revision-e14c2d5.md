# Revisión del contenido retirado por e14c2d5

Fecha: 2026-11-09. Cambios realizados por Luciano Lyall (lucianolyall@gmail.com).

Se comparó **e14c2d5^ → e14c2d5**, no el conjunto de todos los commits de 0.3.2. Las líneas «antes» de este informe corresponden a `e14c2d5^`; así se pueden localizar los bloques aunque los comentarios o restauraciones cambien su posición actual. Se revisaron los once archivos de `src/` afectados. La eliminación de CSS de notas y contenedores vacíos no añade contenido textual a este inventario.

## Restaurado en 0.3.3

| Archivo y línea antes | Texto que se había retirado | Resultado y motivo |
| --- | --- | --- |
| `src/pages/aviso-legal.astro:90` | «EMAX Energía analiza cómo consume la energía cada empresa y construye con esos datos su propuesta de tarifa.» | Restaurado en la línea actual 85. Describe el análisis ya ofrecido en el sitio, sin afirmar quién suministra ni una habilitación pendiente. Se mantiene también la información general sobre servicios de luz y gas. |
| `src/pages/guias/cups-y-tus-datos.astro:83`, apartado «Lo que no hacemos» | «No te cambiamos de compañía sin tu conformidad expresa. Y no te llamamos si no nos lo has pedido.» | Restaurado, línea actual 87. No depende de identificar quién consulta el SIPS. |
| Mismo archivo y línea antes, apartado «Si no quieres darlo» | «No hace falta: podemos empezar con tu factura o con dos datos de tu negocio.» | Restaurado, línea actual 88. Describe las alternativas del formulario existentes en el sitio. |
| Mismo archivo y línea antes, apartado «Responsable del tratamiento» | «ENERGYMAX ADVISORY, S.L.U. · Política de privacidad» | Restaurado, línea actual 89, con su enlace original a `/privacidad/`. El responsable ya está identificado. |

La guía había ocultado un `<dl>` con cinco apartados. Se restauran estos tres; se mantienen comentados los dos que afirman cómo EMAX consulta los datos.

## Canal Directo y atención al cliente: inventario completo

| Archivo y línea antes | Qué se ocultó o sustituyó | Decisión en 0.3.3 |
| --- | --- | --- |
| `src/pages/canal-directo/index.astro:151` | Solo el marcador `[LEGAL: validar]` junto a «Con su permiso, siempre». | Sigue como comentario. **No se quitó ninguna sección ni el texto de permiso**: el título, el párrafo «Solo contactamos con quien ha aceptado…», el formulario y el aviso de privacidad permanecen visibles. El aviso de la línea 314 no fue retirado por este commit. |
| `src/pages/atencion-al-cliente.astro:172` | Solo `[LEGAL]` junto a «Quiero hacer una reclamación». | Sigue oculto; tarjeta y título visibles. |
| Mismo archivo, `:176` | «Por teléfono, por email o por la vía electrónica que fije la asesoría.» | Se mantiene «Puedes contactar con nosotros por teléfono o por email para explicarnos tu caso». No se repone una vía electrónica sin identificar. |
| Mismo archivo, `:181` | Bloque «Cómo», cuyo único valor era `[LEGAL: vías de reclamación y a nombre de quién se presenta · art. 6.1.x]`. | Sigue comentado; no contenía un procedimiento utilizable. |
| Mismo archivo, `:187` | Bloque «Plazo de respuesta», cuyo único valor era `[PENDIENTE: plazo del art. 55, por verificar]`. | Sigue comentado; no había un plazo confirmado. |
| Mismo archivo, `:213` | Solo `[LEGAL]` junto a «Mi reclamación no se ha resuelto». | Sigue oculto; tarjeta y título visibles. |
| Mismo archivo, `:217` | «Entidad de resolución alternativa de litigios: pendiente (31 L1, L2).» | Se mantiene la invitación a contactar para explicar el caso. No se repone la nota interna. |
| Mismo archivo, `:220` | Contenedor de la entidad alternativa, cuyo único contenido era `[LEGAL: entidad y enlace · art. 6.1.ak]`. | Sigue comentado hasta identificar la entidad. |
| Mismo archivo, `:228` | El texto de botón «Pedir mediación» se cambió a «Consultar reclamación»; el enlace no se eliminó. | Se mantiene la consulta, sin prometer una mediación no identificada. |

**No se restauran secciones de Canal Directo ni de atención al cliente**, porque sus tarjetas y canales útiles no se habían ocultado. En atención al cliente también se conservaron «Qué incluir» (nombre/empresa, CUPS, factura y explicación), el email de soporte, «Abrir reclamación», el enlace a CNMC y el contacto para derechos.

## Resto del inventario: se mantiene sin publicar

| Archivo y línea antes | Bloque o frase retirados | Motivo para mantener la decisión |
| --- | --- | --- |
| `src/components/tarifas/comunidades/ComoLoHacemosComunidades.astro:38` | «Una comunidad, por lo general, no se deduce el IVA: es lo que pagan los vecinos.» y la nota de la línea 39 `[LEGAL: validar con asesoría fiscal]`. | Validación fiscal pendiente. Sigue visible el consejo de comparar el importe con impuestos. |
| `src/pages/aviso-legal.astro:67` | Fila «Registro Mercantil», con el único valor `[PENDIENTE: tomo, folio y hoja (31 L1)]`. | No hay datos registrales utilizables; se mantiene el comentario. |
| Mismo archivo, `:76` | «BORRADOR TÉCNICO para la asesoría · no se publica · lo marcado entre corchetes falta o lo decide la asesoría». | Aviso interno sin información útil para el visitante. |
| Mismo archivo, `:82` | Marcador sobre la inscripción registral y el registro «de Madrid de Granollers». | La frase de titular, NIF y domicilio sigue visible; el dato registral continúa sin confirmar. |
| Mismo archivo, `:90` | «El suministro de electricidad lo realiza una comercializadora habilitada, que aparece identificada con su razón social en el contrato y en la factura.» y la nota de asesoría sobre nombrar las comercializadoras. | Falta identificar la entidad suministradora y validar el papel de EMAX. Se restaura solo la frase de análisis, indicada arriba. |
| `src/pages/cookies.astro:54` | El mismo aviso «BORRADOR TÉCNICO para la asesoría…». | Nota interna; el texto de almacenamiento no se ocultó en este commit. |
| `src/pages/guias/cups-y-tus-datos.astro:35` y `:83` | Marcador `[LEGAL: validar entera]` del índice y del título «Qué hacemos en EMAX con tu CUPS». | Se conservaron ambos títulos; solo desaparece la nota. |
| Mismo archivo, `:83`, nota del punto de acceso único | «La norma prevé además un punto de acceso único a los datos de consumo de todos los clientes, que gestionará Red Eléctrica como operador del sistema cuando se apruebe la orden ministerial que lo desarrolle (RD 88/2026, disposición adicional novena).» y `[VERIFICAR el estado de la orden el día de publicar]`. | Estado normativo expresamente marcado para comprobar antes de publicar. |
| Mismo archivo, `:83`, apartado «Para qué» | «Lo usamos para consultar los datos técnicos y de consumo de tu suministro y preparar tu diagnóstico.» | Afirma una consulta por EMAX cuya habilitación sigue sin identificar. Comentado en la línea actual 93. |
| Mismo archivo y línea, apartado «Antes de consultar» | «Te pedimos que confirmes que eres el titular o que la empresa titular te autoriza.» | Formaba parte del procedimiento de consulta sin validar. Comentado en la línea actual 94; no se confunde esa confirmación con una habilitación de acceso al SIPS. |
| `src/pages/guias/factura-luz-empresa.astro:84`, impuesto eléctrico | «El tipo general es del 5,11269632 %, pero en 2026 ha habido rebajas temporales», `[VERIFICAR: tipo vigente el día de publicar]` y «Algunos usos industriales tienen una reducción de la base». | Tipos y reducciones marcados sin verificar. Permanece visible la indicación de comprobar el tipo de la factura y la explicación general del impuesto. |
| Mismo archivo y línea, IVA | `[LEGAL: validar (31 L6)]` y «Para la mayoría de empresas es deducible; el impuesto eléctrico, en cambio, es coste». | Validación fiscal pendiente; se conserva «Se aplica sobre el total» y la consulta a la asesoría fiscal. |
| Mismo archivo y línea, revisión fiscal | «Datos fiscales revisados el [PENDIENTE: fecha de publicación].» | No se afirma una revisión ni una fecha inexistentes. |
| `src/pages/guias/peaje-3-0td.astro:87` | Solo `[PENDIENTE: tabla de Baleares, Canarias, Ceuta y Melilla (art. 7.2.a) y festivos verificados por año]`. | No existía una tabla con datos que restaurar; sigue el comentario. |
| `src/pages/guias/potencia-contratada.astro:27` | Resumen «Cambiarla tiene reglas: Doce meses entre cambios, costes y plazos». | Resume reglas de la tabla que se había marcado para no publicar sin verificar. |
| Mismo archivo, `:28` | Resumen «Por temporadas, todavía no: La norma lo prevé, pero aún no se puede usar». | Estado de la regulación temporal sin verificar. |
| Mismo archivo, `:243` | Fecha de revisión pendiente y `[VERIFICAR: RD 88/2026, art. 38; esta tabla no se publica hasta verificarlo]`. | Marcadores internos; no hay fecha ni revisión confirmadas. |
| Mismo archivo, `:247` | La tabla completa «Cambiar la potencia: preguntas, respuestas y fuente». Sus siete respuestas se detallan abajo. | Todo el bloque estaba expresamente sujeto a verificación previa; se conserva comentado y con el título/anchor y el contacto visibles. |
| Mismo archivo, `:290` | `[PENDIENTE: cómo lo gestiona EMAX (31 O5)]` dentro de la última fila de la tabla. | No hay una gestión confirmada que restaurar; queda en el bloque comentado. |
| Mismo archivo, `:302` | «La norma prevé que puedas tener una potencia distinta por trimestres, meses, días u horas, dentro de tus derechos de extensión. Pero todavía no se puede usar: empezará a aplicarse cuando la CNMC fije los precios de esas potencias temporales» y su marcador de verificación. | No se repone un estado normativo sin verificar. |
| Mismo archivo, `:304` | «Cuando se pueda, tendrá su propia guía». | Depende del estado anterior; no es una guía existente ni información independiente. |
| `src/pages/guias/precio-fijo-o-indexado.astro:84` | Fila «Indexado» cuyo único valor era `[PENDIENTE: nombre y componentes del indexado (31 C4)]`. | No tenía datos del producto; la explicación general del indexado sigue visible. |
| `src/pages/privacidad.astro:46` | Inciso «consultar el CUPS si nos autorizas» en el resumen de finalidades. | Falta validar el procedimiento/habilitación específica del acceso. |
| Mismo archivo, `:61` | Aviso «BORRADOR TÉCNICO para la asesoría…». | Nota interna. |
| Mismo archivo, `:65` | Recuadro cuyo único contenido ya era un comentario Astro sobre reparto de responsabilidades. | No tenía texto visible; se eliminó solo el recuadro vacío. El comentario se conserva. |
| Mismo archivo, `:71` | Solo `[ASESORÍA: ¿hay delegado de protección de datos? La fuente no lo identifica]`. | El responsable y contacto siguen visibles; el DPD está sin confirmar. |
| Mismo archivo, `:98` | Fila «Consultar los datos técnicos y de consumo de tu suministro con el CUPS, si nos lo das», con base propuesta «Tu solicitud y tu declaración de que eres el titular o estás autorizado (art. 6.1.b)» y nota para confirmar quién accede al SIPS y bajo qué acuerdo. | La base específica y la habilitación no están confirmadas. |
| Mismo archivo, `:130` | Solo `[ASESORÍA: base y forma de esta comunicación]` junto a la comercializadora. | No se ocultó la frase visible de la comercializadora, solo la nota interna. |
| Mismo archivo, `:134` | Párrafo cuyo único contenido ya era un comentario Astro de transferencias internacionales. | No tenía texto visible; se eliminó solo el párrafo vacío. Se conserva el comentario. |

### Contenido exacto de las siete filas de la tabla de potencia comentada

Todas pertenecen a `src/pages/guias/potencia-contratada.astro` en `e14c2d5^`:

| Línea | Pregunta | Respuesta retirada de la publicación |
| --- | --- | --- |
| 259 | ¿Cada cuánto puedo cambiarla? | «Tu distribuidora puede negarse si no han pasado doce meses desde tu último cambio voluntario, salvo que cambies de estructura tarifaria.» |
| 264 | ¿Cuánto cuesta bajarla? | «Nada más que lo regulado en el régimen de acometidas.» |
| 269 | ¿Cuánto cuesta subirla? | «Los derechos regulados: acceso, enganche y, si superas los derechos de extensión que tienes, extensión.» |
| 274 | Si la bajo, ¿puedo volver a subirla? | «Sí. Mientras conserves tus derechos de extensión, no pagas extensión hasta la potencia que tenías. Esos derechos tienen fecha de caducidad, y la fecha consta en los datos de tu suministro.» |
| 279 | ¿Cuánto tarda? | «72 horas. Si hay que actuar en tu instalación o en el contador, cinco días hábiles en baja tensión y diez en alta.» |
| 284 | ¿Hay alguna comprobación? | «En baja tensión, si tu contrato tiene más de veinte años y subes potencia, se verifica tu instalación.» |
| 289 | ¿Quién lo tramita? | «El cambio se hace sobre tu contrato de acceso con la distribuidora, normalmente a través de tu comercializadora», seguido del marcador sobre la gestión de EMAX. |

No se ha hecho una validación jurídica nueva de las afirmaciones que siguen ocultas. La separación anterior permite recuperar la información independiente sin presentar como resueltos los extremos pendientes.
