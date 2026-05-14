<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AnimalSeeder extends Seeder
{
    private const HERO_DESTINATION = 'animals/hero';

    public function run(): void
    {
        $animals = [
            [
                'slug'         => 'cattle',
                'name'         => 'Cattle',
                'description'  => 'Premium cattle feed additives designed to boost growth, milk production, and overall herd health for Nigerian farmers.',
                'hero_source'  => 'assets/images/backgrounds/cows-green-field-sunny-day.jpg',
                'order_column' => 1,
            ],
            [
                'slug'         => 'pigs',
                'name'         => 'Pigs',
                'description'  => 'Specialized pig feed additives formulated for optimal swine growth, health, and productivity on Nigerian farms.',
                'hero_source'  => 'assets/images/backgrounds/selective-closeup-shot-pink-pigs-barn.jpg',
                'order_column' => 2,
            ],
            [
                'slug'         => 'poultry',
                'name'         => 'Poultry',
                'description'  => 'Poultry feed additives engineered for healthier birds, better egg production, and improved broiler performance across Nigeria.',
                'hero_source'  => 'assets/images/backgrounds/hens-factory-chicken-cages.jpg',
                'order_column' => 3,
            ],
        ];

        foreach ($animals as $row) {
            $row['hero_image'] = $this->prepareHero($row['slug'], $row['hero_source']);
            unset($row['hero_source']);

            Animal::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    /**
     * Copy the bundled background onto the public disk so the saved value
     * mirrors a real Filament FileUpload result. Idempotent: an existing
     * destination is reused. Returns null if the source is missing — the
     * blade fallback then takes over.
     */
    private function prepareHero(string $slug, string $source): ?string
    {
        $absolute = public_path($source);
        $destPath = self::HERO_DESTINATION . '/' . $slug . '-' . basename($source);

        if (Storage::disk('public')->exists($destPath)) {
            return $destPath;
        }

        if (! File::exists($absolute)) {
            return null;
        }

        Storage::disk('public')->put($destPath, File::get($absolute));

        return $destPath;
    }
}
