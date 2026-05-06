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
}
