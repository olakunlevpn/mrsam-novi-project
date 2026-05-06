/** 
 * products-data.js
 * 
 * This file contains the complete product catalog for Novi Agro.
 * Structure: Category → Product Type → Array of Products
 **/

const productCatalog = {
    "Cattle": {
        "Premixes": [
            {
                "id": "CT-PRE-01",
                "title": "Namix Broiler FINISHER Premix 0.25%",
                "image": "assets/images/products/Namix.jpg",
                "description": "Comprehensive vitamin and mineral pre-blend specifically balanced for neonatal calves to support early immune system development and skeletal growth.",
                "composition": "Vitamin A, D3, E, K3, B-Complex, Copper, Zinc, Manganese, Iron, Iodine, Selenium, Cobalt."
            },
            {
                "id": "CT-PRE-02",
                "title": "Namix Grower Premix 0.25%",
                "image": "assets/images/products/lactation_boost.png",
                "description": "High-performance premix for lactating dairy cows, formulated to optimize milk yield and maintain body condition during peak lactation.",
                "composition": "Biotin, Niacin, Protected Choline, Organic Zinc, Vitamin E 1000, Magnesium Oxide, Calcium Carbonate."
            },
            {
                "id": "CT-PRE-03",
                "title": "Namix Broiler Grower Premix 0.25%",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Tailored for beef cattle in the finishing phase to enhance carcass quality, promote marbling, and improve average daily gain (ADG).",
                "composition": "Vitamin A, D3, E, Manganese Glycinate, Zinc Methionate, Selenium Yeast, Monensin Sodium."
            },
            {
                "id": "CT-PRE-04",
                "title": "NAMIX Layer Premix 0.25%",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Specialized premix for transition cows to prevent metabolic disorders such as ketosis and milk fever during the pre-calving period.",
                "composition": "Anionic Salts, Vitamin D3 (high dose), Magnesium Chloride, Calcium Propionate, Niacinamide."
            },
            {
                "id": "CT-PRE-05",
                "title": "NAMIX  Premix 0.25%",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Concentrated trace mineral supplement using highly bioavailable chelated minerals for improved reproductive performance and hoof health.",
                "composition": "Zinc Proteinate, Manganese Proteinate, Copper Proteinate, Cobalt Carbonate, Ethylenediamine Dihydroiodide."
            }
        ],
        "Concentrates": [
            {
                "id": "CT-CON-01",
                "title": "Rumen-Fill 40% Protein Concentrate",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "High-protein concentrate designed for on-farm mixing with local grains to produce a balanced total mixed ration (TMR).",
                "composition": "Soybean Meal, Cottonseed Cake, Bypass Protein, Urea (slow release), Limestone, Dicalcium Phosphate."
            },
            {
                "id": "CT-CON-02",
                "title": "Cattle-Vital Liquid Energy Booster",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Rapidly absorbed liquid energy concentrate for high-yielding cows to bridge the negative energy balance in early lactation.",
                "composition": "Propylene Glycol, Glycerol, Vitamin B12, Niacin, Cobalt Sulfate, Dextrose."
            },
            {
                "id": "CT-CON-03",
                "title": "Beef-Max Fattening Concentrate",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Energy-dense concentrate for rapid fattening of bulls and steers, ensuring optimal fat cover and weight gain.",
                "composition": "Maize Germ, Molasses, Vegetable Fat, Bypass Protein, Ammonium Chloride, Vitamin-Mineral Pack."
            },
            {
                "id": "CT-CON-04",
                "title": "Calf-Start Pelleted Concentrate",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Highly palatable starter concentrate to encourage early dry feed intake and rumen development in weaning calves.",
                "composition": "Flaked Maize, Toasted Soybeans, Whey Powder, Glucose, Sweet Flavors, Probiotics, Organic Acids."
            },
            {
                "id": "CT-CON-05",
                "title": "Dairy-Plus High Yield Concentrate",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Premium concentrate for elite dairy herds, focusing on amino acid balance and rumen-protected nutrients for maximum production.",
                "composition": "Protected Methionine, Protected Lysine, Live Yeast, Megalac (Bypass Fat), Sodium Bicarbonate, Vits & Minerals."
            }
        ],
        "Toxin Binder": [
            {
                "id": "CT-TOX-01",
                "title": "PF - SORB+",
                "image": "assets/images/products/PF-Sorb+.jpg",
                "description": "Advanced mycotoxin binder that effectively adsorbs Aflatoxins, Zearalenone, and Fumonisins in contaminated silage and grain.",
                "composition": "Hydrated Sodium Calcium Aluminosilicate (HSCAS), Bentonite, Yeast Cell Wall (Beta-glucans)."
            },
            {
                "id": "CT-TOX-02",
                "title": "PF - PRIMETOX",
                "image": "assets/images/products/primetox.jpg",
                "description": "Dual-action toxin binder that not only neutralizes toxins but also provides botanical liver support to aid detoxification.",
                "composition": "Activated Charcoal, Clinoptilolite, Silymarin (Milk Thistle), Betaine, Vitamin E."
            },
            
        ],
        "Enzymes": [
            {
                "id": "CT-ENZ-01",
                "title": "NAZYME Multi Pro",
                "image": "assets/images/products/Multipro.jpg",
                "description": "Multi-enzyme complex designed to break down tough cellulose and hemicellulose fibers in low-quality forage and silage.",
                "composition": "Cellulase, Xylanase, Beta-Glucanase, Pectinase, Amylase."
            },
            {
                "id": "CT-ENZ-02",
                "title": "NAZYME Multi Active",
                "image": "assets/images/products/Multipro.jpg",
                "description": "High-potency phytase enzyme to release plant-bound phosphorus, reducing the need for inorganic phosphate supplementation.",
                "composition": "6-Phytase (derived from Trichoderma reesei), Thermostable carrier."
            },
            {
                "id": "CT-ENZ-03",
                "title": "NAPHYT 5000 (Phytase)",
                "image": "assets/images/products/Naphyt2.png",
                "description": "High-potency phytase enzyme that releases bound phosphorus from plant-based cattle feeds, reducing the need for inorganic phosphate supplementation.",
                "composition": "6-Phytase (E.coli-derived), Calcium Carbonate carrier, Thermostable coating."
            },
            {
                "id": "CT-ENZ-04",
                "title": "BETA-Mannanase",
                "image": "assets/images/products/Mannanase.jpg",
                "description": "Specialized beta-mannanase enzyme that breaks down mannans in soybean and palm kernel meals, improving energy utilization in cattle diets.",
                "composition": "Beta-Mannanase (Bacillus lentus), Wheat bran carrier."
            }
        ],
        "Acidifier": [
            {
                "id": "CT-ACI-01",
                "title": "Rumen-Acid pH Control",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Liquid acidifier for drinking water to prevent pathogenic bacteria and maintain optimal rumen pH.",
                "composition": "Formic Acid, Propionic Acid, Lactic Acid."
            },
            {
                "id": "CT-ACI-02",
                "title": "Bovi-Safe Granular Acid",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Granular acidifier for in-feed inclusion to maintain intestinal health and feed hygiene.",
                "composition": "Calcium Formate, Citric Acid, Fumaric Acid."
            },
            {
                "id": "CT-ACI-03",
                "title": "Dry-Period Acidify Pak",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Specialized acidifier for dry cows to prevent metabolic disorders and improve mineral absorption.",
                "composition": "Buffered Organic Acids, Vitamin D3, Magnesium."
            },
            {
                "id": "CT-ACI-04",
                "title": "Silage-Fresh Acid Inoculant",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Acid-based silage inoculant to speed up fermentation and prevent aerobic spoilage.",
                "composition": "Lactic Acid Bacteria, Formic Acid, Sodium Formate."
            },
            {
                "id": "CT-ACI-05",
                "title": "Water-Guard Liquid Acid",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Powerful water acidifier to reduce microbial growth in drinking lines and improve water quality.",
                "composition": "Buffered Blend of Formic and Propionic Acids."
            }
        ],
        "Ca & P Sources": [
            {
                "id": "CT-CAP-01",
                "title": "Mega-Cal D3 Mineral Complex",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Essential calcium and phosphorus supplement for bone development and high milk production.",
                "composition": "Calcium Carbonate, Dicalcium Phosphate, Vitamin D3."
            },
            {
                "id": "CT-CAP-02",
                "title": "Bovi-Phos High Bioavailability P",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Highly bioavailable phosphorus source for rapid correction of deficiency and improved fertility.",
                "composition": "Monocalcium Phosphate, Magnesium, Trace Minerals."
            },
            {
                "id": "CT-CAP-03",
                "title": "Liquid-Cal Performance Drench",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Fast-acting liquid calcium for high-producing cows during peak lactation or recovery.",
                "composition": "Calcium Gluconate, Vitamin D3, Magnesium Chloride."
            },
        ],
        "Growth Promoter": [
            {
                "id": "CT-GRO-01",
                "title": "Beef-Gain Phytogenic Booster",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Natural growth promoter using plant extracts to improve feed conversion and weight gain.",
                "composition": "Oregano Oil, Capsicum, Saponins."
            },
            {
                "id": "CT-GRO-02",
                "title": "Rumen-Boost Yeast Culture",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Live yeast culture to stabilize rumen flora and increase nutrient digestibility in beef cattle.",
                "composition": "Saccharomyces cerevisiae, Fermentation metabolites."
            },
            {
                "id": "CT-GRO-03",
                "title": "Sweet-Malt Feed Appetizer",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Palatable appetizer to encourage higher dry matter intake in transition cows and calves.",
                "composition": "Natural Malt Extracts, Molasses solids, Vanilla aroma."
            },
        ],
        "Mould Inhibitor": [
            {
                "id": "CT-MOU-01",
                "title": "Barn-Shield Liquid Inhibitor",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Liquid mould inhibitor for cattle silage and total mixed rations (TMR) in hot climates.",
                "composition": "Propionic Acid, Ammonium Propionate, Sorbic Acid."
            },
            {
                "id": "CT-MOU-02",
                "title": "Silage-Gold Dry Inhibitor",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Dry powder inhibitor for on-farm feed mixing and grain storage protection.",
                "composition": "Calcium Propionate, Sodium Diacetate, Silica."
            },
        ],
        "Choline": [
            {
                "id": "CT-CHO-01",
                "title": "Rumen-Protected Choline 25%",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Prevents fatty liver and improves metabolic efficiency in transition dairy cows.",
                "composition": "Choline Chloride (coated), Vitamin B12, Cobalt."
            },
            {
                "id": "CT-CHO-02",
                "title": "Bovi-Vit Liquid Choline",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "High-dose liquid choline for rapid transition cow recovery and liver health.",
                "composition": "Choline, Niacin, Riboflavin, Pantothenic Acid."
            },
        ],
        "Emulsifier": [
            {
                "id": "CT-EMU-01",
                "title": "Fat-Digest Lyso-Lecithin",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Natural biosurfactant to improve bypass fat absorption and energy efficiency.",
                "composition": "Lysophospholipids, Lecithin, Silica carrier."
            },
            {
                "id": "CT-EMU-02",
                "title": "Bovi-Emuls 50 Concentrated",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Concentrated emulsifier for high-fat dairy rations to maximize fat digestibility.",
                "composition": "Glycerol Monostearate, Tween 80, Vegetable oils."
            },
        ],
        "Anti-Stress": [
            {
                "id": "CT-STS-01",
                "title": "Bovi-Calm Magnesium Pak",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Reduces shipping fever and heat stress indicators in transport or sales yards.",
                "composition": "Magnesium Oxide, Chromium Picolinate, L-Tryptophan."
            },
            {
                "id": "CT-STS-02",
                "title": "Stress-Guard Electrolyte",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Rapid hydration and electrolyte balance for stressed cattle in extreme weather.",
                "composition": "Potassium, Sodium, Vitamin C, Vitamin E."
            },
            {
                "id": "CT-STS-03",
                "title": "Cool-Boost Betaine",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Osmo-protectant to maintain cellular hydration and productivity during heat stress.",
                "composition": "96% Pure Betaine Anhydrous."
            },
        ],
        "Pellet Binder": [
            {
                "id": "CT-PEL-01",
                "title": "Mega-Bond Lignosulfonate",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "High-strength binder for durable, dust-free pellets with excellent durability index.",
                "composition": "Calcium Lignosulfonate, Mineral fillers."
            },
            {
                "id": "CT-PEL-02",
                "title": "Bovi-Pel Guar Gum",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Natural vegetable-based binder for specialty beef and organic-compliant pellets.",
                "composition": "Guar Gum, Modified Starch, Silica."
            },
            {
                "id": "CT-PEL-03",
                "title": "Hard-Pel Bentonite",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Mineral-based binder that also provides trace minerals and toxin protection.",
                "composition": "Activated Sodium Bentonite, Diatomaceous earth."
            },
            
        ],
        "Hepatoprotector": [
            {
                "id": "CT-HEP-01",
                "title": "Liver-Vital Silybin Extract",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Maintains hepatocyte membrane integrity and protects against oxidative damage.",
                "composition": "Silymarin, Artichoke Extract, Betaine."
            },
            {
                "id": "CT-HEP-02",
                "title": "Liv-Fix Botanical",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Natural liver support using traditional botanicals for long-term health and efficiency.",
                "composition": "Turmeric, Boldo, Milk Thistle, Dextrose carrier."
            }
        ],
        "Amino Acids": [
            {
                "id": "CT-AMI-01",
                "title": "L-Methionine",
                "image": "assets/images/products/Methioline.jpg",
                "description": "Rumen-protected methionine to optimize milk protein synthesis and immune function.",
                "composition": "Methionine (micro-encapsulated) 60%."
            },
            {
                "id": "CT-AMI-02",
                "title": "L-Lysine",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Rumen-protected lysine for optimal amino acid balancing in dairy diets.",
                "composition": "Lysine Hydrochloride (micro-encapsulated) 50%."
            },
            {
                "id": "CT-AMI-03",
                "title": "L-Threonine",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Supports gut integrity and immune protein production in growing cattle.",
                "composition": "L-Threonine 98.5% min."
            },
        ]
    },
    "Pigs": {
        "Premixes": [
            {
                "id": "PG-PRE-01",
                "title": "Namix Pig Premix 0.5%",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Specialized premix for creep and starter feeds, ensuring rapid gut development and high survivability in young piglets.",
                "composition": "Vitamin A, D3, E, B12, High-dose Zinc Oxide, Copper Sulfate, L-Lysine, DL-Methionine, Threonine."
            },
            
        ],
        "Growth Promoter": [
            {
                "id": "PG-GRO-01",
                "title": "Swine-Grow Natural Promoter",
                "image": "assets/images/products/swine_grower.png",
                "description": "Antibiotic-free growth promoter using phytogenic compounds to enhance appetite and nutrient absorption.",
                "composition": "Essential Oils (Oregano, Thyme), Cinnamon Extract, Capsicum, Prebiotic MOS."
            },
            {
                "id": "PG-GRO-02",
                "title": "Gut-Integrity Probiotic Pack",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Concentrated probiotic supplement to stabilize intestinal flora and prevent post-weaning diarrhea (PWD).",
                "composition": "Bacillus subtilis, Lactobacillus acidophilus, Bifidobacterium, Fructo-oligosaccharides (FOS)."
            },
            {
                "id": "PG-GRO-03",
                "title": "Fatten-All Energy Syrup",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "High-energy supplement for rapid finishing and improved fat cover.",
                "composition": "Glycerol, B-Vitamins, Glucogenic Amino Acids."
            },
            {
                "id": "PG-GRO-04",
                "title": "Stress-Free Swine Calm",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Natural supplement to reduce cannibalism and tail-biting in high-density pens.",
                "composition": "Magnesium, Tryptophan, Valerian Extract."
            },
            {
                "id": "PG-GRO-05",
                "title": "Pig-Power Multi-Enzyme",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Improves digestibility of cereal-based diets and reduces feed costs.",
                "composition": "Xylanase, Beta-Glucanase, Protease, Alpha-Galactosidase."
            }
        ],
        "Acidifier": [
            {
                "id": "PG-ACI-01",
                "title": "Pork-Acid pH Guard",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Synergistic blend of organic acids to lower stomach pH, inhibiting pathogenic bacteria like E. coli and Salmonella.",
                "composition": "Formic Acid, Propionic Acid, Lactic Acid, Citric Acid, Ammonium Formate."
            },
            {
                "id": "PG-ACI-02",
                "title": "Gut-Acid Powder",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Stable granular acidifier for on-farm feed mixing and intestinal health.",
                "composition": "Citric Acid, Malic Acid, Fumaric Acid, Calcium Formate."
            },
            {
                "id": "PG-ACI-03",
                "title": "Water-Plus pH Drop",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Water line cleaner and intestinal acidifier for continuous protection.",
                "composition": "Phosphoric Acid, Lactic Acid, Copper Sulfate."
            },
            {
                "id": "PG-ACI-04",
                "title": "Sow-Care Urogenital Acid",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Prevents urinary tract infections and associated reproductive failures in breeding sows.",
                "composition": "Benzoic Acid, DL-Methionine, Vitamin E."
            },
            {
                "id": "PG-ACI-05",
                "title": "Bio-Acid Protection",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Buffered organic acid blend for pathogenic control without feed palatability loss.",
                "composition": "Calcium Formate, Ammonium Propionate, Sorbic Acid."
            }
        ],
        "Concentrates": [
            {
                "id": "PG-CON-01",
                "title": "40% Swine Starter Concentrate",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "High-protein concentrate tailored for weaning piglets to ensure smooth transition to solid feed.",
                "composition": "Fish Meal, Soybean Meal, Whey Powder, Organic Acids."
            },
            {
                "id": "PG-CON-02",
                "title": "Sow-Nutri Gestation Concentrate",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Balanced concentrate for pregnant sows focusing on fetal development and mother vitality.",
                "composition": "Standardized Proteins, Fiber sources, Minerals, Folic Acid."
            },
            {
                "id": "PG-CON-03",
                "title": "Finish-Max 20% Concentrate",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Economic concentrate for the finishing phase, optimizing cost-per-kg gain.",
                "composition": "Sunflower Meal, DDGS, Limestone, Synthetic Lysine."
            },
            {
                "id": "PG-CON-04",
                "title": "Milk-Boost Lactation Concentrate",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "High-energy concentrate for nursing sows to support high milk production and litter growth.",
                "composition": "Vegetable Fats, Lysine-rich proteins, Choline Chloride."
            },
            {
                "id": "PG-CON-05",
                "title": "Universal Swine Base",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Versatile base mix for all swine growth stages, just add energy and protein sources.",
                "composition": "Mineral-Vitamin core, Calcium, Phosphorus, Salt."
            }
        ],
        "Toxin Binder": [
             {
                "id": "CT-TOX-01",
                "title": "PF - SORB+",
                "image": "assets/images/products/PF-Sorb+.jpg",
                "description": "Advanced mycotoxin binder that effectively adsorbs Aflatoxins, Zearalenone, and Fumonisins in contaminated silage and grain.",
                "composition": "Hydrated Sodium Calcium Aluminosilicate (HSCAS), Bentonite, Yeast Cell Wall (Beta-glucans)."
            },
            {
                "id": "CT-TOX-02",
                "title": "PF - PRIMETOX",
                "image": "assets/images/products/primetox.jpg",
                "description": "Dual-action toxin binder that not only neutralizes toxins but also provides botanical liver support to aid detoxification.",
                "composition": "Activated Charcoal, Clinoptilolite, Silymarin (Milk Thistle), Betaine, Vitamin E."
            },
        ],
        "Ca & P Sources": [
            {
                "id": "PG-CAP-01",
                "title": "Pig-Phos 18%",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "High-phosphorus mineral for skeletal strength and reproductive longevity in breeding sows.",
                "composition": "Dicalcium Phosphate, Magnesium, Zinc."
            },
            {
                "id": "PG-CAP-02",
                "title": "Starter-Cal High Bio",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Optimized calcium source for weaning piglets to support rapid skeletal growth.",
                "composition": "Calcium Formate, Calcium Carbonate, Vitamin D3."
            },
            {
                "id": "PG-CAP-03",
                "title": "Finisher-Phos Economic",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Cost-effective mineral blend for final growth stage requirements.",
                "composition": "Tricalcium Phosphate, Salt, Mineral core."
            },
            {
                "id": "PG-CAP-04",
                "title": "Sow-Cal-Plus D3",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Calcium supplement for high-yielding nursing sows to prevent metabolic crashes.",
                "composition": "Calcium Gluconate, Vitamin D3, B-Complex."
            },
            {
                "id": "PG-CAP-05",
                "title": "Bone-Hard Swine Mineral",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Comprehensive macro-mineral package for heavy finishing pig varieties.",
                "composition": "Calcium, Phosphorus, Magnesium, Manganese, Zinc."
            }
        ],
        "Mould Inhibitor": [
            {
                "id": "PG-MOU-01",
                "title": "Swine-Fresh Liquid Inhibitor",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Powerful mould protection specifically designed for liquid feeding systems.",
                "composition": "Propionic Acid, Ammonium Propionate, Sorbic Acid."
            },
            {
                "id": "PG-MOU-02",
                "title": "Grain-Protect Swine Dry",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Dry powder to prevent heating and nutrient loss in stored pig feed.",
                "composition": "Calcium Propionate, Sorbic Acid, Silica carrier."
            },
            {
                "id": "PG-MOU-03",
                "title": "Silage-Plus Swine Preservative",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Specific inhibitor for fermentation control and shelf-life extension in silages.",
                "composition": "Organic Acid Salts, Natamycin, Antioxidants."
            },
            {
                "id": "PG-MOU-04",
                "title": "Patho-Block Fungal Guard",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Inhibits a wide range of fungi and yeasts in the trough and feed lines.",
                "composition": "Buffered Acids, Botanical extracts, Antifungal salts."
            },
            {
                "id": "PG-MOU-05",
                "title": "Universal Pig Feed Shield",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "All-in-one preservation for raw materials and finished pig feed types.",
                "composition": "Mixed Organic Acids, Silicates, Botanical inhibitors."
            }
        ],
        "Enzymes": [
            {
                "id": "PG-ENZ-01",
                "title": "NAZYME Multi Pro",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Significantly increases phosphorus availability from plant-based feeds.",
                "composition": "High-purity 6-Phytase, Thermostable matrix."
            },
            {
                "id": "PG-ENZ-02",
                "title": "NAZYME Multi Active",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Breaks down non-starch polysaccharides for better energy extraction from grains.",
                "composition": "Xylanase, Beta-Glucanase, Glucanase."
            },
            {
                "id": "PG-ENZ-03",
                "title": "NAPHYT 5000 (Phytase)",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Specifically formulated for young piglets with immature digestive tracts.",
                "composition": "Amylase, Protease, Lipase, Pectinase."
            },
            {
                "id": "PG-ENZ-04",
                "title": "BETA-Mannanase",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Optimized for barley and wheat-heavy finishing diets to improve gain.",
                "composition": "Beta-Glucanase, Xylanase, Amylase."
            }
        ],
        "Emulsifier": [
            {
                "id": "PG-EMU-01",
                "title": "Fat-Link Swine Emulsifier",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Improves dispersion and stability of added vegetable fats in the diet.",
                "composition": "Soy Lecithin, Polysorbates, Vegetable carriers."
            },
            {
                "id": "PG-EMU-02",
                "title": "Lipo-Digest Piglet Emulsifier",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Helps weaning piglets digest milk fats and oils efficiently during transition.",
                "composition": "Lyso-phospholipids, Lecithin powder."
            },
            {
                "id": "PG-EMU-03",
                "title": "Energy-Link Emulsifier plus",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Synergistic blend for total dietary fat utilization and absorption.",
                "composition": "Soy Lecithin, Tween 80, Organic Acids."
            },
            {
                "id": "PG-EMU-04",
                "title": "Oil-Boost Liquid Swine",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Ensures even oil distribution and rapid absorption in mixed rations.",
                "composition": "Emulsifying Agents, Vegetable Oil carrier, Antioxidants."
            },
            {
                "id": "PG-EMU-05",
                "title": "Economic-Fat Emulsifier",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Cost-effective emulsification solution for large-scale swine finishing operations.",
                "composition": "Mineral core, Lecithin, Surfactants."
            }
        ],
        "Anti-Stress": [
            {
                "id": "PG-STS-01",
                "title": "Swine-Calm Tryptophan Pak",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Reduces aggression and tail-biting in newly grouped or stressed pigs.",
                "composition": "L-Tryptophan, Magnesium Oxide, Vitamin B6."
            },
            {
                "id": "PG-STS-02",
                "title": "Hydra-Plus Swine Electrolyte",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Rapid hydration and metabolic support during transport or heat stress.",
                "composition": "Potassium, Sodium, Chloride, Dextrose, Vitamin C."
            },
            {
                "id": "PG-STS-03",
                "title": "Betaine-Cool Swine",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Maintains cellular water balance and feed intake during summer months.",
                "composition": "96% Pure Betaine Anhydrous."
            },
            {
                "id": "PG-STS-04",
                "title": "Immuno-Stress Piglet Booster",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Supports immune vigor and gut integrity during the stressful weaning period.",
                "composition": "Vitamin C, Vitamin E, Organic Selenium, Yeast."
            },
            {
                "id": "PG-STS-05",
                "title": "Stress-Free Swine Botanical",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Natural herbs to reduce stress indicators and maintain consistent feed intake.",
                "composition": "Passiflora, Valerian Extract, Lemon Balm Extract."
            }
        ],
        "Pellet Binder": [
            {
                "id": "PG-PEL-01",
                "title": "Mega-Bond Lignosulfonate Pig",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Provides superior pellet durability and reduced dust for swine rations.",
                "composition": "Calcium Lignosulfonate, Fine mineral carriers."
            },
            {
                "id": "PG-PEL-02",
                "title": "Pork-Pel Guar Gum",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Natural plant-based binder specifically for high-quality piglet starter pellets.",
                "composition": "Guar Gum, Modified Starch, Silica."
            },
            {
                "id": "PG-PEL-03",
                "title": "Hard-Pel Bentonite Swine",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Mineral binder with additional toxin binding capabilities for pig feed.",
                "composition": "Sodium Bentonite, Activated Clays."
            },
            {
                "id": "PG-PEL-04",
                "title": "Pellet-Plus Polymer Binder",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Low-inclusion synthetic binder for extremely dense and durable pellets.",
                "composition": "Polymethacrylate, Silica, Stabilizers."
            },
            {
                "id": "PG-PEL-05",
                "title": "Steam-Fix Swine Core",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Optimized for high-steam pelleting throughput and thermal stability.",
                "composition": "Modified wheat starches, Natural polymers, Emulsifiers."
            }
        ],
        "Hepatoprotector": [
            {
                "id": "PG-HEP-01",
                "title": "Liver-Vital Swine Formula",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Protects liver function from metabolic stress and dietary toxins throughout growth.",
                "composition": "Silybin, Choline Chloride, Vitamin B12, Zinc."
            },
            {
                "id": "PG-HEP-02",
                "title": "Pork-Liv Detox formula",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Rapid liver cleansing and metabolic restoration following toxin challenge.",
                "composition": "Methionine, Betaine, Inositol, Biotin."
            },
            {
                "id": "PG-HEP-03",
                "title": "Sow-Safe Liver Support",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Specifically for breeding sows to ensure reproductive longevity and piglet health.",
                "composition": "Folic Acid, Choline, Plant Botanicals, Vitamin E."
            },
            {
                "id": "PG-HEP-04",
                "title": "Metabol-Guard Swine",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Prevents fat accumulation in the liver during the intensive finishing phase.",
                "composition": "L-Carnitine, B-Vitamins, Niacin."
            },
            {
                "id": "PG-HEP-05",
                "title": "Liv-Fix Swine Botanical",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Natural liver aid using artichoke and boldo for consistent daily performance.",
                "composition": "Artichoke Extract, Boldo Extract, Turmeric."
            }
        ],
        "Amino Acids": [
            {
                "id": "PG-AMI-01",
                "title": "Swine-Lys L-Lysine",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "First-limiting amino acid for rapid lean tissue growth and protein deposition.",
                "composition": "L-Lysine HCl 98.5% min."
            },
            {
                "id": "PG-AMI-02",
                "title": "Swine-Met DL-Methionine",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Essential sulfur amino acid for immunity and structural protein synthesis in pigs.",
                "composition": "DL-Methionine 99% pure min."
            },
            {
                "id": "PG-AMI-03",
                "title": "Swine-Thr L-Threonine",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Supports gut health, mucin production and immune defenses in young pig varieties.",
                "composition": "L-Threonine 98.5% min."
            },
            {
                "id": "PG-AMI-04",
                "title": "Swine-Tryp L-Tryptophan",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Essential for stress management, behavior control and optimal feed intake.",
                "composition": "L-Tryptophan 99% pure min."
            },
            {
                "id": "PG-AMI-05",
                "title": "Swine-Val L-Valine",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Optimizes amino acid balance for high-performing modern genetic strains.",
                "composition": "L-Valine 98% pure, feed grade."
            }
        ]
    },
    "Poultry": {
        "Premixes": [
            {
                "id": "CT-PRE-01",
                "title": "Namix Broiler FINISHER Premix 0.25%",
                "image": "assets/images/products/Namix.jpg",
                "description": "Comprehensive vitamin and mineral pre-blend specifically balanced for neonatal calves to support early immune system development and skeletal growth.",
                "composition": "Vitamin A, D3, E, K3, B-Complex, Copper, Zinc, Manganese, Iron, Iodine, Selenium, Cobalt."
            },
            {
                "id": "CT-PRE-02",
                "title": "Namix Grower Premix 0.25%",
                "image": "assets/images/products/lactation_boost.png",
                "description": "High-performance premix for lactating dairy cows, formulated to optimize milk yield and maintain body condition during peak lactation.",
                "composition": "Biotin, Niacin, Protected Choline, Organic Zinc, Vitamin E 1000, Magnesium Oxide, Calcium Carbonate."
            },
            {
                "id": "CT-PRE-03",
                "title": "Namix Broiler Grower Premix 0.25%",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Tailored for beef cattle in the finishing phase to enhance carcass quality, promote marbling, and improve average daily gain (ADG).",
                "composition": "Vitamin A, D3, E, Manganese Glycinate, Zinc Methionate, Selenium Yeast, Monensin Sodium."
            },
            {
                "id": "CT-PRE-04",
                "title": "NAMIX Layer Premix 0.25%",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Specialized premix for transition cows to prevent metabolic disorders such as ketosis and milk fever during the pre-calving period.",
                "composition": "Anionic Salts, Vitamin D3 (high dose), Magnesium Chloride, Calcium Propionate, Niacinamide."
            },
            {
                "id": "CT-PRE-05",
                "title": "NAMIX  Premix 0.25%",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Concentrated trace mineral supplement using highly bioavailable chelated minerals for improved reproductive performance and hoof health.",
                "composition": "Zinc Proteinate, Manganese Proteinate, Copper Proteinate, Cobalt Carbonate, Ethylenediamine Dihydroiodide."
            }
        ],
        "Concentrates": [
            {
                "id": "PL-CON-01",
                "title": "Layer-Max 35% Concentrate",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Complete protein and mineral concentrate for commercial egg production, just add maize.",
                "composition": "Limestone, Soya Meal, Essential Amino Acids, Poultry Premix."
            },
            {
                "id": "PL-CON-02",
                "title": "Broiler-Grow 40% Concentrate",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "High-performance broiler concentrate focusing on rapid growth and lean muscle.",
                "composition": "Fishmeal, Maize Gluten, L-Lysine, DL-Methionine, Salt."
            },
            {
                "id": "PL-CON-03",
                "title": "Universal Poultry Base Mix",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Flexible base for multiple bird species, providing a robust nutritional foundation.",
                "composition": "Dicalcium Phosphate, Vitamin Core, Trace Mineral Pak."
            },
            {
                "id": "PL-CON-04",
                "title": "Duck-Spec Liquid Concentrate",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Optimized for waterfowl growth and high-quality feathering.",
                "composition": "Biotin, Niacin, Riboflavin, Methionine booster."
            },
            {
                "id": "PL-CON-05",
                "title": "Natural-Style Herbal Concentrate",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Concentrate compliant with antibiotic-free production standards.",
                "composition": "Herbal Extracts, Seaweed Meal, Natural Antioxidants."
            }
        ],
        "Coccidiostat": [
            {
                "id": "PL-COC-01",
                "title": "Avi-Safe Coccidiostat 10%",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Effective prevention and control of coccidiosis caused by Eimeria species in broilers and pullets.",
                "composition": "Salinomycin Sodium 10%, special carrier for uniform mixing."
            },
            {
                "id": "PL-COC-02",
                "title": "Mega-Stop Chemical Coccidiostat",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Powerful chemical coccidiostat for use in shuttle or rotation programs to prevent resistance build-up.",
                "composition": "Robenidine Hydrochloride 6.6%, Corn cob carrier."
            },
            {
                "id": "PL-COC-03",
                "title": "Coccidi-Mix Dual Action",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Combination of chemical and ionophore agents for reliable control in high-challenge environments.",
                "composition": "Monensin, Nicarbazin, Limestone carrier."
            },
            {
                "id": "PL-COC-04",
                "title": "Native-Shield Natural Control",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Plant-based prevention for organic poultry or withdrawal phases.",
                "composition": "Essential Oils, Condensed Tannins, Saponins."
            },
            {
                "id": "PL-COC-05",
                "title": "Poultry-Fix Anticoccidial",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Water-soluble treatment for acute outbreaks with rapid recovery effect.",
                "composition": "Amprolium, Vitamin K3, Vitamin A."
            }
        ],
        "Pigments/ Yolk Colourants": [
            {
                "id": "PL-PIG-01",
                "title": "Yolk-Bright Yellow Pigment",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Natural marigold extract to achieve the desired golden-yellow yolk color preferred by consumers.",
                "composition": "Lutein, Zeaxanthin (from Tagetes erecta), Antioxidants."
            },
            {
                "id": "PL-PIG-02",
                "title": "Ruby-Red Egg Colourant",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Concentrated red pigment for achieving deep orange to red yolk shades in specialty egg production.",
                "composition": "Canthaxanthin (synthetic), Stabilized starch matrix."
            },
            {
                "id": "PL-PIG-03",
                "title": "Sun-Gold Carotenoid Blend",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Balanced yellow and orange pigments for a natural 'farm-fresh' yolk appearance.",
                "composition": "Paprika Extract, Marigold Solids, Vitamin E."
            },
            {
                "id": "PL-PIG-04",
                "title": "Bio-Glow Skin Pigment",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Ensures attractive yellow skin color in corn-fed poultry markets.",
                "composition": "Apo-ester, Xanthophylls, Corn gluten meal."
            },
            {
                "id": "PL-PIG-05",
                "title": "Shine-Egg Shell Pigment",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Improves pigmentation and smoothness of brown-shelled eggs.",
                "composition": "Protoporphyrin booster, Iron, Trace Minerals."
            }
        ],
        "Amino Acids": [
            {
                "id": "PL-AMI-01",
                "title": "Avi-Lys 98% L-Lysine",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Essential first-limiting amino acid for poultry to optimize protein synthesis and muscle growth.",
                "composition": "L-Lysine Hydrochloride 98.5% min."
            },
            {
                "id": "PL-AMI-02",
                "title": "Avi-Met DL-Methionine",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Crucial sulfur-containing amino acid for feathering, immunity, and overall bird performance.",
                "composition": "DL-Methionine 99.0% min."
            },
            {
                "id": "PL-AMI-03",
                "title": "Avi-Thr 98% L-Threonine",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Third-limiting amino acid essential for gut health and intestinal mucin production.",
                "composition": "L-Threonine 98.5% min."
            },
            {
                "id": "PL-AMI-04",
                "title": "Avi-Tryp L-Tryptophan",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Improves behavior, reduces aggression, and supports growth during stress periods.",
                "composition": "L-Tryptophan 99% pure."
            },
            {
                "id": "PL-AMI-05",
                "title": "Avi-Val L-Valine",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Branched-chain amino acid to support muscle growth and total body protein accretion.",
                "composition": "L-Valine 98% pure, feed grade."
            }
        ],
        "Toxin Binder": [
           {
                "id": "CT-TOX-01",
                "title": "PF - SORB+",
                "image": "assets/images/products/PF-Sorb+.jpg",
                "description": "Advanced mycotoxin binder that effectively adsorbs Aflatoxins, Zearalenone, and Fumonisins in contaminated silage and grain.",
                "composition": "Hydrated Sodium Calcium Aluminosilicate (HSCAS), Bentonite, Yeast Cell Wall (Beta-glucans)."
            },
            {
                "id": "CT-TOX-02",
                "title": "PF - PRIMETOX",
                "image": "assets/images/products/primetox.jpg",
                "description": "Dual-action toxin binder that not only neutralizes toxins but also provides botanical liver support to aid detoxification.",
                "composition": "Activated Charcoal, Clinoptilolite, Silymarin (Milk Thistle), Betaine, Vitamin E."
            }, 
            
        ],
        "Enzymes": [
            {
                "id": "PL-ENZ-01",
                "title": "NAZYME Multi Pro",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Significantly increases phosphorus availability from plant-based feeds.",
                "composition": "High-purity 6-Phytase, Thermostable matrix."
            },
            {
                "id": "PL-ENZ-02",
                "title": "NAZYME Multi Active",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Breaks down non-starch polysaccharides for better energy extraction from grains.",
                "composition": "Xylanase, Beta-Glucanase, Glucanase."
            },
            {
                "id": "PL-ENZ-03",
                "title": "NAPHYT 5000 (Phytase)",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "High-potency phytase enzyme that releases bound phosphorus from plant-based poultry feeds, improving mineral availability and reducing environmental impact.",
                "composition": "6-Phytase (E.coli-derived), Calcium Carbonate carrier, Thermostable coating."
            },
            {
                "id": "PL-ENZ-04",
                "title": "BETA-Mannanase",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Optimized for barley and wheat-heavy finishing diets to improve gain.",
                "composition": "Beta-Glucanase, Xylanase, Amylase."
            }
        ],
        "Acidifier": [
            {
                "id": "PL-ACI-01",
                "title": "Poultry-Acid pH Water",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Acidifier for water lines to control Salmonella and E. coli counts continuously.",
                "composition": "Formic Acid, Propionic Acid, Copper salts."
            },
            {
                "id": "PL-ACI-02",
                "title": "Gut-Acid-Avi Powder",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Powdered acidifier for broiler and layer feed hygiene and intestinal health.",
                "composition": "Ammonium Formate, Lactic Acid, Fumaric Acid."
            },
            {
                "id": "PL-ACI-03",
                "title": "Chick-Safe Acidifier",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Gentle acid blend for starter chicks to support early gut acidification and immune vigor.",
                "composition": "Citric Acid, Malic Acid, Sorbic Acid."
            },
            {
                "id": "PL-ACI-04",
                "title": "Water-Line Sanitizer Plus",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Dual-action acidifier and cleaner for nipples and drinking tanks at all stages.",
                "composition": "Acid Blend, Surfactants, Hydrogen Peroxide stabilizer."
            },
            {
                "id": "PL-ACI-05",
                "title": "Bio-Acid Poultry Shield",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Buffered organic acids for pathogenic inhibition during high-challenge periods.",
                "composition": "Sodium Formate, Organic Acids, Calcium Propionate."
            }
        ],
        "Ca & P Sources": [
            {
                "id": "PL-CAP-01",
                "title": "Egg-Shell-Cal D3",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "High-solubility calcium source for optimal eggshell formation and reduced breakage.",
                "composition": "Calcium Pidolate, Vitamin D3, Magnesium."
            },
            {
                "id": "PL-CAP-02",
                "title": "Avi-Phos High Bio P",
                "image": "assets/images/products/product-1-1.jpg",
                "description": "Mineral supplement to prevent perosis and skeletal issues in heavy broilers.",
                "composition": "Dicalcium Phosphate, Manganese, Zinc."
            },
            {
                "id": "PL-CAP-03",
                "title": "Layer-Phos Performance",
                "image": "assets/images/products/product-1-2.jpg",
                "description": "Optimized Ca:P ratio for maintainance and peak production in commercial layers.",
                "composition": "Limestone, Monocalcium Phosphate, Salt."
            },
            {
                "id": "PL-CAP-04",
                "title": "Bone-Growth Broiler Mineral",
                "image": "assets/images/products/product-1-3.jpg",
                "description": "Supports rapid skeletal development and prevents leg weakness in broiler strains.",
                "composition": "Tri-Calcium Phosphate, Zinc, Copper."
            },
            {
                "id": "PL-CAP-05",
                "title": "Liquid-Cal Poultry Drench",
                "image": "assets/images/products/product-1-4.jpg",
                "description": "Rapid calcium replacement for layers in early peak or following production stress.",
                "composition": "Calcium Gluconate, Vitamin D3, B-Complex."
            }
        ],
        "Growth Promoter": [
            {
                "id": "PL-GRO-01",
                "title": "Avi-Grow Phytogenic",
                "image": "assets/images/products/product-1-5.jpg",
                "description": "Natural appetite stimulant using plant essential oils to improve weight gain.",
                "composition": "Cinnamaldehyde, Carvacrol, Capsicum."
            },
            {
                "id": "PL-GRO-02",
                "title": "Poultry-Boost Probiotic",
                "image": "assets/images/products/product-1-6.jpg",
                "description": "Stabilizes intestinal microflora for better feed conversion and disease resistance.",
                "composition": "Bacillus subtilis, Yeast culture, FOS."
            },
            {
                "id": "PL-GRO-03",
                "title": "Chick-Start Energy Gel",
                "image": "assets/images/products/product-1-7.jpg",
                "description": "Rapid energy and nutrient boost for day-old chicks during transportation.",
                "composition": "Glucose, Electrolytes, Amino Acids, Vitamins."
            },
            {
                "id": "PL-GRO-04",
                "title": "Feather-Grow Amino Pak",
                "image": "assets/images/products/product-1-8.jpg",
                "description": "Specialized for rapid feathering and skin health in meat and breeder birds.",
                "composition": "Organic Trace Minerals, Biotin, Zinc Methionate."
            },
            {
                "id": "PL-GRO-05",
                "title": "Avi-Digest Multi-Enzyme",
                "image": "assets/images/products/product-1-9.jpg",
                "description": "Enzyme complex to release locked energy and phosphorus from plant-based diets.",
                "composition": "Amylase, Protease, Xylanase, Phytase."
            }
        ]
    }
};

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

// console.log(`Total products loaded: ${productsData.length}`);
