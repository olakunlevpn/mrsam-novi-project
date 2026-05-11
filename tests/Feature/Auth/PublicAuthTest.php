<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_loads(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('Create an account', false);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sam Tester',
            'email' => 'sam.tester@example.com',
            'password' => 'Sup3rsecret!',
            'password_confirmation' => 'Sup3rsecret!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'sam.tester@example.com',
            'name' => 'Sam Tester',
        ]);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Mismatch Tester',
            'email' => 'mismatch@example.com',
            'password' => 'Sup3rsecret!',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'mismatch@example.com']);
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Sign in', false);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_logout_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_forgot_password_page_loads(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertOk();
        $response->assertSee('Forgot your password?', false);
    }

    public function test_verify_email_page_loads_for_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertOk();
        $response->assertSee('Verify your email', false);
    }

    public function test_unverified_user_cannot_access_verified_route(): void
    {
        // Comment posting (Task 5.4) is the first feature behind 'verified'
        // middleware. Until then, there is no public verified-gated route to
        // exercise here, so this is a placeholder reminder.
        $this->markTestSkipped('Add a verified-route check once Task 5.4 wires comment posting through the verified middleware.');
    }
}
