<?php

namespace Tests\Feature\Models;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageBlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_field_is_cast_to_array(): void
    {
        $page = Page::create(['title' => 'Test Page', 'status' => 'draft']);

        $block = PageBlock::create([
            'page_id' => $page->id,
            'type'    => 'hero',
            'data'    => ['headline' => 'Hi'],
        ]);

        $block = $block->fresh();

        $this->assertTrue(is_array($block->data));
        $this->assertSame('Hi', $block->data['headline']);
    }

    public function test_belongs_to_page(): void
    {
        $page = Page::create(['title' => 'Parent Page', 'status' => 'draft']);

        $block = PageBlock::create([
            'page_id' => $page->id,
            'type'    => 'hero',
        ]);

        $this->assertInstanceOf(Page::class, $block->page);
        $this->assertSame($page->id, $block->page->id);
    }

    public function test_cascade_delete_drops_blocks_with_page(): void
    {
        $page = Page::create(['title' => 'Cascade Test', 'status' => 'draft']);

        PageBlock::create(['page_id' => $page->id, 'type' => 'hero']);
        PageBlock::create(['page_id' => $page->id, 'type' => 'footer']);

        $this->assertSame(2, PageBlock::where('page_id', $page->id)->count());

        $page->delete();

        $this->assertSame(0, PageBlock::where('page_id', $page->id)->count());
    }
}
