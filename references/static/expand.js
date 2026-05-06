/**
 * expand.js
 * Node.js utility to expand the product catalog with generated variants.
 *
 * Usage: node expand.js
 *
 * Reads the productCatalog from products-data.js, generates additional
 * product variants per category, and writes the expanded data back.
 */
const fs = require('fs');

// Read the current file
const content = fs.readFileSync('assets/js/products-data.js', 'utf8');

// Extract the productCatalog object using a regex
const catalogMatch = content.match(/const\s+productCatalog\s*=\s*(\{[\s\S]*?\n\});/);
if (!catalogMatch) {
    console.error("Could not find productCatalog object in products-data.js");
    process.exit(1);
}

// Use Function constructor instead of eval for safer parsing
let productCatalog;
try {
    productCatalog = new Function('return ' + catalogMatch[1])();
} catch (e) {
    console.error("Failed to parse productCatalog:", e.message);
    process.exit(1);
}

const modifiers = [
    'Plus', 'Advanced', 'Pro', 'Ultra', 'Premium',
    'Max', 'Supreme', 'Elite', 'Ultimate', 'Extreme',
    'Forte', 'Optima', 'Prime', 'Select', 'Super',
    'Extra', 'Vantage', 'Matrix', 'Core', 'Genesis'
];

const totalPerCategory = 20;

// Expand each animal -> productType array
Object.keys(productCatalog).forEach(animal => {
    Object.keys(productCatalog[animal]).forEach(productType => {
        const products = productCatalog[animal][productType];
        const baseCount = products.length;

        if (baseCount >= totalPerCategory) return;

        const variantsNeeded = totalPerCategory - baseCount;
        let added = 0;

        for (let i = 0; added < variantsNeeded; i++) {
            const base = products[i % baseCount];
            const modifier = modifiers[(baseCount + added) % modifiers.length];
            const indexForImage = baseCount + added + 1;

            products.push({
                id: base.id + '-' + (added + 1),
                title: base.title + ' ' + modifier,
                image: `assets/images/products/${animal.toLowerCase()}-${productType.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${indexForImage}.jpg`,
                description: base.description + " Formulated for enhanced efficacy.",
                composition: base.composition
            });
            added++;
        }
    });
});

// Rebuild the file with the expanded catalog
const output = `/**
 * products-data.js
 *
 * This file contains the complete product catalog for Novi Agro.
 * Structure: Category → Product Type → Array of Products
 */

const productCatalog = ${JSON.stringify(productCatalog, null, 4)};

/**
 * FLAT DATA TARGET FOR PRODUCT-MANAGER.JS
 *
 * Flattens the nested productCatalog into a flat array for the dynamic engine.
 * Each product object contains:
 * - id, name, image, description, composition, animal, category
 */
const productsData = [];

Object.keys(productCatalog).forEach(animalKey => {
    Object.keys(productCatalog[animalKey]).forEach(productType => {
        productCatalog[animalKey][productType].forEach(product => {
            productsData.push({
                id: product.id,
                name: product.title,
                image: product.image,
                category: productType,      // e.g. "Toxin Binder"
                animal: animalKey.toLowerCase(), // e.g. "cattle"
                description: product.description,
                composition: product.composition,
                benefits: product.benefits || "",  // Optional field
                usage: product.usage || ""      // Optional field
            });
        });
    });
});

// console.log(\`Total products loaded: \${productsData.length}\`);
`;

fs.writeFileSync('assets/js/products-data.js', output, 'utf8');
console.log("Done. Expanded catalog written to assets/js/products-data.js");
