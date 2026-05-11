<?php

namespace Tests\Feature\Blog;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class CommentPostingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure the per-user rate limiter is clean between tests.
        RateLimiter::clear('global');
    }

    /**
     * Shared payload that satisfies the validator (body + min-fill-time).
     *
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'body'            => 'This is a thoughtful, substantive comment.',
            '_form_loaded_at' => time() - 120,
            '_hp_email'       => '',
        ], $overrides);
    }

    public function test_guest_cannot_post_comment(): void
    {
        $post = Post::factory()->published()->create();

        $this->post(route('comments.store', $post), $this->validPayload())
            ->assertRedirect('/login');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_unverified_user_cannot_post_comment(): void
    {
        $user = User::factory()->unverified()->create();
        $post = Post::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload())
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_verified_user_can_post_top_level_comment(): void
    {
        $user = User::factory()->create(); // verified by default
        $post = Post::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload())
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id'   => $post->id,
            'user_id'   => $user->id,
            'parent_id' => null,
            'status'    => 'pending',
        ]);
    }

    public function test_verified_user_can_post_reply_to_top_level_comment(): void
    {
        $user   = User::factory()->create();
        $post   = Post::factory()->published()->create();
        $parent = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
        ]);

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload([
                'parent_id' => $parent->id,
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id'   => $post->id,
            'user_id'   => $user->id,
            'parent_id' => $parent->id,
            'status'    => 'pending',
        ]);
    }

    public function test_cannot_reply_to_a_reply_two_levels_deep(): void
    {
        $user   = User::factory()->create();
        $post   = Post::factory()->published()->create();
        $top    = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
        ]);
        $reply = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'parent_id' => $top->id,
        ]);

        $response = $this->actingAs($user)
            ->from(route('blog.show', $post))
            ->post(route('comments.store', $post), $this->validPayload([
                'parent_id' => $reply->id,
            ]));

        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseCount('comments', 2);
    }

    public function test_honeypot_field_blocks_submission(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create();

        $response = $this->actingAs($user)
            ->from(route('blog.show', $post))
            ->post(route('comments.store', $post), $this->validPayload([
                '_hp_email' => 'spammer@example.com',
            ]));

        $response->assertSessionHasErrors('_hp_email');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_form_loaded_at_min_fill_time_blocks_too_fast(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create();

        $response = $this->actingAs($user)
            ->from(route('blog.show', $post))
            ->post(route('comments.store', $post), $this->validPayload([
                '_form_loaded_at' => time(),
            ]));

        $response->assertSessionHasErrors('_form_loaded_at');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_form_loaded_at_old_enough_passes(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload([
                '_form_loaded_at' => time() - 61,
            ]))
            ->assertRedirect();

        $this->assertDatabaseCount('comments', 1);
    }

    public function test_rate_limit_blocks_after_10_in_an_hour(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)
                ->post(route('comments.store', $post), $this->validPayload([
                    'body' => "Comment number {$i}",
                ]))
                ->assertRedirect();
        }

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload([
                'body' => 'Comment number 11',
            ]))
            ->assertStatus(429);

        $this->assertDatabaseCount('comments', 10);
    }

    public function test_comments_disabled_on_post_rejects_submission(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create([
            'allow_comments' => false,
        ]);

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_unpublished_post_rejects_comment_submission(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'status'       => 'draft',
            'published_at' => null,
        ]);

        $this->actingAs($user)
            ->post(route('comments.store', $post), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_approved_comments_render_publicly(): void
    {
        $post = Post::factory()->published()->create();

        $approvedOne = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
            'body'      => 'APPROVED-COMMENT-ALPHA',
        ]);
        $approvedTwo = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
            'body'      => 'APPROVED-COMMENT-BETA',
        ]);
        $pending = Comment::factory()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
            'status'    => 'pending',
            'body'      => 'PENDING-SECRET-DRAFT',
        ]);

        $response = $this->get(route('blog.show', $post));

        $response->assertOk();
        $response->assertSee('APPROVED-COMMENT-ALPHA', false);
        $response->assertSee('APPROVED-COMMENT-BETA', false);
        $response->assertDontSee('PENDING-SECRET-DRAFT', false);
    }
}
