<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PostComments;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostCommentsTest extends TestCase
{
    use RefreshDatabase;

    private function openPost(): Post
    {
        return Post::factory()->published()->create(['allow_comments' => true]);
    }

    public function test_verified_user_posts_pending_comment_by_default(): void
    {
        $user = User::factory()->create();
        $post = $this->openPost();

        $component = Livewire::actingAs($user)
            ->test(PostComments::class, ['post' => $post])
            ->set('body', 'A genuine, thoughtful comment.');

        $this->travel(6)->seconds();

        $component->call('submit')->assertHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);
    }

    public function test_moderation_off_approves_and_shows_inline(): void
    {
        Setting::set('comments.moderation', false, 'comments');

        $user = User::factory()->create();
        $post = $this->openPost();

        $component = Livewire::actingAs($user)
            ->test(PostComments::class, ['post' => $post])
            ->set('body', 'Visible immediately when moderation is off.');

        $this->travel(6)->seconds();

        $component->call('submit')
            ->assertHasNoErrors()
            ->assertSee('Visible immediately when moderation is off.');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'status'  => 'approved',
        ]);
    }

    public function test_honeypot_blocks_submission(): void
    {
        $user = User::factory()->create();
        $post = $this->openPost();

        $component = Livewire::actingAs($user)
            ->test(PostComments::class, ['post' => $post])
            ->set('body', 'Looks fine but the trap is filled.')
            ->set('hpEmail', 'bot@spam.test');

        $this->travel(6)->seconds();

        $component->call('submit')->assertHasErrors('body');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_too_fast_submission_is_blocked(): void
    {
        $user = User::factory()->create();
        $post = $this->openPost();

        // No time travel: the form is submitted within the min-fill window.
        Livewire::actingAs($user)
            ->test(PostComments::class, ['post' => $post])
            ->set('body', 'Submitted instantly like a bot.')
            ->call('submit')
            ->assertHasErrors('body');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_reply_targets_top_level_comment(): void
    {
        Setting::set('comments.moderation', false, 'comments');

        $author = User::factory()->create();
        $post   = $this->openPost();
        $parent = $post->comments()->create([
            'user_id' => $author->id,
            'body'    => 'Parent comment.',
            'status'  => 'approved',
        ]);

        $user = User::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(PostComments::class, ['post' => $post])
            ->call('setReply', $parent->id)
            ->set('body', 'A reply to the parent.');

        $this->travel(6)->seconds();

        $component->call('submit')->assertHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'post_id'   => $post->id,
            'parent_id' => $parent->id,
            'status'    => 'approved',
        ]);
    }
}
