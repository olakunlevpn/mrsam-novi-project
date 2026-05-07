<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Settings\Pages\CreateSetting;
use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_setting(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateSetting::class)
            ->fillForm([
                'group' => 'brand',
                'key'   => 'brand.color',
                'value' => '#1a9120',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'group' => 'brand',
            'key'   => 'brand.color',
        ]);

        $setting = Setting::where('key', 'brand.color')->first();
        $this->assertSame('#1a9120', $setting->value);
    }

    public function test_admin_can_edit_setting(): void
    {
        Setting::set('contact.email', 'old@example.com', 'contact');

        $setting = Setting::where('key', 'contact.email')->first();

        Livewire::actingAs($this->admin)
            ->test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm(['value' => 'new@example.com'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('new@example.com', Setting::get('contact.email'));
    }

    public function test_key_must_be_unique(): void
    {
        Setting::set('site.title', 'Existing Title', 'site');

        Livewire::actingAs($this->admin)
            ->test(CreateSetting::class)
            ->fillForm([
                'group' => 'site',
                'key'   => 'site.title',
                'value' => 'Duplicate',
            ])
            ->call('create')
            ->assertHasFormErrors(['key']);
    }
}
