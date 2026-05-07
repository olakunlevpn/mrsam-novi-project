<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
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

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'animal_id' => $animalId,
                    'product_category_id' => $categoryId,
                    'name' => $row['name'],
                    'sku' => $sku,
                    'hero_image' => isset($row['image']) ? ltrim($row['image'], '/') : null,
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
}
