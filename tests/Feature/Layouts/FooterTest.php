<?php

namespace Tests\Feature\Layouts;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\View\Composers\SiteComposer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // SiteComposer caches settings/menus in-process AND footer
        // collections in the framework cache. Clear both between tests.
        SiteComposer::clearCache();
    }

    /**
     * Build a minimal animal + category and return a ready-to-create
     * Product payload, with optional overrides.
     *
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function productPayload(array $overrides = []): array
    {
        $animal = Animal::firstOrCreate(
            ['slug' => 'cattle'],
            ['name' => 'Cattle', 'order_column' => 1],
        );

        $category = ProductCategory::firstOrCreate(
            ['animal_id' => $animal->id, 'slug' => 'concentrates'],
            ['name' => 'Concentrates'],
        );

        return array_merge([
            'animal_id'           => $animal->id,
            'product_category_id' => $category->id,
            'name'                => 'Sample Product',
            'hero_image'          => 'products/sample.jpg',
            'status'              => 'published',
        ], $overrides);
    }

    #[Test]
    public function footer_gallery_renders_setting_images(): void
    {
        Setting::set('footer.gallery_images', [
            ['src' => '/storage/footer/gallery-1.jpg', 'alt' => 'First Picture'],
            ['src' => '/storage/footer/gallery-2.jpg', 'alt' => 'Second Picture'],
        ], 'footer');
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();

        $response->assertSee('/storage/footer/gallery-1.jpg', false);
        $response->assertSee('alt="First Picture"', false);
        $response->assertSee('/storage/footer/gallery-2.jpg', false);
        $response->assertSee('alt="Second Picture"', false);
    }

    #[Test]
    public function footer_gallery_hides_when_empty(): void
    {
        // No gallery_images Setting at all - widget must not render.
        $response = $this->get('/')->assertOk();

        $response->assertDontSee('footer-widget--gallery', false);
    }

    #[Test]
    public function footer_categories_render_animals_from_db(): void
    {
        Animal::create(['slug' => 'cattle',  'name' => 'Cattle',  'order_column' => 1]);
        Animal::create(['slug' => 'pigs',    'name' => 'Pigs',    'order_column' => 2]);
        Animal::create(['slug' => 'poultry', 'name' => 'Poultry', 'order_column' => 3]);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();

        $response->assertSee('>Cattle</a>', false);
        $response->assertSee('>Pigs</a>', false);
        $response->assertSee('>Poultry</a>', false);
        // Static tail entries.
        $response->assertSee('>All Products</a>', false);
        $response->assertSee('>FAQ</a>', false);
    }

    #[Test]
    public function footer_products_render_two_most_recent(): void
    {
        // Older products that must NOT appear in the widget.
        Product::create($this->productPayload([
            'name' => 'Older Alpha',
            'slug' => 'older-alpha',
        ]))->forceFill(['updated_at' => now()->subDays(5)])->save();

        Product::create($this->productPayload([
            'name' => 'Older Beta',
            'slug' => 'older-beta',
        ]))->forceFill(['updated_at' => now()->subDays(4)])->save();

        Product::create($this->productPayload([
            'name' => 'Older Gamma',
            'slug' => 'older-gamma',
        ]))->forceFill(['updated_at' => now()->subDays(3)])->save();

        // Two newest - these are what we expect in the widget.
        Product::create($this->productPayload([
            'name' => 'Newest Product',
            'slug' => 'newest-product',
        ]))->forceFill(['updated_at' => now()->subMinute()])->save();

        Product::create($this->productPayload([
            'name' => 'Second Newest',
            'slug' => 'second-newest',
        ]))->forceFill(['updated_at' => now()->subDays(1)])->save();

        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();

        // Scope assertions to the footer products widget so $product->name
        // appearing elsewhere (e.g. JSON-LD on other pages) cannot mask a
        // miss. The home page has no Product mentions outside the footer.
        $response->assertSee('Newest Product', false);
        $response->assertSee('Second Newest', false);
        $response->assertDontSee('Older Alpha', false);
        $response->assertDontSee('Older Beta', false);
        $response->assertDontSee('Older Gamma', false);
    }

    #[Test]
    public function footer_products_link_to_clean_url(): void
    {
        $product = Product::create($this->productPayload([
            'name' => 'Linked Product',
            'slug' => 'linked-product',
        ]));
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();

        $response->assertSee('href="' . route('products.show', $product) . '"', false);
    }

    #[Test]
    public function saving_a_product_busts_footer_cache(): void
    {
        // Prime the cache with an empty product list.
        $first = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('Fresh Cache Buster', $first);

        // Create a new published product AFTER the first render.
        Product::create($this->productPayload([
            'name' => 'Fresh Cache Buster',
            'slug' => 'fresh-cache-buster',
        ]));

        // No manual cache clear: AppServiceProvider's Product::saved hook
        // is responsible for busting site.footer_products.
        $second = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('Fresh Cache Buster', $second);
    }
}
