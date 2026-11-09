# Changelog

Este archivo registra los cambios del proyecto desde la tanda de correcciones de la auditoría (fase 1). Todo lo anterior a esa fecha solo consta en el historial de git.

El formato sigue [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/). Los códigos entre paréntesis (C-2, A-3, M-10…) son los hallazgos de [AUDITORIA.md](AUDITORIA.md).

## [0.3.2] - 2026-11-09 — Datos legales y revisión de textos

Cambios realizados por Luciano Lyall (lucianolyall@gmail.com) directamente en `main`.

- **Punto 1, extracción:** respaldo de los datos y citas de `https://emaxenergia.com/`, `https://emaxenergia.com/#/aviso-legal` y `https://emaxenergia.com/#/privacidad`, consultadas con JavaScript el 2026-11-09. Se documentan datos ausentes, contradicciones y ruta de cookies sin contenido. Archivo: `docs/legal-fuente-emaxenergia-com.md`.
- **Punto 2, incorporación:** domicilio social de Calle Gerona 38 Local (08187 Santa Eulalia de Ronçana), contacto legal y de derechos `administracion@consultoriaemax.com`, responsable ENERGYMAX, conservación de clientes potenciales hasta tres años y de clientes según contrato, propiedad intelectual general y jurisdicción de consumidores/personas jurídicas, desde aviso legal y privacidad de la web actual. Se elimina «marca de Grupo EMAX», no respaldado. El teléfono 933 949 343 y `hola@` se confirman en el footer con Chromium headless. Se añade el contacto de derechos a los cinco formularios, sin dar por validada su base jurídica. Archivos: `src/data/site.ts`, `src/components/Seo.astro`, `src/components/Footer.astro`, `src/pages/aviso-legal.astro`, `src/pages/privacidad.astro`, `src/pages/atencion-al-cliente.astro`, `src/pages/canal-directo/index.astro`, `src/components/Formulario.astro`, `src/components/FormularioGas.astro`, `src/components/Header.astro`, `src/components/contacto/HablaConNosotros.astro` y `docs/legal-fuente-emaxenergia-com.md`.

- **Punto 3, encargados:** se identifican Hostinger (alojamiento), Google Workspace (recepción/gestión de correos) y Resend (notificaciones de formularios), por confirmación expresa del usuario, no por la web actual. CRM y garantías de transferencias internacionales quedan en comentarios no renderizados, sin inventar garantías. Archivo: `src/pages/privacidad.astro`.

- **Punto 4, cookies:** la tabla enumera únicamente `emax:consentimiento` (`localStorage`, campos `analitica`, `caduca`, `version`, validez 365 días) y `emax:formulario` (`sessionStorage`, campos según Contacto/Canal Directo, hasta cerrar la pestaña, borrar datos o sustituir con otro envío). Se retiran GA4 y las claves inexistentes `emax:funnel`, `emax:presolicitud:[referencia]` y `emax:origen`, y la afirmación falsa de borrado al mostrar la confirmación. Privacidad y el texto del banner explican que no se carga analítica. **No se cambia el script, el diseño ni el funcionamiento del banner.** Archivos: `src/pages/cookies.astro`, `src/pages/privacidad.astro`, `src/components/CookieBanner.astro`.

- **Punto 5, pendientes sin publicación:** se sustituyen todos los marcadores internos por comentarios Astro o de frontmatter que no se renderizan, incluidas guías y tarjetas comerciales. Se eliminan los avisos visibles de borrador, filas sin datos y frases incompletas. Quedan por confirmar inscripción registral, titular de marca/Maxi, papel de EMAX, DPD, CRM, responsabilidades/cesiones, SIPS, garantías de transferencias, Canal Directo, vías/plazos de reclamación y entidad de resolución alternativa. También quedan las validaciones fiscales y normativas señaladas en las guías y los componentes del producto indexado. La tabla de cambios de potencia y la sección de uso del CUPS por EMAX se conservan comentadas al carecer de validación; los anchors permanecen. Archivos: `src/pages/aviso-legal.astro`, `src/pages/privacidad.astro`, `src/pages/cookies.astro`, `src/pages/atencion-al-cliente.astro`, `src/pages/canal-directo/index.astro`, `src/pages/guias/{cups-y-tus-datos,factura-luz-empresa,peaje-3-0td,potencia-contratada,precio-fijo-o-indexado}.astro`, `src/components/tarifas/comunidades/ComoLoHacemosComunidades.astro`.

## [0.3.1] - 2026-11-09 — Cierre de formularios y preparación del lanzamiento

Cambios realizados por Luciano Lyall (lucianolyall@gmail.com) directamente en `main`.

### Agregado

- **Bloque de lanzamiento en `public/.htaccess`** (comentado, desactivado): redirección 301 de `emaxenergia.es` (con y sin `www`, http y https) a `https://emaxenergia.com` conservando ruta y query. Lleva instrucciones de activación y comprobación, y explica que con el bloque activo la regla de `noindex` del `.es` deja de importar porque todo redirige. Hoy el comportamiento no cambia: el `.es` sirve el sitio con `noindex`.
- **Sección "Lanzamiento en .com"** en [README.md](README.md) con el checklist a-f (subida de `dist/`, configuración del .com, verificación de indexación, redirección del .es, Search Console y envíos reales).
- **Regla de dominios** en [CLAUDE.md](CLAUDE.md) y [AGENTS.md](AGENTS.md): emaxenergia.com es el canónico e indexable; el .es solo es revisión con noindex y, tras el lanzamiento, redirige con 301.

### Cambiado

- **Trampa de tiempo sin depender del reloj** (N-9): el campo `t` (timestamp absoluto del cliente) se sustituye por `te`, los milisegundos transcurridos desde que el formulario se mostró, medidos con `performance.now()`. Un dispositivo con el reloj mal puesto ya no se trata como bot. El helper expone `marcarInicio(tipo)` y añade `te` solo; el inicio se marca al abrir el modal (funnels de luz y gas, en cada apertura), al abrir el popover "Te llamamos" y al cargar la página (contacto y Canal Directo). El PHP solo considera bot si `te` falta, no es un entero o es menor de 3000, sin comparar con la hora del servidor. Archivos: [src/scripts/enviarFormulario.ts](src/scripts/enviarFormulario.ts), [public/api/enviar-formulario.php](public/api/enviar-formulario.php), [src/components/Formulario.astro](src/components/Formulario.astro), [src/components/FormularioGas.astro](src/components/FormularioGas.astro), [src/components/Header.astro](src/components/Header.astro), [src/components/contacto/HablaConNosotros.astro](src/components/contacto/HablaConNosotros.astro) y [src/pages/canal-directo/index.astro](src/pages/canal-directo/index.astro). `te` no está en la lista blanca de campos del correo: es un campo de control que el PHP consume aparte, igual que `web`.
- **Los `mailto:` ya no llevan `?cc=`** (N-12): pie ([src/components/Footer.astro](src/components/Footer.astro)), contacto ([src/components/contacto/HablaConNosotros.astro](src/components/contacto/HablaConNosotros.astro)), atención al cliente ([src/pages/atencion-al-cliente.astro](src/pages/atencion-al-cliente.astro), que pasa a `?subject=` sin copia) y Canal Directo ([src/pages/canal-directo/index.astro](src/pages/canal-directo/index.astro), que enlaza a `hola@` en lugar de a `gerencia@` y `hola@` con copia). Así `gerencia@` ya no aparece en ninguna parte del HTML y `administracion@` solo donde una página la presenta como contacto del área.
- **Documentación**: README y ejemplo de configuración pasan de `t` a `te`.
- Las cuatro tarjetas de "Lo que miramos nosotros" de la home pasan de `<article tabindex="0">` a enlaces `<a href="/metodo/">`, el mismo destino que el botón "Ver el método completo". Sin cambios visuales (texto blanco, sin subrayado). Archivo: [src/components/home/Variables.astro](src/components/home/Variables.astro).

### Eliminado

- `site.correosFormularios`, `emailGerencia` y `emailAdministracion` de [src/data/site.ts](src/data/site.ts): duplicaban el `routing` del PHP (los dos últimos no se usaban). Los destinatarios de los formularios viven solo en `emax-forms-config.php`. Usos que había: Canal Directo (el `mailto:` mostraba `gerencia@` y `hola@` con copia) y atención al cliente (la copia del `mailto:`); ambos reemplazados como se indica arriba. `emailSoporte` se mantiene, con un comentario, porque atención al cliente la muestra como contacto del área.
- El campo `t` del payload y su comprobación en el PHP.

