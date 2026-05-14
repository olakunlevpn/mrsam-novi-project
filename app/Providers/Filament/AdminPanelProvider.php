<?php

namespace App\Providers\Filament;

use App\Models\Setting;
use App\Support\AssetUrl;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(false)
            ->font('Inter')
            ->brandName(__('admin.brand_name'))
            ->brandLogo(fn (): ?string => $this->resolveBrandAsset('brand.logo'))
            ->favicon(fn (): ?string => $this->resolveBrandAsset('brand.favicon'))
            // Palette pulled from the public site. #1a9120 is the dominant
            // Novi Agro green used across grdeen.css; we mirror it as the
            // admin's primary so notifications, focus rings, badges and
            // active nav items all read as the same brand.
            ->colors([
                'primary' => Color::hex('#1a9120'),
                'success' => Color::hex('#1a9120'),
                'info'    => Color::hex('#078f19'),
                'warning' => Color::Amber,
                'danger'  => Color::Red,
                'gray'    => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * Read a branding setting and return a URL the panel can serve. Wrapped
     * in a try/catch so a missing settings table (e.g. before the very
     * first migrate) never breaks panel boot.
     */
    private function resolveBrandAsset(string $key): ?string
    {
        try {
            $value = Setting::get($key);
        } catch (\Throwable) {
            return null;
        }

        return is_string($value) ? AssetUrl::resolve($value) : null;
    }
}
