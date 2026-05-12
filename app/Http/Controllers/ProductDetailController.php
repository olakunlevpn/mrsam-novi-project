<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductDetailController extends Controller
{
    /**
     * Show a single published product. Replaces the legacy in-page SPA
     * detail view (?id=...) with a dedicated, server-rendered route so each
     * product gets its own canonical URL, SEO meta, and structured data.
     */
    public function show(Product $product)
    {
        if ($product->status !== 'published') {
            // Status-locked 404 so admin-controlled visibility wins over the
            // route-model binding (which only matches by slug).
            throw new NotFoundHttpException("Product '{$product->slug}' not found");
        }

        $product->load(['animal', 'productCategory', 'seoMeta']);

        // Related products: same category, exclude self, dedupe by name
        // (mirrors ProductCatalogService::fetch() so the related strip and
        // the catalog list stay visually consistent).
        $related = Product::query()
            ->published()
            ->with(['animal:id,slug,name', 'productCategory:id,name'])
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->unique('name')
            ->take(3)
            ->values();

        return view('pages.product', [
            'product' => $product,
            'related' => $related,
            // Hand the product to head.blade.php as $page so its existing
            // SeoMeta-driven <title>/canonical/og tags pick up product SEO.
            'page'    => $product,
        ]);
    }
}