### Seguridad

- Ya no hay direcciones internas en enlaces de copia ni en el JS: `gerencia@` no aparece en ningún archivo de `dist/`.
- Un dispositivo con el reloj mal puesto ya no pierde envíos por la trampa de tiempo.

### Decisiones tomadas

- **Direcciones que se dejan a decisión de Luciano** (aparecen en el HTML porque una página las presenta como contacto de un área): `administracion@emaxenergia.com` en [src/pages/atencion-al-cliente.astro](src/pages/atencion-al-cliente.astro) (líneas 33, 51, 203 y 252, a través de `site.emailSoporte`, definido en [src/data/site.ts](src/data/site.ts) línea 12) y en [src/components/contacto/HablaConNosotros.astro](src/components/contacto/HablaConNosotros.astro) línea 43.
- **`noindex` solo en el .es**: se comprobó con un Apache local simulando hosts. `X-Robots-Tag` solo sale con host `emaxenergia.es` (con o sin `www`, con o sin puerto) y nunca con `emaxenergia.com` ni con hosts parecidos. En `dist/` solo llevan meta robots `noindex` la 404, `/contacto/recibido/` y `/canal-directo/enviado/`; `robots.txt` permite todo y apunta a `https://emaxenergia.com/sitemap-index.xml`, y canonical y sitemap son del .com.
- **El bloque de redirección va antes de la regla genérica de HTTPS** para que `http://emaxenergia.es/x/` no dé dos saltos; se comprobó con Apache local que, activado, el .es y `www.` redirigen con 301 al .com con la misma ruta y query.
- **La trampa de tiempo se marca por tipo de formulario** (`marcarInicio(tipo)`) y, si nunca se marcó, `te` es 0 y el servidor lo trata como bot.

### Notas de despliegue

- El bloque de redirección del .es **no se activa hasta el lanzamiento** (checklist del README). El .es necesita SSL válido, también para `www`.
- Tras el cambio de `t` a `te`, hay que subir juntos el PHP y el `dist/`: un PHP nuevo con un JS viejo (que manda `t`) trataría todos los envíos como bot sin enviar nada.

### Pendiente (fuera de esta tanda)

- Las mismas pendientes de 0.3.0: clave de Resend, verificación del dominio remitente, prueba real de envío en Hostinger, unificación de `Formulario` y `FormularioGas`, y textos legales.
- Decidir si `administracion@` se queda visible en atención al cliente y en contacto.

## [0.3.0] - 2026-11-09 — Formularios y despliegue

Cambios realizados por Luciano Lyall (lucianolyall@gmail.com) directamente en `main`.

### Agregado

- **Configuración externa del endpoint** (C-1): [config/emax-forms-config.example.php](config/emax-forms-config.example.php), con todas las claves comentadas y sin secretos (`transport`, `resend_api_key`, `from`, `smtp_*`, `allowed_origins`, `routing`, `rate_limit`, `storage_dir`, `ip_salt`). El PHP la lee de `dirname(__DIR__, 2) . '/emax-forms-config.php'`, fuera de `public_html`. Si falta o está incompleta responde 503 `{"ok":false,"error":"configuracion"}` y no envía.
- **Helper compartido de envío** (C-1): [src/scripts/enviarFormulario.ts](src/scripts/enviarFormulario.ts). Hace el `fetch`, interpreta la respuesta (éxito solo con 2xx y `ok === true`), bloquea el botón mientras envía y muestra el error accesible. Lo usan los cinco formularios.
- **Aviso de error de envío**: estilos `.form-error-envio` y `[aria-invalid="true"]` en [src/styles/global.css](src/styles/global.css), y atributos `data-contacto-*` en el `<body>` de [src/layouts/BaseLayout.astro](src/layouts/BaseLayout.astro), de donde el helper toma el teléfono y el email de `site.ts` (así no acaban dentro del JS).
- **`public/api/.htaccess`**: solo `enviar-formulario.php` es accesible, sin listado de directorio.
- **Cabecera `X-Robots-Tag: noindex, nofollow`** cuando el host es `emaxenergia.es` ([public/.htaccess](public/.htaccess)).

### Cambiado

- **Endpoint** [public/api/enviar-formulario.php](public/api/enviar-formulario.php) reescrito (C-1, M-5):
  - Transporte por función, elegido con `transport`: **Resend** (curl a `https://api.resend.com/emails`, `Authorization: Bearer`, timeout 10 s, éxito solo con 2xx e `id`; el log solo lleva el código HTTP y el mensaje de error de Resend) o **SMTP** (la implementación anterior, ahora con el cuerpo en `quoted-printable`).
  - Destinatarios solo de `routing` (soporte/contacto: `administracion@` con copia a `gerencia@` y `hola@`; leads de luz, gas, llamada y Canal Directo: `gerencia@` y `hola@` con copia a `administracion@`). Ningún email interno queda en el PHP. El navegador ya no manda `para` ni `cc`.
  - Origen: 403 si `Origin` (o el host del `Referer`) no está en `allowed_origins`.
  - Solo se aceptan los tipos de `routing` (400 si no) y, por tipo, solo los campos de una lista blanca; el resto se descarta. Si faltan obligatorios o hay email o teléfono inválidos: 422 `{"ok":false,"error":"validacion","campos":[…]}`. Ver "Decisiones tomadas".
  - Honeypot `web` y trampa de tiempo `t` (envío en menos de 3 s, `t` ausente o inválido): 200 `{"ok":true}` sin enviar.
  - Límite por IP: un archivo en `storage_dir` por `sha256(ip + ip_salt)` (nunca la IP en claro), 429 al superarlo y limpieza de archivos vencidos.
  - Respuestas: 200, 502 `envio` si falla el transporte, 405, 400, 403, 413, 422, 429 y 503. Nunca devuelve destinatarios, configuración ni detalles internos.
  - Compatible con PHP 8.0 y superior.
- **Los cinco formularios** envían por el helper y ya no confirman en falso (C-1): [src/components/Formulario.astro](src/components/Formulario.astro) (`lead-luz`), [src/components/FormularioGas.astro](src/components/FormularioGas.astro) (`lead-gas`), [src/components/contacto/HablaConNosotros.astro](src/components/contacto/HablaConNosotros.astro) (`soporte-contacto`), el popover "Te llamamos" de [src/components/Header.astro](src/components/Header.astro) (`lead-llamada`) y [src/pages/canal-directo/index.astro](src/pages/canal-directo/index.astro) (`lead-canal-directo`, que pasa de script inline a script empaquetado en TypeScript). Se quitan los `catch` que daban el envío por bueno.
  - Si falla el envío no se avanza al paso "Listo" ni se redirige a `/contacto/recibido/` o `/canal-directo/enviado/`; se muestra el error junto al botón con teléfono y email, y se conserva lo escrito.
  - 422 marca los campos con `aria-invalid` y enfoca el primero; 429 muestra "Demasiados intentos. Prueba de nuevo en unos minutos."
  - Cada formulario manda `t`: el momento de abrir el modal (funnels) o de renderizar la página (contacto, popover y Canal Directo).
  - El input del popover pasa de `name="telefono_llamame"` a `name="telefono"`.
  - Contacto y Canal Directo solo guardan datos en `sessionStorage` si el envío fue bien (M-6, parcial).
- **`.htaccess`** (A-4): HTTP → HTTPS y `www` → sin `www` conservan el host de la petición (`%{HTTP_HOST}`) en lugar de un dominio fijo, así que valen para `emaxenergia.es` y `emaxenergia.com`. La CSP no cambia: el envío a Resend lo hace el servidor, y `connect-src 'self'` alcanza.
- **`.gitattributes`**: LF también para `*.php` y `public/api/.htaccess`.
- **`og:image`** (M-3): [src/components/Seo.astro](src/components/Seo.astro) añade `og:image:width`, `og:image:height` (los reales de la imagen, 2048 × 1152) y `og:image:alt`.
- **Versión** del proyecto: 0.1.0 → 0.3.0 en [package.json](package.json) (la 0.2.0 de Erika no la había subido).

### Corregido

