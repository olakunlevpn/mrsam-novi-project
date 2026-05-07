<?php

namespace Tests\Feature\Models;

use App\Models\Animal;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_category_with_auto_slug(): void
    {
        $category = ProductCategory::create(['name' => 'Toxin Binder']);

        $this->assertSame('toxin-binder', $category->slug);
    }

    public function test_slug_unique_per_animal(): void
    {
        $cattle = Animal::create(['name' => 'Cattle']);
        $pigs = Animal::create(['name' => 'Pigs']);

        $cattlePremix = ProductCategory::create([
            'animal_id' => $cattle->id,
            'name' => 'Premixes',
        ]);

        $pigsPremix = ProductCategory::create([
            'animal_id' => $pigs->id,
            'name' => 'Premixes',
        ]);

        // Same name allowed under different animals — both slugs should be 'premixes'
        $this->assertSame('premixes', $cattlePremix->slug);
        $this->assertSame('premixes', $pigsPremix->slug);

        // Same name under same animal should get a suffixed slug
        $duplicate = ProductCategory::create([
            'animal_id' => $cattle->id,
            'name' => 'Premixes',
        ]);

        $this->assertNotSame('premixes', $duplicate->slug);
        $this->assertStringStartsWith('premixes', $duplicate->slug);
    }

    public function test_belongs_to_animal_nullable(): void
    {
        $category = ProductCategory::create([
            'animal_id' => null,
            'name' => 'Cross Animal Mix',
        ]);

        $this->assertNotNull($category->id);
        $this->assertNull($category->animal_id);
        $this->assertNull($category->animal);
    }
}
