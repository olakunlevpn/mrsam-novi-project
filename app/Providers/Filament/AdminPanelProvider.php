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
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
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
            ->renderHook(PanelsRenderHook::HEAD_END, fn (): HtmlString => $this->brandStyles())
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

    /**
     * Brand-aligned CSS overrides injected into the admin <head>.
     *
     * Filament's auto-contrast picks the primary-400 shade for buttons in
     * light mode, which makes mid-tone greens look washed-out with dark
     * text. We pin all primary buttons to the solid brand green (#1a9120)
     * with white text, and dress the sidebar in a light tint of the same
     * green so navigation feels part of the same family. Hover and active
     * states use darker shades from the same scale for depth.
     */
    private function brandStyles(): HtmlString
    {
        $css = <<<'CSS'
:root {
    --novi-50:  #e8f5ea;
    --novi-100: #c8e6cc;
    --novi-200: #a5d6a9;
    --novi-300: #7cc481;
    --novi-400: #4fb053;
    --novi-500: #2da130;
    --novi-600: #1a9120;
    --novi-700: #14781b;
    --novi-800: #0f5f15;
    --novi-900: #0a3f0e;
}

/* Solid brand buttons with white text in light mode. */
.fi-btn.fi-color-primary,
.fi-btn.fi-btn-color-primary,
button.fi-color-primary.fi-btn {
    background-color: var(--novi-600) !important;
    color: #ffffff !important;
    border-color: var(--novi-600) !important;
}
.fi-btn.fi-color-primary:hover,
.fi-btn.fi-btn-color-primary:hover,
button.fi-color-primary.fi-btn:hover {
    background-color: var(--novi-700) !important;
    border-color: var(--novi-700) !important;
    color: #ffffff !important;
}
.fi-btn.fi-color-primary:focus,
.fi-btn.fi-btn-color-primary:focus {
    outline-color: var(--novi-500) !important;
}

/* Sidebar dressed in a light tint of the brand green. */
.fi-sidebar,
.fi-sidebar-nav {
    background-color: var(--novi-50) !important;
    border-right: 1px solid var(--novi-100) !important;
}
.fi-sidebar-header {
    background-color: var(--novi-50) !important;
    border-bottom: 1px solid var(--novi-100) !important;
}
.fi-sidebar-group-label {
    color: var(--novi-700) !important;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Default state for sidebar menu items. */
.fi-sidebar-item-btn {
    color: var(--novi-900) !important;
}
.fi-sidebar-item-btn .fi-icon,
.fi-sidebar-item-btn svg {
    color: var(--novi-700) !important;
}

/* Hover: solid brand green with white text + icon. */
.fi-sidebar-item-btn:hover,
.fi-sidebar-item > .fi-sidebar-item-btn:hover {
    background-color: var(--novi-600) !important;
    color: #ffffff !important;
}
.fi-sidebar-item-btn:hover .fi-icon,
.fi-sidebar-item-btn:hover svg,
.fi-sidebar-item-btn:hover .fi-sidebar-item-label {
    color: #ffffff !important;
}

/* Active item: solid brand green pill with white text. */
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
.fi-sidebar-item-btn.fi-active {
    background-color: var(--novi-600) !important;
    color: #ffffff !important;
}
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn:hover,
.fi-sidebar-item-btn.fi-active:hover {
    background-color: var(--novi-700) !important;
    color: #ffffff !important;
}
.fi-sidebar-item.fi-active .fi-icon,
.fi-sidebar-item.fi-active svg,
.fi-sidebar-item.fi-active .fi-sidebar-item-label,
.fi-sidebar-item-btn.fi-active .fi-icon,
.fi-sidebar-item-btn.fi-active svg,
.fi-sidebar-item-btn.fi-active .fi-sidebar-item-label {
    color: #ffffff !important;
}

/* Topbar collapse trigger sits in the sidebar header on desktop. */
.fi-topbar-open-sidebar-btn:hover,
.fi-sidebar-close-collapse-sidebar-btn:hover {
    background-color: var(--novi-100) !important;
}

/* Table header tinted to match the sidebar so the panel reads as one
   continuous surface instead of a white slab next to a green rail. */
.fi-ta thead,
.fi-ta-header-cell {
    background-color: var(--novi-50) !important;
}
.fi-ta-header-cell {
    color: var(--novi-900) !important;
    border-bottom-color: var(--novi-100) !important;
}
CSS;

        return new HtmlString('<style>' . $css . '</style>');
    }
}
