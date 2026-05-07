<?php

namespace Tests\Feature\Models;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_page_with_auto_slug(): void
    {
        $page = Page::create([
            'title'  => 'About Us',
            'status' => 'draft',
        ]);

        $this->assertSame('about-us', $page->slug);
    }

    public function test_route_key_is_slug(): void
    {
        $this->assertSame('slug', (new Page)->getRouteKeyName());
    }

    public function test_published_scope_excludes_draft(): void
    {
        Page::create(['title' => 'Published Page', 'status' => 'published']);
        Page::create(['title' => 'Draft Page', 'status' => 'draft']);

        $this->assertSame(1, Page::published()->count());
    }

    public function test_published_scope_includes_published_at_in_past(): void
    {
        // Published with past published_at — should be included
        Page::create([
            'title'        => 'Past Published',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);

        // Published with future published_at — should be excluded
        Page::create([
            'title'        => 'Future Published',
            'status'       => 'published',
            'published_at' => now()->addHour(),
        ]);

        $this->assertSame(1, Page::published()->count());
    }

    public function test_blocks_relation_returns_ordered_blocks(): void
    {
        $page = Page::create(['title' => 'Order Test', 'status' => 'draft']);

        PageBlock::create(['page_id' => $page->id, 'type' => 'hero',  'order_column' => 2]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'intro', 'order_column' => 0]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'cta',   'order_column' => 1]);

        $orders = $page->blocks()->get()->pluck('order_column')->all();

        $this->assertSame([0, 1, 2], $orders);
    }

    public function test_visible_blocks_excludes_hidden(): void
    {
        $page = Page::create(['title' => 'Visibility Test', 'status' => 'draft']);

        PageBlock::create(['page_id' => $page->id, 'type' => 'hero',   'is_visible' => true]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'footer', 'is_visible' => true]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'hidden', 'is_visible' => false]);

        $this->assertSame(2, $page->visibleBlocks()->count());
    }
}
