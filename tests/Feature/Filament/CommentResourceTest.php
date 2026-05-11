<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Comments\CommentResource;
use App\Filament\Resources\Comments\Pages\ListComments;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(CommentResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_pending_filter_is_default(): void
    {
        $post = Post::factory()->create(['author_id' => $this->admin->id]);

        $pending  = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);
        $approved = Comment::factory()->approved()->create(['post_id' => $post->id]);

        Livewire::actingAs($this->admin)
            ->test(ListComments::class)
            ->assertCanSeeTableRecords([$pending])
            ->assertCanNotSeeTableRecords([$approved]);
    }

    public function test_admin_can_approve_comment(): void
    {
        $post    = Post::factory()->create(['author_id' => $this->admin->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        Livewire::actingAs($this->admin)
            ->test(ListComments::class)
            ->callTableAction('approve', $comment);

        $this->assertSame('approved', $comment->fresh()->status);
    }

    public function test_admin_can_reject_comment(): void
    {
        $post    = Post::factory()->create(['author_id' => $this->admin->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        Livewire::actingAs($this->admin)
            ->test(ListComments::class)
            ->callTableAction('reject', $comment);

        $this->assertSame('rejected', $comment->fresh()->status);
    }

    public function test_admin_can_mark_spam(): void
    {
        $post    = Post::factory()->create(['author_id' => $this->admin->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        Livewire::actingAs($this->admin)
            ->test(ListComments::class)
            ->callTableAction('spam', $comment);

        $this->assertSame('spam', $comment->fresh()->status);
    }

    public function test_bulk_approve_updates_selected(): void
    {
        $post = Post::factory()->create(['author_id' => $this->admin->id]);
        $a = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);
        $b = Comment::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        Livewire::actingAs($this->admin)
            ->test(ListComments::class)
            ->callTableBulkAction('approve', [$a->getKey(), $b->getKey()]);

        $this->assertSame('approved', $a->fresh()->status);
        $this->assertSame('approved', $b->fresh()->status);
    }
}
