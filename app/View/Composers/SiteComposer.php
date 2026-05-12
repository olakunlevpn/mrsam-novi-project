<?php

namespace App\View\Composers;

use App\Models\Animal;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

/**
 * Inject site-wide values into every layout view.
 *
 * - $settings:          flat ['key' => value] map of every Setting row
 * - $menus:             ['location' => Menu] map of every Menu, eager-loaded with items + children
 * - $footerCategories:  collection of {label, url} entries derived from the Animal table
 * - $footerProducts:    collection of the two most-recently-updated published Products
 *
 * Wired in AppServiceProvider via View::composer('layouts.app', SiteComposer::class).
 */
class SiteComposer
{
    /**
     * Cache TTL (seconds) for the footer collections. Busted on Animal/Product
     * saves via AppServiceProvider, so the TTL is the worst-case staleness
     * window if the bust hook is bypassed (e.g. tinker, manual SQL).
     */
    public const FOOTER_CACHE_TTL = 600;

    public const FOOTER_CATEGORIES_KEY = 'site.footer_categories';

    public const FOOTER_PRODUCTS_KEY = 'site.footer_products';

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
        $view->with('settings',         $this->settings());
        $view->with('menus',            $this->menus());
        $view->with('footerCategories', $this->footerCategories());
        $view->with('footerProducts',   $this->footerProducts());
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
     * Footer "Categories" widget data. Sourced from the Animal table so the
     * widget tracks whatever animals the admin has actually published, with
     * static "All Products" + "FAQ" tail entries. Cached per request and via
     * the framework cache for FOOTER_CACHE_TTL seconds.
     *
     * @return Collection<int, array{label: string, url: string}>
     */
    public function footerCategories(): Collection
    {
        $animals = Animal::query()
            ->orderBy('order_column')
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Animal $animal): array => [
                'label' => $animal->name,
                'url'   => Route::has('animals.' . $animal->slug)
                    ? route('animals.' . $animal->slug)
                    : url('/' . $animal->slug),
            ]);

        return $animals->push(
            ['label' => __('site.footer.all_products'), 'url' => route('products')],
            ['label' => __('site.footer.faq'),          'url' => route('faq')],
        )->values();
    }

    /**
     * Footer "Products" widget data. Two most-recently-touched published
     * products, restricted to those that have a hero image so the card slot
     * never renders an empty <img>. Not cached: caching Eloquent models in
     * the framework cache breaks across deploys when the model signature
     * changes (stale rows unserialize as __PHP_Incomplete_Class).
     *
     * @return Collection<int, \App\Models\Product>
     */
    public function footerProducts(): Collection
    {
        return Product::query()
            ->where('status', 'published')
            ->whereNotNull('hero_image')
            ->orderByDesc('updated_at')
            ->limit(2)
            ->get(['id', 'slug', 'name', 'hero_image', 'updated_at']);
    }

    /**
     * Reset the in-memory caches. Used by tests with RefreshDatabase so each
     * test sees its own seeded settings and menus. Framework-level cache
     * keys are flushed too so the footer collections re-resolve in the next
     * request within the same test.
     */
    public static function clearCache(): void
    {
        static::$settingsCache = null;
        static::$menusCache    = null;

        Cache::forget(self::FOOTER_CATEGORIES_KEY);
        Cache::forget(self::FOOTER_PRODUCTS_KEY);
    }
}
