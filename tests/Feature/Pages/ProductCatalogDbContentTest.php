<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The product-catalog block's control labels (search placeholder, sort options,
 * filter/category headings) render from page_blocks.data when present.
 */
class ProductCatalogDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_catalog_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'products')->first();
        $block = $page->blocks()->where('type', 'product-catalog')->first();
        $block->update([
            'data' => [
                'search_placeholder' => 'Find our feeds fast',
                'sort_default_label' => 'Order: Default',
                'filter_type_title'  => 'Feed Type',
            ],
        ]);

        $this->get('/products')
            ->assertOk()
            ->assertSee('Find our feeds fast', false)
            ->assertSee('Order: Default', false)
            ->assertSee('Feed Type', false)
            ->assertDontSee('Search for available products', false)
            ->assertDontSee('Sort by Default', false);
    }
}
