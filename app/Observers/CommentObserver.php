<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentReplyReceived;
use App\Notifications\CommentSubmittedToAdmin;
use Illuminate\Support\Facades\Notification;

class CommentObserver
{
    /**
     * Queue notifications when a new comment row is persisted.
     *
     * - All admin users get a moderation alert.
     * - If the comment is a reply, the parent comment's author also
     *   receives a reply alert, unless they are the replier themselves
     *   (no self-notification) or their account has been deleted
     *   (user_id is nullable / nullOnDelete).
     */
    public function created(Comment $comment): void
    {
        $admins = User::query()->where('is_admin', true)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new CommentSubmittedToAdmin($comment));
        }

        if ($comment->parent_id === null) {
            return;
        }

        $parentAuthor = $comment->parent?->author;

        if ($parentAuthor === null) {
            return;
        }

        if ((int) $parentAuthor->getKey() === (int) $comment->user_id) {
            return;
        }

        $parentAuthor->notify(new CommentReplyReceived($comment));
    }
}
