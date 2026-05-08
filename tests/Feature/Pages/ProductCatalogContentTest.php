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
        $this->get('/products.html')
            ->assertOk()
            ->assertSee('Search for available products', false)
            ->assertSee('Sort by Default', false)
            ->assertSee('>Product Type ', false)
            ->assertSee('Animal Category', false)
            ->assertSee('Back to Catalog', false)
            ->assertSee('>Description</h5>', false)
            ->assertSee('>Composition</h5>', false)
            ->assertSee('Typical Benefits', false)
            ->assertSee('Usage &amp; Directions', false)
            ->assertSee('>Enquire Now</span>', false)
            ->assertSee('>Related Products</h4>', false);
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

        $response = $this->get('/products.html')->assertOk();
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

        $response = $this->get('/products.html')->assertOk();
        $response->assertSee('>Type of Product ', false);
        $response->assertSee('Species', false);
        $response->assertSee('>Pork</a>', false);
        $response->assertDontSee('>Swine/Pigs</a>', false);
    }

    public function test_overrides_replace_detail_view_labels(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'cattle')->first();
        $block = $page->blocks()->where('type', 'product-catalog')->first();
        $block->update([
            'data' => [
                'back_label'                => 'Browse all',
                'detail_description_label'  => 'Overview',
                'detail_benefits_label'     => 'Why this product',
                'enquire_label'             => 'Request a quote',
                'related_label'             => 'You might also like',
            ],
        ]);

        $response = $this->get('/cattle.html')->assertOk();
        $response->assertSee('Browse all', false);
        $response->assertSee('>Overview</h5>', false);
        $response->assertSee('Why this product', false);
        $response->assertSee('Request a quote', false);
        $response->assertSee('You might also like', false);
        $response->assertDontSee('>Description</h5>', false);
        $response->assertDontSee('>Related Products</h4>', false);
    }

    public function test_animal_pages_inherit_their_own_overrides_per_block(): void
    {
        $this->seed(PageSeeder::class);

        // The cattle page has its own product-catalog block; overriding it
        // should NOT affect the products page's catalog rendering.
        $cattle = Page::where('slug', 'cattle')->first();
        $cattle->blocks()->where('type', 'product-catalog')->first()
            ->update(['data' => ['cat_cattle_label' => 'Premium Cattle']]);

        $cattleResponse   = $this->get('/cattle.html')->assertOk();
        $productsResponse = $this->get('/products.html')->assertOk();

        $cattleResponse->assertSee('Premium Cattle', false);
        $productsResponse->assertDontSee('Premium Cattle', false);
        $productsResponse->assertSee('>Cattle</a>', false);
    }
}
