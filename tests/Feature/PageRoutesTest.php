<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('pageUrlProvider')]
    public function test_page_returns_200(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public static function pageUrlProvider(): array
    {
        return [
            ['/'],
            ['/about.html'],
            ['/products.html'],
            ['/cattle.html'],
            ['/pigs.html'],
            ['/poultry.html'],
            ['/services.html'],
            ['/contact.html'],
            ['/faq.html'],
        ];
    }

    public function test_layout_renders_header_brand_and_footer(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Novi Agro', false);
    }

    public function test_animal_page_sets_body_category(): void
    {
        $this->get('/cattle.html')->assertSee('data-category="Cattle"', false);
        $this->get('/pigs.html')->assertSee('data-category="Pigs"', false);
        $this->get('/poultry.html')->assertSee('data-category="Poultry"', false);
        $this->get('/products.html')->assertSee('data-category="All"', false);
    }

    public function test_non_catalog_pages_omit_body_category(): void
    {
        foreach (['/', '/about.html', '/services.html', '/contact.html', '/faq.html'] as $url) {
            $this->get($url)->assertDontSee('data-category=', false);
        }
    }

    public function test_home_renders_all_section_blocks(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        // hero block
        $response->assertSee('WELCOME TO NOVI-AGRO', false);
        // feature-grid block
        $response->assertSee('Livestock Feeding Award', false);
        // about-intro block
        $response->assertSee('Brings your farming business to life', false);
        // species-cards block
        $response->assertSee('species-section__heading', false);
        // services-summary block
        $response->assertSee('Nutrient-rich additives for optimal livestock health', false);
        // work-process block
        $response->assertSee('See how we complete the work', false);
        // benefits block
        $response->assertSee('Why is mine different from others?', false);
        // stats-bar block
        $response->assertSee('The grass is always', false);
        // cta-booking block
        $response->assertSee('Book a FREE', false);
        // partners-carousel block
        $response->assertSee('Partnering with Global Leaders in', false);
    }

    public function test_about_renders_all_section_blocks(): void
    {
        $response = $this->get('/about.html');
        $response->assertOk();
        // page-header-about block
        $response->assertSee('committed to providing quality livestock additives', false);
        // breadcrumb-about block
        $response->assertSee('About Us', false);
        // about-detail block
        $response->assertSee('We offer expert livestock solutions', false);
        // feature-grid-about block
        $response->assertSee('Trusted by over 2,000 farmers across Nigeria', false);
        // benefits-about block
        $response->assertSee('Our expertise in', false);
        // journey-growth block
        $response->assertSee('Novi-Agro began its journey in 2022', false);
        // customer-growth block
        $response->assertSee('Growth Metrics', false);
        // testimonials block
        $response->assertSee('Hear from our happy', false);
    }

    public function test_home_emits_organization_jsonld(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('"@type": "Organization"', false);
        $response->assertSee('"@context"', false);
    }

    public function test_home_pushes_inline_styles(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        // First CSS rule in the inline style block, unique to the home page
        $response->assertSee('.video-hero-one {', false);
    }

    public function test_products_renders_all_section_blocks(): void
    {
        $response = $this->get('/products.html');
        $response->assertOk();
        // page-header-products block
        $response->assertSee('Our Products', false);
        // breadcrumb-products block
        $response->assertSee('grdeen-breadcrumb', false);
        // product-catalog block — catalog listing wrapper
        $response->assertSee('catalog-listing', false);
        // product-catalog block — filter sidebar
        $response->assertSee('product-type-filters', false);
        // product-catalog block — product container slot
        $response->assertSee('product-container', false);
        // product-catalog block — product detail view
        $response->assertSee('product-details', false);
        // product-catalog block — sort controls
        $response->assertSee('sort-dropdown', false);
    }

    public function test_products_loads_catalog_scripts(): void
    {
        $response = $this->get('/products.html');
        $response->assertOk();
        $response->assertDontSee('/assets/js/products-data.js', false);
        $response->assertSee('/assets/js/state/app.state.js', false);
        $response->assertSee('/assets/js/main.js', false);
    }

    public function test_home_does_not_load_catalog_scripts(): void
    {
        $response = $this->get('/');
        $response->assertDontSee('/assets/js/products-data.js', false);
        $response->assertDontSee('/assets/js/state/app.state.js', false);
    }

    public function test_cattle_renders_all_section_blocks(): void
    {
        $response = $this->get('/cattle.html');
        $response->assertOk();
        $response->assertSee('page-header__title">Cattle', false);
        $response->assertSee('Livestock Solutions', false);
        $response->assertSee('product__showing-wrap', false);
    }

    public function test_pigs_renders_all_section_blocks(): void
    {
        $response = $this->get('/pigs.html');
        $response->assertOk();
        $response->assertSee('page-header__title">Pigs', false);
        $response->assertSee('Swine Solutions', false);
        $response->assertSee('product__showing-wrap', false);
    }

    public function test_poultry_renders_all_section_blocks(): void
    {
        $response = $this->get('/poultry.html');
        $response->assertOk();
        $response->assertSee('page-header__title">Poultry', false);
        $response->assertSee('product__showing-wrap', false);
    }

    public function test_animal_pages_load_catalog_scripts(): void
    {
        foreach (['/cattle.html', '/pigs.html', '/poultry.html'] as $url) {
            $response = $this->get($url);
            // products-data.js was removed - frontend now fetches /api/products
            $response->assertDontSee('/assets/js/products-data.js', false);
            // main.js still wires the catalog UI
            $response->assertSee('/assets/js/main.js', false);
        }
    }

    public function test_animal_pages_check_correct_radio(): void
    {
        $this->get('/cattle.html')->assertSee('id="cat-cattle" value="cattle"', false)->assertSee('checked', false);
        $this->get('/pigs.html')->assertSee('id="cat-pigs" value="pigs"', false)->assertSee('checked', false);
        $this->get('/poultry.html')->assertSee('id="cat-poultry" value="poultry"', false)->assertSee('checked', false);
        $this->get('/products.html')->assertSee('id="cat-all" value="all"', false)->assertSee('checked', false);
    }

    public function test_services_renders_all_section_blocks(): void
    {
        $response = $this->get('/services.html');
        $response->assertOk();
        // page-header-services block
        $response->assertSee('Take a look at the services we offer', false);
        // breadcrumb-services block
        $response->assertSee('Our Services', false);
        // service-cards-grid block
        $response->assertSee('Livestock Additives', false);
        $response->assertSee('service-one__item', false);
        $response->assertSee('Export Services', false);
    }

    public function test_contact_renders_all_section_blocks(): void
    {
        $response = $this->get('/contact.html');
        $response->assertOk();
        // page-header-contact block
        $response->assertSee('Contact Our Team', false);
        // breadcrumb-contact block
        $response->assertSee('Contact Us', false);
        // contact-info-cards block
        $response->assertSee('Do you have questions?', false);
        $response->assertSee('KM 10, Old Lagos-Ibadan Expressway', false);
        // contact-form block — submits to local route by default
        $response->assertSee('action="' . route('contact.submit') . '"', false);
        $response->assertSee('Send a message', false);
        // contact-map block
        $response->assertSee('google-map__contact', false);
        $response->assertSee('Novi%20Agro%20Ltd', false);
    }

    public function test_faq_renders_all_section_blocks(): void
    {
        $response = $this->get('/faq.html');
        $response->assertOk();
        // page-header-faq block
        $response->assertSee('Your Business Should Thrive', false);
        // breadcrumb-faq block
        $response->assertSee('>FAQ<', false);
        // faq-accordion block
        $response->assertSee('Need help?', false);
        $response->assertSee('Everything you need to know about our products and services', false);
        $response->assertSee("What's wrong with my Livestock farm?", false);
        $response->assertSee('grdeen-accrodion', false);
    }

    public function test_info_pages_dont_load_catalog_scripts(): void
    {
        foreach (['/services.html', '/contact.html', '/faq.html'] as $url) {
            $response = $this->get($url);
            $response->assertDontSee('/assets/js/products-data.js', false);
            $response->assertDontSee('/assets/js/state/app.state.js', false);
        }
    }
}
