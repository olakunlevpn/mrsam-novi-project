<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicPageRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/this-page-does-not-exist.html')->assertNotFound();
    }

    public function test_admin_created_page_renders_via_generic_shell(): void
    {
        $page = Page::create([
            'slug'         => 'promo-spring',
            'title'        => 'Spring Promotions',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);

        PageBlock::create([
            'page_id'      => $page->id,
            'type'         => 'hero',
            'data'         => [
                'subtitle' => 'PROMO SENTINEL',
                'headline' => 'Spring Sale Headline',
            ],
            'order_column' => 0,
            'is_visible'   => true,
        ]);

        $response = $this->get('/promo-spring.html');
        $response->assertOk();
        $response->assertSee('Spring Sale Headline', false);
        $response->assertSee('PROMO SENTINEL', false);
        // Generic shell uses page title for the <title> tag.
        $response->assertSee('<title>Spring Promotions', false);
    }

    public function test_draft_admin_page_returns_404(): void
    {
        Page::create([
            'slug'         => 'draft-only',
            'title'        => 'Draft Only',
            'layout'       => 'custom',
            'status'       => 'draft',
        ]);

        $this->get('/draft-only.html')->assertNotFound();
    }

    public function test_future_published_at_returns_404(): void
    {
        Page::create([
            'slug'         => 'future-page',
            'title'        => 'Scheduled',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get('/future-page.html')->assertNotFound();
    }

    public function test_named_routes_still_take_precedence(): void
    {
        // Even with an admin Page row at slug=about, /about.html must still
        // hit the named about route (which uses the dedicated pages.about
        // Blade with its specific SEO meta).
        Page::create([
            'slug'   => 'about',
            'title'  => 'Different About Title',
            'layout' => 'custom',
            'status' => 'published',
        ]);

        $response = $this->get('/about.html')->assertOk();
        // The dedicated pages.about Blade defines this title, not the DB row.
        $response->assertSee('About | Novi-Agro | Quality Livestock Solutions', false);
    }

    public function test_cms_shell_renders_seo_meta_from_db(): void
    {
        $page = Page::create([
            'slug'         => 'with-seo',
            'title'        => 'With SEO',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);
        $page->setSeo([
            'title'            => 'Custom SEO Title',
            'meta_description' => 'Custom SEO description.',
            'canonical_url'    => 'https://novi-agro.com/with-seo',
        ]);

        $response = $this->get('/with-seo.html')->assertOk();
        $response->assertSee('<title>Custom SEO Title', false);
        $response->assertSee('Custom SEO description.', false);
        $response->assertSee('canonical" href="https://novi-agro.com/with-seo"', false);
    }
}
