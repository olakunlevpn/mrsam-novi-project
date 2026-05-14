<?php

namespace App\Concerns;

use App\Support\AssetUrl;
use Illuminate\Support\Collection;

/**
 * Adds typed access to a model's PageBlock collection.
 *
 * Hosting model must define a `blocks()` HasMany relation returning PageBlocks.
 */
trait HasBlocks
{
    /**
     * Per-instance cache of visible blocks grouped by type. Each Page render
     * does many $page->block(type, key) lookups; without this each lookup
     * re-filters the entire blocks collection.
     *
     * @var array<string, Collection>|null
     */
    private ?array $blocksByTypeCache = null;

    /**
     * All blocks of a given type, in order. Returns an empty collection if none.
     */
    public function blocksOfType(string $type): Collection
    {
        return $this->blocksGroupedByType()[$type] ?? collect();
    }

    /**
     * The first block of a given type. Useful for sections that should appear once
     * (e.g. a single hero per page).
     */
    public function blockData(string $type, ?int $index = null): ?array
    {
        $matching = $this->blocksOfType($type);

        if ($index === null) {
            return $matching->first()?->data;
        }

        return $matching->get($index)?->data;
    }

    /**
     * Convenience: pull a specific key out of the first block of a type, with a default.
     * Useful for `$page->block('hero', 'headline', 'Default Headline')`.
     */
    public function block(string $type, string $key, mixed $default = null): mixed
    {
        $data = $this->blockData($type) ?? [];
        return data_get($data, $key, $default);
    }

    /**
     * Same as `block()` but resolves the returned value through AssetUrl
     * so image fields stored as public-disk paths render as URLs. Use
     * for `background_image`, `image_vet`, partner logos and similar.
     */
    public function blockAsset(string $type, string $key, ?string $default = null): ?string
    {
        $raw = $this->block($type, $key);
        if (! is_string($raw) || $raw === '') {
            return $default;
        }
        return AssetUrl::resolve($raw, $default);
    }

    /**
     * Reset the in-memory block cache. Call after mutating blocks within a
     * single request (e.g. updating data in a test then re-rendering).
     */
    public function clearBlocksCache(): void
    {
        $this->blocksByTypeCache = null;
    }

    /**
     * Group the loaded blocks collection by type, filtering hidden ones once.
     *
     * @return array<string, Collection>
     */
    private function blocksGroupedByType(): array
    {
        if ($this->blocksByTypeCache !== null) {
            return $this->blocksByTypeCache;
        }

        return $this->blocksByTypeCache = $this->blocks
            ->where('is_visible', true)
            ->groupBy('type')
            ->map(fn (Collection $group) => $group->values())
            ->all();
    }
}
