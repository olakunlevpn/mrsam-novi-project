<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class ManageMail extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 8;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.mail.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.mail.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'mail' => [
                'host'         => ['label' => 'cms.settings_cluster.field.mail_host'],
                'port'         => ['label' => 'cms.settings_cluster.field.mail_port'],
                'encryption'   => [
                    'type'    => 'select',
                    'label'   => 'cms.settings_cluster.field.mail_encryption',
                    'default' => 'tls',
                    'options' => [
                        'none' => 'cms.settings_cluster.field.mail_encryption_none',
                        'tls'  => 'cms.settings_cluster.field.mail_encryption_tls',
                        'ssl'  => 'cms.settings_cluster.field.mail_encryption_ssl',
                    ],
                ],
                'username'     => ['label' => 'cms.settings_cluster.field.mail_username'],
                'password'     => ['type' => 'password', 'label' => 'cms.settings_cluster.field.mail_password'],
                'from_address' => ['label' => 'cms.settings_cluster.field.mail_from_address', 'email' => true],
                'from_name'    => ['label' => 'cms.settings_cluster.field.mail_from_name'],
            ],
        ];
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return array_merge(parent::getHeaderActions(), [
            Action::make('sendTest')
                ->label(__('cms.settings_cluster.mail.test'))
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('gray')
                ->action('sendTestEmail'),
        ]);
    }

    /**
     * Send a test email using the SMTP details currently in the form (no save
     * required), so the admin can verify credentials before committing them.
     */
    public function sendTestEmail(): void
    {
        $state = $this->form->getState();
        $get   = fn (string $key) => $state[$this->flatKey('mail', $key)] ?? null;

        $recipient = $get('from_address') ?: auth()->user()->email;

        if (blank($get('host'))) {
            Notification::make()
                ->title(__('cms.settings_cluster.mail.test_no_host'))
                ->warning()
                ->send();

            return;
        }

        // Apply the form values to the live mail config for this request only.
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $get('host'));
        Config::set('mail.mailers.smtp.port', (int) ($get('port') ?: 587));
        Config::set('mail.mailers.smtp.username', $get('username'));
        Config::set('mail.mailers.smtp.password', $get('password'));
        Config::set('mail.mailers.smtp.scheme', $get('encryption') === 'ssl' ? 'smtps' : null);
        if (filled($get('from_address'))) {
            Config::set('mail.from.address', $get('from_address'));
        }
        if (filled($get('from_name'))) {
            Config::set('mail.from.name', $get('from_name'));
        }

        try {
            Mail::raw(__('cms.settings_cluster.mail.test_body'), function ($message) use ($recipient): void {
                $message->to($recipient)->subject(__('cms.settings_cluster.mail.test_subject'));
            });

            Notification::make()
                ->title(__('cms.settings_cluster.mail.test_sent', ['email' => $recipient]))
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title(__('cms.settings_cluster.mail.test_failed'))
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
