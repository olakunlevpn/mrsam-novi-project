<?php

namespace Tests\Feature\Concerns;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasBlocksTest extends TestCase
{
    use RefreshDatabase;

    private function buildPageWithBlocks(): Page
    {
        $page = Page::create(['title' => 'Has Blocks Test', 'status' => 'published']);

        // First hero (visible)
        PageBlock::create([
            'page_id'      => $page->id,
            'type'         => 'hero',
            'data'         => ['headline' => 'Welcome', 'subheadline' => 'Sub'],
            'order_column' => 0,
            'is_visible'   => true,
        ]);

        // Feature grid (visible)
        PageBlock::create([
            'page_id'      => $page->id,
            'type'         => 'feature-grid',
            'data'         => ['items' => [1, 2, 3]],
            'order_column' => 1,
            'is_visible'   => true,
        ]);

        // Second hero (visible)
        PageBlock::create([
            'page_id'      => $page->id,
            'type'         => 'hero',
            'data'         => ['headline' => 'Second Hero'],
            'order_column' => 2,
            'is_visible'   => true,
        ]);

        // Hidden hero — must NOT appear in blocksOfType
        PageBlock::create([
            'page_id'      => $page->id,
            'type'         => 'hero',
            'data'         => ['headline' => 'Hidden Hero'],
            'order_column' => 3,
            'is_visible'   => false,
        ]);

        return $page->fresh('blocks');
    }

    public function test_blocks_of_type_returns_visible_only(): void
    {
        $page = $this->buildPageWithBlocks();

        $this->assertSame(2, $page->blocksOfType('hero')->count());
    }

    public function test_block_data_returns_first_block_data(): void
    {
        $page = $this->buildPageWithBlocks();

        $this->assertSame('Welcome', $page->blockData('hero')['headline']);
    }

    public function test_block_with_index_returns_specific(): void
    {
        $page = $this->buildPageWithBlocks();

        $this->assertSame('Second Hero', $page->blockData('hero', 1)['headline']);
    }

    public function test_block_helper_with_default(): void
    {
        $page = $this->buildPageWithBlocks();

        $this->assertSame('Welcome', $page->block('hero', 'headline', 'Default'));
        $this->assertSame('X', $page->block('hero', 'nonexistent', 'X'));
    }
}
