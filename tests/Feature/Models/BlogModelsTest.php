<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_post_with_auto_slug(): void
    {
        $post = Post::create([
            'title' => 'Hello Novi Blog',
            'body'  => 'First post body.',
        ]);

        $this->assertSame('hello-novi-blog', $post->slug);
    }

    public function test_route_key_is_slug(): void
    {
        $this->assertSame('slug', (new Post)->getRouteKeyName());
    }

    public function test_post_category_auto_slugs_from_name(): void
    {
        $category = PostCategory::create(['name' => 'Animal Nutrition']);

        $this->assertSame('animal-nutrition', $category->slug);
    }

    public function test_tag_auto_slugs_from_name(): void
    {
        $tag = Tag::create(['name' => 'Poultry']);

        $this->assertSame('poultry', $tag->slug);
    }

    public function test_post_belongs_to_author_category_and_has_tags_and_comments(): void
    {
        $author   = User::factory()->create();
        $category = PostCategory::factory()->create();
        $post     = Post::factory()->create([
            'author_id'        => $author->id,
            'post_category_id' => $category->id,
        ]);

        $tagA = Tag::factory()->create();
        $tagB = Tag::factory()->create();
        $post->tags()->attach([$tagA->id, $tagB->id]);

        Comment::factory()->create(['post_id' => $post->id]);
        Comment::factory()->create(['post_id' => $post->id]);

        $this->assertTrue($post->author->is($author));
        $this->assertTrue($post->category->is($category));
        $this->assertCount(2, $post->tags);
        $this->assertCount(2, $post->comments);
    }

    public function test_comment_belongs_to_post_and_author(): void
    {
        $author  = User::factory()->create();
        $post    = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $author->id,
        ]);

        $this->assertTrue($comment->post->is($post));
        $this->assertTrue($comment->author->is($author));
    }

    public function test_comment_has_parent_and_replies(): void
    {
        $post   = Post::factory()->create();
        $parent = Comment::factory()->create(['post_id' => $post->id]);
        $reply  = Comment::factory()->create([
            'post_id'   => $post->id,
            'parent_id' => $parent->id,
        ]);

        $this->assertTrue($reply->parent->is($parent));
        $this->assertCount(1, $parent->replies);
        $this->assertTrue($parent->replies->first()->is($reply));
    }

    public function test_post_published_scope_returns_only_published_with_past_published_at(): void
    {
        // Draft — excluded
        Post::factory()->create(['status' => 'draft']);

        // Published in the past — included
        Post::factory()->published()->create([
            'published_at' => now()->subDay(),
        ]);

        // Published, but scheduled in the future — excluded
        Post::factory()->create([
            'status'       => 'published',
            'published_at' => now()->addDay(),
        ]);

        // Published with null published_at — excluded (no date <= now)
        Post::factory()->create([
            'status'       => 'published',
            'published_at' => null,
        ]);

        $this->assertSame(1, Post::published()->count());
    }

    public function test_comment_approved_and_pending_scopes_filter_by_status(): void
    {
        $post = Post::factory()->create();

        Comment::factory()->count(2)->approved()->create(['post_id' => $post->id]);
        Comment::factory()->count(3)->create(['post_id' => $post->id]); // pending default
        Comment::factory()->rejected()->create(['post_id' => $post->id]);
        Comment::factory()->spam()->create(['post_id' => $post->id]);

        $this->assertSame(2, Comment::approved()->count());
        $this->assertSame(3, Comment::pending()->count());
        $this->assertSame(['approved'], Comment::approved()->pluck('status')->unique()->values()->all());
        $this->assertSame(['pending'], Comment::pending()->pluck('status')->unique()->values()->all());
    }

    public function test_post_factory_published_state_sets_status_and_published_at(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->published_at->lessThanOrEqualTo(now()));
    }

    public function test_comment_factory_states(): void
    {
        $approved = Comment::factory()->approved()->create();
        $rejected = Comment::factory()->rejected()->create();
        $spam     = Comment::factory()->spam()->create();
        $pending  = Comment::factory()->create();

        $this->assertSame('approved', $approved->status);
        $this->assertSame('rejected', $rejected->status);
        $this->assertSame('spam', $spam->status);
        $this->assertSame('pending', $pending->status);
    }

    public function test_post_casts_published_at_to_datetime_and_allow_comments_to_bool(): void
    {
        $post = Post::factory()->published()->create([
            'allow_comments' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $post->published_at);
        $this->assertTrue($post->allow_comments);
    }
}
