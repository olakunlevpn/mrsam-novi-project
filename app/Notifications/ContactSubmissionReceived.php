<?php

namespace App\Notifications;

use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSubmissionReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly ContactSubmission $submission)
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
        $submission = $this->submission;

        $reviewUrl = ContactSubmissionResource::getUrl('edit', [
            'record' => $submission->getKey(),
        ]);

        $mail = (new MailMessage())
            ->subject(__('contact.email.admin_subject'))
            ->line(__('contact.email.admin_greeting'))
            ->line(__('contact.email.admin_excerpt_intro', [
                'name'  => $submission->name,
                'email' => $submission->email,
            ]));

        if (! empty($submission->phone)) {
            $mail->line('Phone: '.$submission->phone);
        }

        if (! empty($submission->subject)) {
            $mail->line(__('contact.email.admin_subject_line', [
                'subject' => $submission->subject,
            ]));
        }

        return $mail
            ->line($submission->message)
            ->action(__('contact.email.admin_action'), $reviewUrl);
    }
}
