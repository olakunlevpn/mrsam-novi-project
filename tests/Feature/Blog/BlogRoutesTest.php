<?php

namespace Tests\Feature\Blog;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\View\Composers\SiteComposer;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteComposer::clearCache();
    }

    public function test_blog_index_returns_200_and_lists_published_posts(): void
    {
        $published = Post::factory()->count(3)->published()->create();
        $drafts    = Post::factory()->count(2)->create();

        $response = $this->get('/blog');

        $response->assertOk();
        foreach ($published as $post) {
            $response->assertSee($post->title, false);
        }
        foreach ($drafts as $post) {
            $response->assertDontSee($post->title, false);
        }
    }

    public function test_blog_index_paginates_at_ten(): void
    {
        // 15 posts with distinct, ordered published_at timestamps so we can
        // identify exactly which 10 belong on page 1 vs the 5 on page 2.
        $posts = collect(range(0, 14))->map(fn (int $i) => Post::factory()->published()->create([
            'title'        => "Pagination Post #{$i}",
            'published_at' => now()->subMinutes($i),
        ]));

        $response = $this->get('/blog');
        $response->assertOk();
        $response->assertSee('page=2', false);

        $body = $response->getContent();

        // First 10 titles (newest first) on page 1; last 5 not yet visible.
        $page1 = $posts->take(10);
        $page2 = $posts->slice(10);

        foreach ($page1 as $post) {
            $this->assertStringContainsString($post->title, $body,
                "Expected '{$post->title}' on page 1.");
        }
        foreach ($page2 as $post) {
            $this->assertStringNotContainsString($post->title, $body,
                "Did not expect '{$post->title}' on page 1.");
        }

        $page2Response = $this->get('/blog?page=2');
        $page2Response->assertOk();
        $page2Body = $page2Response->getContent();

        foreach ($page2 as $post) {
            $this->assertStringContainsString($post->title, $page2Body,
                "Expected '{$post->title}' on page 2.");
        }
    }

    public function test_blog_index_orders_posts_newest_first(): void
    {
        $oldest = Post::factory()->published()->create([
            'title'        => 'Oldest Insights Post',
            'published_at' => now()->subDays(3),
        ]);
        $middle = Post::factory()->published()->create([
            'title'        => 'Middle Insights Post',
            'published_at' => now()->subDays(1),
        ]);
        $newest = Post::factory()->published()->create([
            'title'        => 'Newest Insights Post',
            'published_at' => now()->subHours(1),
        ]);

        $body = $this->get('/blog')->assertOk()->getContent();

        $newestPos = strpos($body, $newest->title);
        $middlePos = strpos($body, $middle->title);
        $oldestPos = strpos($body, $oldest->title);

        $this->assertNotFalse($newestPos, 'Newest post should be rendered.');
        $this->assertNotFalse($middlePos, 'Middle post should be rendered.');
        $this->assertNotFalse($oldestPos, 'Oldest post should be rendered.');
        $this->assertLessThan($middlePos, $newestPos, 'Newest must appear before middle.');
        $this->assertLessThan($oldestPos, $middlePos, 'Middle must appear before oldest.');
    }

    public function test_blog_show_renders_published_post(): void
    {
        $post = Post::factory()->published()->create([
            'title' => 'A Glimpse Into Animal Nutrition',
            'body'  => '<p>Body of the published post about animal nutrition.</p>',
        ]);

        $response = $this->get('/blog/' . $post->slug);

        $response->assertOk();
        $response->assertSee('A Glimpse Into Animal Nutrition', false);
        $response->assertSee('Body of the published post about animal nutrition.', false);
    }

    public function test_blog_show_resolves_slug_starting_with_category_or_tag(): void
    {
        // Regression guard: a post whose slug starts with "category" or "tag"
        // (e.g. auto-slugged "Category Leaders in Animal Nutrition") must
        // still resolve, not be swallowed by the archive routes or the old
        // negative-lookahead constraint.
        $catPost = Post::factory()->published()->create([
            'title' => 'Category Leaders in Animal Nutrition',
        ]);
        $this->assertStringStartsWith('category-', $catPost->slug);

        $this->get('/blog/' . $catPost->slug)
            ->assertOk()
            ->assertSee('Category Leaders in Animal Nutrition', false);

        $tagPost = Post::factory()->published()->create([
            'title' => 'Tag Heuer of Premixes',
        ]);
        $this->assertStringStartsWith('tag-', $tagPost->slug);

        $this->get('/blog/' . $tagPost->slug)
            ->assertOk()
            ->assertSee('Tag Heuer of Premixes', false);
    }

    public function test_blog_show_returns_404_for_unpublished_post(): void
    {
        $post = Post::factory()->create([
            'status'       => 'draft',
            'published_at' => null,
        ]);

        $this->get('/blog/' . $post->slug)->assertNotFound();
    }

    public function test_blog_show_returns_404_for_future_published_post(): void
    {
        $post = Post::factory()->create([
            'status'       => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get('/blog/' . $post->slug)->assertNotFound();
    }

    public function test_blog_category_archive_shows_only_that_category(): void
    {
        $catA = PostCategory::create(['name' => 'Cattle Care']);
        $catB = PostCategory::create(['name' => 'Poultry Care']);

        $postsA = Post::factory()->count(2)->published()->create(['post_category_id' => $catA->id]);
        $postsB = Post::factory()->count(2)->published()->create(['post_category_id' => $catB->id]);

        $response = $this->get('/blog/category/' . $catA->slug);

        $response->assertOk();
        foreach ($postsA as $post) {
            $response->assertSee($post->title, false);
        }
        foreach ($postsB as $post) {
            $response->assertDontSee($post->title, false);
        }
    }

    public function test_blog_tag_archive_shows_only_tagged_posts(): void
    {
        $tag = Tag::create(['name' => 'Premix']);

        $tagged   = Post::factory()->count(2)->published()->create();
        $untagged = Post::factory()->count(2)->published()->create();

        foreach ($tagged as $post) {
            $post->tags()->attach($tag->id);
        }

        $response = $this->get('/blog/tag/' . $tag->slug);

        $response->assertOk();
        foreach ($tagged as $post) {
            $response->assertSee($post->title, false);
        }
        foreach ($untagged as $post) {
            $response->assertDontSee($post->title, false);
        }
    }

    public function test_blog_index_link_in_nav(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="' . route('blog.index') . '"', false);
    }

    public function test_seeded_primary_menu_renders_blog_link(): void
    {
        $this->seed(MenuSeeder::class);
        SiteComposer::clearCache();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="' . route('blog.index') . '"', false);
        $response->assertSee(__('blog.nav_label'), false);
    }
}
