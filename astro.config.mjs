// @ts-check
// Sitio estático. El build genera HTML para publicarlo sin Node.
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://emaxenergia.com',
  output: 'static',
  integrations: [sitemap()],
});