- **Envíos perdidos sin aviso** (C-1): antes el endpoint devolvía `ok:true` aunque ni SMTP ni `mail()` hubieran enviado, y los formularios ignoraban el resultado o daban por bueno cualquier fallo de red. Ahora un envío fallido se ve.
- **Redirecciones fijas a `emaxenergia.com`** en `.htaccess`: habrían impedido revisar el sitio en el `.es`.

### Eliminado

- **Respaldo con `mail()`** de PHP: si el transporte configurado falla, el envío falló.
- **Lectura de credenciales fuera del archivo de configuración** (variables de entorno, valores en el propio PHP) y el comentario que sugería escribir la contraseña en el PHP (seguridad).
- **`$_POST`** como alternativa al JSON: el endpoint solo acepta JSON.
- **CSS muerto** `.ck__b--rechazar-unused` en [src/components/CookieBanner.astro](src/components/CookieBanner.astro).
- `para`, `cc` y `fecha` de los payloads del navegador, y los `catch` que ignoraban el error.

### Seguridad

- Las credenciales (clave de Resend, contraseña SMTP) y los destinatarios viven solo en `emax-forms-config.php`, fuera de `public_html` y del repositorio. En `dist/_astro/*.js` y en el PHP no queda ninguna dirección `@emaxenergia.com`.
- Origen permitido, tipos y campos en lista blanca, honeypot, trampa de tiempo y límite por IP (hash con sal) frenan el spam y el abuso del endpoint. El correo solo contiene campos de la lista con etiquetas fijas, y los saltos de línea se limpian en asunto y cabeceras.
- `public/api/` solo expone el endpoint.

### Decisiones tomadas

- **Lista blanca y obligatorios por tipo.** Se definieron revisando qué envía hoy cada formulario (los campos que no están en la lista se descartan):

  | Tipo | Formulario | Campos permitidos | Obligatorios |
  |---|---|---|---|
  | `lead-luz` | funnel de luz | `nombre`, `telefono`, `franja`, `suministros`, `canal`, `tramo_origen` | `nombre`, `telefono` |
  | `lead-gas` | funnel de gas | los de `lead-luz` más `segmento`, `gasto_mensual` | `nombre`, `telefono` |
  | `lead-llamada` | popover "Te llamamos" | `telefono` | `telefono` |
  | `lead-canal-directo` | Canal Directo | `nombre`, `empresa`, `perfil`, `provincia`, `email`, `telefono` | los seis (el formulario ya los exige) |
  | `soporte-contacto` | contacto | `nombre`, `email`, `asunto`, `mensaje`, `telefono` | `nombre`, `email`, `mensaje` (los que ya exige el formulario) |

  Todos admiten además `pagina` (solo si es una ruta del propio sitio), `web` y `t`. Cada campo tiene su longitud máxima. El teléfono debe tener al menos 6 dígitos y solo caracteres de teléfono; el email se valida con `FILTER_VALIDATE_EMAIL`. Se quitó `fecha`: la pone el servidor.
- **Qué cuenta para el límite.** Cuentan los envíos que llegan hasta el transporte y los intentos que saltan el honeypot o la trampa de tiempo; los 422 no consumen. Por defecto, 5 envíos cada 10 minutos por IP (configurable).
- **Orden de comprobaciones:** método, configuración, origen, JSON, tipo, anti-spam, validación, límite, envío.
- **Honeypot y trampa de tiempo responden 200 `ok:true`**, como se pidió, para no dar pistas a los bots. Riesgo conocido: un dispositivo con el reloj mal puesto se trata como bot (AUDITORIA N-9).
- **Se usa `REMOTE_ADDR`, no `X-Forwarded-For`**, porque este último se puede falsificar. Si Hostinger pone un proxy delante hay que revisarlo (N-10).
- **Un solo commit para el PHP, el ejemplo de configuración y la validación**, porque son el mismo archivo.
- **Los `mailto:` de las páginas** (`site.correosFormularios` en `site.ts`) no se tocaron: no estaban en el alcance.
- **Compatibilidad con PHP 8.0**: sin `never` ni `array_is_list`, por si Hostinger no ofrece 8.1.

### Notas de despliegue

- **Crear `emax-forms-config.php` en Hostinger, fuera de `public_html`.** Copiar `config/emax-forms-config.example.php` a la carpeta que está **dos niveles por encima de `public_html/api/`** (en Hostinger suele ser `/home/<usuario>/domains/<dominio>/emax-forms-config.php`), con permisos `600`. Rellenar `resend_api_key`, `from`, `ip_salt` (por ejemplo con `openssl rand -hex 32`) y los orígenes permitidos. Hasta entonces el endpoint responde 503.
- **`storage_dir`**: carpeta fuera de `public_html` (en el ejemplo, `emax-forms-storage` junto al archivo de configuración), con permisos `700` y escribible por PHP. El endpoint intenta crearla.
- **`allowed_origins`** debe incluir el dominio donde se sirve el sitio (`https://emaxenergia.es` en la revisión y `https://emaxenergia.com` después). Si no, todos los envíos dan 403.
- **En el `.es` se envía `X-Robots-Tag: noindex, nofollow`.** Cuando el `.es` sea público (o se retire), borrar las dos líneas marcadas en `public/.htaccess`.
- **Remitente verificado en Resend**: el dominio de `from` tiene que estar verificado (SPF y DKIM) antes del primer envío real.
- Comprobar con `curl -I` las cabeceras, que las redirecciones conservan el host y que `/api/` no se lista.

### Pendiente (fuera de esta tanda)

- **API key de Resend** (cuenta del grupo) y su carga en `emax-forms-config.php`.
- **Verificación del dominio remitente** en Resend.
- **Prueba real de envío en Hostinger** (Resend y, si se usa, SMTP; solo se han probado con una clave falsa y sin servidor SMTP).
- **Unificación de `Formulario.astro` y `FormularioGas.astro`** (comparten ya el helper de envío, pero no el marcado ni los estilos).
- **Textos legales** (C-3): marcadores `[PENDIENTE]`, `[ASESORÍA]` y `[LEGAL]`, y nombrar al proveedor de correo (Resend) en la política de privacidad.
- Vigilar el reloj del dispositivo y el límite por IP tras publicar (AUDITORIA N-9 y N-10).

## [0.2.0] - 2026-11-06 — Formulario específico de Gas Natural y enrutamiento de correos / SMTP Hostinger

Cambios realizados por Erika Fernanda Pesca Alfonso (epescaalfonso@gmail.com).

### Agregado

- **Formulario específico para Gas Natural**: [src/components/FormularioGas.astro](src/components/FormularioGas.astro). Conserva la estructura, cabecera, stepper de 4 pasos y columna lateral de [src/components/Formulario.astro](src/components/Formulario.astro), adaptando el Paso 1 ("Tu gasto") al boceto de Gas Natural: selector de segmento `¿Para quién es?` (`Empresa o negocio`, `Comunidad de vecinos`, `Hogar`), pregunta e importes mensuales dinámicos en 4 botones (`Menos de 300 €`, `300 – 1.000 €`, `1.000 – 3.000 €`, `Más de 3.000 €` para empresas/comunidades; tramos domésticos para hogar), enlace `No lo sé` y bloque explicativo `Por qué te lo preguntamos`.
- **Endpoint PHP con soporte SMTP para Hostinger y Google Workspace** (C-1): [public/api/enviar-formulario.php](public/api/enviar-formulario.php). Recibe los envíos de todos los formularios web, valida el campo trampa antispam (`web`) y enruta los correos por SMTP (`smtp.hostinger.com` o `smtp.gmail.com`, puerto 465 SSL / 587 TLS, con respaldo en `mail()`):
  - **Leads comerciales** (`lead-luz`, `lead-gas`, `lead-llamada`, `lead-canal-directo`) → **Para**: `gerencia@emaxenergia.com`, `hola@emaxenergia.com` · **CC**: `administracion@emaxenergia.com`.
  - **Soporte y página de Contacto** (`soporte-contacto`) → **Para**: `administracion@emaxenergia.com` · **CC**: `gerencia@emaxenergia.com`, `hola@emaxenergia.com`.

### Cambiado

