<?php

namespace Tests\Feature\Seeders;

use App\Models\Product;
use Database\Seeders\AnimalSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageSeedingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Route all writes to the public disk into an in-memory fake so the
        // test suite never litters the developer's storage directory.
        Storage::fake('public');
    }

    public function test_every_seeded_product_has_a_hero_image_on_disk(): void
    {
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $products = Product::all();
        $this->assertGreaterThan(100, $products->count());

        foreach ($products as $product) {
            $this->assertNotNull(
                $product->hero_image,
                "Product {$product->slug} has null hero_image",
            );
            $this->assertTrue(
                Storage::disk('public')->exists($product->hero_image),
                "Product {$product->slug} hero_image {$product->hero_image} not on the public disk",
            );
            $this->assertStringStartsWith('products/hero/', $product->hero_image);
        }
    }

    public function test_product_api_returns_storage_url_for_image(): void
    {
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $response = $this->getJson('/api/products?animal=cattle');
        $response->assertOk();

        $data = $response->json();
        $this->assertNotEmpty($data['items']);

        $firstImage = $data['items'][0]['image'];
        $this->assertIsString($firstImage);
        $this->assertStringContainsString('/storage/products/hero/', $firstImage);
    }

    public function test_reseeding_is_idempotent_and_keeps_existing_image_files(): void
    {
        $this->seed([
            AnimalSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $firstSnapshot = Product::pluck('hero_image', 'slug')->all();
        $this->assertNotEmpty($firstSnapshot);

        $this->seed(ProductSeeder::class);

        $secondSnapshot = Product::pluck('hero_image', 'slug')->all();
        $this->assertSame(
            $firstSnapshot,
            $secondSnapshot,
            'Re-seeding should preserve the original hero_image path when the file still exists on disk',
        );

        foreach ($secondSnapshot as $slug => $path) {
            $this->assertTrue(
                Storage::disk('public')->exists($path),
                "Re-seed dropped image for {$slug}",
            );
        }
    }
}
