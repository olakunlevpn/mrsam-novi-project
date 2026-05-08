<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use App\Models\SeoMeta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageSeoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_creating_page_persists_seo_meta_through_morph_relation(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreatePage::class)
            ->fillForm([
                'title'        => 'Promo',
                'slug'         => 'promo',
                'layout'       => 'custom',
                'status'       => 'published',
                'is_homepage'  => false,
                'order_column' => 0,
                'seoMeta'      => [
                    'title'            => 'Spring Promo | Novi Agro',
                    'meta_description' => 'Limited-time spring promo on premium feed additives.',
                    'canonical_url'    => 'https://novi-agro.com/promo',
                    'og_title'         => 'Spring Promo',
                    'og_description'   => 'Save on spring feed deals.',
                    'og_image'         => 'https://novi-agro.com/promo.png',
                    'noindex'          => false,
                    'robots'           => 'index, follow',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $page = Page::where('slug', 'promo')->first();
        $this->assertNotNull($page);

        $seo = $page->seoMeta;
        $this->assertInstanceOf(SeoMeta::class, $seo);
        $this->assertSame('Spring Promo | Novi Agro', $seo->title);
        $this->assertSame('Limited-time spring promo on premium feed additives.', $seo->meta_description);
        $this->assertSame('https://novi-agro.com/promo', $seo->canonical_url);
        $this->assertFalse($seo->noindex);
    }

    public function test_editing_page_updates_existing_seo_row(): void
    {
        $page = Page::create([
            'slug'   => 'existing',
            'title'  => 'Existing',
            'layout' => 'custom',
            'status' => 'draft',
        ]);

        $page->setSeo([
            'title'            => 'Old Title',
            'meta_description' => 'Old description',
            'noindex'          => false,
        ]);

        $this->assertSame(1, SeoMeta::where('seoable_type', Page::class)->where('seoable_id', $page->id)->count());

        Livewire::actingAs($this->admin)
            ->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'seoMeta' => [
                    'title'            => 'New Title',
                    'meta_description' => 'New description',
                    'noindex'          => true,
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Still exactly one SEO row (updated, not duplicated).
        $this->assertSame(1, SeoMeta::where('seoable_type', Page::class)->where('seoable_id', $page->id)->count());

        $seo = $page->fresh()->seoMeta;
        $this->assertSame('New Title', $seo->title);
        $this->assertSame('New description', $seo->meta_description);
        $this->assertTrue($seo->noindex);
    }
}
