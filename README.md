# EMAX Energía · emaxenergia.com

Sitio web estático de EMAX Energía (ENERGYMAX ADVISORY, S.L.U.), asesoría de tarifas de luz y gas para empresas. Páginas de método, tarifas, sectores, guías, contacto y textos legales, con un formulario modal ("funnel") para pedir el análisis de la factura.

Hecho con [Astro](https://astro.build) (`output: 'static'`) y publicado en Hostinger (Apache/LiteSpeed), sin Node en el servidor.

## Requisitos

- Node.js 22.12 o superior.
- Red en el primer `npm run build`: Astro descarga las fuentes de fontsource y las guarda en `node_modules/.astro/fonts`.

## Comandos

| Comando | Qué hace |
|---|---|
| `npm install` | Instala las dependencias |
| `npm run dev` | Servidor de desarrollo en `http://localhost:4321` |
| `npm run build` | Genera el sitio en `dist/` |
| `npm run preview` | Sirve `dist/` en local para revisar el resultado del build |
| `npm run check` | Comprueba los tipos con `astro check` (debe dar 0 errores) |

En desarrollo, una URL sin barra final (`/metodo`) da 404 porque `trailingSlash` es `'always'`. En producción, Apache redirige a la versión con barra.

## Estructura

```text
├── config/                 emax-forms-config.example.php (plantilla de la configuración del endpoint)
├── public/                 favicon, .htaccess y api/enviar-formulario.php (se copian tal cual a dist/)
├── src/
│   ├── components/         Header, Footer, Dock, Formulario (modal), CookieBanner, Logo, Seo
│   │   └── <sección>/      Un componente por bloque de página: home, metodo, diagnostico,
│   │                       sectores (con una subcarpeta por sector), tarifas, guias, contacto
│   ├── data/               site.ts (datos de la empresa y del pie), guiasData.ts, glosario.ts
│   ├── imagenes/           Imágenes de las páginas (se importan desde los componentes)
│   ├── layouts/            BaseLayout.astro (todas las páginas) y Guia.astro (guías)
│   ├── pages/              Una ruta por archivo; incluye 404.astro y robots.txt.ts
│   ├── scripts/            enviarFormulario.ts (helper de envío de todos los formularios)
│   └── styles/             global.css, tokens.css, guia.css
├── astro.config.mjs        site, trailingSlash, sitemap, fuentes
├── AUDITORIA.md            Hallazgos de la auditoría y su estado
└── CHANGELOG.md            Registro de cambios
```

## Convenciones

- **Imágenes.** Van en `src/imagenes/` con nombre en kebab-case (minúsculas, sin espacios, tildes ni paréntesis; carpetas igual). Siempre se importan y se muestran con `<Picture>` (o `<Image>`) de `astro:assets`, con `widths`, `sizes` y `formats={['avif', 'webp']}`. Nada de `<img src={imagen.src}>` ni de imágenes en `public/`. Máximo 2400 px de ancho; JPG calidad 85 si no hay transparencia. Solo la imagen principal de cada página lleva `loading="eager"` y `fetchpriority="high"`.
- **Enlaces internos con barra final**: `/metodo/`, `/tarifas/gas-natural/`, `/tarifas/#pieza-2`. Esto incluye los enlaces que vienen de `src/data/` y de props. Los anchors apuntan a ids que existen en la página destino.
- **Botones que abren el formulario**: llevan `data-abrir-funnel` (el modal lo gestiona `Formulario.astro`) y un `href` de respaldo a `/contacto/`.
- **Fuentes locales.** Se declaran en `astro.config.mjs` (Inter, Poppins, Sora y Literata) y se cargan con `<Font>` en `BaseLayout.astro`. En el CSS no se escriben nombres de familia sueltos: se usan `var(--font-inter)`, `var(--font-poppins)`, `var(--font-sora)` y `var(--font-literata)`, o los alias de `global.css` (`--font-texto`, `--font-titulos`, `--font-acento`). No se enlaza ningún recurso de terceros.
- **SEO.** Cada página pasa `title`, `description` y `path` (con barra final) a `BaseLayout`, que los entrega a `Seo.astro`. Los títulos son únicos y no llevan el sufijo "| EMAX Energía" (lo añade `Seo.astro`). Las páginas que no deben indexarse (confirmaciones de envío, 404) pasan la prop `noindex` y se excluyen del sitemap en `astro.config.mjs`.
- **Formularios.** Todo formulario nuevo envía con el helper `src/scripts/enviarFormulario.ts` (no se escribe otro `fetch`), tiene un tipo en `routing` y su lista blanca de campos en el PHP, y lleva el honeypot `web` y el campo `t`. Los destinatarios y las credenciales no se escriben nunca en el código: viven solo en `emax-forms-config.php`.
- **Rutas nuevas con enlaces nuevos**: tras añadirlas, `npm run build` y revisar que `dist/` no tiene enlaces ni anchors rotos.

## Formularios y envío de correo

**Flujo.** El sitio es estático; los cinco formularios (funnel de luz, funnel de gas, contacto, popover "Te llamamos" y Canal Directo) envían por `fetch` un JSON a `public/api/enviar-formulario.php`, que corre en Hostinger (PHP 8.x) y manda el correo con **Resend** (API HTTPS) o por SMTP. Todos usan el mismo helper, `src/scripts/enviarFormulario.ts`: solo confirman si el servidor responde `200` con `{"ok":true}`; cualquier otro caso, incluido un error de red, es un fallo y el usuario ve un aviso con el teléfono y el email de contacto, sin perder lo que escribió. En desarrollo y en hosting sin PHP (por ejemplo Vercel) no hay endpoint: los formularios mostrarán el error.

**Configuración externa.** El PHP lee toda su configuración (credenciales, destinatarios, orígenes permitidos, límite de envíos) de `dirname(__DIR__, 2) . '/emax-forms-config.php'`, es decir, **dos niveles por encima de `public_html/api/`**: fuera de `public_html` y fuera del repositorio. La plantilla, con todas las claves comentadas y sin secretos, es [config/emax-forms-config.example.php](config/emax-forms-config.example.php). Los destinatarios y las credenciales viven solo ahí: ninguna dirección interna se escribe en el PHP ni en el JS.

**Tipos de formulario** (cada uno tiene su lista blanca de campos y sus obligatorios en el PHP; solo se aceptan los que están en `routing`): `lead-luz`, `lead-gas`, `lead-llamada`, `lead-canal-directo` y `soporte-contacto`. Los campos de control son `web` (honeypot, tiene que ir vacío) y `t` (milisegundos del momento en que se abrió o renderizó el formulario; si el envío llega en menos de 3 s se trata como bot).

**Códigos de respuesta** (siempre JSON):

| Código | Cuerpo | Cuándo |
|---|---|---|
| 200 | `{"ok":true}` | Enviado. También cuando salta el honeypot o la trampa de tiempo (no se envía nada) |
| 400 | `{"ok":false,"error":"formato"}` o `"tipo"` | JSON inválido o tipo desconocido |
| 403 | `{"ok":false,"error":"origen"}` | `Origin` (o el host del `Referer`) no está en `allowed_origins` |
| 405 | `{"ok":false,"error":"metodo_no_permitido"}` | No es POST |
| 413 | `{"ok":false,"error":"payload_demasiado_grande"}` | Más de 32 KB |
| 422 | `{"ok":false,"error":"validacion","campos":[…]}` | Falta un obligatorio o un campo no es válido |
| 429 | `{"ok":false,"error":"limite"}` | Demasiados envíos desde esa IP |
| 502 | `{"ok":false,"error":"envio"}` | Falló el transporte de correo |
| 503 | `{"ok":false,"error":"configuracion"}` | Falta `emax-forms-config.php` o un dato obligatorio |

**Probarlo en local** (necesita PHP 8 con `curl` y `mbstring`):

1. `npm run build`.
2. Crear una carpeta temporal con la misma estructura que Hostinger, fuera del repositorio, por ejemplo `prueba/public_html` (copia de `dist/`) y `prueba/emax-forms-config.php` (copia del ejemplo con `transport` = `resend`, una `resend_api_key` falsa, `allowed_origins` = `['http://localhost:8080']`, el `routing` de prueba y `storage_dir` = `__DIR__ . '/storage'`).
3. `php -S localhost:8080 -t prueba/public_html`.
4. Probar con `curl` (`-H "Origin: http://localhost:8080"`, `Content-Type: application/json`): un GET da 405; otro origen, 403; un tipo desconocido, 400; faltan obligatorios, 422; con `t` de hace menos de 3 s, 200 sin enviar; con la clave falsa, 502; y superado el límite, 429.
5. Borrar la carpeta temporal al terminar.

Los errores del transporte (código HTTP y mensaje de Resend, sin datos del formulario) van al `error_log` de PHP.

## Despliegue en Hostinger

1. `npm run build`.
2. Subir **el contenido** de `dist/` a `public_html/` (no la carpeta `dist` en sí). Incluye `.htaccess` y `api/.htaccess`, que son archivos ocultos: hay que asegurarse de que el cliente FTP o el administrador de archivos los sube. El sitio se sirve en `emaxenergia.es` (revisión, con `noindex`) y después en `emaxenergia.com`: el mismo contenido vale para los dos.
3. Crear `emax-forms-config.php` fuera de `public_html` (ver [Formularios y envío de correo](#formularios-y-envío-de-correo)). Sin él los formularios no envían.
4. Comprobar con `curl -I https://emaxenergia.com/` las cabeceras (CSP, HSTS…), la redirección a HTTPS y sin `www`, y que una URL inexistente devuelve `404.html`.

`public/.htaccess` solo lo interpreta Apache/LiteSpeed. El HSTS arranca con `max-age=300`; hay que subirlo a `31536000` cuando HTTPS esté confirmado (el archivo lo explica). Si se añade un servicio externo (formularios, analítica…), hay que añadir su dominio a la CSP.

## Más información

- [CHANGELOG.md](CHANGELOG.md): qué cambió en cada tanda, quién lo hizo y en qué archivos.
- [AUDITORIA.md](AUDITORIA.md): hallazgos de la auditoría, su estado y el plan de corrección.
