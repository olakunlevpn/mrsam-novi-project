<?php

namespace App\Providers;

use App\Cms\BlockRegistry;
use App\View\Composers\SiteComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
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

        // Comment posting throttle: 10/hour per authenticated user (falls back
        // to IP for guests, who are blocked earlier by the auth middleware).
        // Surfaces the localized blog.comment_rate_limit message back on the
        // post page instead of Laravel's default 429.
        RateLimiter::for('comments', function (Request $request) {
            return Limit::perHour(10)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return back()->withErrors(['body' => __('blog.comment_rate_limit')]);
                });
        });
    }
}
