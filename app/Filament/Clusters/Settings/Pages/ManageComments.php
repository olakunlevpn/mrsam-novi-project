<?php

namespace App\Filament\Clusters\Settings\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ManageComments extends AbstractSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.pages.comments.nav');
    }

    public function getTitle(): string
    {
        return __('cms.settings_cluster.pages.comments.title');
    }

    protected function fieldDefinitions(): array
    {
        return [
            'comments' => [
                'moderation' => [
                    'type'    => 'toggle',
                    'label'   => 'cms.settings_cluster.field.comments_moderation',
                    'default' => true,
                ],
            ],
        ];
    }
}
