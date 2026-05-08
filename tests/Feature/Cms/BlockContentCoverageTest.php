<?php

namespace Tests\Feature\Cms;

use App\Cms\BlockRegistry;
use Tests\TestCase;

/**
 * Sentinel test guarding the CMS principle: every registered block partial
 * must read at least one of its content fields via the $page->block(...)
 * accessor. This prevents regressions where a developer adds a new block
 * but forgets to make its content editable from the admin.
 */
class BlockContentCoverageTest extends TestCase
{
    public function test_every_registered_block_uses_page_block_accessor(): void
    {
        $registry = new BlockRegistry;
        $missing  = [];

        foreach ($registry->all() as $type => $meta) {
            $relative = str_replace('.', '/', $meta['view']) . '.blade.php';
            $absolute = resource_path('views/' . $relative);

            $contents = file_get_contents($absolute);
            if (! str_contains($contents, '$page->block(')) {
                $missing[] = "{$type} ({$meta['view']})";
            }
        }

        $this->assertEmpty(
            $missing,
            "These block partials do not call \$page->block(...) anywhere — "
                . "their content is hardcoded and cannot be edited from the admin:\n"
                . implode("\n", $missing),
        );
    }

    public function test_partials_use_consistent_block_type_keys(): void
    {
        // For each registered block, the type string should appear at least once
        // as the first argument to $page->block(...) in its partial. Catches
        // copy-paste bugs where a partial accidentally reads from a different
        // block's data.
        $registry = new BlockRegistry;
        $issues   = [];

        foreach ($registry->all() as $type => $meta) {
            $relative = str_replace('.', '/', $meta['view']) . '.blade.php';
            $absolute = resource_path('views/' . $relative);
            $contents = file_get_contents($absolute);

            // Count $page->block('SOMETHING', ...) usages and ensure $type is among them.
            preg_match_all("/\\\$page->block\\(\\s*['\"]([^'\"]+)['\"]/", $contents, $matches);
            $usedTypes = array_unique($matches[1] ?? []);

            if (! in_array($type, $usedTypes, true)) {
                $issues[] = "{$type} reads from: " . implode(', ', $usedTypes ?: ['(none)']);
            }
        }

        $this->assertEmpty(
            $issues,
            "Block partials read from a block-type key that doesn't match their own registration:\n"
                . implode("\n", $issues),
        );
    }
}
