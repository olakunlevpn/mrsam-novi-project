<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Cache key + TTL (seconds) for the rendered sitemap XML. We cache the
     * full XML so the controller is a single string lookup on hot paths.
     * Published-content edits should clear this cache (or wait 1 hour).
     */
    private const CACHE_KEY = 'sitemap.xml';

    private const CACHE_TTL = 3600;

    public function index()
    {
        $xml = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => $this->buildXml());

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Build the sitemap XML by walking every public surface (home, named
     * static routes, animals, admin-created Pages, published Posts) and
     * skipping anything marked noindex in seo_meta.
     */
    private function buildXml(): string
    {
        $sitemap = Sitemap::create();

        // Home (highest priority).
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setPriority(1.0)
        );

        // Static named pages: each has a corresponding Page row (slug
        // matches), so we look it up for updated_at + seoMeta. Falling back
        // to "now()" + included keeps the route present even if the DB row
        // is missing.
        $staticRoutes = [
            'about'           => 'about',
            'products'        => 'products',
            'animals.cattle'  => 'cattle',
            'animals.pigs'    => 'pigs',
            'animals.poultry' => 'poultry',
            'services'        => 'services',
            'contact'         => 'contact',
            'faq'             => 'faq',
        ];

        $staticPages = Page::with('seoMeta')
            ->whereIn('slug', array_values($staticRoutes))
            ->get()
            ->keyBy('slug');

        foreach ($staticRoutes as $routeName => $slug) {
            $page = $staticPages->get($slug);
            if ($this->isNoindex($page)) {
                continue;
            }
            $sitemap->add(
                Url::create(route($routeName))
                    ->setLastModificationDate($page?->updated_at ?? now())
                    ->setPriority(0.8)
            );
        }

        // Admin-created Pages (catch-all `/{slug}.html`). Skip the slugs
        // already covered by named routes above and any with noindex.
        $reservedSlugs = array_values($staticRoutes);
        $reservedSlugs[] = 'home';

        Page::published()
            ->with('seoMeta')
            ->whereNotIn('slug', $reservedSlugs)
            ->where('is_homepage', false)
            ->get()
            ->each(function (Page $page) use ($sitemap) {
                if ($this->isNoindex($page)) {
                    return;
                }
                $sitemap->add(
                    Url::create(route('page.show', ['slug' => $page->slug]))
                        ->setLastModificationDate($page->updated_at ?? now())
                        ->setPriority(0.6)
                );
            });

        // Blog index.
        if (Route::has('blog.index')) {
            $sitemap->add(
                Url::create(route('blog.index'))
                    ->setLastModificationDate(now())
                    ->setPriority(0.7)
            );
        }

        // Published Posts. Drafts/scheduled are excluded by the scope.
        Post::published()
            ->with('seoMeta')
            ->get()
            ->each(function (Post $post) use ($sitemap) {
                if ($this->isNoindex($post)) {
                    return;
                }
                $sitemap->add(
                    Url::create(route('blog.show', $post))
                        ->setLastModificationDate($post->updated_at ?? $post->published_at ?? now())
                        ->setPriority(0.6)
                );
            });

        return $sitemap->render();
    }

    /**
     * Sitemap rule: respect each model's seoMeta.noindex flag. Null model =>
     * not noindex (just missing DB row).
     */
    private function isNoindex(?Model $model): bool
    {
        if ($model === null) {
            return false;
        }
        /** @var SeoMeta|null $seo */
        $seo = $model->getRelationValue('seoMeta');
        return (bool) ($seo?->noindex);
    }

    /**
     * Flush the cached sitemap. Called from model observers / admin actions
     * after publishing content so the next /sitemap.xml hit rebuilds.
     */
    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
