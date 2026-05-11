<?php

namespace Tests\Feature\Seo;

use App\Http\Controllers\SitemapController;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\View\Composers\SiteComposer;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteComposer::clearCache();
        SitemapController::forget();
    }

    public function test_blog_show_emits_article_jsonld(): void
    {
        $post = Post::factory()->published()->create([
            'title'   => 'Sample SEO Article',
            'excerpt' => 'A short excerpt about the article.',
        ]);

        $response = $this->get(route('blog.show', $post));

        $response->assertOk();
        $response->assertSee('"@type":"Article"', false);
        $response->assertSee('Sample SEO Article', false);
        $response->assertSee('"datePublished"', false);
    }

    public function test_blog_show_emits_breadcrumb_jsonld(): void
    {
        $post = Post::factory()->published()->create([
            'title' => 'Breadcrumb Post',
        ]);

        $response = $this->get(route('blog.show', $post));

        $response->assertOk();
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertSee('Breadcrumb Post', false);
    }

    public function test_about_emits_breadcrumb_jsonld(): void
    {
        $response = $this->get('/about.html');

        $response->assertOk();
        $response->assertSee('"@type":"BreadcrumbList"', false);
        // Home + About entries.
        $response->assertSee('"name":"Home"', false);
        $response->assertSee('"name":"About"', false);
    }

    public function test_seo_meta_overrides_default_title_on_page(): void
    {
        $page = Page::create([
            'slug'         => 'seo-title-page',
            'title'        => 'Untouched Title',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);
        $page->setSeo(['title' => 'Custom SEO Title']);

        $response = $this->get('/seo-title-page.html');

        $response->assertOk();
        $response->assertSee('<title>Custom SEO Title', false);
    }

    public function test_canonical_url_renders_when_set(): void
    {
        $page = Page::create([
            'slug'         => 'canonical-page',
            'title'        => 'Canonical Page',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);
        $page->setSeo(['canonical_url' => 'https://other.test/canonical-page']);

        $response = $this->get('/canonical-page.html');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="https://other.test/canonical-page"', false);
    }

    public function test_noindex_flag_renders_meta_robots(): void
    {
        $page = Page::create([
            'slug'         => 'noindex-page',
            'title'        => 'Noindex Page',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);
        $page->setSeo(['noindex' => true]);

        $response = $this->get('/noindex-page.html');

        $response->assertOk();
        $response->assertSee('<meta name="robots" content="noindex', false);
    }

    public function test_sitemap_xml_returns_xml_with_published_urls(): void
    {
        $page = Page::create([
            'slug'         => 'sitemap-page',
            'title'        => 'Sitemap Page',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringStartsWith('application/xml', $response->headers->get('Content-Type'));
        $body = $response->getContent();
        $this->assertStringContainsString('<urlset', $body);
        $this->assertStringContainsString(route('home'), $body);
        $this->assertStringContainsString('/sitemap-page.html', $body);
    }

    public function test_sitemap_excludes_noindex_pages(): void
    {
        $hidden = Page::create([
            'slug'         => 'hidden-page',
            'title'        => 'Hidden Page',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);
        $hidden->setSeo(['noindex' => true]);

        $visible = Page::create([
            'slug'         => 'visible-page',
            'title'        => 'Visible Page',
            'layout'       => 'custom',
            'status'       => 'published',
            'published_at' => now()->subHour(),
        ]);

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('/visible-page.html', $body);
        $this->assertStringNotContainsString('/hidden-page.html', $body);
    }

    public function test_sitemap_includes_published_blog_posts_and_excludes_drafts(): void
    {
        $published = Post::factory()->published()->create(['slug' => 'live-post']);
        $draft     = Post::factory()->create(['slug' => 'draft-post', 'status' => 'draft']);

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('/blog/live-post', $body);
        $this->assertStringNotContainsString('/blog/draft-post', $body);
    }

    public function test_robots_txt_returns_default_when_no_setting(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $this->assertStringStartsWith('text/plain', $response->headers->get('Content-Type'));
        $body = $response->getContent();
        $this->assertStringContainsString('User-agent: *', $body);
        $this->assertStringContainsString('Sitemap: ' . route('sitemap'), $body);
    }

    public function test_robots_txt_uses_settings_override(): void
    {
        $custom = "User-agent: Googlebot\nDisallow: /private\n";
        Setting::set('seo.robots_txt', $custom, 'seo');

        $body = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertSame($custom, $body);
    }

    public function test_saving_a_post_invalidates_sitemap_cache(): void
    {
        // Prime cache.
        $first = $this->get('/sitemap.xml')->getContent();

        // Create a published post AFTER first render.
        $post = Post::factory()->published()->create(['slug' => 'fresh-news']);

        // Sitemap must include the new URL on next request (cache busted).
        $second = $this->get('/sitemap.xml')->getContent();

        $this->assertStringNotContainsString('fresh-news', $first);
        $this->assertStringContainsString('fresh-news', $second);
    }

    public function test_saving_a_page_invalidates_sitemap_cache(): void
    {
        $first = $this->get('/sitemap.xml')->getContent();

        $page = Page::create([
            'slug' => 'fresh-page',
            'title' => 'Fresh',
            'layout' => 'custom',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $second = $this->get('/sitemap.xml')->getContent();

        $this->assertStringNotContainsString('fresh-page', $first);
        $this->assertStringContainsString('fresh-page', $second);
    }
}
