# Revisión de textos visibles · 0.3.2

Fecha: 2026-11-09. Cambios realizados por Luciano Lyall (lucianolyall@gmail.com).

Se recorrieron las **40 páginas HTML de `dist/` con Chromium headless y Playwright**, leyendo el texto visible del navegador y revisando su versión sin líneas repetidas. No hubo errores JavaScript, peticiones externas ni enlaces internos o anchors rotos en ese recorrido. La revisión editorial no verifica todas las afirmaciones fiscales, comerciales o normativas del sitio.

## Correcciones evidentes

| Página / archivo | Hallazgo | Resultado |
| --- | --- | --- |
| Home, `src/components/home/ComoEmpezar.astro:59` | La petición menciona «DEL nos encargamos». | `main`, tras `git pull`, ya contiene **«DEL RESTO nos encargamos.»**, también visible en el build. Se conserva el título y sus estilos. |
| Contacto recibido, `src/components/contacto/HeroContactoRecibido.astro` | «En cambio, quieres que revisemos tu factura» quedaba como una afirmación extraña en un enlace de acción. | «¿Quieres que revisemos tu factura?» |
| Método, `src/components/metodo/QuienLeeTusDatos.astro` | «ASI FUNCIONA», sin tilde. | «ASÍ FUNCIONA». |
| Precio fijo o indexado, `src/pages/guias/precio-fijo-o-indexado.astro` | Se mostraba literalmente `&quot;la mejor opción siempre&quot;`, porque las entidades estaban dentro de una cadena de JavaScript. | Comillas reales en la cadena; se muestran como comillas. |
| Hoteles, `src/components/sectores/hoteles/PorQueElVerano.astro` | Coma entre sujeto y verbo en «POR QUÉ EL VERANO, se paga todo el año». | Se retira la coma conservando las partes y sus estilos. |

Las frases cortadas por marcadores, los avisos de borrador y la falsa afirmación de borrado de `sessionStorage` se resolvieron en los puntos 4 y 5 del CHANGELOG.

## Dudas conservadas, sin corregir cifras ni promesas

- `src/pages/guias/bajar-factura-luz-empresa.astro`: «Un ejemplo real de cambio sin análisis» junto a «Ejemplo ilustrativo». Hace falta confirmar si se basa en un caso real o cambiar su clasificación.
- Home frente a potencia contratada/tarifas: el diagnóstico del obrador propone 22 kW en P1/P2 con febrero, mientras la propuesta anual es 25/30 kW con doce meses. Puede ser intencional, pero el mensaje de la home necesita revisión para que no parezca la recomendación final basada en un mes.
- `src/pages/guias/energia-reactiva.astro`: 21,34 € mensuales frente a «unos 252 € al año». Multiplicar un mes por doce da 256,08 €, pero no se sabe si se pretende anualizar febrero o usar el histórico; no se corrige a ciegas.
- `src/components/diagnostico/CasoRealObrador.astro`: «Potencia P1-P6 + Impuestos» por 234,60 € no aclara si incluye IVA. Se mantiene para revisión del desglose del ejemplo.
- `src/pages/guias/tarifa-indexada-con-techo.astro`: el resumen de la siguiente guía dice «OMIE [...] hora a hora», mientras la guía de OMIE explica precios por cuarto de hora. Requiere revisar el resumen técnico, no solo ortografía.
- Las afirmaciones normativas de las guías que no estaban marcadas no se han validado jurídicamente. Las que sí tenían notas de verificación siguen documentadas en comentarios, con la tabla de potencia y los bloques temporales sin renderizar.

## Evidencias locales

El recorrido guarda el texto visible y los resultados en `.astro/legal-review/` (ignorado por Git). La revisión visual de aviso legal, privacidad, cookies, footer y título de la home, a 1440 y 375 px, se registra en `docs/verificacion-legal-0.3.2.md`.
