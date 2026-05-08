<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersCarouselTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_partners_render_when_no_db_array(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('/assets/images/resources/brand-logo1-1.png', false)
            ->assertSee('/assets/images/resources/brand-logo1-2.png', false)
            ->assertSee('/assets/images/resources/brand-logo1-3.png', false)
            ->assertSee('/assets/images/resources/brand-logo1-4.png', false);
    }

    public function test_partners_array_replaces_defaults(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'partners-carousel')->first();
        $block->update([
            'data' => [
                'title'    => 'Our Trusted Partners',
                'partners' => [
                    ['logo' => '/img/partner-a.png', 'url' => 'https://partner-a.com', 'alt' => 'Partner A'],
                    ['logo' => '/img/partner-b.png', 'url' => 'https://partner-b.com', 'alt' => 'Partner B'],
                ],
            ],
        ]);

        $response = $this->get('/')->assertOk();
        $response->assertSee('Our Trusted Partners', false);
        $response->assertSee('/img/partner-a.png', false);
        $response->assertSee('/img/partner-b.png', false);
        $response->assertSee('https://partner-a.com', false);
        $response->assertSee('alt="Partner A"', false);
        // Defaults must NOT appear.
        $response->assertDontSee('/assets/images/resources/brand-logo1-1.png', false);
        $response->assertDontSee('/assets/images/resources/brand-logo1-4.png', false);
    }

    public function test_partner_without_url_falls_back_to_products_route(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'partners-carousel')->first();
        $block->update([
            'data' => [
                'partners' => [
                    ['logo' => '/img/no-url.png'],
                ],
            ],
        ]);

        $response = $this->get('/')->assertOk();
        $response->assertSee('/img/no-url.png', false);
        $response->assertSee('href="' . route('products') . '"', false);
    }

    public function test_partner_with_simple_string_value_is_treated_as_logo(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'partners-carousel')->first();
        $block->update([
            'data' => [
                'partners' => ['/img/simple-1.png', '/img/simple-2.png'],
            ],
        ]);

        $response = $this->get('/')->assertOk();
        $response->assertSee('/img/simple-1.png', false);
        $response->assertSee('/img/simple-2.png', false);
    }

    public function test_empty_partners_array_falls_back_to_defaults(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'partners-carousel')->first();
        $block->update(['data' => ['partners' => []]]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/assets/images/resources/brand-logo1-1.png', false);
    }
}
