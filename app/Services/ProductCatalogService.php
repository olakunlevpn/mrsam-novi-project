<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCatalogService
{
    public const ITEMS_PER_PAGE = 12;

    /**
     * Fetch a paginated, filtered, sorted, name-deduped slice of the catalog.
     *
     * Mirrors the legacy public/assets/js/services/product.service.js
     * fetchProducts() shape so the frontend can swap to this endpoint
     * without changing its consumer code.
     */
    public function fetch(array $filters): array
    {
        $animal = $filters['animal'] ?? 'all';
        $type = $filters['type'] ?? 'all';
        $search = trim((string) ($filters['search'] ?? ''));
        $sort = $filters['sort'] ?? 'default';
        $page = max(1, (int) ($filters['page'] ?? 1));

        $query = Product::query()
            ->published()
            ->select('products.*')
            ->with(['animal:id,slug,name', 'productCategory:id,name'])
            ->when($animal !== 'all', function ($q) use ($animal) {
                $q->whereHas('animal', fn ($qa) => $qa->where('slug', strtolower($animal)));
            })
            ->when($type !== 'all', function ($q) use ($type) {
                $q->whereHas('productCategory', fn ($qc) => $qc->where('name', $type));
            })
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                      ->orWhere('description', 'like', $like);
                });
            });

        $all = $query->get();

        // Name de-dup (matches client-side Map by name, first occurrence wins)
        $deduped = $all->unique('name')->values();

        $sorted = match ($sort) {
            'z-a' => $deduped->sortByDesc(fn ($p) => Str::lower($p->name))->values(),
            'newest' => $deduped->sortByDesc('id')->values(),
            'popular' => $deduped->sortByDesc(fn ($p) => Str::length((string) $p->description))->values(),
            default => $deduped->sortBy(fn ($p) => Str::lower($p->name))->values(),
        };

        $totalItems = $sorted->count();
        $totalPages = max(1, (int) ceil($totalItems / self::ITEMS_PER_PAGE));
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $start = ($page - 1) * self::ITEMS_PER_PAGE;
        $items = $sorted->slice($start, self::ITEMS_PER_PAGE)->values();

        return [
            'items' => $items->map(fn ($p) => $this->shape($p))->all(),
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'itemsPerPage' => self::ITEMS_PER_PAGE,
            'startItem' => $totalItems === 0 ? 0 : $start + 1,
            'endItem' => min($page * self::ITEMS_PER_PAGE, $totalItems),
        ];
    }

    private function shape(Product $p): array
    {
        return [
            'id' => $p->sku ?: $p->slug,
            // Slug is the canonical key for the per-product detail page;
            // the catalog JS uses it to build `/products/{slug}` links.
            'slug' => $p->slug,
            'name' => $p->name,
            'image' => $p->hero_image ? Storage::disk('public')->url($p->hero_image) : null,
            'category' => $p->productCategory?->name,
            'animal' => $p->animal?->slug,
            'description' => (string) $p->description,
            'composition' => (string) $p->composition,
            'benefits' => (string) ($p->benefits ?? ''),
            'usage' => (string) ($p->usage_instructions ?? ''),
        ];
    }
}
