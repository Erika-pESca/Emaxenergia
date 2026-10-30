// @ts-check
// Sitio estático. El build genera HTML para publicarlo sin Node.
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://emaxenergia.com',
  output: 'static',
  integrations: [sitemap()],
  vite: {
    server: {
      allowedHosts: ['oil-diaper-snort.ngrok-free.dev', '.ngrok-free.dev'],
    },
  },
});
