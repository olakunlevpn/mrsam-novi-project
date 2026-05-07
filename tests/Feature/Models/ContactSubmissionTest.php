<?php

namespace Tests\Feature\Models;

use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_submission_with_default_status_new(): void
    {
        $submission = ContactSubmission::create([
            'name'    => 'John Doe',
            'email'   => 'john@example.com',
            'message' => 'Hello there.',
        ]);

        $this->assertSame('new', $submission->status);
    }

    public function test_unread_scope(): void
    {
        ContactSubmission::create(['name' => 'A', 'email' => 'a@example.com', 'message' => 'msg', 'status' => 'new']);
        ContactSubmission::create(['name' => 'B', 'email' => 'b@example.com', 'message' => 'msg', 'status' => 'new']);
        ContactSubmission::create(['name' => 'C', 'email' => 'c@example.com', 'message' => 'msg', 'status' => 'read']);

        $this->assertSame(2, ContactSubmission::unread()->count());
    }
}