- **Configuración centralizada de correos y conexión de formularios** (C-1):
  - [src/data/site.ts](src/data/site.ts): añade `emailGerencia` (`gerencia@emaxenergia.com`), `emailAdministracion` / `emailSoporte` (`administracion@emaxenergia.com`) y el mapa `correosFormularios` (`leads` y `soporteContacto`).
  - [src/components/Formulario.astro](src/components/Formulario.astro) y [src/components/FormularioGas.astro](src/components/FormularioGas.astro): conectan el envío de leads a `/api/enviar-formulario.php` (`gerencia@emaxenergia.com` y `hola@emaxenergia.com`, CC `administracion@emaxenergia.com`).
  - [src/components/Header.astro](src/components/Header.astro): conecta el formulario *"Te llamamos gratis"* a `/api/enviar-formulario.php` con destino a `gerencia@emaxenergia.com` y `hola@emaxenergia.com` (CC `administracion@emaxenergia.com`).
  - [src/components/contacto/HablaConNosotros.astro](src/components/contacto/HablaConNosotros.astro): conecta el formulario *"¿Es otra cosa? Escríbenos"* a `/api/enviar-formulario.php` con destino a `administracion@emaxenergia.com` (CC `gerencia@emaxenergia.com`, `hola@emaxenergia.com`), guarda los datos en `sessionStorage` para `/contacto/recibido/` y actualiza los enlaces `mailto:` de contacto.
  - [src/pages/atencion-al-cliente.astro](src/pages/atencion-al-cliente.astro): actualiza los enlaces de correo de soporte y reclamaciones a `administracion@emaxenergia.com` (con copia a `gerencia@emaxenergia.com` y `hola@emaxenergia.com`).
  - [src/pages/canal-directo/index.astro](src/pages/canal-directo/index.astro): conecta el formulario de Canal Directo a `/api/enviar-formulario.php` (`gerencia@emaxenergia.com` y `hola@emaxenergia.com`, CC `administracion@emaxenergia.com`), corrige el guardado en `sessionStorage` y reemplaza la fuente suelta por `var(--font-poppins)`.
  - [src/components/Footer.astro](src/components/Footer.astro): añade copia (`?cc=gerencia@emaxenergia.com,administracion@emaxenergia.com`) al enlace directo de correo de la barra superior del pie de página.
- **Layout base y página de Gas Natural**:
  - [src/layouts/BaseLayout.astro](src/layouts/BaseLayout.astro): nueva prop opcional `tipoFormulario?: "general" | "gas"` para renderizar `FormularioGas.astro` sin alterar el `Formulario.astro` del resto de páginas.
  - [src/pages/tarifas/gas-natural.astro](src/pages/tarifas/gas-natural.astro): pasa `tipoFormulario="gas"` a `BaseLayout`.
  - [src/components/tarifas/gas/EmpiezaPorLoQuePagas.astro](src/components/tarifas/gas/EmpiezaPorLoQuePagas.astro): añade `data-segmento` a los botones de cada tarjeta desplegable para preseleccionar el segmento al abrir el formulario de gas y elimina el parámetro `idx` no usado.

## [0.1.0] - 2026-11-05 — Auditoría fase 1

Cambios realizados por Luciano Lyall (lucianolyall@gmail.com) en la rama `fix/auditoria-fase-1`.

### Agregado

- **Página 404** (A-4): [src/pages/404.astro](src/pages/404.astro). Usa `BaseLayout` (header, dock y footer), el mismo estilo que las páginas de confirmación, un mensaje corto y enlaces a inicio, tarifas y contacto. Lleva `noindex`. Se genera como `dist/404.html`.
- **Prop `noindex`** (A-5): en [src/components/Seo.astro](src/components/Seo.astro) y [src/layouts/BaseLayout.astro](src/layouts/BaseLayout.astro). Emite `<meta name="robots" content="noindex, nofollow">`. Aplicada a `/contacto/recibido/` ([src/pages/contacto/recibido.astro](src/pages/contacto/recibido.astro)), `/canal-directo/enviado/` ([src/pages/canal-directo/enviado.astro](src/pages/canal-directo/enviado.astro)) y `/404/`.
- **Filtro de sitemap** (A-5): `sitemap({ filter })` en [astro.config.mjs](astro.config.mjs) excluye esas tres páginas. El sitemap pasa de 39 a 37 URLs.
- **`public/.htaccess`** (A-4): HTTPS y redirección `www` → dominio sin `www`, `ErrorDocument 404`, cabeceras de seguridad (CSP, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `X-Frame-Options`, HSTS con `max-age=300`), caché inmutable para `/_astro/`, HTML sin caché y compresión. Ver "Notas de despliegue".
- **`.gitattributes`**: fuerza saltos de línea LF en `public/.htaccess`.
- **Fuentes locales** (A-1): bloque `fonts` en [astro.config.mjs](astro.config.mjs) y componentes `<Font>` en [src/layouts/BaseLayout.astro](src/layouts/BaseLayout.astro). Ver "Decisiones tomadas" para los pesos.
- **Tipado** (M-10): `@astrojs/check` y `typescript` como `devDependencies` en [package.json](package.json) y script `npm run check` (`astro check`).
- **`<Picture>` de `astro:assets`** (A-2): avif y webp con `widths` y `sizes` en las 53 imágenes de contenido y en el logo. Ver "Cambiado".
- **README.md y CHANGELOG.md** (este archivo).

### Cambiado

- **Dependencias** (A-7): `astro` 7.3.6 → 7.4.5 y `@astrojs/sitemap` 3.7.4. `npm audit fix` (sin `--force`) deja 0 vulnerabilidades. Archivos: [package-lock.json](package-lock.json).
- **Versión** del proyecto: 0.0.1 → 0.1.0 en [package.json](package.json).
- **Títulos únicos** (M-1). El sufijo " | EMAX Energía" lo sigue añadiendo [src/components/Seo.astro](src/components/Seo.astro) solo si el título no menciona ya a EMAX, para no duplicarlo en las páginas que lo llevan con otro separador.

  | Página | Título anterior | Título nuevo |
  |---|---|---|
  | [src/pages/index.astro](src/pages/index.astro) | EMAX Energía | Tarifas de luz y gas para empresas a partir de tu consumo \| EMAX Energía |
  | [src/pages/metodo/index.astro](src/pages/metodo/index.astro) | EMAX Energía | Método: tres etapas y cinco decisiones antes de elegir tarifa \| EMAX Energía |
  | [src/pages/diagnostico/index.astro](src/pages/diagnostico/index.astro) | EMAX Energía | Qué recibes: el informe de consumo de tu empresa \| EMAX Energía |

