<?php

namespace App\Providers;

use App\Cms\BlockRegistry;
use App\View\Composers\SiteComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BlockRegistry::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', SiteComposer::class);

        // Paginator markup matches the rest of the site (Bootstrap 5).
        // Affects public paginators only; Filament uses its own renderer.
        Paginator::useBootstrapFive();
    }
}
