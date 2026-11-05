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
├── public/                 favicon.ico, favicon.svg y .htaccess (se copian tal cual a dist/)
├── src/
│   ├── components/         Header, Footer, Dock, Formulario (modal), CookieBanner, Logo, Seo
│   │   └── <sección>/      Un componente por bloque de página: home, metodo, diagnostico,
│   │                       sectores (con una subcarpeta por sector), tarifas, guias, contacto
│   ├── data/               site.ts (datos de la empresa y del pie), guiasData.ts, glosario.ts
│   ├── imagenes/           Imágenes de las páginas (se importan desde los componentes)
│   ├── layouts/            BaseLayout.astro (todas las páginas) y Guia.astro (guías)
│   ├── pages/              Una ruta por archivo; incluye 404.astro y robots.txt.ts
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
- **Rutas nuevas con enlaces nuevos**: tras añadirlas, `npm run build` y revisar que `dist/` no tiene enlaces ni anchors rotos.

## Despliegue en Hostinger

1. `npm run build`.
2. Subir **el contenido** de `dist/` a `public_html/` (no la carpeta `dist` en sí). Incluye `.htaccess`, que es un archivo oculto: hay que asegurarse de que el cliente FTP o el administrador de archivos lo sube.
3. Comprobar con `curl -I https://emaxenergia.com/` las cabeceras (CSP, HSTS…), la redirección a HTTPS y sin `www`, y que una URL inexistente devuelve `404.html`.

`public/.htaccess` solo lo interpreta Apache/LiteSpeed. El HSTS arranca con `max-age=300`; hay que subirlo a `31536000` cuando HTTPS esté confirmado (el archivo lo explica). Si se añade un servicio externo (formularios, analítica…), hay que añadir su dominio a la CSP.

## Más información

- [CHANGELOG.md](CHANGELOG.md): qué cambió en cada tanda, quién lo hizo y en qué archivos.
- [AUDITORIA.md](AUDITORIA.md): hallazgos de la auditoría, su estado y el plan de corrección.
