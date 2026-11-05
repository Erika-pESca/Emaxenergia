# Auditoría técnica pre-publicación — emaxenergia.com

Fecha: 2026-11-05 · Auditoría sobre `main`. La fase 1 de correcciones se aplicó el mismo día en la rama `fix/auditoria-fase-1` (versión 0.1.0, ver [CHANGELOG.md](CHANGELOG.md)); cada hallazgo resuelto lo indica con ✅. · Astro 7.3.6, `output: 'static'` · Destino: Hostinger (Apache/LiteSpeed, sin Node).
El informe original se hizo en modo solo lectura. Lo que sigue es ese informe más el estado tras la fase 1.

## Cómo se hizo y qué NO se pudo comprobar

| Comprobación | Resultado |
|---|---|
| `npx astro check` | **No se pudo ejecutar** en la auditoría (faltaban `@astrojs/check` y `typescript`). En la fase 1 se instalaron: 34 errores → 0 (M-10). |
| Recorrido de `dist/` (39 páginas HTML) | Hecho con un script propio: links internos, anchors, assets, `alt`, `target=_blank`, títulos, descripciones, canonical, h1. |
| Navegador headless contra `localhost:4321` | Hecho: 0 errores de consola, 0 requests fallidas, en la home. Pruebas del desplegable y desbordes a 375 px. |
| `npm audit` / `npm outdated` | Hecho. |
| Historial git (`git log -p --all`) | Patrones de secretos: **sin hallazgos reales**. Ver B-6. |
| Cabeceras HTTP reales, HTTPS, redirecciones | **No verificable** desde local: es lo que habrá que probar en Hostinger tras publicar. |
| Contraste de color, lector de pantalla | No medidos con herramienta; solo revisión de código y capturas. |
| Duplicación entre componentes de sectores/tarifas | Solo cuantificada por tamaño; **no se han comparado línea a línea**. |

---

## Resumen ejecutivo

El sitio compila y no da errores de consola, pero **no está listo para publicar**. Lo más grave:

1. **Ningún formulario envía datos a ningún sitio**, y aun así las páginas dicen "Hemos recibido tu solicitud". Los leads se pierden y el usuario cree que lo hemos recibido.
2. **Los tres botones principales "Analizar mi factura" (cabecera, dock, menú móvil) no hacen nada**: apuntan a `/#estudio` y ese id no existe en ninguna página.
3. **Las páginas legales están llenas de marcadores `[PENDIENTE]`, `[ASESORÍA]`, `[LEGAL]` visibles al público** (39 de 39 páginas llevan al menos uno por el footer). Falta el registro mercantil, el plazo de conservación, el buzón de privacidad, el CRM y el proveedor de email.
4. Imágenes sin optimizar: `dist/` pesa 138 MB, con banners PNG de 4–4,5 MB.
5. Fuentes de Google cargadas antes del consentimiento.

---

## CRÍTICO

