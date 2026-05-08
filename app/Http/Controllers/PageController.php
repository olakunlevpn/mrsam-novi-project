<?php

namespace App\Http\Controllers;

use App\Models\Page;

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

    private function renderPage(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->with(['blocks', 'seoMeta'])
            ->first();

        if (! $page) {
            $page = new Page(['slug' => $slug, 'title' => ucfirst($slug)]);
            $page->setRelation('blocks', collect());
            $page->setRelation('seoMeta', null);
        }

        return view("pages.{$slug}", ['page' => $page]);
    }
}
