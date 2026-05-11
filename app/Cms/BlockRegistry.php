<?php

namespace App\Cms;

use Closure;

/**
 * Maps PageBlock `type` strings to view paths, human labels, and optional
 * Filament field schemas describing the editable shape of the block's `data`
 * JSON column.
 *
 * Each registered block:
 * - `type`   : machine key stored in page_blocks.type
 * - `view`   : view path Laravel will render via @include
 * - `label`  : human label shown in the admin block picker
 * - `group`  : grouping label for the admin picker (e.g. 'Home', 'Headers')
 * - `fields` : optional Closure returning an array of Filament field components.
 *              When provided, the page form replaces the generic KeyValue editor
 *              with a typed form. When null, the generic KeyValue editor is used.
 */
class BlockRegistry
{
    /**
     * @var array<string, array{view: string, label: string, group: string, fields: ?Closure}>
     */
    private array $blocks = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    public function register(string $type, string $view, string $label, string $group = 'General', ?Closure $fields = null): void
    {
        $this->blocks[$type] = [
            'view'   => $view,
            'label'  => $label,
            'group'  => $group,
            'fields' => $fields,
        ];
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->blocks);
    }

    public function viewFor(string $type): ?string
    {
        return $this->blocks[$type]['view'] ?? null;
    }

    public function labelFor(string $type): string
    {
        return $this->blocks[$type]['label'] ?? $type;
    }

    public function groupFor(string $type): string
    {
        return $this->blocks[$type]['group'] ?? 'General';
    }

    /**
     * Whether this block type has a structured field schema.
     */
    public function hasFieldsFor(string $type): bool
    {
        return ! empty($this->blocks[$type]['fields']);
    }

    /**
     * Resolve the structured field schema for a block type.
     *
     * @return array<int, mixed>  Filament field components
     */
    public function fieldsFor(string $type): array
    {
        $resolver = $this->blocks[$type]['fields'] ?? null;
        if ($resolver === null) {
            return [];
        }
        $result = $resolver();
        return is_array($result) ? $result : [];
    }

    /**
     * @return array<string, array{view: string, label: string, group: string, fields: ?Closure}>
     */
    public function all(): array
    {
        return $this->blocks;
    }

    /**
     * @return array<string, string>  type => label, sorted by group then label
     */
    public function selectOptions(): array
    {
        $rows = [];
        foreach ($this->blocks as $type => $meta) {
            $rows[$type] = $meta['group'] . ' / ' . $meta['label'];
        }
        asort($rows);
        return $rows;
    }

    private function registerDefaults(): void
    {
        // Home page blocks
        $this->register('hero',                'blocks.hero',                'Hero Video',                  'Home',
            fn () => BlockSchemas::hero());
        $this->register('feature-grid',        'blocks.feature-grid',        'Feature Grid',                'Home');
        $this->register('about-intro',         'blocks.about-intro',         'About Intro',                 'Home');
        $this->register('species-cards',       'blocks.species-cards',       'Species Cards',               'Home');
        $this->register('services-summary',    'blocks.services-summary',    'Services Summary',            'Home');
        $this->register('work-process',        'blocks.work-process',        'Work Process',                'Home');
        $this->register('benefits',            'blocks.benefits',            'Benefits',                    'Home');
        $this->register('stats-bar',           'blocks.stats-bar',           'Stats Bar',                   'Home');
        $this->register('cta-booking',         'blocks.cta-booking',         'CTA Booking',                 'Home',
            fn () => BlockSchemas::ctaBooking());
        $this->register('partners-carousel',   'blocks.partners-carousel',   'Partners Carousel',           'Home',
            fn () => BlockSchemas::partnersCarousel());

        // About page blocks
        $this->register('about-detail',        'blocks.about-detail',        'About Detail',                'About');
        $this->register('feature-grid-about',  'blocks.feature-grid-about',  'Feature Grid (About)',        'About');
        $this->register('benefits-about',      'blocks.benefits-about',      'Benefits (About)',            'About');
        $this->register('journey-growth',      'blocks.journey-growth',      'Journey & Growth',            'About');
        $this->register('customer-growth',     'blocks.customer-growth',     'Customer Growth',             'About');
        $this->register('testimonials',        'blocks.testimonials',        'Testimonials',                'About',
            fn () => BlockSchemas::testimonials());

        // Page header blocks (one per page) — share schema
        $pageHeaderTypes = [
            'page-header-about'    => 'About',
            'page-header-products' => 'Products',
            'page-header-cattle'   => 'Cattle',
            'page-header-pigs'     => 'Pigs',
            'page-header-poultry'  => 'Poultry',
            'page-header-services' => 'Services',
            'page-header-contact'  => 'Contact',
            'page-header-faq'      => 'FAQ',
        ];
        foreach ($pageHeaderTypes as $type => $label) {
            $this->register($type, 'blocks.' . $type, "Page Header ({$label})", 'Headers',
                fn () => BlockSchemas::pageHeader());
        }

        // Breadcrumb blocks (one per page) — share schema
        $breadcrumbTypes = [
            'breadcrumb-about'    => 'About',
            'breadcrumb-products' => 'Products',
            'breadcrumb-cattle'   => 'Cattle',
            'breadcrumb-pigs'     => 'Pigs',
            'breadcrumb-poultry'  => 'Poultry',
            'breadcrumb-services' => 'Services',
            'breadcrumb-contact'  => 'Contact',
            'breadcrumb-faq'      => 'FAQ',
        ];
        foreach ($breadcrumbTypes as $type => $label) {
            $this->register($type, 'blocks.' . $type, "Breadcrumb ({$label})", 'Breadcrumbs',
                fn () => BlockSchemas::breadcrumb());
        }

        // Catalog / Services / Contact / FAQ
        $this->register('product-catalog',      'blocks.product-catalog',      'Product Catalog',            'Catalog');
        $this->register('service-cards-grid',   'blocks.service-cards-grid',   'Service Cards Grid',         'Services');
        $this->register('contact-info-cards',   'blocks.contact-info-cards',   'Contact Info Cards',         'Contact');
        $this->register('contact-form',         'blocks.contact-form',         'Contact Form',               'Contact',
            fn () => BlockSchemas::contactForm());
        $this->register('contact-map',          'blocks.contact-map',          'Contact Map',                'Contact',
            fn () => BlockSchemas::contactMap());
        $this->register('faq-accordion',        'blocks.faq-accordion',        'FAQ Accordion',              'FAQ',
            fn () => BlockSchemas::faqAccordion());
    }
}
