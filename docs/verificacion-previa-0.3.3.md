# Verificación previa a publicación · 0.3.3

Fecha: 2026-11-09. Cambios realizados por Luciano Lyall (lucianolyall@gmail.com).

## Título de la home en el HTML inicial

Tras `git pull` (`Already up to date.`), `src/components/home/ComoEmpezar.astro:57` y el `dist/index.html` existente ya contienen el título completo:

```html
<h2 class="proceso-titulo">
  TÚ HACES TRES COSAS.<br />
  DEL RESTO <span class="proceso-titulo-cursiva">nos encargamos.</span>
</h2>
```

El título es texto estático, no se construye con JavaScript, pseudo-elementos ni contenido de una animación. El script del componente controla la línea y los estados de los pasos, sin modificar el título. Su CSS no oculta «RESTO» ni cambia su contenido. No se modificó el título, sus estilos ni la interacción al no reproducirse el problema en este checkout; la captura/Lighthouse mencionados no permiten atribuir una causa al texto incompleto observado en otra ejecución.

Se extrajo el `<h2>` directamente de `dist/index.html` y se comprobó su texto exacto. Además, Chromium headless abrió la home con `javaScriptEnabled: false` en estos cuatro contextos:

| Ancho | `prefers-reduced-motion` | Resultado |
| --- | --- | --- |
| 1440 px | Sin preferencia | Título completo y visible |
| 1440 px | `reduce` | Título completo y visible |
| 375 px | Sin preferencia | Título completo y visible |
| 375 px | `reduce` | Título completo y visible |

Texto leído en los cuatro casos: **TÚ HACES TRES COSAS. DEL RESTO nos encargamos.**

Evidencias locales, ignoradas por Git: `.astro/revision-033/home-estatica.json` y las cuatro capturas `home-sin-js-*.png`. La comprobación se repitió tras las restauraciones y el build de 0.3.3, con el mismo resultado.

## Comprobaciones del build de 0.3.3

| Comprobación | Resultado |
| --- | --- |
| `git pull` antes de empezar | `Already up to date.`; rama `main` e identidad local Luciano Lyall `<lucianolyall@gmail.com>` |
| `npx astro check` | 157 archivos, 0 errores, 0 warnings, 0 hints |
| `npm run check`, repetido tras el ajuste móvil | 157 archivos, 0 errores, 0 warnings, 0 hints |
| `npm run build`, repetido tras el ajuste móvil | 40 páginas, sin warnings |
| `rg -n 'consultoriaemax' src --stats` | 0 coincidencias, 223 archivos revisados |
| `rg -n '\[PENDIENTE|\[ASESORÍA|\[LEGAL|PENDIENTE|consultoriaemax' dist --stats` | 0 coincidencias, 791 archivos revisados |
| HTML inicial de la home | Texto completo presente directamente en `dist/index.html` |
| Home sin JavaScript | Texto completo y visible a 1440 y 375 px, con y sin movimiento reducido |
| Home, aviso legal, privacidad, Canal Directo y atención al cliente | HTTP 200; capturas revisadas a 1440 y 375 px; contacto legal nuevo legible; sin desbordamiento horizontal de página |
| Canal Directo | «Con su permiso, siempre», su párrafo, perfiles y formulario siguen visibles; no se había ocultado ninguna sección |
| Guía del CUPS | Los tres apartados restaurados aparecen en el HTML y en el navegador; los dos de consulta quedan comentados |
| JavaScript y recursos en las cinco páginas | 0 errores de página, 0 peticiones externas |
| Imágenes diferidas de la home | Se recorre la página antes de la captura: todas las imágenes del contenido están cargadas a ambos anchos |
| Tarjetas de atención al cliente a 375 px | Tras el ajuste: las seis miden 327 px y quedan entre x=24 y x=351, sin descendientes que sobresalgan |
| Versiones | `package.json` y las dos entradas raíz de `package-lock.json`: 0.3.3 |

En Windows se usaron `npm.cmd` y `npx.cmd` para evitar los wrappers `.ps1` bloqueados por PowerShell. El resultado de `rg` sin coincidencias tiene código de salida 1, que aquí indica la ausencia esperada del texto buscado.

## Ajuste derivado de la revisión visual

La primera captura de atención al cliente a 375 px reveló un recorte aunque el ancho del documento no indicara scroll horizontal: el contenido mínimo de las tarjetas las extendía hasta x=387,1875. Se añadió `min-width: 0` y `overflow-wrap: anywhere` a `.ac-card` dentro de la media query móvil existente en `src/pages/atencion-al-cliente.astro`. Se mantienen la cuadrícula, colores, textos y estructura. La comprobación posterior midió cada tarjeta y todos sus descendientes; ninguna excede sus límites. Se repitieron check y build tras ese cambio.

Las capturas completas de las cinco páginas y los recortes de título, permiso, reclamaciones, derechos y CUPS están en `.astro/revision-033/`. `verificacion.json` conserva el recorrido inicial; `atencion-final.json` registra las seis tarjetas después del ajuste. Las capturas de la home se actualizaron tras cargar sus imágenes diferidas. Las salidas completas están en `check.log`, `npm-check.log` y `build.log`, dentro de esa carpeta ignorada por Git.

No se enviaron formularios, se crearon ramas ni se publicaron cambios. La revisión de los asuntos que siguen ocultos está en [el inventario de e14c2d5](revision-e14c2d5.md); el contacto vigente y la referencia actualizada del SIPS constan en [el mapa de pendientes](verificacion-legal-0.3.2.md).