- **`trailingSlash: 'always'`** (M-2) en [astro.config.mjs](astro.config.mjs). El canonical de [src/components/Seo.astro](src/components/Seo.astro) se normaliza siempre con barra final y coincide con las URLs del sitemap. [src/layouts/Guia.astro](src/layouts/Guia.astro) pasa `path` con barra final.
- **Enlaces internos con barra final** (M-2): 374 enlaces normalizados en `href`, `enlace`, `btnHref`, `action`, `path` y `window.location.href` de los `.astro` y `.ts` de `src/` (incluido `src/data/`). No se tocaron enlaces externos, `tel:`, `mailto:` ni archivos con extensión. Resumen por ruta (la lista completa está en el diff del commit `fix(seo): títulos únicos, trailingSlash always…`):

  | Ruta anterior | Ruta nueva | Apariciones |
  |---|---|---|
  | `/guias/potencia-contratada` | `/guias/potencia-contratada/` | 29 (con anchors: `#cuanta`, `#cambiar`) |
  | `/metodo` | `/metodo/` | 27 (con anchors: `#decisiones`, `#potencia`, `#riesgo`, `#condiciones`) |
  | `/guias/factura-luz-empresa` | `/guias/factura-luz-empresa/` | 22 (con anchors: `#tres-partes`, `#impuestos`, `#lecturas`) |
  | `/guias/precio-fijo-o-indexado` | `/guias/precio-fijo-o-indexado/` | 22 |
  | `/diagnostico` | `/diagnostico/` | 21 (con anchors: `#factura-obrador`, `#empezar-con-poco`) |
  | `/guias/peaje-3-0td` | `/guias/peaje-3-0td/` | 21 (con anchors: `#periodos`, `#potencia`) |
  | `/guias/exceso-de-potencia` | `/guias/exceso-de-potencia/` | 18 |
  | `/guias/energia-reactiva` | `/guias/energia-reactiva/` | 17 |
  | `/guias/cups-y-tus-datos` | `/guias/cups-y-tus-datos/` | 13 |
  | `/tarifas` | `/tarifas/` | 12 |
  | `/sectores/restaurantes` | `/sectores/restaurantes/` | 12 (con anchors: `#tu-peaje`) |
  | `/privacidad` | `/privacidad/` | 11 |
  | `/sectores/varios-suministros` | `/sectores/varios-suministros/` | 10 |
  | `/cookies` | `/cookies/` | 9 |
  | `/guias` | `/guias/` | 9 |
  | `/tarifas/gas-natural` | `/tarifas/gas-natural/` | 8 |
  | `/sectores/hoteles` | `/sectores/hoteles/` | 8 |
  | `/guias/omie` | `/guias/omie/` | 8 |
  | `/guias/revision-precio-luz-empresa` | `/guias/revision-precio-luz-empresa/` | 8 |
  | `/tarifas/comunidades-de-propietarios` | `/tarifas/comunidades-de-propietarios/` | 7 |
  | `/sectores/logistica-frio` | `/sectores/logistica-frio/` | 7 |
  | `/sectores/industria` | `/sectores/industria/` | 7 |
  | `/guias/bajar-factura-luz-empresa` | `/guias/bajar-factura-luz-empresa/` | 7 |
  | `/contacto` | `/contacto/` | 7 |
  | `/sectores/supermercados` | `/sectores/supermercados/` | 6 |
  | `/aviso-legal` | `/aviso-legal/` | 6 |
  | `/particulares` | `/particulares/` | 5 |
  | `/sectores` | `/sectores/` | 5 (con anchors: `#no-esta-en-la-lista`) |
  | `/atencion-al-cliente` | `/atencion-al-cliente/` | 4 |
  | `/guias/maximetro` | `/guias/maximetro/` | 4 |
  | `/guias/peaje-6-1td` | `/guias/peaje-6-1td/` | 4 |
  | `/contacto/recibido` | `/contacto/recibido/` | 3 |
  | `/canal-directo` | `/canal-directo/` | 3 |
  | `/guias/glosario` | `/guias/glosario/` | 3 |
  | `/guias/renovacion-contrato-luz-empresa` | `/guias/renovacion-contrato-luz-empresa/` | 3 |
  | `/guias/subida-factura-luz-empresa` | `/guias/subida-factura-luz-empresa/` | 3 |
  | `/canal-directo/enviado` | `/canal-directo/enviado/` | 3 |
  | `/guias/tarifa-indexada-con-techo` | `/guias/tarifa-indexada-con-techo/` | 2 |

- **Botones "Analizar mi factura"** (C-2). Ahora llevan `data-abrir-funnel` y un `href` de respaldo `/contacto/` para cuando no hay JS (antes `/#estudio` o `#estudio`, un id que no existe en ninguna página).

  | Archivo | Cambio |
  |---|---|
  | [src/components/Header.astro](src/components/Header.astro) | CTA de escritorio y CTA del menú móvil: `href="/#estudio"` → `href="/contacto/"` + `data-abrir-funnel` |
  | [src/components/Dock.astro](src/components/Dock.astro) | `href="/#estudio"` → `href="/contacto/"` + `data-abrir-funnel` |
  | [src/components/contacto/HeroContactoRecibido.astro](src/components/contacto/HeroContactoRecibido.astro) | `href="/#estudio"` → `href="/contacto/"` (ya tenía `data-abrir-funnel`) |
  | [src/components/contacto/HeroContacto.astro](src/components/contacto/HeroContacto.astro) | `href="#estudio"` → `href="/contacto/"` (ya tenía `data-abrir-funnel`) |
  | [src/components/tarifas/CompararOfertas.astro](src/components/tarifas/CompararOfertas.astro) | `href="#estudio"` → `href="/contacto/"` (ya tenía `data-abrir-funnel`) |
  | [src/components/tarifas/gas/SegunQuienPaga.astro](src/components/tarifas/gas/SegunQuienPaga.astro) | `btnHref: "#estudio"` → `"/contacto/"` y nuevo campo `abreFunnel: true` en los datos, en lugar de comparar con `"#estudio"` |
  | [src/data/site.ts](src/data/site.ts) | `primaryCta.href`: `/#estudio` → `/contacto/` |

