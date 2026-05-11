<?php

namespace App\Notifications;

use App\Filament\Resources\Comments\CommentResource;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentSubmittedToAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Comment $comment)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $comment = $this->comment;
        $post    = $comment->post;
        $author  = $comment->author?->name ?? __('blog.deleted_user');

        $moderateUrl = CommentResource::getUrl('edit', ['record' => $comment->getKey()]);

        return (new MailMessage())
            ->subject(__('blog.email.admin_subject'))
            ->line(__('blog.email.admin_greeting', [
                'post_title' => $post?->title ?? '',
            ]))
            ->line(__('blog.email.admin_excerpt_intro', [
                'author_name' => $author,
            ]))
            ->line($comment->body)
            ->action(__('blog.email.admin_action'), $moderateUrl);
    }
}
