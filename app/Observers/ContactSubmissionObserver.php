<?php

namespace App\Observers;

use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Support\Facades\Notification;

class ContactSubmissionObserver
{
    /**
     * Queue an admin notification whenever a new contact submission is
     * persisted so the team is alerted without polling the inbox.
     */
    public function created(ContactSubmission $submission): void
    {
        $admins = User::query()->where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::send($admins, new ContactSubmissionReceived($submission));
    }
}
