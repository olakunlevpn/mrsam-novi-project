<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageContactInfo extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.contact.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.contact.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'contact' => [
                'email'   => ['label' => 'cms.settings_cluster.field.contact_email',   'email' => true],
                'phone'   => ['label' => 'cms.settings_cluster.field.contact_phone'],
                'address' => ['label' => 'cms.settings_cluster.field.contact_address', 'textarea' => true],
            ],
        ];
    }
}
