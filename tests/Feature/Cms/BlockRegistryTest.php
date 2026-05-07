<?php

namespace Tests\Feature\Cms;

use App\Cms\BlockRegistry;
use Tests\TestCase;

class BlockRegistryTest extends TestCase
{
    public function test_registers_all_seeded_block_types(): void
    {
        $registry = new BlockRegistry;

        // Every block type seeded by PageSeeder must resolve in the registry.
        $seededTypes = [
            // Home
            'hero', 'feature-grid', 'about-intro', 'species-cards',
            'services-summary', 'work-process', 'benefits', 'stats-bar',
            'cta-booking', 'partners-carousel',
            // About
            'page-header-about', 'breadcrumb-about', 'about-detail',
            'feature-grid-about', 'benefits-about', 'journey-growth',
            'customer-growth', 'testimonials',
            // Products / Animal pages
            'page-header-products', 'breadcrumb-products', 'product-catalog',
            'page-header-cattle', 'breadcrumb-cattle',
            'page-header-pigs', 'breadcrumb-pigs',
            'page-header-poultry', 'breadcrumb-poultry',
            // Services
            'page-header-services', 'breadcrumb-services', 'service-cards-grid',
            // Contact
            'page-header-contact', 'breadcrumb-contact',
            'contact-info-cards', 'contact-form', 'contact-map',
            // FAQ
            'page-header-faq', 'breadcrumb-faq', 'faq-accordion',
        ];

        foreach ($seededTypes as $type) {
            $this->assertTrue(
                $registry->has($type),
                "BlockRegistry is missing seeded block type: {$type}",
            );
        }
    }

    public function test_view_for_returns_blocks_namespace_view(): void
    {
        $registry = new BlockRegistry;

        $this->assertSame('blocks.hero', $registry->viewFor('hero'));
        $this->assertSame('blocks.product-catalog', $registry->viewFor('product-catalog'));
    }

    public function test_view_for_unknown_type_returns_null(): void
    {
        $registry = new BlockRegistry;

        $this->assertNull($registry->viewFor('does-not-exist'));
    }

    public function test_label_falls_back_to_type_for_unknown(): void
    {
        $registry = new BlockRegistry;

        $this->assertSame('does-not-exist', $registry->labelFor('does-not-exist'));
    }

    public function test_label_for_known_type_is_human_readable(): void
    {
        $registry = new BlockRegistry;

        $this->assertSame('Hero Video', $registry->labelFor('hero'));
        $this->assertSame('Product Catalog', $registry->labelFor('product-catalog'));
    }

    public function test_select_options_returns_grouped_strings(): void
    {
        $registry = new BlockRegistry;

        $options = $registry->selectOptions();

        $this->assertArrayHasKey('hero', $options);
        $this->assertStringContainsString('Home', $options['hero']);
        $this->assertStringContainsString('Hero Video', $options['hero']);
    }

    public function test_register_overrides_existing_block(): void
    {
        $registry = new BlockRegistry;

        $registry->register('hero', 'blocks.custom-hero', 'Custom Hero', 'Custom');

        $this->assertSame('blocks.custom-hero', $registry->viewFor('hero'));
        $this->assertSame('Custom Hero', $registry->labelFor('hero'));
        $this->assertSame('Custom', $registry->groupFor('hero'));
    }

    public function test_singleton_resolves_from_container(): void
    {
        $a = app(BlockRegistry::class);
        $b = app(BlockRegistry::class);

        $this->assertSame($a, $b);
    }

    public function test_every_registered_view_has_a_blade_file_on_disk(): void
    {
        $registry = new BlockRegistry;

        foreach ($registry->all() as $type => $meta) {
            $relative = str_replace('.', '/', $meta['view']) . '.blade.php';
            $absolute = resource_path('views/' . $relative);

            $this->assertFileExists(
                $absolute,
                "Block '{$type}' references missing view {$meta['view']} ({$relative})",
            );
        }
    }
}
