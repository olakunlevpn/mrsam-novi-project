<?php

namespace Tests\Feature\View\Components;

use App\Cms\BlockRegistry;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The PageBlocks component renders a Page's visible blocks in order.
 * Tests use a temporary block type wired to a tiny ad-hoc view to
 * keep assertions independent of the real site partials.
 */
class PageBlocksTest extends TestCase
{
    use RefreshDatabase;

    private function registerSentinelBlocks(): void
    {
        $registry = app(BlockRegistry::class);
        $registry->register('test-sentinel', 'sentinel', 'Test Sentinel', 'Tests');
        $registry->register('test-other',    'other',    'Test Other',    'Tests');

        // Add the test fixtures directory to the view finder so plain
        // 'sentinel' and 'other' resolve to our minimal Blade files.
        view()->getFinder()->prependLocation(__DIR__ . '/__views');
    }

    protected function tearDown(): void
    {
        // Reset the singleton so altered registrations do not leak into other tests.
        $this->app->forgetInstance(BlockRegistry::class);
        parent::tearDown();
    }

    public function test_renders_visible_blocks_in_order_using_registry_views(): void
    {
        $this->registerSentinelBlocks();

        $page = Page::create(['title' => 'Render Test', 'status' => 'published']);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-sentinel', 'data' => ['msg' => 'first'],  'order_column' => 0]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-other',    'data' => ['msg' => 'middle'], 'order_column' => 1]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-sentinel', 'data' => ['msg' => 'last'],   'order_column' => 2]);

        $page = $page->fresh('visibleBlocks');

        $html = view('wrapper', ['page' => $page])->render();

        $this->assertStringContainsString('SENTINEL:first', $html);
        $this->assertStringContainsString('OTHER:middle',   $html);
        $this->assertStringContainsString('SENTINEL:last',  $html);

        // Ensure correct order
        $this->assertLessThan(strpos($html, 'OTHER:middle'),   strpos($html, 'SENTINEL:first'));
        $this->assertLessThan(strpos($html, 'SENTINEL:last'),  strpos($html, 'OTHER:middle'));
    }

    public function test_skips_hidden_blocks(): void
    {
        $this->registerSentinelBlocks();

        $page = Page::create(['title' => 'Hidden Test', 'status' => 'published']);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-sentinel', 'data' => ['msg' => 'visible'],  'order_column' => 0, 'is_visible' => true]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-sentinel', 'data' => ['msg' => 'hidden'],   'order_column' => 1, 'is_visible' => false]);

        $page = $page->fresh('visibleBlocks');

        $html = view('wrapper', ['page' => $page])->render();

        $this->assertStringContainsString('SENTINEL:visible', $html);
        $this->assertStringNotContainsString('SENTINEL:hidden', $html);
    }

    public function test_silently_skips_blocks_with_unknown_type(): void
    {
        $this->registerSentinelBlocks();

        $page = Page::create(['title' => 'Unknown Test', 'status' => 'published']);
        PageBlock::create(['page_id' => $page->id, 'type' => 'no-such-block', 'data' => ['msg' => 'should-not-render'], 'order_column' => 0]);
        PageBlock::create(['page_id' => $page->id, 'type' => 'test-sentinel', 'data' => ['msg' => 'should-render'],     'order_column' => 1]);

        $page = $page->fresh('visibleBlocks');

        $html = view('wrapper', ['page' => $page])->render();

        $this->assertStringContainsString('SENTINEL:should-render', $html);
        $this->assertStringNotContainsString('should-not-render', $html);
    }
}
