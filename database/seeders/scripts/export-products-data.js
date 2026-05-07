/**
 * One-shot exporter: parses public/assets/js/products-data.js and writes
 * database/seeders/data/products.json. Run once via:
 *   node database/seeders/scripts/export-products-data.js
 * The JSON file is the canonical seed source from then on;
 * this script is kept for reproducibility only.
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { createRequire } from 'module';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const jsPath = path.resolve(__dirname, '../../../public/assets/js/products-data.js');
const outPath = path.resolve(__dirname, '../data/products.json');

const jsContent = fs.readFileSync(jsPath, 'utf8');

// The file defines productCatalog (nested) then populates productsData
// via Object.keys forEach loops. Evaluate in a plain Function scope so
// both const declarations are visible and productsData is populated.
let productsData;
try {
    const fn = new Function(
        '__out__',
        jsContent + '\n__out__.value = productsData;'
    );
    const ref = { value: null };
    fn(ref);
    productsData = ref.value;
} catch (e) {
    console.error('Failed to evaluate products-data.js:', e.message);
    process.exit(1);
}

if (!Array.isArray(productsData) || productsData.length === 0) {
    console.error('productsData is empty or not an array after evaluation.');
    process.exit(1);
}

fs.mkdirSync(path.dirname(outPath), { recursive: true });
fs.writeFileSync(outPath, JSON.stringify(productsData, null, 2));
console.log('Wrote ' + productsData.length + ' products to ' + outPath);
