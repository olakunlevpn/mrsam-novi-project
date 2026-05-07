<?php

namespace Tests\Feature\Seeders;

use App\Models\Page;
use App\Models\PageBlock;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_nine_pages(): void
    {
        $this->seed(PageSeeder::class);
        $this->assertSame(9, Page::count());
        foreach (['home', 'about', 'products', 'cattle', 'pigs', 'poultry', 'services', 'contact', 'faq'] as $slug) {
            $this->assertTrue(Page::where('slug', $slug)->exists(), "Missing page: {$slug}");
        }
    }

    public function test_only_home_is_marked_homepage(): void
    {
        $this->seed(PageSeeder::class);
        $homepages = Page::where('is_homepage', true)->get();
        $this->assertCount(1, $homepages);
        $this->assertSame('home', $homepages->first()->slug);
    }

    public function test_home_page_has_expected_block_types_in_order(): void
    {
        $this->seed(PageSeeder::class);
        $home = Page::where('slug', 'home')->first();
        $types = $home->blocks()->orderBy('order_column')->pluck('type')->all();
        $this->assertSame(
            ['hero', 'feature-grid', 'about-intro', 'species-cards', 'services-summary', 'work-process', 'benefits', 'stats-bar', 'cta-booking', 'partners-carousel'],
            $types
        );
    }

    public function test_all_pages_are_published(): void
    {
        $this->seed(PageSeeder::class);
        $this->assertSame(9, Page::published()->count());
    }

    public function test_seeder_is_idempotent_on_pages(): void
    {
        $this->seed(PageSeeder::class);
        $pageCountFirst = Page::count();
        $blockCountFirst = PageBlock::count();

        $this->seed(PageSeeder::class);
        $this->assertSame($pageCountFirst, Page::count());
        $this->assertSame($blockCountFirst, PageBlock::count());
    }

    public function test_blocks_are_visible_by_default(): void
    {
        $this->seed(PageSeeder::class);
        $this->assertSame(0, PageBlock::where('is_visible', false)->count());
    }
}
