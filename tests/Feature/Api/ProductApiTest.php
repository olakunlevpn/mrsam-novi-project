<?php

namespace Tests\Feature\Api;

use Database\Seeders\AnimalSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([AnimalSeeder::class, ProductCategorySeeder::class, ProductSeeder::class]);
    }

    public function test_endpoint_returns_expected_shape(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertOk();
        $response->assertJsonStructure([
            'items' => [
                '*' => ['id', 'name', 'image', 'category', 'animal', 'description', 'composition', 'benefits', 'usage'],
            ],
            'totalItems',
            'totalPages',
            'currentPage',
            'itemsPerPage',
            'startItem',
            'endItem',
        ]);
        $this->assertSame(12, $response->json('itemsPerPage'));
        $this->assertGreaterThan(0, $response->json('totalItems'));
    }

    public function test_filters_by_animal_slug(): void
    {
        $response = $this->getJson('/api/products?animal=cattle');
        $response->assertOk();
        foreach ($response->json('items') as $item) {
            $this->assertSame('cattle', $item['animal']);
        }
    }

    public function test_filters_by_category_name(): void
    {
        // Use a category we know exists from seeding. Pick the first cattle category.
        $cattleAll = $this->getJson('/api/products?animal=cattle');
        $firstCategory = $cattleAll->json('items.0.category');
        $this->assertNotEmpty($firstCategory);

        $filtered = $this->getJson('/api/products?animal=cattle&type=' . urlencode($firstCategory));
        $filtered->assertOk();
        foreach ($filtered->json('items') as $item) {
            $this->assertSame($firstCategory, $item['category']);
        }
    }

    public function test_search_matches_name_and_description(): void
    {
        // Pull a name from the catalog
        $term = $this->getJson('/api/products')->json('items.0.name');
        $this->assertNotEmpty($term);

        $response = $this->getJson('/api/products?search=' . urlencode(substr($term, 0, 5)));
        $response->assertOk();
        $this->assertGreaterThan(0, $response->json('totalItems'));
    }

    public function test_sort_default_is_alphabetical(): void
    {
        $names = collect($this->getJson('/api/products?sort=default')->json('items'))->pluck('name');
        $sorted = $names->sortBy(fn ($n) => mb_strtolower($n))->values();
        $this->assertSame($sorted->all(), $names->all());
    }

    public function test_sort_z_a_reverses(): void
    {
        $names = collect($this->getJson('/api/products?sort=z-a')->json('items'))->pluck('name');
        $sorted = $names->sortByDesc(fn ($n) => mb_strtolower($n))->values();
        $this->assertSame($sorted->all(), $names->all());
    }

    public function test_pagination_clamps_to_last_page(): void
    {
        $response = $this->getJson('/api/products?page=9999');
        $response->assertOk();
        $totalPages = $response->json('totalPages');
        $this->assertSame($totalPages, $response->json('currentPage'));
        $this->assertGreaterThan(0, count($response->json('items')));
    }

    public function test_dedup_by_name(): void
    {
        $names = collect($this->getJson('/api/products?animal=all')->json('items'))->pluck('name');
        $this->assertSame($names->count(), $names->unique()->count(), 'Items contain duplicate names');
    }

    public function test_zero_results_returns_empty_items(): void
    {
        $response = $this->getJson('/api/products?search=' . urlencode('zzz_no_match_xyz_' . uniqid()));
        $response->assertOk();
        $this->assertSame(0, $response->json('totalItems'));
        $this->assertSame(0, $response->json('startItem'));
        $this->assertSame([], $response->json('items'));
    }
}
