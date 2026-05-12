<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use App\Models\PageBlock;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verify that the page-header and breadcrumb partials read DB values via
 * $page->block(...) accessors, falling back to the original defaults when
 * no DB row (or no `data` payload) is present.
 */
class HeaderBreadcrumbDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_header_renders_default_when_no_db_row(): void
    {
        // No PageSeeder. The transient stub has no blocks; defaults render.
        $this->get('/cattle')
            ->assertOk()
            ->assertSee('page-header__title">Cattle', false)
            ->assertSee('/assets/images/backgrounds/cows-green-field-sunny-day.jpg', false);
    }

    public function test_breadcrumb_renders_default_when_no_db_row(): void
    {
        $this->get('/cattle')
            ->assertOk()
            ->assertSee('Livestock Solutions', false);
    }

    public function test_page_header_uses_db_data_when_present(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'cattle')->first();
        $block = $page->blocks()->where('type', 'page-header-cattle')->first();
        $block->update([
            'data' => [
                'title'            => 'Custom Cattle Heading',
                'background_image' => '/assets/images/backgrounds/custom-cattle.jpg',
            ],
        ]);

        $this->get('/cattle')
            ->assertOk()
            ->assertSee('Custom Cattle Heading', false)
            ->assertSee('/assets/images/backgrounds/custom-cattle.jpg', false)
            // Original default must NOT render
            ->assertDontSee('page-header__title">Cattle<', false);
    }

    public function test_breadcrumb_uses_db_data_when_present(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'pigs')->first();
        $block = $page->blocks()->where('type', 'breadcrumb-pigs')->first();
        $block->update(['data' => ['label' => 'Pork Solutions']]);

        $this->get('/pigs')
            ->assertOk()
            ->assertSee('Pork Solutions', false)
            ->assertDontSee('Swine Solutions', false);
    }

    public function test_hidden_blocks_are_not_rendered_via_visibleblocks_filter(): void
    {
        // Sanity: visibleBlocks filtering doesn't apply to legacy @include calls.
        // The current page Blade @includes the partial unconditionally; toggling
        // is_visible on the DB row hides only data overrides, not the partial.
        // This test pins down the current behavior: defaults still render
        // even when the DB row is hidden, because @include is static.
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'pigs')->first();
        $block = $page->blocks()->where('type', 'breadcrumb-pigs')->first();
        $block->update([
            'is_visible' => false,
            'data'       => ['label' => 'Hidden Override'],
        ]);

        $response = $this->get('/pigs')->assertOk();
        // Hidden block's data is filtered out by HasBlocks::blocksOfType,
        // so the partial falls back to its hardcoded default.
        $response->assertSee('Swine Solutions', false);
        $response->assertDontSee('Hidden Override', false);
    }
}
