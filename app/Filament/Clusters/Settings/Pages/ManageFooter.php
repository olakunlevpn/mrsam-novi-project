<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageFooter extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.footer.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.footer.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'footer' => [
                'categories_title' => ['label' => 'cms.settings_cluster.field.footer_categories_title'],
                'gallery_title'    => ['label' => 'cms.settings_cluster.field.footer_gallery_title'],
                'products_title'   => ['label' => 'cms.settings_cluster.field.footer_products_title'],
                'gallery_images'   => [
                    'label' => 'cms.settings_cluster.field.footer_gallery_images',
                    'type'  => 'gallery_repeater',
                ],
            ],
        ];
    }
}
