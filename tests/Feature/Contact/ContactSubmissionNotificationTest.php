<?php

namespace Tests\Feature\Contact;

use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactSubmissionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_submission_notifies_admins(): void
    {
        Notification::fake();

        $adminOne = User::factory()->create(['is_admin' => true]);
        $adminTwo = User::factory()->create(['is_admin' => true]);
        $nonAdmin = User::factory()->create(['is_admin' => false]);

        $this->post(route('contact.submit'), [
            'name'    => 'Jane Farmer',
            'email'   => 'jane@example.com',
            'phone'   => '+2348000000000',
            'subject' => 'Cattle feed inquiry',
            'message' => 'Hello, I would like a quote for cattle premix.',
        ])->assertRedirect();

        Notification::assertSentTo($adminOne, ContactSubmissionReceived::class);
        Notification::assertSentTo($adminTwo, ContactSubmissionReceived::class);
        Notification::assertNotSentTo($nonAdmin, ContactSubmissionReceived::class);
    }

    public function test_submission_notification_implements_should_queue(): void
    {
        $submission = ContactSubmission::create([
            'name'    => 'Test',
            'email'   => 'test@example.com',
            'message' => 'Body',
            'status'  => 'new',
        ]);

        $this->assertInstanceOf(ShouldQueue::class, new ContactSubmissionReceived($submission));
    }

    public function test_admin_subject_uses_lang_key(): void
    {
        $admin      = User::factory()->create(['is_admin' => true]);
        $submission = ContactSubmission::create([
            'name'    => 'Test',
            'email'   => 'test@example.com',
            'message' => 'Body',
            'status'  => 'new',
        ]);

        $mail = (new ContactSubmissionReceived($submission))->toMail($admin);

        $this->assertSame(__('contact.email.admin_subject'), $mail->subject);
    }
}
