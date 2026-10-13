import fs from 'node:fs';

const html = fs.readFileSync('scratch/home_raw.html', 'utf8');
const h2s = [...html.matchAll(/<h[1-3][^>]*>(.*?)<\/h[1-3]>/gs)].map(m => m[1].replace(/<[^>]+>/g, '').trim());
console.log('Headings:', h2s);
