<?php

namespace Tests\Feature\Settings;

use App\Filament\Clusters\Settings\Pages\ManageMail;
use App\Models\Setting;
use App\Models\User;
use App\Support\DatabaseMailConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class MailSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_overrides_mail_config_when_host_is_set(): void
    {
        Setting::set('mail.host', 'smtp.example.com', 'mail');
        Setting::set('mail.port', '465', 'mail');
        Setting::set('mail.username', 'postmaster@example.com', 'mail');
        Setting::set('mail.password', 'secret', 'mail');
        Setting::set('mail.encryption', 'ssl', 'mail');
        Setting::set('mail.from_address', 'noreply@example.com', 'mail');

        DatabaseMailConfig::apply();

        $this->assertSame('smtp', Config::get('mail.default'));
        $this->assertSame('smtp.example.com', Config::get('mail.mailers.smtp.host'));
        $this->assertSame(465, Config::get('mail.mailers.smtp.port'));
        $this->assertSame('smtps', Config::get('mail.mailers.smtp.scheme'));
        $this->assertSame('noreply@example.com', Config::get('mail.from.address'));
    }

    public function test_apply_is_noop_when_host_blank(): void
    {
        Config::set('mail.mailers.smtp.host', 'untouched.test');

        DatabaseMailConfig::apply(); // no mail.host setting

        $this->assertSame('untouched.test', Config::get('mail.mailers.smtp.host'));
    }

    public function test_test_button_sends_mail_with_form_values(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(ManageMail::class)
            ->fillForm([
                'mail__host'         => 'smtp.example.com',
                'mail__port'         => '587',
                'mail__encryption'   => 'tls',
                'mail__from_address' => 'noreply@example.com',
            ])
            ->call('sendTestEmail')
            ->assertNotified(__('cms.settings_cluster.mail.test_sent', ['email' => 'noreply@example.com']));
    }

    public function test_test_button_warns_without_host(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(ManageMail::class)
            ->fillForm(['mail__host' => ''])
            ->call('sendTestEmail')
            ->assertNotified(__('cms.settings_cluster.mail.test_no_host'));

        Mail::assertNothingSent();
    }
}