### C-1. Los formularios no envían nada, pero muestran confirmación
- [Formulario.astro:441-456](src/components/Formulario.astro#L441-L456): el `fetch('/api/leads')` está comentado; la función solo hace `console.log` de los datos y devuelve `true`. Luego pasa al paso 4 "Listo".
- [HablaConNosotros.astro:162-179](src/components/contacto/HablaConNosotros.astro#L162-L179): valida y hace `window.location.href = "/contacto/recibido"`. No hay petición de red.
- [canal-directo.astro:343-418](src/pages/canal-directo.astro#L343-L418): guarda en `sessionStorage` y redirige a `/canal-directo/enviado`. Tampoco envía.
- [Header.astro:535-545](src/components/Header.astro#L535-L545): el popover "Te llamamos gratis" muestra "éxito" y se cierra a los 3 s sin enviar nada.
- **Por qué importa:** cada lead se pierde en silencio. Además, decir "Hemos recibido tu consulta" cuando no es cierto es un problema comercial y de consumo. Un sitio estático en Hostinger no tiene `/api/leads`.
- **Corrección:** elegir backend antes de publicar. Opciones sin Node en el servidor: (a) un script PHP en Hostinger que valide y envíe email por SMTP; (b) un servicio externo de formularios con DPA y servidores UE (FormSubmit, Formspree EU, Web3Forms…; confirmar ubicación de datos); (c) un CRM con endpoint de formulario. Mientras tanto, no mostrar "recibido": mostrar error si el envío falla. Unificar los 4 formularios en un solo módulo JS.

### C-2. Los CTA "Analizar mi factura" no abren el formulario

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): Header (escritorio y móvil), Dock, `HeroContactoRecibido`, `HeroContacto`, `CompararOfertas` y `SegunQuienPaga` llevan `data-abrir-funnel` y `href` de respaldo `/contacto/`. Verificado en navegador a 1440 y 375 px: todos abren el modal.
- [Header.astro:342](src/components/Header.astro#L342), [Header.astro:400](src/components/Header.astro#L400) (móvil), [Dock.astro:17](src/components/Dock.astro#L17), [HeroContactoRecibido.astro:63](src/components/contacto/HeroContactoRecibido.astro#L63): `href="/#estudio"` sin `data-abrir-funnel`.
- El modal solo se abre con `[data-abrir-funnel]` ([Formulario.astro:384-391](src/components/Formulario.astro#L384-L391)). No existe ningún `id="estudio"` en `dist/` (comprobado en las 39 páginas). Otros enlaces con `#estudio`: [CompararOfertas.astro:77](src/components/tarifas/CompararOfertas.astro#L77) (este sí lleva `data-abrir-funnel`), [SegunQuienPaga.astro:21](src/components/tarifas/gas/SegunQuienPaga.astro#L21) (`btnHref: "#estudio"`, hay que comprobar si pasa el atributo), [HeroContacto.astro:20](src/components/contacto/HeroContacto.astro#L20) (sí lo lleva).
- **Por qué importa:** es la llamada a la acción principal del sitio y está en todas las páginas. Pulsarla no hace nada o salta a la home.
- **Corrección:** añadir `data-abrir-funnel` a esos enlaces (el `href` queda como respaldo sin JS, p. ej. `/contacto`). Revisar que `SegunQuienPaga` también lo lleve.

### C-3. Texto legal en borrador visible al público (RGPD / LSSI)
- 42 apariciones de `[LEGAL]` y más de 10 marcadores distintos en producción. Ejemplos:
  - [Footer.astro:163](src/components/Footer.astro#L163): `[LEGAL]` en el copyright de **todas** las páginas.
  - [aviso-legal.astro:70](src/pages/aviso-legal.astro#L70), [:83](src/pages/aviso-legal.astro#L83): Registro Mercantil (tomo, folio, hoja) pendiente. La LSSI (art. 10) exige los datos de inscripción registral.
  - [aviso-legal.astro:104](src/pages/aviso-legal.astro#L104): titular de la marca sin decidir. El footer dice "marca de Grupo EMAX", la política dice ENERGYMAX ADVISORY.
  - [privacidad.astro:66](src/pages/privacidad.astro#L66): responsable del tratamiento sin decidir; [:71](src/pages/privacidad.astro#L71) buzón de privacidad y DPD; [:125](src/pages/privacidad.astro#L125) plazo de conservación; [:130](src/pages/privacidad.astro#L130) CRM y proveedor de email sin nombrar; [:135](src/pages/privacidad.astro#L135) transferencias fuera del EEE; [:140](src/pages/privacidad.astro#L140) buzón para ejercer derechos.
  - [canal-directo.astro:314](src/pages/canal-directo.astro#L314): `[LEGAL: 31 L2]` junto al aviso del formulario.
- **Por qué importa:** una política de privacidad incompleta incumple los arts. 13 RGPD y 11 LOPDGDD; los marcadores internos dan imagen de web sin terminar.
- **Corrección:** esto lo tiene que cerrar la empresa o su asesoría, no el código. Lista de datos que hacen falta: inscripción registral; titular de marca; responsable del tratamiento en cada fase; email de privacidad; DPD (sí/no); plazos; proveedores (CRM, email, hosting); transferencias internacionales; jurisdicción. Después, quitar las clases/etiquetas `.pendiente`. Mientras no estén, **no publicar**.

### C-4. Los formularios no informan de la protección de datos en el punto de recogida
- [Formulario.astro:176-216](src/components/Formulario.astro#L176-L216) (paso 3, el que pide nombre y teléfono): solo dice "un consultor técnico te llamará…". No hay enlace a la política, ni responsable, ni finalidad, ni derechos.
- [HablaConNosotros.astro:132-134](src/components/contacto/HablaConNosotros.astro#L132-L134) y [canal-directo.astro:314](src/pages/canal-directo.astro#L314) sí enlazan `/privacidad`, pero sin información por capas (responsable, finalidad, base, derechos). El popover de la cabecera tampoco la lleva.
- **Corrección:** primera capa en cada formulario (responsable, finalidad, derechos, enlace). No hace falta casilla para contactos basados en la solicitud del usuario; sí si se van a enviar comunicaciones comerciales. Para el funnel, que además lee datos de consumo, conviene confirmar la base legal con la asesoría.

---

## ALTO

### A-1. Fuentes de Google cargadas antes del consentimiento

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): fuentes locales con la API de fuentes de Astro (fontsource), solo los pesos que usa el CSS, preload de Inter 400 y Poppins 700/800, fallbacks con métricas ajustadas. Fuera el `<link>` y los `preconnect` de Google. 0 peticiones externas en el sitio.
- [BaseLayout.astro:23-29](src/layouts/BaseLayout.astro#L23-L29): `preconnect` a `fonts.googleapis.com` / `fonts.gstatic.com` y hoja de estilos con 4 familias y ~20 pesos. Se descarga en la primera visita, sin consentimiento, y envía la IP del visitante a Google.
- **Por qué importa:** es el caso típico de sanción/reclamación RGPD (sentencia LG München, 2022); la AEPD lo trata como tratamiento con transferencia a EE. UU. Y la política de privacidad no lo menciona. También es peso y bloqueo de render.
- **Corrección:** alojar las fuentes en el propio dominio (p. ej. `@fontsource` o los `.woff2` en `/_astro`), reducir pesos (Inter, Literata, Poppins y Sora son demasiadas), quitar los `preconnect`.

### A-2. Imágenes sin optimizar: 138 MB en `dist/`

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): originales de 150,0 MB a 14,3 MB (máx. 2400 px, JPG 85) y las 54 imágenes en `<Picture>` avif/webp; `dist/` de 125,6 MB a 47,2 MB (con respaldo jpg/png por tamaño). Solo el hero lleva `eager` + `fetchpriority="high"`. Sin imágenes en `public/` ni en CSS. Capturas antes/después: misma altura y aspecto.
- Todas las imágenes se usan con `<img src={imagen.src}>` (p. ej. [Hero.astro:17-18](src/components/home/Hero.astro#L17-L18)). **Ningún componente usa `astro:assets` (`<Image>`/`<Picture>`)**, así que Astro solo copia y renombra los PNG originales.
- Mayores: `Banner (3).png` 4,5 MB, `Banner.png` 4,5 MB, `banner 2.png` 4,4 MB, `Banner-home.png` 4,2 MB, `No te prometemos.png` 4,2 MB, `Supermecado B.png` / `Tienda a.png` / `Hotel de costa.png` / `Restaurante a.png` ~2,8 MB, `personal.jpg` 2,5 MB. En total hay decenas por encima de 300 KB. `src/imagenes` pesa 151 MB.
- La home carga `Banner-home.png` (4,2 MB) como imagen de hero: LCP muy malo en móvil y desperdicio de datos.
- **Corrección:** migrar a `<Image>`/`<Picture>` de `astro:assets` (AVIF/WebP, `widths`/`sizes`, `width`/`height` para evitar CLS). Mientras, comprimir los originales (objetivo <300 KB por imagen, hero <200 KB). Dejar `fetchpriority="high"` solo en la imagen LCP.

### A-3. Enlaces internos rotos (15 páginas) y anchors inexistentes

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): 0 enlaces rotos y 0 anchors inexistentes en `dist/`. Tablas enlace anterior → nuevo en el CHANGELOG. `#supuestos` se quitó (no hay sección que lo trate); el resto apunta a ids que ya existían.
Enlaces a rutas que no generan página (404):
| Enlace | Dónde |
|---|---|
| `/calcular-ahorro` | [CierreGasto.astro:74](src/components/guias/CierreGasto.astro#L74) y el campo `enlace` de 13 guías (p. ej. [cups-y-tus-datos.astro:68](src/pages/guias/cups-y-tus-datos.astro#L68), [glosario.astro:14](src/pages/guias/glosario.astro#L14), [maximetro.astro:52](src/pages/guias/maximetro.astro#L52), [omie.astro:56](src/pages/guias/omie.astro#L56)) |
| `/gas-natural` | [FacturaPrevisible.astro:95](src/components/sectores/hoteles/FacturaPrevisible.astro#L95) (la ruta real es `/tarifas/gas-natural`) |
| `/tarifas/luz-empresas` | [QueMiramosEnTuLocal.astro:40](src/components/sectores/restaurantes/QueMiramosEnTuLocal.astro#L40) (la ruta real es `/tarifas`) |

Anchors a ids que no existen en la página destino:
`/tarifas#modalidad` (5 páginas), `/tarifas#estructura`, `/sectores/supermercados#reactiva`, `/sectores/industria#peaje`, `/sectores/hoteles#potencia-hoy`, `/guias/factura-luz-empresa#supuestos`, y `#estudio` (C-2).
(`#configurar-cookies` no es un fallo: lo gestiona el JS del banner.)

- **Corrección:** `/calcular-ahorro` → crear la página o apuntarlo a `/contacto` o abrir el funnel; corregir las 2 rutas; añadir los ids o quitar los anchors.
- No se encontraron imágenes sin `alt` (0 de todas las `<img>` en `dist/`) ni enlaces `target="_blank"` sin `rel="noopener noreferrer"`. Los "assets perdidos" que listó el script son falsos positivos: son nombres con espacios en `srcset` y los archivos existen en `dist/_astro/`. Aun así, los nombres con espacios, paréntesis y caracteres raros (`Hero (1).png`, `La persona}.png`, `sectores-indice-t¿hogar.png`) conviene renombrarlos (ver B-3).

### A-4. Faltan cabeceras de seguridad, `.htaccess` y página 404

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): `public/.htaccess` y `src/pages/404.astro` creados. HSTS en `max-age=300` hasta confirmar HTTPS. **Pendiente de verificar en Hostinger con `curl -I`.**
- No hay `.htaccess` ni `404.html` en `dist/` (no existe `src/pages/404.astro`). En Hostinger, una URL inexistente devolverá la página de error por defecto del servidor.
- Propuesta completa más abajo (sección "Propuesta de `.htaccess`").

### A-5. Páginas de confirmación indexables y en el sitemap

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): prop `noindex` en `Seo.astro`, aplicada a las dos confirmaciones y a la 404; excluidas del sitemap (39 → 37 URLs).
- `/contacto/recibido/` y `/canal-directo/enviado/` se generan y entran en el sitemap (no hay filtro en `astro.config.mjs`). [Seo.astro](src/components/Seo.astro) no tiene opción `noindex`.
- **Corrección:** prop `noindex` en `Seo.astro` que emita `<meta name="robots" content="noindex, nofollow">`, y `sitemap({ filter: ... })` para excluirlas.

### A-6. Consentimiento de cookies: aceptar y rechazar no pesan igual, y el texto legal no coincide con el código
- [CookieBanner.astro:21-26](src/components/CookieBanner.astro#L21-L26), estilos `.ck__b--aceptar` (relleno azul, sombra) vs `.ck__b--rechazar` (borde blanco, texto gris): mismo tamaño, **distinto peso visual**. La guía de cookies de la AEPD (2023) exige que rechazar sea tan fácil y visible como aceptar.
- Lo que sí está bien: consentimiento por defecto `denied` (Consent Mode v2, [CookieBanner.astro:92-104](src/components/CookieBanner.astro#L92-L104)); elección guardada en `localStorage` con caducidad de 1 año; se puede cambiar desde el footer ("Configurar cookies" → `data-abrir-cookies`); panel con `<dialog>`; solo se registra la categoría "Analítica".
- **Desajuste:** [cookies.astro:105](src/pages/cookies.astro#L105) y la política de privacidad describen Google Analytics 4 (`_ga`, `_ga_*`). **El código no carga GA4 ni ningún script de Google**: solo define un `gtag` y el consentimiento por defecto. Hoy el banner no bloquea nada porque no hay nada que bloquear. Cuando se añada GA4 hay que cargarlo **solo tras** `granted` (no solo `gtag("consent","update")`; con Consent Mode básico el script no debe cargarse hasta el consentimiento).
- Con solo "Analítica" la función "Configurar" es mínima, pero válida. En 1280×720 el banner tapa el texto del hero (captura): minimizar o desplazar.
- **Corrección:** igualar el peso visual de los dos botones; decidir si habrá GA4. Si no, quitar GA4 de las políticas y el banner puede reducirse a un aviso informativo (solo `localStorage` técnico, que no requiere consentimiento); si sí, cargar el script condicionalmente.

### A-7. Dependencias con vulnerabilidades `high`

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): `npm audit` 0 vulnerabilidades; astro 7.4.5.
- `npm audit`: `undici` 8.0.0–8.12.1 (8 avisos) y `zod` <4.6.6 (1 aviso). Ambos son transitivos de `astro` y **solo afectan a build/dev**, no al sitio publicado (HTML estático). Aun así, `astro` 7.3.6 → 7.4.5 está disponible y `npm audit fix` los resuelve.
- **Corrección:** actualizar a astro 7.4.5 con `npm audit fix` y repetir el build.

### A-8. Desplegables de la cabecera: lógica frágil (bug visto)
**Qué se comprobó:** en navegador, la home carga con los tres desplegables cerrados (`opacity:0`, `visibility:hidden`, sin clase `abierto`). Hover → abre; mover el ratón fuera → cierra a los ~180 ms; clic → alterna; Escape y Tab no los cierran. **No he conseguido reproducir el estado "Método abierto y semitransparente al cargar".** Y los tres chevrons son idénticos en markup y estilo calculado (12 px, mismo color): no he encontrado la diferencia visual del chevron de "Método"; si se ve distinto, será por el estado `activo` en `/metodo` o `/diagnostico`, o por la captura. Las hipótesis siguientes son causas posibles, no confirmadas:
1. **Dos fuentes de verdad.** La visibilidad la gobierna el CSS (`.nav-dropdown:hover`, `.abierto`, [Header.astro:748-818](src/components/Header.astro#L748-L818)), pero el estado JS (`abierto`, `aria-expanded`) puede ir por otro lado. Un panel que aparece por `:hover` con el cursor parado bajo "Método" (p. ej. al recargar) queda visible aunque JS no lo sepa, y la transición de `0.2 s` explica el aspecto semitransparente si se captura a mitad.
2. **Tablets/táctil (≥961 px):** el toque dispara `mouseenter` (añade `abierto`) y luego `click`, que ve `isActive=true` y lo **cierra** → el menú no se puede abrir con el dedo ([Header.astro:474-503](src/components/Header.astro#L474-L503)).
3. **Teclado:** Escape no cierra; no hay `focusout`; al tabular a otro enlace no desplegable, el panel queda abierto; los enlaces del panel solo son enfocables si está abierto.
4. `z-index: 9999` del panel por encima de todo, incluido el modal si coincide (el modal usa también 9999, [Formulario.astro](src/components/Formulario.astro)). Debe ser jerarquía definida.
- **Corrección:** una sola fuente de verdad (clase `abierto`, sin `:hover` en CSS), abrir por hover solo si `matchMedia('(hover:hover)')`, click para táctil y teclado, cerrar con Escape y `focusout`, limpiar el temporizador. Normalizar `z-index` con variables.

---

## MEDIO

### M-1. Títulos duplicados (SEO)

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): títulos propios en `/`, `/metodo/` y `/diagnostico/`; 0 títulos duplicados.
`/`, `/metodo` y `/diagnostico` tienen exactamente `<title>EMAX Energía</title>` ([index.astro:16](src/pages/index.astro#L16), [metodo/index.astro:18](src/pages/metodo/index.astro#L18), [diagnostico/index.astro:13](src/pages/diagnostico/index.astro#L13)). Como `Seo.astro` solo añade el sufijo si el título no contiene "EMAX", se queda así. Las descripciones son únicas (0 duplicados). **Corrección:** títulos descriptivos, p. ej. "Método de análisis de consumo para empresas | EMAX Energía".

### M-2. Canonical sin barra final vs. sitemap y URL real con barra final

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): `trailingSlash: 'always'`, canonical con barra y 374 enlaces internos normalizados. Canonical == sitemap en las 37 páginas indexables.
`<link rel="canonical">` emite `https://emaxenergia.com/metodo` ([Seo.astro:14](src/components/Seo.astro#L14)) pero el sitemap lista `https://emaxenergia.com/metodo/` y Apache redirige `/metodo` → `/metodo/` (cada página es `carpeta/index.html`). Google puede tratar el canonical como una URL redirigida. **Corrección:** `trailingSlash: 'always'` en `astro.config.mjs` y canonical con barra, o configurar `build.format: 'file'`. Elegir uno y que coincida con el sitemap.

### M-3. Open Graph incompleto
No hay `og:image` ni `twitter:image` (`twitter:card` es `summary_large_image` sin imagen). Tampoco hay datos estructurados de `Organization`/`LocalBusiness` (solo `Article` en guías, [Guia.astro:476](src/layouts/Guia.astro#L476)). **Corrección:** imagen social 1200×630 por defecto + JSON-LD de organización.

### M-4. Formularios GET con datos personales en la URL
[HablaConNosotros.astro:72](src/components/contacto/HablaConNosotros.astro#L72) (`action="/contacto/recibido" method="GET"`) y [canal-directo.astro:210-211](src/pages/canal-directo.astro#L210-L211). Con JS todo bien (hay `preventDefault`), pero sin JS o si falla el script, el envío manda nombre, email, teléfono y mensaje en la query string (historial, logs del servidor, `Referer`). **Corrección:** `method="post"` con el nuevo backend; el `action` de respaldo nunca debe ser GET con datos personales.

### M-5. Protección anti-spam desigual
Solo [canal-directo.astro:309](src/pages/canal-directo.astro) tiene honeypot (`cd-web`). El formulario de contacto, el funnel y el popover no tienen ninguno, ni límite de envío, ni CAPTCHA. Cuando haya backend real, el spam llegará. **Corrección:** honeypot + validación y límite de tasa en servidor (y, si hace falta, Turnstile/hCaptcha, que carga un script de terceros: documentarlo en cookies).

### M-6. Datos personales en `sessionStorage`
[canal-directo.astro:399-416](src/pages/canal-directo.astro#L399-L416) guarda nombre, empresa, email y teléfono en `emax:formulario` para mostrarlos en la confirmación. Está declarado en la política de cookies, pero no es necesario. Se borra al cerrar la pestaña. **Corrección:** mostrar una confirmación genérica y no guardarlos.

### M-7. Modal del formulario sin gestión de foco
[Formulario.astro](src/components/Formulario.astro): `role="dialog" aria-modal="true"`, cierra con Escape, pero **no mueve el foco al abrir, no lo atrapa y no lo devuelve** al cerrar (no hay ninguna llamada a `focus()`). Teclado y lector de pantalla pueden salir al contenido de detrás. **Corrección:** `<dialog>` nativo con `showModal()` (como ya hace el banner de cookies) o focus trap manual + `inert` en el resto.

### M-8. Menú móvil
[Header.astro:576-596](src/components/Header.astro#L576-L596): abre/cierra con el botón, clic fuera y al pulsar un enlace; bloquea el scroll con `body.style.overflow`. Falta: Escape, mover el foco al abrir, atrapar el foco, actualizar `aria-label` ("Abrir menú" no cambia a "Cerrar menú"), y **liberar el bloqueo de scroll si la ventana pasa de >960 px mientras está abierto** (queda `overflow:hidden`).

### M-9. Anchors/ids y desbordes en móvil
A 375 px `scrollWidth` = 375 en las 10 páginas probadas (no hay scroll horizontal). Hay elementos que sobresalen pero están recortados o son decorativos: `.shimmer-luz`, `.compromisos-circulo-*` en la home, y las tablas `.tabla-desplegable` (tarifas) y `.tabla-legal` (cookies) en 375 px, que deberían tener contenedor con `overflow-x:auto` (la de cookies ya tiene `.tabla-scroll`; la de tarifas, por revisar). `ARTICLE.paso-tarjeta` en `/diagnostico` también sobresale. No he revisado visualmente cada página en móvil.

### M-10. Sin tipado comprobable

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): `@astrojs/check` y `typescript` instalados, script `npm run check`; 34 errores → 0. Quedan 9 avisos de variables sin usar.
`@astrojs/check` y `typescript` no están en `package.json`, así que `astro check` no corre (ni en CI). `tsconfig.json` extiende `astro/tsconfigs/strict`. **Corrección:** añadirlos como `devDependencies` y arreglar lo que salga. Hay código con `any` (`let timerCierre: any` en [Header.astro:471](src/components/Header.astro#L471)).

### M-11. Archivos de prueba en producción

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): borrados los 3 archivos de prueba y los 11 `.jpg` de `public/imagenes/` (ninguna referencia en `src/`).
`public/test_metodo_final.jpg`, `public/test_nav_centered.jpg` y `public/video-prueba.mp4` (1,9 MB) se copian a `dist/` y estarán en `https://emaxenergia.com/...`. Además `public/imagenes/*` (14 MB; `maxi.jpg` 2,4 MB, `personal.jpg` 2,5 MB) duplica imágenes de `src/imagenes`. **Corrección:** borrar los tres archivos de prueba; comprobar qué de `public/imagenes` se usa y eliminar el resto.

### M-12. Configuración de desarrollo residual

> ✅ **Resuelto en 0.1.0** ([CHANGELOG](CHANGELOG.md)): bloque `allowedHosts` eliminado.
[astro.config.mjs:10-14](astro.config.mjs#L10-L14): `vite.server.allowedHosts` con `oil-diaper-snort.ngrok-free.dev` y `.ngrok-free.dev`. Solo afecta al servidor de desarrollo (no al build), pero deja un subdominio de ngrok personal en el repositorio y permite cualquier túnel ngrok. **Corrección:** eliminar el bloque (o dejarlo solo bajo `process.env`).

---

## BAJO

- **B-1. `set:html` / `innerHTML`:** todos con datos estáticos del propio código (iconos SVG en [TecnologiaPersonas.astro:65](src/components/home/TecnologiaPersonas.astro#L65), HTML de `fila.contenido` en [CincoDecisiones.astro:275](src/components/metodo/CincoDecisiones.astro#L275), JSON-LD en [Guia.astro:476](src/layouts/Guia.astro#L476), plantillas en [LoQueHayEnTuInforme.astro](src/components/diagnostico/LoQueHayEnTuInforme.astro) y [peaje-3-0td.astro:128-157](src/pages/guias/peaje-3-0td.astro#L128-L157)). **No hay XSS hoy** porque nada viene del usuario ni de la URL; en `peaje-3-0td` la fecha viene de un `<input type=date>`, y `periodo`/`motivo` los calcula el script. Mantener la regla: nunca meter en esos `innerHTML` valores de formularios, query string o `localStorage`. Preferir `textContent`.
- **B-2. `console.log`/`console.error` olvidados:** [Formulario.astro:451,455](src/components/Formulario.astro#L451). Se van con C-1.
- ✅ **B-3 (resuelto en 0.1.0).** **Nombres de archivo de imágenes:** con espacios, paréntesis, `}` y `¿` en `src/imagenes` (`Hero (1).png`, `La persona}.png`, `sectores-indice-t¿hogar.png`, `Revisión de facturas en la oficina.png`). Funcionan en el build pero rompen en algunos FTP/Windows/URL. Hay erratas en nombres: `Supermecado B`, `Naev A`, `logisticca y frio`, `sumistros`.
- **B-4. Atributos duplicados** en el formulario de [canal-directo.astro](src/pages/canal-directo.astro) (`required` y `placeholder` repetidos en algún `<input>` del bloque de nombre/teléfono). HTML inválido aunque compile.
- **B-5. Código muerto en [site.ts](src/data/site.ts):** `headerNav`, `footerNav`, `legalNav` y `phoneCta` no se importan en ningún componente (el Footer define sus propias listas); solo `site` y `primaryCta` se usan. Los textos de contacto están duplicados a mano (`tel:+34933949343`, `hola@emaxenergia.com`) en lugar de usar `site`. Riesgo de que se desincronicen.
- **B-6. Historial git:** sin secretos (se buscó `api_key`, `secret`, `token`, `password`, `AKIA`, `BEGIN … PRIVATE`, `G-XXXX`, `GTM-`, `UA-`, `smtp`, `webhook`, `ngrok`; solo hay coincidencias falsas como clases CSS `tag-auditoria`). `.env` está en `.gitignore` y no hay ningún `.env`, clave o certificado versionado. Sí consta en el historial algún commit con HTML generado (contiene `window.__emaxPreview`), señal de que `dist` se versionó en algún momento; no afecta a la seguridad. El único autor ajeno al usuario actual es "Erika Fernanda Pesca Alfonso" con un email personal en los metadatos: no es un fallo, solo conviene saberlo antes de hacer público el repo.
- **B-7. Rutas:** `canal-directo.astro` + carpeta `canal-directo/enviado.astro` no se pisan (generan `/canal-directo/` y `/canal-directo/enviado/`) pero es una mezcla inconsistente; el resto usa `carpeta/index.astro`. Mover a `canal-directo/index.astro`. `canal-directo.astro` tiene 984 líneas con CSS y JS propios.
- ✅ **B-8 (resuelto en 0.1.0).** **Navegación activa obsoleta:** [Header.astro:215](src/components/Header.astro#L215) comprueba `startsWith("/gas-natural")` y `"/comunidades-de-propietarios"`, que no existen como rutas raíz (están bajo `/tarifas/`).
- **B-9. Duplicación entre sectores y tarifas:** los 5 sectores tienen cada uno `Hero*`, `Cierre*Gasto`, `QueMiramos*` y `LoQueNoHacemos*` propios (los `Hero*` de sectores suman 2.613 líneas). Probablemente comparten estructura y variantes de color; no se ha comparado línea a línea. Plantilla única con datos en `src/data/` reduciría mucho el mantenimiento.
- **B-10. README y CLAUDE.md desactualizados:** [README.md](README.md) es la plantilla "Astro Starter Kit: Minimal" sin información del proyecto; [CLAUDE.md](CLAUDE.md) y [AGENTS.md](AGENTS.md) repiten las instrucciones de Astro y no describen estructura, comandos de build/despliegue a Hostinger, ni convenciones. Faltan los pasos de despliegue (`npm run build` → subir `dist/`).
- **B-11. Metadatos con "v12.16"** y comentarios de "boceto oficial" en cabeceras de archivos: ruido de proceso interno.
- **B-12. Banner de cookies:** se muestra por JS tras cargar (`hidden` → visible), con posible salto de layout; añadir `role="region"`/`aria-live` o renderizarlo visible por defecto.
- **B-13. Sitemap sin `lastmod`** y `robots.txt` correcto (`Allow: /` + `Sitemap: https://emaxenergia.com/sitemap-index.xml`). Ambos apuntan al dominio correcto.
- **B-14. SEO bien resuelto:** `lang="es"`, 1 solo `<h1>` por página en las 39, `description` única, `og:locale=es_ES`, favicon SVG. No he auditado la jerarquía h2/h3.

---

## Propuesta de `.htaccess` (no creada todavía)

Va en `public/.htaccess` para que Astro lo copie a `dist/`. **Probar primero en un subdominio o con HSTS corto.**

```apache
# --- HTTPS y dominio canónico ---
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^ https://emaxenergia.com%{REQUEST_URI} [L,R=301]
RewriteCond %{HTTP_HOST} ^www\. [NC]
RewriteRule ^ https://emaxenergia.com%{REQUEST_URI} [L,R=301]

# --- Páginas de error ---
ErrorDocument 404 /404.html

# --- Cabeceras de seguridad ---
<IfModule mod_headers.c>
  Header always set X-Content-Type-Options "nosniff"
  Header always set Referrer-Policy "strict-origin-when-cross-origin"
  Header always set Permissions-Policy "camera=(), microphone=(), geolocation=(), payment=(), interest-cohort=()"
  Header always set X-Frame-Options "SAMEORIGIN"
  # HSTS: empezar con max-age corto (300) y subir a 31536000 cuando HTTPS esté confirmado en todo el dominio
  Header always set Strict-Transport-Security "max-age=300; includeSubDomains"
  # CSP: el sitio usa scripts y estilos inline de Astro, así que de momento 'unsafe-inline'.
  # Si se alojan las fuentes en local (A-1) no hacen falta fonts.googleapis.com / fonts.gstatic.com.
  Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'"
</IfModule>

# --- Caché ---
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/html "access plus 0 seconds"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/avif "access plus 1 year"
</IfModule>
<IfModule mod_headers.c>
  # /_astro/ lleva hash en el nombre: inmutable
  <If "%{REQUEST_URI} =~ m#^/_astro/#">
    Header set Cache-Control "public, max-age=31536000, immutable"
  </If>
</IfModule>

# --- Compresión ---
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css application/javascript image/svg+xml application/xml
</IfModule>
```
Notas: si el formulario acaba enviando a un servicio externo, hay que añadir su dominio a `connect-src` y `form-action`. Si se carga GA4, añadir `googletagmanager.com` y `google-analytics.com` a `script-src`/`connect-src`. Hay que crear `src/pages/404.astro` (A-4). LiteSpeed lee `.htaccess`, pero hay que comprobar con `curl -I` qué cabeceras salen de verdad.

---

## Hallazgos nuevos durante la fase 1

- **N-1 (Bajo). Imágenes sin uso y duplicadas en `src/imagenes/`.** `home/oficinas-y-comercios.jpg` y `home/varios.jpg` no se importan; `home/maxi.jpg` se importa (`maxiImg`) pero no se usa. `personal.jpg` y `maxi.jpg` están en la raíz y en `home/`, y `sectores-indice-industria` existe tres veces con el mismo contenido. Corrección: borrar lo que no se usa y dejar una sola copia.
- **N-2 (Medio). Variable CSS `--font-cuerpo` sin definir.** Se usa 49 veces y no la define ningún archivo, así que siempre cae en el valor por defecto de cada sitio (Sora en unos, Poppins en otros, Inter en uno). Corrección: definirla en `global.css` y decidir qué familia es la del cuerpo.
- **N-3 (Bajo). Sora 400 se pedía pero nunca se cargó.** El navegador lo servía con el peso 500. Se mantuvo igual para no cambiar el aspecto; si se quiere el 400 real hay que añadirlo a `astro.config.mjs`.
- **N-4 (Medio). El build necesita red para las fuentes.** La API de fuentes baja las fuentes de fontsource en el primer `npm run build` (después las cachea en `node_modules/.astro/fonts`). Un CI o una máquina nueva sin red no podría construir el sitio. Corrección: documentado en el README; si se quiere evitar, usar archivos `.woff2` versionados con el proveedor `local`.
- **N-5 (Bajo). Comentarios con archivos inexistentes.** `HeroMetodo.astro` cita `fondo_metodo.jpg`, que no existe en el proyecto.
- **N-6 (Informativo). La CSP `form-action 'self'` y `connect-src 'self'`** bloquearán un servicio externo de formularios (C-1) hasta que se añada su dominio al `.htaccess`.
- **N-7 (Informativo). `/metodo` sin barra da 404 en desarrollo** con `trailingSlash: 'always'`; en Hostinger debería redirigir Apache. Comprobar tras publicar.
- **N-8 (Bajo). 9 avisos de `astro check`** por variables o parámetros sin usar (`primaryCta` en `Header.astro`, `TituloAcento` en 4 componentes de guías, `maxiImg`, `site` en `HeroMetodo.astro`, `idx`, `currentIndex`).

---

## Plan de corrección por prioridad

Los puntos marcados con ✅ se resolvieron en la fase 1 (versión 0.1.0).

**Bloqueantes: no publicar sin esto**
1. **C-1** Elegir backend y enviar los formularios de verdad; no mostrar "recibido" si falla. Unificar los 4 formularios.
2. **C-3 / C-4** La empresa/asesoría cierra los datos legales pendientes; después se quitan los marcadores y se añade información por capas en cada formulario.
3. ✅ **C-2** Arreglar los CTA "Analizar mi factura" (`data-abrir-funnel`).
4. ✅ **A-1** Alojar las fuentes en local.

**Antes del lanzamiento**
5. ✅ **A-3** Arreglar los 15 enlaces rotos y los anchors.
6. ✅ **A-2** Optimizar imágenes (`astro:assets` + compresión de los originales).
7. ✅ **A-4 / A-5** `.htaccess`, `404.astro`, `noindex` y exclusión del sitemap en las páginas de confirmación.
8. **A-6** Igualar el peso de aceptar/rechazar; alinear políticas y código sobre GA4.
9. ✅ **M-1, M-2** (resueltos) y **M-3** (pendiente): Títulos únicos, canonical/trailing slash, imagen OG y JSON-LD.
10. ✅ **M-11, M-12** Borrar archivos de prueba y la config de ngrok.
11. ✅ **A-7** `npm audit fix` / astro 7.4.5, y rehacer el build.

**Después (calidad y accesibilidad)**
12. **A-8, M-7, M-8** Rehacer el control de desplegables, el modal y el menú móvil con teclado/foco correctos.
13. **M-4, M-5, M-6** POST en formularios, honeypot en todos, no guardar datos personales en `sessionStorage`.
14. ✅ **M-10** Instalar `@astrojs/check` y arreglar los tipos.
15. **B-1…B-14** Limpieza: código muerto en `site.ts`, nombres de imágenes, README/CLAUDE.md, y unificar los componentes de sectores.

**Pendiente de verificar tras publicar:** `curl -I` de cabeceras reales, redirección HTTP→HTTPS y www→apex, 404 real, y una pasada de contraste con herramienta (axe/Lighthouse) y de lector de pantalla.

---

*Fase 1 aplicada en `fix/auditoria-fase-1`. Siguiente tanda: C-1, C-3, C-4, A-6 y A-8 (ver "Pendiente" en el CHANGELOG).*
