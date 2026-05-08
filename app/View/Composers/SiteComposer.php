<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\View\View;

/**
 * Inject site-wide values into every layout view.
 *
 * - $settings: flat ['key' => value] map of every Setting row
 * - $menus:    ['location' => Menu] map of every Menu, eager-loaded with items + children
 *
 * Wired in AppServiceProvider via View::composer('layouts.app', SiteComposer::class).
 */
class SiteComposer
{
    /**
     * @var array<string, mixed>|null
     */
    private static ?array $settingsCache = null;

    /**
     * @var array<string, \App\Models\Menu>|null
     */
    private static ?array $menusCache = null;

    public function compose(View $view): void
    {
        $view->with('settings', $this->settings());
        $view->with('menus',    $this->menus());
    }

    /**
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        if (static::$settingsCache !== null) {
            return static::$settingsCache;
        }

        $rows = Setting::query()
            ->select(['key', 'value'])
            ->get()
            ->mapWithKeys(fn (Setting $s) => [$s->key => $s->value])
            ->all();

        return static::$settingsCache = $rows;
    }

    /**
     * @return array<string, \App\Models\Menu>
     */
    private function menus(): array
    {
        if (static::$menusCache !== null) {
            return static::$menusCache;
        }

        $rows = Menu::query()
            ->with(['items.children'])
            ->get()
            ->mapWithKeys(fn (Menu $m) => [$m->location => $m])
            ->all();

        return static::$menusCache = $rows;
    }

    /**
     * Reset the in-memory caches. Used by tests with RefreshDatabase so each
     * test sees its own seeded settings and menus.
     */
    public static function clearCache(): void
    {
        static::$settingsCache = null;
        static::$menusCache    = null;
    }
}
