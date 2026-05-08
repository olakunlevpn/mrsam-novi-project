<?php

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

/**
 * Inject site-wide values into every layout view.
 *
 * - $settings: flat ['key' => value] map of every Setting row
 * - settings() helper-style access via the existing Setting::get('key', default)
 *
 * Wired in AppServiceProvider via View::composer('layouts.app', SiteComposer::class).
 */
class SiteComposer
{
    /**
     * Cached settings map keyed by `key`. Memoized for the request lifetime.
     *
     * @var array<string, mixed>|null
     */
    private static ?array $cache = null;

    public function compose(View $view): void
    {
        $view->with('settings', $this->settings());
    }

    /**
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        $rows = Setting::query()
            ->select(['key', 'value'])
            ->get()
            ->mapWithKeys(fn (Setting $s) => [$s->key => $s->value])
            ->all();

        return static::$cache = $rows;
    }

    /**
     * Reset the in-memory cache. Used by tests with RefreshDatabase so each
     * test sees its own seeded settings.
     */
    public static function clearCache(): void
    {
        static::$cache = null;
    }
}
