<?php

namespace Tests\Feature\Pages;

use App\Models\Setting;
use App\View\Composers\SiteComposer;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsFrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Composer caches settings statically; reset between tests.
        SiteComposer::clearCache();
    }

    public function test_default_brand_and_contact_render_when_no_settings(): void
    {
        $response = $this->get('/')->assertOk();
        // Falls back to original hardcoded values.
        $response->assertSee('mailto:info@novi-agro.com', false);
        $response->assertSee('tel:+2347041041756', false);
        $response->assertSee('New Garage, Ibadan.', false);
    }

    public function test_seeded_settings_render_in_topbar_and_footer(): void
    {
        $this->seed(SettingSeeder::class);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        // Same default values come from the seeder, so they should render.
        $response->assertSee('mailto:info@novi-agro.com', false);
        $response->assertSee('tel:+2347041041756', false);
        $response->assertSee('New Garage, Ibadan.', false);
    }

    public function test_custom_settings_replace_defaults(): void
    {
        Setting::set('contact.email',   'hello@example.com',          'contact');
        Setting::set('contact.phone',   '+15551234567',               'contact');
        Setting::set('contact.address', 'Custom Office, Lagos.',      'contact');
        Setting::set('social.facebook', 'https://facebook.com/novi',  'social');
        Setting::set('social.instagram', 'https://instagram.com/novi', 'social');
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();

        $response->assertSee('mailto:hello@example.com', false);
        $response->assertSee('tel:+15551234567', false);
        $response->assertSee('Custom Office, Lagos.', false);
        $response->assertSee('https://facebook.com/novi', false);
        $response->assertSee('https://instagram.com/novi', false);

        // Defaults must NOT appear.
        $response->assertDontSee('mailto:info@novi-agro.com', false);
        $response->assertDontSee('tel:+2347041041756', false);
        $response->assertDontSee('New Garage, Ibadan.', false);
    }

    public function test_brand_logo_setting_replaces_default(): void
    {
        Setting::set('brand.logo', '/assets/images/custom-logo.svg', 'brand');
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('/assets/images/custom-logo.svg', false);
    }

    public function test_brand_tagline_setting_replaces_default(): void
    {
        Setting::set('brand.tagline', 'Feeding the future.', 'brand');
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('Feeding the future.', false)
            ->assertDontSee('Quality Feed - Healthy Life', false);
    }
}
