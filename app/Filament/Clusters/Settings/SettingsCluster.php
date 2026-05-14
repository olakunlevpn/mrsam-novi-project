<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 90;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return __('cms.nav.group.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('cms.settings_cluster.nav.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('cms.settings_cluster.nav.label');
    }
}
