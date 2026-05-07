<?php

namespace Tests\Feature\Seeders;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Database\Seeders\AnimalSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_animal_seeder_creates_three_canonical_animals(): void
    {
        $this->seed(AnimalSeeder::class);
        $this->assertSame(3, Animal::count());
        foreach (['cattle', 'pigs', 'poultry'] as $slug) {
            $this->assertTrue(Animal::where('slug', $slug)->exists(), "Missing animal: {$slug}");
        }
    }

    public function test_full_catalog_seed_chain(): void
    {
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->assertSame(3, Animal::count());
        $this->assertGreaterThan(0, ProductCategory::count());
        $this->assertGreaterThan(0, Product::count());

        // Every product must have a valid animal and category.
        $orphans = Product::query()
            ->whereNull('animal_id')
            ->orWhereNull('product_category_id')
            ->count();
        $this->assertSame(0, $orphans);
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
        $animalsAfterFirst = Animal::count();
        $categoriesAfterFirst = ProductCategory::count();
        $productsAfterFirst = Product::count();

        // Run again
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->assertSame($animalsAfterFirst, Animal::count());
        $this->assertSame($categoriesAfterFirst, ProductCategory::count());
        $this->assertSame($productsAfterFirst, Product::count());
    }
}
