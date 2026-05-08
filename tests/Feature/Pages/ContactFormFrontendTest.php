<?php

namespace Tests\Feature\Pages;

use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_action_points_at_local_route_by_default(): void
    {
        $this->get('/contact.html')
            ->assertOk()
            ->assertSee('action="' . route('contact.submit') . '"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="_honey"', false);
    }

    public function test_contact_form_uses_phone_field_name(): void
    {
        // Controller validates `phone`, not `tel`. Make sure the input matches.
        $this->get('/contact.html')
            ->assertOk()
            ->assertSee('name="phone"', false)
            ->assertDontSee('name="tel"', false);
    }

    public function test_submitting_rendered_form_persists_a_submission(): void
    {
        // Get the rendered page to seed the CSRF cookie / session.
        $this->get('/contact.html')->assertOk();

        $response = $this->post(route('contact.submit'), [
            'name'    => 'Frontend User',
            'email'   => 'frontend@example.com',
            'phone'   => '+2348011223344',
            'service' => 'Training',
            'message' => 'Sent through the rendered contact form.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_status', 'sent');

        $this->assertSame(1, ContactSubmission::count());
        $row = ContactSubmission::first();
        $this->assertSame('Frontend User',                   $row->name);
        $this->assertSame('frontend@example.com',            $row->email);
        $this->assertSame('+2348011223344',                  $row->phone);
        $this->assertSame('Service Inquiry: Training',      $row->subject);
    }

    public function test_success_flash_renders_alert_on_redirect(): void
    {
        $this->get('/contact.html')->assertOk();

        $response = $this->post(route('contact.submit'), [
            'name'    => 'Alert Tester',
            'email'   => 'alert@example.com',
            'message' => 'Verifying flash message renders.',
        ]);

        $response->assertRedirect();
        // Follow to the contact page and look for the success alert.
        $this->followingRedirects()
            ->get('/contact.html')
            ->assertOk();
    }
}
