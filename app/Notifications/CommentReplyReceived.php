<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyReceived extends Notification implements ShouldQueue
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
        $reply    = $this->comment;
        $post     = $reply->post;
        $replier  = $reply->author?->name ?? __('blog.deleted_user');

        $conversationUrl = $post
            ? route('blog.show', $post).'#comment-'.$reply->getKey()
            : url('/');

        return (new MailMessage())
            ->subject(__('blog.email.reply_subject'))
            ->line(__('blog.email.reply_greeting', ['replier' => $replier]))
            ->line(__('blog.email.reply_excerpt_intro', [
                'post_title' => $post?->title ?? '',
            ]))
            ->line($reply->body)
            ->line(__('blog.email.pending_warning'))
            ->action(__('blog.email.reply_action'), $conversationUrl);
    }
}
