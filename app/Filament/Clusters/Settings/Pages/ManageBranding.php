<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageBranding extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.branding.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.branding.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'brand' => [
                'name'    => ['label' => 'cms.settings_cluster.field.brand_name'],
                'tagline' => ['label' => 'cms.settings_cluster.field.brand_tagline'],
                'logo'    => [
                    'label'     => 'cms.settings_cluster.field.brand_logo',
                    'type'      => 'image_upload',
                    'directory' => 'branding/logo',
                    'max_size'  => 2048,
                    'accept'    => ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'],
                ],
                'favicon' => [
                    'label'     => 'cms.settings_cluster.field.brand_favicon',
                    'type'      => 'favicon_upload',
                    'directory' => 'branding/favicons',
                ],
            ],
        ];
    }
}
