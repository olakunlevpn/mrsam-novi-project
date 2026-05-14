<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageSiteIdentity extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.site.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.site.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'site' => [
                'title_suffix' => ['label' => 'cms.settings_cluster.field.site_title_suffix'],
            ],
        ];
    }
}
