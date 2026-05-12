<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_catalog_chrome_renders(): void
    {
        // The in-page detail labels (Back to Catalog, Description, etc.)
        // moved to the server-rendered /products/{slug} route, so the
        // listing-only catalog no longer emits them.
        $this->get('/products')
            ->assertOk()
            ->assertSee('Search for available products', false)
            ->assertSee('Sort by Default', false)
            ->assertSee('>Product Type ', false)
            ->assertSee('Animal Category', false)
            ->assertDontSee('Back to Catalog', false)
            ->assertDontSee('>Related Products</h4>', false);
    }

    public function test_overrides_replace_search_and_sort_labels(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'products')->first();
        $block = $page->blocks()->where('type', 'product-catalog')->first();
        $block->update([
            'data' => [
                'search_placeholder'  => 'Find a product...',
                'sort_default_label'  => 'Default Order',
                'sort_newest_label'   => 'Newest First',
            ],
        ]);

        $response = $this->get('/products')->assertOk();
        $response->assertSee('placeholder="Find a product..."', false);
        $response->assertSee('Default Order', false);
        $response->assertSee('Newest First', false);
        $response->assertDontSee('Search for available products', false);
    }

    public function test_overrides_replace_filter_titles(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'products')->first();
        $block = $page->blocks()->where('type', 'product-catalog')->first();
        $block->update([
            'data' => [
                'filter_type_title'   => 'Type of Product',
                'filter_animal_title' => 'Species',
                'cat_pigs_label'      => 'Pork',
            ],
        ]);

        $response = $this->get('/products')->assertOk();
        $response->assertSee('>Type of Product ', false);
        $response->assertSee('Species', false);
        $response->assertSee('>Pork</a>', false);
        $response->assertDontSee('>Swine/Pigs</a>', false);
    }

    public function test_animal_pages_inherit_their_own_overrides_per_block(): void
    {
        $this->seed(PageSeeder::class);

        // The cattle page has its own product-catalog block; overriding it
        // should NOT affect the products page's catalog rendering.
        $cattle = Page::where('slug', 'cattle')->first();
        $cattle->blocks()->where('type', 'product-catalog')->first()
            ->update(['data' => ['cat_cattle_label' => 'Premium Cattle']]);

        $cattleResponse   = $this->get('/cattle')->assertOk();
        $productsResponse = $this->get('/products')->assertOk();

        $cattleResponse->assertSee('Premium Cattle', false);
        $productsResponse->assertDontSee('Premium Cattle', false);
        $productsResponse->assertSee('>Cattle</a>', false);
    }
}
