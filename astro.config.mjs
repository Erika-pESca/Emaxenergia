// @ts-check
// Sitio estático. El build genera HTML para publicarlo sin Node.
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

// Páginas que no deben aparecer en el sitemap (llevan noindex).
const SIN_SITEMAP = ['/contacto/recibido/', '/canal-directo/enviado/', '/404/'];

export default defineConfig({
  site: 'https://emaxenergia.com',
  output: 'static',
  trailingSlash: 'always',
  integrations: [
    sitemap({
      filter: (page) => !SIN_SITEMAP.some((ruta) => page.endsWith(ruta)),
    }),
  ],
});