- **CTA de las guías** (A-3). El campo `cta.enlace` de las 13 guías que apuntaba a `/calcular-ahorro` pasa al valor especial `"#abrir-funnel"`. [src/layouts/Guia.astro](src/layouts/Guia.astro) lo resuelve en un único sitio: `href="/contacto/"` y `data-abrir-funnel`. Así no se duplica lógica en cada guía. Guías: cups-y-tus-datos, energia-reactiva, exceso-de-potencia, glosario, maximetro, omie, peaje-3-0td, peaje-6-1td, precio-fijo-o-indexado, renovacion-contrato-luz-empresa, revision-precio-luz-empresa, subida-factura-luz-empresa y tarifa-indexada-con-techo (en `src/pages/guias/`).
- **Tipografías** (A-1). Astro nombra las familias con un hash, así que los 843 literales `"Poppins"`, `"Sora"`, `"Inter"` y `"Literata"` de 109 archivos `.astro` y `.css` pasan a `var(--font-poppins)`, `var(--font-sora)`, `var(--font-inter)` y `var(--font-literata)`. Las variables `--font-*` de [src/styles/global.css](src/styles/global.css) y `--f-*` de [src/styles/tokens.css](src/styles/tokens.css) apuntan a esas variables.
- **Imágenes** (A-2, B-3).
  - Originales optimizados: `src/imagenes` pasa de 150,0 MB a 14,3 MB. Máximo 2400 px de ancho (solo `fondo-tarifas.jpg` lo superaba, con 2752 px). PNG sin transparencia → JPG calidad 85 (mozjpeg). JPG existentes recomprimidos a calidad 85. Los dos PNG con transparencia (`logo-principal.png` y `home/maxi.png`) siguen en PNG optimizado.
  - Renombrados con `git mv` a kebab-case, también las carpetas (`sectores_hoteles` → `sectores-hoteles`, `sectores indice` → `sectores-indice`, `varios_sumistros` → `varios-suministros`). Erratas corregidas: supermecado, naev, logisticca, sumistros, friorifica, restaurantea. Los 79 imports de `src/` se actualizaron.
  - Los 53 `<img src={x.src}>` de componentes y el logo pasan a `<Picture>` con `formats={['avif', 'webp']}`. Solo las imágenes de hero (LCP) llevan `loading="eager"` y `fetchpriority="high"`; el resto, `loading="lazy"`. En [src/components/contacto/HeroContacto.astro](src/components/contacto/HeroContacto.astro) la primera tarjeta es la prioritaria y la segunda pasa a lazy.
  - [src/components/home/Variables.astro](src/components/home/Variables.astro): los datos pasan de `foto: fotoCurva.src` a la imagen importada.
  - [src/components/Logo.astro](src/components/Logo.astro): `<Picture>` con `densities={[1, 2]}`. El `<picture>` conserva su caja (`.logo picture { display: inline }`); con `display: contents` el pie crecía 7 px menos.
  - [src/styles/global.css](src/styles/global.css): `picture { display: contents }`, para que el `<img>` se maqueta como antes.
  Renombrados (76 archivos). Todos con `git mv`; los convertidos de PNG a JPG cambian además de extensión:

  | Nombre anterior | Nombre nuevo | Antes | Después |
  |---|---|---|---|
  | `src/imagenes/comunidad_a_complejo.jpg` | `src/imagenes/comunidad-a-complejo.jpg` | 967 KB | 231 KB |
  | `src/imagenes/comunidad_b_bloque.jpg` | `src/imagenes/comunidad-b-bloque.jpg` | 1.1 MB | 275 KB |
  | `src/imagenes/consultor-emax.jpg` | `src/imagenes/consultor-emax.jpg` | 703 KB | 118 KB |
  | `src/imagenes/contacto/Agente sonriente en oficina moderna.png` | `src/imagenes/contacto/agente-sonriente-en-oficina-moderna.jpg` | 1.9 MB | 151 KB |
  | `src/imagenes/contacto/Revisión de facturas en la oficina.png` | `src/imagenes/contacto/revision-de-facturas-en-la-oficina.jpg` | 1.9 MB | 129 KB |
  | `src/imagenes/diagnostico/Hero (1).png` | `src/imagenes/diagnostico/hero-1.jpg` | 1.9 MB | 191 KB |
  | `src/imagenes/fondo_tarifas.jpg` | `src/imagenes/fondo-tarifas.jpg` | 1.2 MB | 26 KB |
  | `src/imagenes/home/Banner-home.png` | `src/imagenes/home/banner-home.jpg` | 4.0 MB | 193 KB |
  | `src/imagenes/home/clinicas.png` | `src/imagenes/home/clinicas.jpg` | 1.7 MB | 121 KB |
  | `src/imagenes/home/hoteles.png` | `src/imagenes/home/hoteles.jpg` | 1.7 MB | 136 KB |
  | `src/imagenes/home/logisticca y frio.png` | `src/imagenes/home/logistica-y-frio.jpg` | 2.0 MB | 209 KB |
  | `src/imagenes/home/maxi.jpg` | `src/imagenes/home/maxi.jpg` | 2.3 MB | 305 KB |
  | `src/imagenes/home/Maxi.png` | `src/imagenes/home/maxi.png` | 919 KB | 208 KB |
  | `src/imagenes/home/No te prometemos.png` | `src/imagenes/home/no-te-prometemos.jpg` | 4.0 MB | 216 KB |
  | `src/imagenes/home/oficinas y comercios.png` | `src/imagenes/home/oficinas-y-comercios.jpg` | 1.8 MB | 139 KB |
  | `src/imagenes/home/personal.jpg` | `src/imagenes/home/personal.jpg` | 2.4 MB | 273 KB |
  | `src/imagenes/home/restaurantes.png` | `src/imagenes/home/restaurantes.jpg` | 1.9 MB | 178 KB |
  | `src/imagenes/home/sectores-indice-industria.png` | `src/imagenes/home/sectores-indice-industria.jpg` | 2.1 MB | 216 KB |
  | `src/imagenes/home/sectores-indice-restaurante.png` | `src/imagenes/home/sectores-indice-restaurante.jpg` | 2.0 MB | 203 KB |
  | `src/imagenes/home/talleres mecanicos.png` | `src/imagenes/home/talleres-mecanicos.jpg` | 2.1 MB | 190 KB |
  | `src/imagenes/home/tarjeta-curva-negocio.jpg` | `src/imagenes/home/tarjeta-curva-negocio.jpg` | 739 KB | 145 KB |
  | `src/imagenes/home/tarjeta-energia-reactiva.jpg` | `src/imagenes/home/tarjeta-energia-reactiva.jpg` | 763 KB | 144 KB |
  | `src/imagenes/home/tarjeta-propuesta-medida.jpg` | `src/imagenes/home/tarjeta-propuesta-medida.jpg` | 763 KB | 148 KB |
  | `src/imagenes/home/tarjeta-tecnico-potencia.jpg` | `src/imagenes/home/tarjeta-tecnico-potencia.jpg` | 772 KB | 151 KB |
  | `src/imagenes/home/varios.png` | `src/imagenes/home/varios.jpg` | 1.9 MB | 155 KB |
  | `src/imagenes/logo-principal.png` | `src/imagenes/logo-principal.png` | 68 KB | 23 KB |
  | `src/imagenes/maxi.jpg` | `src/imagenes/maxi.jpg` | 2.3 MB | 305 KB |
  | `src/imagenes/metodo/Hero.png` | `src/imagenes/metodo/hero.jpg` | 1.5 MB | 130 KB |
  | `src/imagenes/metodo/La persona}.png` | `src/imagenes/metodo/la-persona.jpg` | 1.9 MB | 158 KB |
  | `src/imagenes/metodo/lo que maxi no hace.png` | `src/imagenes/metodo/lo-que-maxi-no-hace.jpg` | 1.9 MB | 167 KB |
  | `src/imagenes/metodo/Maxi (1).png` | `src/imagenes/metodo/maxi-1.jpg` | 1.9 MB | 176 KB |
  | `src/imagenes/personal.jpg` | `src/imagenes/personal.jpg` | 2.4 MB | 273 KB |
  | `src/imagenes/sectores/sectores indice/clinicas (1).png` | `src/imagenes/sectores/sectores-indice/clinicas-1.jpg` | 1.7 MB | 121 KB |
  | `src/imagenes/sectores/sectores indice/Hero (4).png` | `src/imagenes/sectores/sectores-indice/hero-4.jpg` | 2.0 MB | 180 KB |
  | `src/imagenes/sectores/sectores indice/oficinas y comercios (1).png` | `src/imagenes/sectores/sectores-indice/oficinas-y-comercios-1.jpg` | 1.8 MB | 139 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-comunidad.png` | `src/imagenes/sectores/sectores-indice/sectores-indice-comunidad.jpg` | 1.8 MB | 152 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-hoteles.png` | `src/imagenes/sectores/sectores-indice/sectores-indice-hoteles.jpg` | 1.7 MB | 136 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-industria (1).png` | `src/imagenes/sectores/sectores-indice/sectores-indice-industria-1.jpg` | 2.1 MB | 216 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-logistica.png` | `src/imagenes/sectores/sectores-indice/sectores-indice-logistica.jpg` | 2.0 MB | 209 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-restaurante (1).png` | `src/imagenes/sectores/sectores-indice/sectores-indice-restaurante-1.jpg` | 2.0 MB | 203 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-t¿hogar.png` | `src/imagenes/sectores/sectores-indice/sectores-indice-hogar.jpg` | 2.1 MB | 161 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice-varios.png` | `src/imagenes/sectores/sectores-indice/sectores-indice-varios.jpg` | 1.9 MB | 155 KB |
  | `src/imagenes/sectores/sectores indice/sectores-indice.png` | `src/imagenes/sectores/sectores-indice/sectores-indice.jpg` | 1.9 MB | 191 KB |
  | `src/imagenes/sectores/sectores indice/sectores-restaurantea.png` | `src/imagenes/sectores/sectores-indice/sectores-restaurante-a.jpg` | 1.9 MB | 178 KB |
  | `src/imagenes/sectores/sectores indice/sectores-restauranteB.png` | `src/imagenes/sectores/sectores-indice/sectores-restaurante-b.jpg` | 2.0 MB | 199 KB |
  | `src/imagenes/sectores/sectores indice/talleres mecanicos (1).png` | `src/imagenes/sectores/sectores-indice/talleres-mecanicos-1.jpg` | 2.1 MB | 190 KB |
  | `src/imagenes/sectores/sectores_hoteles/banner (1).png` | `src/imagenes/sectores/sectores-hoteles/banner-1.jpg` | 1.5 MB | 108 KB |
  | `src/imagenes/sectores/sectores_hoteles/Hotel de costa.png` | `src/imagenes/sectores/sectores-hoteles/hotel-de-costa.jpg` | 2.6 MB | 314 KB |
  | `src/imagenes/sectores/sectores_hoteles/Hotel urbano.png` | `src/imagenes/sectores/sectores-hoteles/hotel-urbano.jpg` | 2.4 MB | 297 KB |
  | `src/imagenes/sectores/sectores_industria/banner 2.png` | `src/imagenes/sectores/sectores-industria/banner-2.jpg` | 4.2 MB | 221 KB |
  | `src/imagenes/sectores/sectores_industria/banner 6,1Td.png` | `src/imagenes/sectores/sectores-industria/banner-6-1td.jpg` | 1.6 MB | 137 KB |
  | `src/imagenes/sectores/sectores_industria/Naev A.png` | `src/imagenes/sectores/sectores-industria/nave-a.jpg` | 2.5 MB | 318 KB |
  | `src/imagenes/sectores/sectores_industria/Nave B.png` | `src/imagenes/sectores/sectores-industria/nave-b.jpg` | 2.1 MB | 241 KB |
  | `src/imagenes/sectores/sectores_industria/sectores-indice-industria (2).png` | `src/imagenes/sectores/sectores-industria/sectores-indice-industria-2.jpg` | 2.1 MB | 216 KB |
  | `src/imagenes/sectores/sectores_logistica/Almacen seco.png` | `src/imagenes/sectores/sectores-logistica/almacen-seco.jpg` | 2.3 MB | 305 KB |
  | `src/imagenes/sectores/sectores_logistica/Banner (3).png` | `src/imagenes/sectores/sectores-logistica/banner-3.jpg` | 4.3 MB | 243 KB |
  | `src/imagenes/sectores/sectores_logistica/Camara friorifica.png` | `src/imagenes/sectores/sectores-logistica/camara-frigorifica.jpg` | 2.2 MB | 259 KB |
  | `src/imagenes/sectores/sectores_logistica/hero (7).png` | `src/imagenes/sectores/sectores-logistica/hero-7.jpg` | 2.0 MB | 209 KB |
  | `src/imagenes/sectores/sectores_restaurante/Banner.png` | `src/imagenes/sectores/sectores-restaurante/banner.jpg` | 4.3 MB | 216 KB |
  | `src/imagenes/sectores/sectores_restaurante/Hero (5).png` | `src/imagenes/sectores/sectores-restaurante/hero-5.jpg` | 1.9 MB | 191 KB |
  | `src/imagenes/sectores/sectores_restaurante/Restaurante a.png` | `src/imagenes/sectores/sectores-restaurante/restaurante-a.jpg` | 2.6 MB | 375 KB |
  | `src/imagenes/sectores/sectores_restaurante/Restaurante b.png` | `src/imagenes/sectores/sectores-restaurante/restaurante-b.jpg` | 2.0 MB | 235 KB |
  | `src/imagenes/sectores/sectores_supermercados/banner (2).png` | `src/imagenes/sectores/sectores-supermercados/banner-2.jpg` | 1.5 MB | 114 KB |
  | `src/imagenes/sectores/sectores_supermercados/hero (6).png` | `src/imagenes/sectores/sectores-supermercados/hero-6.jpg` | 2.0 MB | 203 KB |
  | `src/imagenes/sectores/sectores_supermercados/Supermecado B.png` | `src/imagenes/sectores/sectores-supermercados/supermercado-b.jpg` | 2.7 MB | 379 KB |
  | `src/imagenes/sectores/sectores_supermercados/Tienda a.png` | `src/imagenes/sectores/sectores-supermercados/tienda-a.jpg` | 2.7 MB | 361 KB |
  | `src/imagenes/sectores/varios_sumistros/hero (8).png` | `src/imagenes/sectores/varios-suministros/hero-8.jpg` | 1.9 MB | 198 KB |
  | `src/imagenes/tarifas/gas/comunidad.png` | `src/imagenes/tarifas/gas/comunidad.jpg` | 2.4 MB | 238 KB |
  | `src/imagenes/tarifas/gas/empresas.png` | `src/imagenes/tarifas/gas/empresas.jpg` | 2.1 MB | 171 KB |
  | `src/imagenes/tarifas/gas/Hero (3).png` | `src/imagenes/tarifas/gas/hero-3.jpg` | 2.1 MB | 191 KB |
  | `src/imagenes/tarifas/gas/hogares.png` | `src/imagenes/tarifas/gas/hogares.jpg` | 506 KB | 58 KB |
  | `src/imagenes/tarifas/gas/tarifa-gas-loquepagas.png` | `src/imagenes/tarifas/gas/tarifa-gas-loquepagas.jpg` | 1.8 MB | 147 KB |
  | `src/imagenes/tarifas/gas/tarifa-gas-peaje.png` | `src/imagenes/tarifas/gas/tarifa-gas-peaje.jpg` | 1.8 MB | 147 KB |
  | `src/imagenes/tarifas/gas/tarifa-gas-precio.png` | `src/imagenes/tarifas/gas/tarifa-gas-precio.jpg` | 1.7 MB | 123 KB |
  | `src/imagenes/tarifas/gas/tarifa-gas-temporada.png` | `src/imagenes/tarifas/gas/tarifa-gas-temporada.jpg` | 1.8 MB | 129 KB |
  | `src/imagenes/tarifas/Hero (2).png` | `src/imagenes/tarifas/hero-2.jpg` | 1.9 MB | 186 KB |

  - Resultado: `dist/` pasa de 125,6 MB (138 MB en la auditoría) a 47,2 MB, con 784 archivos (incluye tres formatos por tamaño: avif, webp y el original como respaldo). Los navegadores con avif solo descargan el avif.
- **Comentarios** en cabeceras de componentes: nombres de imagen actualizados (`Hero (1).png` → `hero-1.jpg`, etc.).

### Corregido

- **Enlaces rotos** (A-3).

  | Enlace anterior | Enlace nuevo | Archivo |
  |---|---|---|
  | `/calcular-ahorro` | `/contacto/` (+ `data-abrir-funnel`, que ya tenía) | [src/components/guias/CierreGasto.astro](src/components/guias/CierreGasto.astro) |
  | `/calcular-ahorro` | `#abrir-funnel` → `/contacto/` + `data-abrir-funnel` | 13 guías en `src/pages/guias/` y [src/layouts/Guia.astro](src/layouts/Guia.astro) |
  | `/gas-natural` | `/tarifas/gas-natural/` | [src/components/sectores/hoteles/FacturaPrevisible.astro](src/components/sectores/hoteles/FacturaPrevisible.astro) |
  | `/tarifas/luz-empresas` | `/tarifas/` | [src/components/sectores/restaurantes/QueMiramosEnTuLocal.astro](src/components/sectores/restaurantes/QueMiramosEnTuLocal.astro) |

