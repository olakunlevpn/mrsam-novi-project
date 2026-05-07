<?php

namespace Tests\Feature\Models;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function makeAnimalAndCategory(): array
    {
        $animal = Animal::create(['name' => 'Poultry']);
        $category = ProductCategory::create([
            'animal_id' => $animal->id,
            'name' => 'Concentrates',
        ]);

        return [$animal, $category];
    }

    public function test_creates_product_with_auto_slug_and_relations(): void
    {
        [$animal, $category] = $this->makeAnimalAndCategory();

        $product = Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Broiler Concentrate 40',
        ]);

        $this->assertSame('broiler-concentrate-40', $product->slug);
        $this->assertInstanceOf(Animal::class, $product->animal);
        $this->assertInstanceOf(ProductCategory::class, $product->productCategory);
        $this->assertSame($animal->id, $product->animal->id);
        $this->assertSame($category->id, $product->productCategory->id);
    }

    public function test_published_scope(): void
    {
        [$animal, $category] = $this->makeAnimalAndCategory();

        Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Product Alpha',
            'status' => 'published',
        ]);

        Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Product Beta',
            'status' => 'published',
        ]);

        Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Product Draft',
            'status' => 'draft',
        ]);

        $this->assertSame(2, Product::published()->count());
    }

    public function test_route_key_is_slug(): void
    {
        $this->assertSame('slug', (new Product)->getRouteKeyName());
    }

    public function test_status_default_published(): void
    {
        [$animal, $category] = $this->makeAnimalAndCategory();

        $product = Product::create([
            'animal_id' => $animal->id,
            'product_category_id' => $category->id,
            'name' => 'Default Status Product',
        ]);

        $this->assertSame('published', $product->status);
    }
}
