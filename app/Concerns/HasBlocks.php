<?php

namespace App\Concerns;

use Illuminate\Support\Collection;

/**
 * Adds typed access to a model's PageBlock collection.
 *
 * Hosting model must define a `blocks()` HasMany relation returning PageBlocks.
 */
trait HasBlocks
{
    /**
     * All blocks of a given type, in order. Returns an empty collection if none.
     */
    public function blocksOfType(string $type): Collection
    {
        return $this->blocks
            ->where('type', $type)
            ->where('is_visible', true)
            ->values();
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
}