- **Anchors inexistentes** (A-3). En ningún caso hizo falta añadir un id nuevo: ya había una sección que correspondía por contenido.

  | Enlace anterior | Enlace nuevo | Archivo | Decisión |
  |---|---|---|---|
  | `/tarifas#estructura` | `/tarifas/#pieza-1` | [src/components/metodo/CincoDecisiones.astro](src/components/metodo/CincoDecisiones.astro) | `#pieza-1` es la pieza "Estructura" del acordeón de tarifas |
  | `/tarifas#modalidad` | `/tarifas/#pieza-2` | [src/components/metodo/CincoDecisiones.astro](src/components/metodo/CincoDecisiones.astro), [src/components/tarifas/comunidades/QueTarifaComunidad.astro](src/components/tarifas/comunidades/QueTarifaComunidad.astro) (2), [src/pages/guias/omie.astro](src/pages/guias/omie.astro), [src/pages/guias/precio-fijo-o-indexado.astro](src/pages/guias/precio-fijo-o-indexado.astro) | `#pieza-2` es la pieza "Modalidad" (fijo, indexado, Cuota Fija) |
  | `/sectores/supermercados#reactiva` | `/sectores/supermercados/#reactiva-supermercados` | [src/pages/guias/energia-reactiva.astro](src/pages/guias/energia-reactiva.astro) | La sección "La reactiva de tus cámaras" ya tenía ese id |
  | `/sectores/industria#peaje` | `/sectores/industria/#peaje-61td` | [src/pages/guias/peaje-3-0td.astro](src/pages/guias/peaje-3-0td.astro) | La sección del peaje 6.1TD ya tenía ese id |
  | `/sectores/hoteles#potencia-hoy` | `/sectores/hoteles/#por-que-el-verano` | [src/pages/guias/potencia-contratada.astro](src/pages/guias/potencia-contratada.astro) | Enlaza desde "¿Y por temporadas?"; la sección que lo trata es "Por qué el verano…" |
  | `/guias/factura-luz-empresa#supuestos` | `/guias/factura-luz-empresa/` (sin anchor) | [src/components/tarifas/CasoTarifa.astro](src/components/tarifas/CasoTarifa.astro) | **Anchor quitado**: ninguna sección de la guía trata los supuestos |

- **Estado activo de Tarifas** (B-8): [src/components/Header.astro](src/components/Header.astro) mira `/tarifas/gas-natural` y `/tarifas/comunidades-de-propietarios` en lugar de `/gas-natural` y `/comunidades-de-propietarios`, que no existen.
- **Errores de tipos** (M-10): `astro check` pasa de 34 errores a 0. Solo cambian tipos, sin tocar lógica:
  - Casts `as HTMLElement` en `getElementById` de [src/components/diagnostico/LoQueHayEnTuInforme.astro](src/components/diagnostico/LoQueHayEnTuInforme.astro) (`hoja`, `pag`), [src/components/metodo/TresEtapas.astro](src/components/metodo/TresEtapas.astro) (`timeline`, `beam`) y [src/components/tarifas/particulares/ComoFuncionaCasa.astro](src/components/tarifas/particulares/ComoFuncionaCasa.astro) (`widget`, `track`, `fillLine`, `cursor`). Los guards de runtime se mantienen.
  - Tipo de la tabla `filas` en `LoQueHayEnTuInforme.astro`.
  - `let timerCierre: any` → `ReturnType<typeof setTimeout> | undefined` en [src/components/Header.astro](src/components/Header.astro).
  - Tipo de `relacionadasFinal` en [src/layouts/Guia.astro](src/layouts/Guia.astro) y de los arrays `secciones`, `resumen` y `senales` en [src/pages/guias/glosario.astro](src/pages/guias/glosario.astro).

