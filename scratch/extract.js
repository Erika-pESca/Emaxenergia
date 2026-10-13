import fs from 'node:fs';
const content = fs.readFileSync('preview_copy.html', 'utf8');
const searchStr = '"/":"';
const startIdx = content.indexOf(searchStr);
if (startIdx !== -1) {
  const dataStart = startIdx + searchStr.length;
  const dataEnd = content.indexOf('"', dataStart);
  const b64 = content.substring(dataStart, dataEnd);
  const html = Buffer.from(b64, 'base64').toString('utf8');
  fs.writeFileSync('scratch/home_raw.html', html, 'utf8');
  console.log('Decoded home length:', html.length);
} else {
  console.log('Not found');
}
