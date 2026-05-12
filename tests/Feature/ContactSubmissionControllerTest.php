<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_submission_persists_and_redirects(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'name'    => 'Adesola Adeyemi',
            'email'   => 'adesola@example.com',
            'phone'   => '+2348012345678',
            'subject' => 'Pricing question',
            'message' => 'Please share pricing for layer feeds.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('contact_status', 'sent');

        $this->assertSame(1, ContactSubmission::count());
        $row = ContactSubmission::first();
        $this->assertSame('Adesola Adeyemi',                  $row->name);
        $this->assertSame('adesola@example.com',              $row->email);
        $this->assertSame('+2348012345678',                   $row->phone);
        $this->assertSame('Pricing question',                 $row->subject);
        $this->assertSame('Please share pricing for layer feeds.', $row->message);
        $this->assertSame('new',                              $row->status);
        $this->assertNotEmpty($row->ip);
    }

    public function test_minimal_required_fields_succeed(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name'    => 'Tola',
            'email'   => 'tola@example.com',
            'message' => 'Hi',
        ]);

        $response->assertRedirect();
        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_service_dropdown_becomes_subject_when_subject_missing(): void
    {
        $this->post(route('contact.submit'), [
            'name'    => 'Bola',
            'email'   => 'bola@example.com',
            'service' => 'Consultation',
            'message' => 'Want consultation.',
        ]);

        $this->assertSame('Service Inquiry: Consultation', ContactSubmission::first()->subject);
    }

    public function test_missing_required_fields_returns_validation_errors(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), []);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_invalid_email_rejected(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'name'    => 'X',
            'email'   => 'not-an-email',
            'message' => 'msg',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_honeypot_filled_blocks_submission(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'name'    => 'Bot',
            'email'   => 'bot@example.com',
            'message' => 'spam',
            '_honey'  => 'I am a bot',
        ]);

        $response->assertSessionHasErrors(['_honey']);
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_message_length_capped(): void
    {
        $response = $this->from('/contact')->post(route('contact.submit'), [
            'name'    => 'X',
            'email'   => 'x@example.com',
            'message' => str_repeat('a', 6000),
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_submission_appears_in_admin_inbox(): void
    {
        $this->post(route('contact.submit'), [
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'message' => 'Inbox test message.',
        ]);

        $this->assertDatabaseHas('contact_submissions', [
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'status'  => 'new',
        ]);
    }
}
