<?php

namespace Tests\Feature\Models;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimalTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_animal_with_auto_slug(): void
    {
        $animal = Animal::create(['name' => 'Cattle']);

        $this->assertSame('cattle', $animal->slug);
    }

    public function test_route_key_is_slug(): void
    {
        $this->assertSame('slug', (new Animal)->getRouteKeyName());
    }

    public function test_has_products_relation(): void
    {
        $animal = Animal::create(['name' => 'Pigs']);

        $category = ProductCategory::create([
            'animal_id' => $animal->id,
            'name' => 'Premixes',
        ]);

        Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Pig Premix A',
        ]);

        Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Pig Premix B',
        ]);

        $this->assertCount(2, $animal->products);
    }
}
