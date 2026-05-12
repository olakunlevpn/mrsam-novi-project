<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verify that SeoMeta values edited via the admin actually replace the
 * default <meta> / <title> / canonical tags emitted by head.blade.php.
 */
class PageSeoFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_meta_tags_render_when_no_seo_row_exists(): void
    {
        $this->seed(PageSeeder::class);

        $this->get('/about')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://novi-agro.com/" />', false)
            ->assertSee('<meta property="og:title" content="NOVI AGRO LTD | Quality Feeds - Healthy Life" />', false);
    }

    public function test_seo_meta_overrides_canonical_and_description(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('slug', 'about')->first();
        $page->setSeo([
            'title'            => 'About Us | Custom SEO Title',
            'meta_description' => 'Custom about-page description for search engines.',
            'canonical_url'    => 'https://novi-agro.com/about',
        ]);

        $response = $this->get('/about')->assertOk();
        $response->assertSee('<title>About Us | Custom SEO Title | Quality Feeds - Healthy Life</title>', false);
        $response->assertSee('<link rel="canonical" href="https://novi-agro.com/about" />', false);
        $response->assertSee('<meta name="description" content="Custom about-page description for search engines." />', false);
        // Default canonical / description must NOT appear.
        $response->assertDontSee('<link rel="canonical" href="https://novi-agro.com/" />', false);
    }

    public function test_seo_meta_overrides_open_graph_and_twitter_tags(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('slug', 'about')->first();
        $page->setSeo([
            'og_title'       => 'Custom OG Title',
            'og_description' => 'Custom OG description text.',
            'og_image'       => 'https://novi-agro.com/share-card.png',
            'canonical_url'  => 'https://novi-agro.com/about',
        ]);

        $response = $this->get('/about')->assertOk();
        $response->assertSee('<meta property="og:title" content="Custom OG Title" />', false);
        $response->assertSee('<meta property="og:description" content="Custom OG description text." />', false);
        $response->assertSee('<meta property="og:image" content="https://novi-agro.com/share-card.png" />', false);
        $response->assertSee('<meta property="og:url" content="https://novi-agro.com/about" />', false);
        $response->assertSee('<meta name="twitter:title" content="Custom OG Title" />', false);
    }

    public function test_noindex_emits_robots_directive(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('slug', 'about')->first();
        $page->setSeo([
            'meta_description' => 'Hidden page',
            'noindex'          => true,
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow" />', false);
    }

    public function test_robots_directive_overrides_when_not_noindex(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::where('slug', 'about')->first();
        $page->setSeo([
            'meta_description' => 'Visible',
            'noindex'          => false,
            'robots'           => 'index, nofollow',
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('<meta name="robots" content="index, nofollow" />', false)
            ->assertDontSee('<meta name="robots" content="noindex', false);
    }
}
