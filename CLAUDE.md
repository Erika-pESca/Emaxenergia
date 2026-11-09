## Development

When starting the dev server, use background mode:

```
astro dev --background
```

Manage the background server with `astro dev stop`, `astro dev status`, and `astro dev logs`.

## Documentation

Full documentation: https://docs.astro.build

Consult these guides before working on related tasks:

- [Adding pages, dynamic routes, or middleware](https://docs.astro.build/en/guides/routing/)
- [Working with Astro components](https://docs.astro.build/en/basics/astro-components/)
- [Using React, Vue, Svelte, or other framework components](https://docs.astro.build/en/guides/framework-components/)
- [Adding or managing content](https://docs.astro.build/en/guides/content-collections/)
- [Adding styles or using Tailwind](https://docs.astro.build/en/guides/styling/)
- [Supporting multiple languages](https://docs.astro.build/en/guides/internationalization/)

## Convenciones del proyecto

- Imágenes en `src/imagenes/` con nombre en kebab-case, siempre con `<Picture>`/`<Image>` de `astro:assets` (avif y webp, `widths` y `sizes`); nada en `public/`. Solo la imagen principal de cada página lleva `loading="eager"` y `fetchpriority="high"`.
- Enlaces internos siempre con barra final (`/metodo/`, `/tarifas/#pieza-2`), también en `src/data/` y en props. Los anchors tienen que existir en la página destino.
- Los botones que abren el formulario llevan `data-abrir-funnel` y un `href` de respaldo a `/contacto/`.
- Fuentes locales (declaradas en `astro.config.mjs`): en el CSS se usan `var(--font-inter)`, `var(--font-poppins)`, `var(--font-sora)` y `var(--font-literata)`, nunca el nombre suelto de la familia. No se enlazan recursos de terceros.
- Las páginas que no deben indexarse pasan la prop `noindex` de `Seo.astro` y se excluyen del sitemap en `astro.config.mjs`.
- Antes de dar un cambio por bueno: `npm run check` (0 errores) y `npm run build`.
- Los destinatarios de los formularios y las credenciales (clave de Resend, SMTP) viven solo en `emax-forms-config.php`, fuera de `public_html` y del repositorio; nunca en el PHP, el JS ni `src/`. Todo formulario nuevo envía con el helper `src/scripts/enviarFormulario.ts`.
- **emaxenergia.com es el dominio canónico e indexable; emaxenergia.es solo sirve como revisión con noindex y, desde el lanzamiento, redirige con 301 al .com.**
- **Cada tanda de cambios agrega su entrada en CHANGELOG.md, con autor y archivos afectados.**

Más detalle en [README.md](README.md), [CHANGELOG.md](CHANGELOG.md) y [AUDITORIA.md](AUDITORIA.md).
