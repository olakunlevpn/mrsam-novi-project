<?php

namespace App\Cms;

/**
 * Maps PageBlock `type` strings to view paths and human labels.
 *
 * The block list mirrors the `resources/views/blocks/*.blade.php` partials
 * that page Blade files used to @include directly. By registering them here,
 * the Filament admin can list available block types and the frontend renderer
 * can resolve a `type` string back to a Blade view path.
 *
 * Each registered block:
 * - `type`  : machine key stored in page_blocks.type
 * - `view`  : view path Laravel will render via @include
 * - `label` : human label shown in the admin block picker
 * - `group` : grouping label for the admin picker (e.g. 'Home', 'Headers')
 */
class BlockRegistry
{
    /**
     * @var array<string, array{view: string, label: string, group: string}>
     */
    private array $blocks = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    public function register(string $type, string $view, string $label, string $group = 'General'): void
    {
        $this->blocks[$type] = [
            'view'  => $view,
            'label' => $label,
            'group' => $group,
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
     * @return array<string, array{view: string, label: string, group: string}>
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
        $this->register('hero',                'blocks.hero',                'Hero Video',                  'Home');
        $this->register('feature-grid',        'blocks.feature-grid',        'Feature Grid',                'Home');
        $this->register('about-intro',         'blocks.about-intro',         'About Intro',                 'Home');
        $this->register('species-cards',       'blocks.species-cards',       'Species Cards',               'Home');
        $this->register('services-summary',    'blocks.services-summary',    'Services Summary',            'Home');
        $this->register('work-process',        'blocks.work-process',        'Work Process',                'Home');
        $this->register('benefits',            'blocks.benefits',            'Benefits',                    'Home');
        $this->register('stats-bar',           'blocks.stats-bar',           'Stats Bar',                   'Home');
        $this->register('cta-booking',         'blocks.cta-booking',         'CTA Booking',                 'Home');
        $this->register('partners-carousel',   'blocks.partners-carousel',   'Partners Carousel',           'Home');

        // About page blocks
        $this->register('about-detail',        'blocks.about-detail',        'About Detail',                'About');
        $this->register('feature-grid-about',  'blocks.feature-grid-about',  'Feature Grid (About)',        'About');
        $this->register('benefits-about',      'blocks.benefits-about',      'Benefits (About)',            'About');
        $this->register('journey-growth',      'blocks.journey-growth',      'Journey & Growth',            'About');
        $this->register('customer-growth',     'blocks.customer-growth',     'Customer Growth',             'About');
        $this->register('testimonials',        'blocks.testimonials',        'Testimonials',                'About');

        // Page header blocks (one per page)
        $this->register('page-header-about',     'blocks.page-header-about',     'Page Header (About)',     'Headers');
        $this->register('page-header-products',  'blocks.page-header-products',  'Page Header (Products)',  'Headers');
        $this->register('page-header-cattle',    'blocks.page-header-cattle',    'Page Header (Cattle)',    'Headers');
        $this->register('page-header-pigs',      'blocks.page-header-pigs',      'Page Header (Pigs)',      'Headers');
        $this->register('page-header-poultry',   'blocks.page-header-poultry',   'Page Header (Poultry)',   'Headers');
        $this->register('page-header-services',  'blocks.page-header-services',  'Page Header (Services)',  'Headers');
        $this->register('page-header-contact',   'blocks.page-header-contact',   'Page Header (Contact)',   'Headers');
        $this->register('page-header-faq',       'blocks.page-header-faq',       'Page Header (FAQ)',       'Headers');

        // Breadcrumb blocks (one per page)
        $this->register('breadcrumb-about',      'blocks.breadcrumb-about',      'Breadcrumb (About)',      'Breadcrumbs');
        $this->register('breadcrumb-products',   'blocks.breadcrumb-products',   'Breadcrumb (Products)',   'Breadcrumbs');
        $this->register('breadcrumb-cattle',     'blocks.breadcrumb-cattle',     'Breadcrumb (Cattle)',     'Breadcrumbs');
        $this->register('breadcrumb-pigs',       'blocks.breadcrumb-pigs',       'Breadcrumb (Pigs)',       'Breadcrumbs');
        $this->register('breadcrumb-poultry',    'blocks.breadcrumb-poultry',    'Breadcrumb (Poultry)',    'Breadcrumbs');
        $this->register('breadcrumb-services',   'blocks.breadcrumb-services',   'Breadcrumb (Services)',   'Breadcrumbs');
        $this->register('breadcrumb-contact',    'blocks.breadcrumb-contact',    'Breadcrumb (Contact)',    'Breadcrumbs');
        $this->register('breadcrumb-faq',        'blocks.breadcrumb-faq',        'Breadcrumb (FAQ)',        'Breadcrumbs');

        // Catalog / Services / Contact / FAQ
        $this->register('product-catalog',      'blocks.product-catalog',      'Product Catalog',            'Catalog');
        $this->register('service-cards-grid',   'blocks.service-cards-grid',   'Service Cards Grid',         'Services');
        $this->register('contact-info-cards',   'blocks.contact-info-cards',   'Contact Info Cards',         'Contact');
        $this->register('contact-form',         'blocks.contact-form',         'Contact Form',               'Contact');
        $this->register('contact-map',          'blocks.contact-map',          'Contact Map',                'Contact');
        $this->register('faq-accordion',        'blocks.faq-accordion',        'FAQ Accordion',              'FAQ');
    }
}
