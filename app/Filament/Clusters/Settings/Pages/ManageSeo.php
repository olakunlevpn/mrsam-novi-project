<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageSeo extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.seo.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.seo.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'seo' => [
                'robots_txt' => [
                    'label'       => 'cms.settings_cluster.field.seo_robots_txt',
                    'placeholder' => "User-agent: *\nDisallow: /admin",
                    'textarea'    => true,
                ],
            ],
        ];
    }
}
