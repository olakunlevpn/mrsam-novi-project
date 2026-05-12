<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function home() { return $this->renderPage('home'); }
    public function about() { return $this->renderPage('about'); }
    public function products() { return $this->renderPage('products'); }
    public function cattle() { return $this->renderPage('cattle'); }
    public function pigs() { return $this->renderPage('pigs'); }
    public function poultry() { return $this->renderPage('poultry'); }
    public function services() { return $this->renderPage('services'); }
    public function contact() { return $this->renderPage('contact'); }
    public function faq() { return $this->renderPage('faq'); }

    /**
     * Generic catch-all for admin-created Pages whose slug does not have a
     * dedicated route + Blade. Renders via the shared `pages.cms` shell.
     *
     * The named routes above take precedence because Laravel matches them
     * before this fallback. So /about still hits about(), not show().
     */
    public function show(string $slug)
    {
        $page = Page::published()
            ->with(['blocks', 'seoMeta'])
            ->where('slug', $slug)
            ->first();

        if (! $page) {
            throw new NotFoundHttpException("Page '{$slug}' not found");
        }

        $animal = Animal::where('slug', $slug)->first();

        // Prefer a dedicated `pages.{slug}` Blade if one exists (legacy pages),
        // else render via the generic CMS shell.
        $view = view()->exists("pages.{$slug}") ? "pages.{$slug}" : 'pages.cms';

        return view($view, [
            'page'   => $page,
            'animal' => $animal,
        ]);
    }

    private function renderPage(string $slug)
    {
        $page = Page::published()
            ->where('slug', $slug)
            ->with(['blocks', 'seoMeta'])
            ->first();

        if (! $page) {
            // A row exists but is not currently published (draft or scheduled).
            // 404 so admin-controlled visibility wins over the legacy stub.
            if (Page::where('slug', $slug)->exists()) {
                throw new NotFoundHttpException("Page '{$slug}' not found");
            }

            $page = new Page(['slug' => $slug, 'title' => ucfirst($slug)]);
            $page->setRelation('blocks', collect());
            $page->setRelation('seoMeta', null);
        }

        // If this slug matches an Animal record, expose it to the view so
        // animal page-header / breadcrumb blocks can use $animal->hero_image
        // and $animal->name as their defaults.
        $animal = Animal::where('slug', $slug)->first();

        return view("pages.{$slug}", [
            'page'   => $page,
            'animal' => $animal,
        ]);
    }
}
