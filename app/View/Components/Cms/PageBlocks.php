<?php

namespace App\View\Components\Cms;

use App\Cms\BlockRegistry;
use App\Models\Page;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Renders the visible blocks of a Page in order.
 *
 * Usage in a Blade view:
 *   <x-cms.page-blocks :page="$page" />
 *
 * Looks up each block's view through BlockRegistry. Blocks whose `type`
 * has no registered view are silently skipped — this prevents stale rows
 * in the DB from breaking the page render.
 */
class PageBlocks extends Component
{
    public function __construct(
        public Page $page,
        protected ?BlockRegistry $registry = null,
    ) {
        $this->registry ??= app(BlockRegistry::class);
    }

    /**
     * Resolve each visible block to a renderable definition.
     *
     * @return Collection<int, array{view: string, data: array, block: \App\Models\PageBlock}>
     */
    public function items(): Collection
    {
        return $this->page->visibleBlocks
            ->map(function ($block) {
                $view = $this->registry->viewFor($block->type);
                if ($view === null) {
                    return null;
                }
                return [
                    'view'  => $view,
                    'data'  => $block->data ?? [],
                    'block' => $block,
                ];
            })
            ->filter()
            ->values();
    }

    public function render(): View
    {
        return view('components.cms.page-blocks');
    }
}
