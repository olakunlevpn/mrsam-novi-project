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

    public function test_hidden_block_removes_its_section_entirely(): void
    {
        // The "Visible on page" toggle controls frontend rendering: hiding a
        // block's DB row removes its whole section. Page blades gate each
        // section on $page->shouldRenderBlock(type), so neither the data
        // override nor the partial's hardcoded default renders when hidden.
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'pigs')->first();
        $block = $page->blocks()->where('type', 'breadcrumb-pigs')->first();
        $block->update([
            'is_visible' => false,
            'data'       => ['label' => 'Hidden Override'],
        ]);

        $response = $this->get('/pigs')->assertOk();
        // Section gone: neither the override nor the default breadcrumb shows.
        $response->assertDontSee('Hidden Override', false);
        $response->assertDontSee('Swine Solutions', false);
    }
}
