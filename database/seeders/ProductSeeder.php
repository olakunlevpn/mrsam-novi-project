<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Map from products.json category name to a real image file that
     * lives in public/assets/images/products/. Every category in the JSON
     * must resolve here so every seeded product gets a relevant fallback
     * when its row-level image path does not exist on disk.
     */
    private const CATEGORY_FALLBACKS = [
        'Premixes'                  => 'Namix.jpg',
        'Concentrates'              => 'Multipro.jpg',
        'Amino Acids'               => 'L-LysineHcl.jpg',
        'Enzymes'                   => 'Mannanase.jpg',
        'Ca & P Sources'            => 'DCP.jpg',
        'Mould Inhibitor'           => 'PF-Sorb+.jpg',
        'Growth Promoter'           => 'Salinomycin.jpg',
        'Toxin Binder'              => 'Primetox.jpg',
        'Acidifier'                 => 'Sodium-Bicarbonate.jpg',
        'Hepatoprotector'           => 'Hepatron.jpg',
        'Choline'                   => 'CholineChloride.jpg',
        'Coccidiostat'              => 'Salinomycin.jpg',
        'Emulsifier'                => 'Multipro.jpg',
        'Anti-Stress'               => 'Hepamix.jpg',
        'Pellet Binder'             => 'Naphyt.jpg',
        'Pigments/ Yolk Colourants' => 'Naphyt2.png',
    ];

    private const FINAL_FALLBACK = 'Namix.jpg';

    public function run(): void
    {
        $jsonPath = database_path('seeders/data/products.json');
        if (! is_file($jsonPath)) {
            $this->command->error("Missing {$jsonPath}");
            return;
        }

        $rows = json_decode(file_get_contents($jsonPath), true, flags: JSON_THROW_ON_ERROR);

        $animalIdsBySlug = Animal::pluck('id', 'slug')->all();

        // Categories indexed by "animal_id|category-slug"
        $categoryIdsByKey = [];
        foreach (ProductCategory::all() as $cat) {
            $categoryIdsByKey[$cat->animal_id . '|' . $cat->slug] = $cat->id;
        }

        $order = 0;
        foreach ($rows as $row) {
            $animalSlug = strtolower($row['animal']);
            $animalId = $animalIdsBySlug[$animalSlug] ?? null;
            if (! $animalId) {
                continue;
            }

            $categoryId = $categoryIdsByKey[$animalId . '|' . Str::slug($row['category'])] ?? null;
            if (! $categoryId) {
                $this->command->warn("Skipping product '{$row['name']}' - missing category '{$row['category']}' for animal '{$animalSlug}'");
                continue;
            }

            // Prefix animal slug so slug and SKU are unique across animals
            // (the source JS reuses the same ID codes for shared products e.g. CT-TOX-01
            // appears under cattle, pigs, and poultry).
            $rawId = $row['id'] ?: $row['name'];
            $slug = Str::slug($animalSlug . '-' . $rawId);
            $sku = $animalSlug . '-' . $row['id'];

            $heroImage = $this->prepareHeroImage($slug, $row);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'animal_id' => $animalId,
                    'product_category_id' => $categoryId,
                    'name' => $row['name'],
                    'sku' => $sku,
                    'hero_image' => $heroImage,
                    'description' => $row['description'] ?? null,
                    'composition' => $row['composition'] ?? null,
                    'benefits' => ($row['benefits'] ?? '') ?: null,
                    'usage_instructions' => ($row['usage'] ?? '') ?: null,
                    'status' => 'published',
                    'order_column' => $order++,
                ],
            );
        }
    }

    /**
     * Copy a real image into the public disk under products/hero/ and
     * return the relative path Filament's FileUpload expects.
     *
     * Re-runnable: if the product already has a hero_image path that
     * exists on the public disk, keep it as-is.
     */
    private function prepareHeroImage(string $slug, array $row): ?string
    {
        $existing = Product::where('slug', $slug)->value('hero_image');
        if ($existing && Storage::disk('public')->exists($existing)) {
            return $existing;
        }

        $sourcePath = $this->resolveSource($row);
        if ($sourcePath === null) {
            return null;
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
        $destPath = 'products/hero/' . $slug . '-' . Str::random(8) . '.' . $extension;

        Storage::disk('public')->put($destPath, File::get($sourcePath));

        return $destPath;
    }

    /**
     * Resolve the absolute source path for a row's image. Prefer the
     * exact file referenced in products.json; fall back to a curated
     * per-category image; finally fall back to a generic image. Returns
     * null only when even the final fallback is missing on disk.
     */
    private function resolveSource(array $row): ?string
    {
        $candidates = [];

        if (! empty($row['image'])) {
            $candidates[] = public_path(ltrim($row['image'], '/'));
        }

        $categoryName = $row['category'] ?? '';
        $fallbackFile = self::CATEGORY_FALLBACKS[$categoryName] ?? self::FINAL_FALLBACK;
        $candidates[] = public_path('assets/images/products/' . $fallbackFile);
        $candidates[] = public_path('assets/images/products/' . self::FINAL_FALLBACK);

        foreach ($candidates as $candidate) {
            if (File::exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
