<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_open_grouped_settings_page(): void
    {
        $this->actingAs($this->admin)
            ->get(ManageSiteSettings::getUrl())
            ->assertOk();
    }

    public function test_non_admin_forbidden(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user)
            ->get(ManageSiteSettings::getUrl())
            ->assertForbidden();
    }

    public function test_existing_settings_are_loaded_into_form(): void
    {
        Setting::set('brand.name',      'Acme Co',                'brand');
        Setting::set('contact.email',   'team@acme.example',      'contact');
        Setting::set('social.facebook', 'https://fb.com/acme',    'social');

        Livewire::actingAs($this->admin)
            ->test(ManageSiteSettings::class)
            ->assertSet('data.brand__name',       'Acme Co')
            ->assertSet('data.contact__email',    'team@acme.example')
            ->assertSet('data.social__facebook',  'https://fb.com/acme');
    }

    public function test_saving_persists_each_field_to_settings_table(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ManageSiteSettings::class)
            ->set('data.brand__name',         'Saved Brand')
            ->set('data.brand__tagline',      'Quality first')
            ->set('data.contact__email',      'hello@example.com')
            ->set('data.contact__phone',      '+1-555-0100')
            ->set('data.contact__address',    '123 Main St')
            ->set('data.social__facebook',    'https://facebook.com/x')
            ->set('data.social__instagram',   'https://instagram.com/x')
            ->set('data.site__title_suffix',  ' | New Suffix')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Saved Brand',                Setting::get('brand.name'));
        $this->assertSame('Quality first',              Setting::get('brand.tagline'));
        $this->assertSame('hello@example.com',          Setting::get('contact.email'));
        $this->assertSame('+1-555-0100',                Setting::get('contact.phone'));
        $this->assertSame('123 Main St',                Setting::get('contact.address'));
        $this->assertSame('https://facebook.com/x',     Setting::get('social.facebook'));
        $this->assertSame('https://instagram.com/x',    Setting::get('social.instagram'));
        $this->assertSame(' | New Suffix',              Setting::get('site.title_suffix'));

        $this->assertDatabaseHas('settings', ['key' => 'brand.name',   'group' => 'brand']);
        $this->assertDatabaseHas('settings', ['key' => 'contact.email','group' => 'contact']);
    }

    public function test_invalid_email_rejected(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ManageSiteSettings::class)
            ->set('data.contact__email', 'not-an-email')
            ->call('save')
            ->assertHasFormErrors(['contact__email']);

        $this->assertNull(Setting::get('contact.email'));
    }

    public function test_invalid_url_rejected_for_social_fields(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ManageSiteSettings::class)
            ->set('data.social__facebook', 'not-a-url')
            ->call('save')
            ->assertHasFormErrors(['social__facebook']);

        $this->assertNull(Setting::get('social.facebook'));
    }
}
