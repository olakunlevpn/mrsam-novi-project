<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageSocialLinks extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.social.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.social.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'social' => [
                'facebook'  => ['label' => 'cms.settings_cluster.field.social_facebook',  'url' => true],
                'instagram' => ['label' => 'cms.settings_cluster.field.social_instagram', 'url' => true],
                'twitter'   => ['label' => 'cms.settings_cluster.field.social_twitter',   'url' => true],
                'linkedin'  => ['label' => 'cms.settings_cluster.field.social_linkedin',  'url' => true],
                'youtube'   => ['label' => 'cms.settings_cluster.field.social_youtube',   'url' => true],
                'tiktok'    => ['label' => 'cms.settings_cluster.field.social_tiktok',    'url' => true],
                'whatsapp'  => ['label' => 'cms.settings_cluster.field.social_whatsapp',  'url' => true],
                'telegram'  => ['label' => 'cms.settings_cluster.field.social_telegram',  'url' => true],
                'pinterest' => ['label' => 'cms.settings_cluster.field.social_pinterest', 'url' => true],
            ],
        ];
    }
}
