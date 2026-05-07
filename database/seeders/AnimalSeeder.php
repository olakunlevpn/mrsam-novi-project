<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $animals = [
            [
                'slug' => 'cattle',
                'name' => 'Cattle',
                'description' => 'Premium cattle feed additives designed to boost growth, milk production, and overall herd health for Nigerian farmers.',
                'hero_image' => 'assets/images/backgrounds/cows-green-field-sunny-day.jpg',
                'order_column' => 1,
            ],
            [
                'slug' => 'pigs',
                'name' => 'Pigs',
                'description' => 'Specialized pig feed additives formulated for optimal swine growth, health, and productivity on Nigerian farms.',
                'hero_image' => 'assets/images/backgrounds/selective-closeup-shot-pink-pigs-barn.jpg',
                'order_column' => 2,
            ],
            [
                'slug' => 'poultry',
                'name' => 'Poultry',
                'description' => 'Poultry feed additives engineered for healthier birds, better egg production, and improved broiler performance across Nigeria.',
                'hero_image' => 'assets/images/backgrounds/hens-factory-chicken-cages.jpg',
                'order_column' => 3,
            ],
        ];

        foreach ($animals as $row) {
            Animal::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
