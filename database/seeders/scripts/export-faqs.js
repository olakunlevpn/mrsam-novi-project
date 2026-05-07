import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const htmlPath = path.resolve(__dirname, '../../../references/static/faq.html');
const outPath = path.resolve(__dirname, '../data/faqs.json');

const html = fs.readFileSync(htmlPath, 'utf8');

// Extract the outer wrapper first so we only look inside the accordion section
const wrapperMatch = html.match(/<div class="faq-page__accordion[^"]*"[^>]*>([\s\S]*?)<\/div>\s*\n\s*<\/div>\s*\n\s*<\/section>/);

if (!wrapperMatch) {
    process.stderr.write('ERROR: could not find accordion wrapper\n');
    process.exit(1);
}

const wrapper = wrapperMatch[1];

// Split into individual accordion items on each opening tag
// Items open with <div class="accrodion"> or <div class="accrodion active">
const blocks = wrapper.split(/<div class="accrodion(?:\s+active)?">/);
blocks.shift(); // remove content before first item

const faqs = [];
let order = 0;

for (const block of blocks) {
    // Extract question from <h4>...<span>...</span>...</h4>
    const qMatch = block.match(/<h4>([\s\S]*?)<span\b/);
    // Extract answer from the <div class="inner"> block
    const aMatch = block.match(/<div class="inner">([\s\S]*?)<\/div>/);

    if (!qMatch || !aMatch) {
        continue;
    }

    const question = qMatch[1].trim();
    const answer = aMatch[1].trim();

    faqs.push({ question, answer, order });
    order++;
}

if (faqs.length === 0) {
    process.stderr.write('ERROR: no FAQ items found — check the regex against the HTML.\n');
    process.exit(1);
}

fs.writeFileSync(outPath, JSON.stringify(faqs, null, 2) + '\n');
process.stdout.write(`Wrote ${faqs.length} FAQs to ${outPath}\n`);
