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
}
