// @ts-check
// Sitio estático. El build genera HTML para publicarlo sin Node.
import { defineConfig, fontProviders } from 'astro/config';
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
  // Fuentes alojadas en el propio dominio (se descargan en el build y se sirven desde /_astro/).
  fonts: [
    {
      provider: fontProviders.fontsource(),
      name: 'Inter',
      cssVariable: '--font-inter',
      weights: [400, 500, 600, 700],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      display: 'swap',
      fallbacks: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
    },
    {
      provider: fontProviders.fontsource(),
      name: 'Poppins',
      cssVariable: '--font-poppins',
      weights: [400, 500, 600, 700, 800],
      // italic: solo lo usa .informe-nota-cierre (peso 400); la API aplica los mismos pesos a ambos estilos
      styles: ['normal', 'italic'],
      subsets: ['latin', 'latin-ext'],
      display: 'swap',
      fallbacks: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
    },
    {
      provider: fontProviders.fontsource(),
      name: 'Sora',
      cssVariable: '--font-sora',
      weights: [500, 600, 700, 800],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      display: 'swap',
      fallbacks: ['sans-serif'],
    },
    {
      provider: fontProviders.fontsource(),
      name: 'Literata',
      cssVariable: '--font-literata',
      weights: [400, 500, 600, 700],
      styles: ['italic'],
      subsets: ['latin', 'latin-ext'],
      display: 'swap',
      fallbacks: ['Georgia', 'Times New Roman', 'serif'],
    },
  ],
});
