<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/products.json');
        if (! is_file($jsonPath)) {
            $this->command->error("Missing {$jsonPath} - run export script first.");
            return;
        }

        $rows = json_decode(file_get_contents($jsonPath), true, flags: JSON_THROW_ON_ERROR);

        $animalIdsBySlug = Animal::pluck('id', 'slug')->all();

        $seen = [];
        foreach ($rows as $row) {
            $animalSlug = strtolower($row['animal']);
            $categoryName = $row['category'];
            $key = $animalSlug . '|' . $categoryName;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $animalId = $animalIdsBySlug[$animalSlug] ?? null;
            if (! $animalId) {
                $this->command->warn("Skipping category '{$categoryName}' - unknown animal '{$animalSlug}'");
                continue;
            }

            ProductCategory::updateOrCreate(
                [
                    'animal_id' => $animalId,
                    'slug' => Str::slug($categoryName),
                ],
                [
                    'name' => $categoryName,
                    'description' => null,
                    'order_column' => 0,
                ],
            );
        }
    }
}
