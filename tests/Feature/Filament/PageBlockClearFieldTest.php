<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\User;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Clearing a typed block field in admin must remove it from the site rather
 * than silently reverting to the Blade default. Filament omits emptied fields
 * from the dehydrated state, so packDataForSave backfills cleared scalar keys
 * with '' — block() then renders empty instead of falling back to the default.
 */
class PageBlockClearFieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_clearing_a_field_hides_it_and_keeps_others(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $defaults = (new \ReflectionClass(PageSeeder::class))
            ->getReflectionConstant('BLOCK_DEFAULTS')->getValue()['about-intro'];

        $page = Page::create([
            'slug' => 'home', 'title' => 'Home', 'layout' => 'home',
            'status' => 'published', 'is_homepage' => true, 'published_at' => now(),
        ]);
        $block = PageBlock::create([
            'page_id' => $page->id, 'type' => 'about-intro', 'order_column' => 0,
            'is_visible' => true, 'data' => $defaults,
        ]);

        $component = Livewire::actingAs($admin)->test(EditPage::class, ['record' => $page->getRouteKey()]);
        $itemKey = array_key_first($component->get('data')['blocks']);

        $component->set("data.blocks.{$itemKey}.paragraph_2", '')
            ->call('save')
            ->assertHasNoFormErrors();

        // Cleared field persists as '' (present), not absent.
        $this->assertSame('', $block->fresh()->data['paragraph_2']);

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('comprehensive range of high-quality livestock feeds additives', $html);
        $this->assertStringContainsString('Novi Agro Nig. Ltd', $html);
    }

    public function test_saving_an_unseeded_block_keeps_its_blade_defaults(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $page = Page::create([
            'slug' => 'home', 'title' => 'Home', 'layout' => 'home',
            'status' => 'published', 'is_homepage' => true, 'published_at' => now(),
        ]);
        // Unseeded block (data null) — the real prod state before the seeder runs.
        $block = PageBlock::create([
            'page_id' => $page->id, 'type' => 'about-intro', 'order_column' => 0,
            'is_visible' => true, 'data' => null,
        ]);

        $component = Livewire::actingAs($admin)->test(EditPage::class, ['record' => $page->getRouteKey()]);
        $component->call('save')->assertHasNoFormErrors();

        // Saving must not backfill empty keys onto an unseeded block, which
        // would blank its Blade defaults on the site.
        $this->assertEmpty($block->fresh()->data ?? []);
        $this->get('/')->assertOk()
            ->assertSee('committed to revolutionizing the agricultural landscape', false);
    }
}
