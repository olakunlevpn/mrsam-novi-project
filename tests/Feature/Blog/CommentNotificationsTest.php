<?php

namespace Tests\Feature\Blog;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentReplyReceived;
use App\Notifications\CommentSubmittedToAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CommentNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_comment_notifies_all_admins(): void
    {
        Notification::fake();

        $adminOne = User::factory()->create(['is_admin' => true]);
        $adminTwo = User::factory()->create(['is_admin' => true]);
        $nonAdmin = User::factory()->create(['is_admin' => false]);
        $author   = User::factory()->create(['is_admin' => false]);
        $post     = Post::factory()->published()->create();

        Comment::factory()->create([
            'post_id'   => $post->id,
            'user_id'   => $author->id,
            'parent_id' => null,
        ]);

        Notification::assertSentTo($adminOne, CommentSubmittedToAdmin::class);
        Notification::assertSentTo($adminTwo, CommentSubmittedToAdmin::class);
        Notification::assertNotSentTo($nonAdmin, CommentSubmittedToAdmin::class);
    }

    public function test_creating_reply_notifies_parent_comment_author(): void
    {
        Notification::fake();

        $userA = User::factory()->create(['is_admin' => false]);
        $userB = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);
        $post  = Post::factory()->published()->create();

        $parent = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'user_id'   => $userA->id,
            'parent_id' => null,
        ]);

        Comment::factory()->create([
            'post_id'   => $post->id,
            'user_id'   => $userB->id,
            'parent_id' => $parent->id,
        ]);

        Notification::assertSentTo($userA, CommentReplyReceived::class);
        Notification::assertNotSentTo($userB, CommentReplyReceived::class);
        Notification::assertNotSentTo($admin, CommentReplyReceived::class);
    }

    public function test_top_level_comment_does_not_send_reply_notification(): void
    {
        Notification::fake();

        $author = User::factory()->create(['is_admin' => false]);
        $post   = Post::factory()->published()->create();

        Comment::factory()->create([
            'post_id'   => $post->id,
            'user_id'   => $author->id,
            'parent_id' => null,
        ]);

        Notification::assertNothingSentTo($author);
    }

    public function test_reply_does_not_double_notify_replier_if_replier_is_parent_author(): void
    {
        Notification::fake();

        $author = User::factory()->create(['is_admin' => false]);
        $post   = Post::factory()->published()->create();

        $parent = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'user_id'   => $author->id,
            'parent_id' => null,
        ]);

        Comment::factory()->create([
            'post_id'   => $post->id,
            'user_id'   => $author->id,
            'parent_id' => $parent->id,
        ]);

        Notification::assertNotSentTo($author, CommentReplyReceived::class);
    }

    public function test_reply_to_comment_whose_author_is_deleted_does_not_throw(): void
    {
        Notification::fake();

        $replier = User::factory()->create(['is_admin' => false]);
        $post    = Post::factory()->published()->create();

        $parent = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'user_id'   => null,
            'parent_id' => null,
        ]);

        Comment::factory()->create([
            'post_id'   => $post->id,
            'user_id'   => $replier->id,
            'parent_id' => $parent->id,
        ]);

        Notification::assertNotSentTo($replier, CommentReplyReceived::class);
    }

    public function test_notifications_implement_should_queue(): void
    {
        $post    = Post::factory()->published()->create();
        $comment = Comment::factory()->make([
            'post_id'   => $post->id,
            'parent_id' => null,
        ]);

        $this->assertInstanceOf(ShouldQueue::class, new CommentSubmittedToAdmin($comment));
        $this->assertInstanceOf(ShouldQueue::class, new CommentReplyReceived($comment));
    }

    public function test_admin_notification_subject_uses_lang_key(): void
    {
        $admin   = User::factory()->create(['is_admin' => true]);
        $post    = Post::factory()->published()->create();
        $comment = Comment::factory()->create([
            'post_id'   => $post->id,
            'parent_id' => null,
        ]);

        $mail = (new CommentSubmittedToAdmin($comment))->toMail($admin);

        $this->assertSame(__('blog.email.admin_subject'), $mail->subject);
    }

    public function test_reply_notification_includes_comment_anchor_url(): void
    {
        $parentAuthor = User::factory()->create(['is_admin' => false]);
        $post         = Post::factory()->published()->create();

        $parent = Comment::factory()->approved()->create([
            'post_id'   => $post->id,
            'user_id'   => $parentAuthor->id,
            'parent_id' => null,
        ]);
        $reply = Comment::factory()->create([
            'post_id'   => $post->id,
            'parent_id' => $parent->id,
        ]);

        $mail = (new CommentReplyReceived($reply))->toMail($parentAuthor);

        $this->assertSame(__('blog.email.reply_subject'), $mail->subject);
        $this->assertStringContainsString('#comment-'.$reply->id, $mail->actionUrl);
    }
}
