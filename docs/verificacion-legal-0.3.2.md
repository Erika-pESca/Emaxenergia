# Verificación de los textos legales · 0.3.2

Fecha: 2026-11-09. Cambios realizados por Luciano Lyall (lucianolyall@gmail.com).

**Actualización en 0.3.3 (2026-11-09):** por decisión de Luciano Lyall, el contacto legal y de derechos vigente es `administracion@emaxenergia.com`; el anterior solo queda como cita histórica en las fuentes. La revisión de `e14c2d5` restaura la descripción del análisis de EMAX en el aviso legal y, en la guía del CUPS, «Lo que no hacemos», «Si no quieres darlo» y «Responsable del tratamiento». Siguen sin publicar los dos apartados que atribuyen a EMAX la consulta del SIPS y requieren validar su habilitación; no se considera resuelto ese pendiente. El inventario completo figura en [la revisión del commit](revision-e14c2d5.md). Las comprobaciones anteriores se conservan como evidencia de 0.3.2; las de 0.3.3 constan en [la verificación previa](verificacion-previa-0.3.3.md).

## Comprobaciones realizadas

| Comprobación | Resultado |
| --- | --- |
| `git pull` en `main`, antes de editar | `Already up to date.` |
| Identidad local de Git | Luciano Lyall `<lucianolyall@gmail.com>` |
| `npm run check` | 157 archivos; 0 errores, 0 warnings, 0 hints |
| `npx astro check` | 157 archivos; 0 errores, 0 warnings, 0 hints |
| `npm run build` | 40 páginas generadas; sin warnings |
| `rg -n '\[PENDIENTE|\[ASESORÍA|\[LEGAL|PENDIENTE' dist --stats` | 0 coincidencias en 791 archivos |
| Domicilio antiguo, «Grupo EMAX», `_ga` y las tres claves de almacenamiento inexistentes en `dist/` | Sin coincidencias |
| Recorrido con Chromium headless de las 40 páginas generadas | 0 errores JavaScript, 0 peticiones externas, 0 enlaces internos o anchors rotos |
| Aviso legal, privacidad y cookies, a 1440 y 375 px | Capturas revisadas; sin desbordamiento horizontal de página; enlaces largos de email legibles |
| Footer de las tres páginas, a 1440 y 375 px | Domicilio actualizado, sin marca de Grupo EMAX ni notas internas; enlaces legales y configuración presentes |
| Tabla de cookies en móvil | Mantiene el scroll horizontal del diseño original. Se puede llegar a finalidad y duración. Los detalles de los campos pasan a párrafos contiguos; tabla final de 265 px de alto a 375 px de ancho de pantalla |
| Título «¿Cómo cambiarte?» en la home | «TÚ HACES TRES COSAS. DEL RESTO nos encargamos.» a 1440 y 375 px, con los estilos originales |
| Panel de cookies | Abre desde la política, permite marcar Analítica y guardar; persiste `analitica`, `caduca`, `version`; no se crean cookies HTTP ni peticiones externas |
| Script del banner comparado con Git | Idéntico; solo se ajustó su texto informativo |
| Versiones | `package.json` y raíz de `package-lock.json`: 0.3.2 |

En Windows se usaron `npm.cmd` y `npx.cmd` porque PowerShell bloquea los wrappers `.ps1`. Las salidas completas están en `.astro/legal-check.log` y `.astro/legal-build.log`. El servidor de desarrollo se inició con `astro dev --background` y se detuvo con `astro dev stop`; la revisión de `dist/` se hizo con `astro preview` en el puerto 4322. No se enviaron formularios ni se publicaron cambios.

## Pendientes que no se renderizan

Las referencias son a los archivos de esta versión. Ocultar las notas no resuelve los asuntos que requieren confirmación de la empresa o su asesoría.

| Pendiente | Archivo y línea |
| --- | --- |
| Registro Mercantil correcto y tomo, folio, hoja e inscripción; la fuente dice «de Madrid de Granollers» | `src/pages/aviso-legal.astro:67`, `:77` |
| Condición de EMAX como asesoría o comercializadora y entidad que suministra | `src/pages/aviso-legal.astro:86` |
| Titular registral de EMAX Energía y titular de Maxi | `src/pages/aviso-legal.astro:100` |
| Reparto de responsabilidades y cesiones por fase | `src/pages/privacidad.astro:62`, `:123` |
| Existencia y contacto del delegado de protección de datos | `src/pages/privacidad.astro:67` |
| Acceso al SIPS, acuerdo habilitante y base específica | `src/pages/privacidad.astro:94`; `src/pages/guias/cups-y-tus-datos.astro:91` (ubicación actualizada en 0.3.3) |
| Existencia e identificación de un CRM | `src/pages/privacidad.astro:122` |
| Transferencias internacionales y garantías de Hostinger, Google Workspace, Resend y, cuando corresponda, WhatsApp | `src/pages/privacidad.astro:126` |
| Validación jurídica del permiso de contacto y de la base/comunicaciones del Canal Directo | `src/pages/canal-directo/index.astro:151`, `:315` |
| Vías formales de reclamación y a nombre de quién se presenta; plazo aplicable; entidad alternativa y enlace | `src/pages/atencion-al-cliente.astro:172`, `:181`, `:182`, `:203`, `:210` |
| Base específica y suficiencia de las primeras capas de los cinco formularios | Documentado en `AUDITORIA.md`, C-4; la fuente no valida los flujos nuevos |
| Deducibilidad del IVA en comunidades | `src/components/tarifas/comunidades/ComoLoHacemosComunidades.astro:39` |
| Tipos/reducciones vigentes del impuesto eléctrico, validación fiscal del IVA y fecha de revisión | `src/pages/guias/factura-luz-empresa.astro:84` |
| Calendarios de Baleares, Canarias, Ceuta y Melilla y festivos por año | `src/pages/guias/peaje-3-0td.astro:87` |
| Tabla de cambios de potencia y revisión del art. 38; gestión por EMAX; aplicación de potencias temporales | `src/pages/guias/potencia-contratada.astro:27`, `:28`, `:243`, `:287`, `:300` |
| Estado de la orden del punto de acceso único de consumo | `src/pages/guias/cups-y-tus-datos.astro:83` |
| Nombre comercial y componentes del indexado EMAX | `src/pages/guias/precio-fijo-o-indexado.astro:84` |

Además se conservan comentarios generales de revisión en las páginas legales. No se obtiene una política de cookies de la web actual (`#/cookies` sin contenido), pero la nueva se documenta con el almacenamiento real del código. Las dudas editoriales que no son simples erratas están en [la revisión de textos](revision-textos-0.3.2.md).

## Evidencias locales

`.astro/legal-review/result.json` contiene el recorrido, enlaces y comprobaciones de ancho; `cookies-final.json` registra la revisión posterior de las filas abreviadas. Las capturas de las tres páginas legales, footer y título de la home, a ambos anchos, están en `.astro/legal-review/*.png`. Son artefactos locales ignorados por Git.