### Eliminado

- **Config de ngrok** (M-12): el bloque `vite.server.allowedHosts` de [astro.config.mjs](astro.config.mjs).
- **Archivos de prueba** (M-11): `public/test_metodo_final.jpg`, `public/test_nav_centered.jpg` y `public/video-prueba.mp4`.
- **Imágenes de `public/imagenes/` sin uso** (M-11): ningún archivo de `src/` apuntaba a `/imagenes/…` (todos los componentes importan desde `src/imagenes`), así que se borraron los 11 `.jpg`: `asesor-negocio-reunion`, `consultor-emax`, `hero-panaderia`, `logo-obrador-santelmo`, `maxi`, `panadero-hero`, `personal`, `tarjeta-curva-negocio`, `tarjeta-energia-reactiva`, `tarjeta-propuesta-medida` y `tarjeta-tecnico-potencia`. En `public/` solo quedan `favicon.ico`, `favicon.svg` y el nuevo `.htaccess`.
- **Google Fonts** (A-1): el `<link>` a `fonts.googleapis.com` y los dos `preconnect` de [src/layouts/BaseLayout.astro](src/layouts/BaseLayout.astro).
- **Imports sin uso**: `Termino` en 14 guías de `src/pages/guias/` y `site` en [src/pages/aviso-legal.astro](src/pages/aviso-legal.astro).

### Seguridad

- `npm audit`: 0 vulnerabilidades (antes 2 de severidad alta: `undici` y `zod`, transitivos de `astro`; solo afectaban a build y desarrollo) (A-7).
- Ya no se carga nada de Google antes del consentimiento: las fuentes se sirven desde el propio dominio (A-1). Es la causa del cambio en la CSP: sin `fonts.googleapis.com` ni `fonts.gstatic.com`.
- Cabeceras de seguridad y CSP en `.htaccess` (A-4). La CSP es `default-src 'self'` con `'unsafe-inline'` en scripts y estilos por lo que genera Astro. Se revisó todo `dist/`: no hay scripts, iframes, imágenes, fuentes ni `fetch` a dominios externos. Los únicos enlaces externos son `wa.me`, `www.cnmc.es` y `www.aepd.es`, que son navegación y no recursos, así que no entran en la CSP.
- Fuera el subdominio de ngrok personal de la configuración (M-12).

### Decisiones tomadas

- **Astro 7.4.5**: `npm audit fix` por sí solo dejaba astro en 7.3.6, así que se subió con `npm update astro` dentro del rango `^7`. No hizo falta `--force`.
- **Fuentes, familias y pesos** (A-1). Se cargan solo los que usa el CSS:

  | Familia | Pesos cargados | Descartado |
  |---|---|---|
  | Inter | 400, 500, 600, 700 normal | — |
  | Poppins | 400, 500, 600, 700, 800 normal e italic | 300 y 900: no se cargaban antes (el navegador usaba 400 y 800) |
  | Sora | 500, 600, 700, 800 normal | **400**: el CSS lo pide, pero antes tampoco se cargaba y el navegador usaba 500. Se mantiene así para que se vea idéntico |
  | Literata | 400, 500, 600, 700 italic | **Normal**: ninguna regla la usa (`em` y las clases de acento son cursiva) |

  - Poppins italic solo lo usa una regla (`.informe-nota-cierre`, peso 400). La API de Astro aplica los mismos pesos a los dos estilos, así que se generan también cursivas 500–800 que nadie descarga. Pesan en `dist/`, no en la red.
  - "IBM Plex Sans", "Baloo 2" e "Instrument Serif" aparecen en el CSS como primera o segunda opción, pero nunca se cargaron: no se añaden.
  - Subsets `latin` y `latin-ext` (ñ, tildes, €).
  - Preload solo de Inter 400 (texto) y Poppins 700 y 800 (titulares), subset latin.
  - `fontProviders.fontsource()` baja las fuentes en el build. Los archivos son estáticos, no variables: es lo que da fontsource para los subsets que se piden.
- **Anchors**: `#supuestos` se quitó (no hay sección que lo trate); el resto se resolvió con ids que ya existían. No se añadió ningún id.
- **Valor especial `#abrir-funnel`** en `cta.enlace` de las guías, en vez de duplicar `data-abrir-funnel` en cada una.
- **`/contacto/` como `href` de respaldo** de los botones que abren el funnel.
- **`.htaccess`**: un solo `RewriteRule` para http → https y quitar `www`, para no encadenar dos redirecciones.
- **Imágenes**: `sectores-indice-hogar.jpg` sustituye a `sectores-indice-t¿hogar.png` (el `¿` parece un error de tecleo). Todos los PNG sin alfa pasaron a JPG calidad 85, incluidos los que podrían tener texto o gráficos (`tarifa-gas-*`); revisados en las capturas, sin diferencias visibles.
- **Imágenes duplicadas**: no se deduplicaron (no estaba en el alcance). Astro las unifica por contenido en el build.
- **`display: contents` en `<picture>`** para todas las imágenes salvo el logo.
- **Tipos**: se dejaron los 9 avisos de "declarado y no usado" que quedan (hints de `astro check`, no errores).

### Notas de despliegue

- **`.htaccess` solo aplica en Hostinger** (Apache/LiteSpeed). Vercel, Netlify y otros lo ignoran. Se publica con el resto de `dist/`.
- **HSTS arranca en `max-age=300`** (5 minutos) a propósito. Cuando se haya confirmado que HTTPS funciona en todo el dominio, hay que subirlo a `31536000` en `public/.htaccess` (el archivo lo explica al principio) y volver a publicar.
- **Verificar las cabeceras reales** con `curl -I https://emaxenergia.com/` después de publicar: CSP, HSTS, `X-Content-Type-Options`, `Cache-Control` de `/_astro/…` y de las páginas HTML, redirección `http` → `https`, `www` → sin `www`, y una URL inexistente (debe devolver `404.html`).
- **`/metodo` sin barra final**: en desarrollo con `trailingSlash: 'always'` da 404. En Hostinger, Apache redirige `/metodo` → `/metodo/` por sí solo; hay que comprobarlo con `curl -I`.
- **El build necesita red** la primera vez, para bajar las fuentes de fontsource. Después quedan en la caché de `node_modules/.astro/fonts`.
- **Al cambiar de servicio de formularios o analítica**, añadir su dominio a `connect-src`, `form-action` y `script-src` de la CSP.

### Pendiente (fuera de esta tanda)

- **C-1**: los formularios no envían datos a ningún sitio y muestran confirmación igualmente.
- **C-3**: marcadores `[PENDIENTE]`, `[ASESORÍA]` y `[LEGAL]` visibles en las páginas legales y en el footer.
- **C-4**: información de protección de datos en el punto de recogida de cada formulario.
- **A-6**: banner de cookies (peso visual de aceptar y rechazar, y desajuste con las políticas sobre Google Analytics).
- **A-8**: lógica de los desplegables del header (estado, teclado, táctil).
- **M-3**: imagen Open Graph y datos estructurados de organización.
- **M-4, M-5, M-6**: formularios GET, anti-spam y datos personales en `sessionStorage`.
- **M-7, M-8**: foco del modal del formulario y del menú móvil.
- **M-9**: tablas en móvil y revisión visual por página.
- **B-1, B-2, B-4, B-5, B-7, B-9 a B-14**: limpieza (código muerto en `site.ts`, atributos duplicados, mover `canal-directo.astro`, unificar componentes de sectores, etc.).
- **Imágenes sin uso en `src/imagenes/`**: `home/oficinas-y-comercios.jpg` y `home/varios.jpg` no se importan en ningún sitio, y `home/maxi.jpg` se importa pero no se usa (`maxiImg` en `TecnologiaPersonas.astro`).
- **Imágenes duplicadas** en `src/imagenes/`: `personal.jpg` y `maxi.jpg` están en la raíz y en `home/`, y `sectores-indice-industria` está tres veces.
- **Los 9 avisos** de `astro check` por variables sin usar.
