<?php

namespace App\Providers;

use App\Cms\BlockRegistry;
use App\Http\Controllers\SitemapController;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use App\Models\Setting;
use App\Observers\CommentObserver;
use App\Observers\ContactSubmissionObserver;
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

        // Queues email notifications to admins (and the parent comment
        // author for replies) whenever a new comment is persisted.
        Comment::observe(CommentObserver::class);

        // Queues an email alert to admins whenever a public contact form
        // submission lands in the database.
        ContactSubmission::observe(ContactSubmissionObserver::class);

        // Flush the cached sitemap whenever publishable content changes so
        // admin edits surface in /sitemap.xml before the 1-hour TTL elapses.
        $flushSitemap = fn () => SitemapController::forget();

        foreach ([Page::class, Post::class, SeoMeta::class] as $model) {
            $model::saved($flushSitemap);
            $model::deleted($flushSitemap);
        }

        // Clear SiteComposer's in-process caches whenever the underlying
        // settings or navigation data changes so admin updates surface on
        // the next request instead of waiting for a worker restart.
        $flushSiteComposer = fn () => SiteComposer::clearCache();

        foreach ([Setting::class, Menu::class, MenuItem::class] as $model) {
            $model::saved($flushSiteComposer);
            $model::deleted($flushSiteComposer);
        }
    }
}
