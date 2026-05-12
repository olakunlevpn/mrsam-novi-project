<?php

namespace Tests\Feature\Products;

use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Build the minimum graph (animal -> category -> product) needed for the
     * detail route. Helper kept local so each test stays self-contained.
     */
    private function makeProduct(array $overrides = []): Product
    {
        $animal = Animal::firstOrCreate(
            ['slug' => 'cattle'],
            ['name' => 'Cattle']
        );
        $category = ProductCategory::firstOrCreate(
            ['animal_id' => $animal->id, 'slug' => 'toxin-binder'],
            ['name' => 'Toxin Binder']
        );

        return Product::create(array_merge([
            'animal_id'           => $animal->id,
            'product_category_id' => $category->id,
            'name'                => 'Detail Route Product',
            'description'         => 'Detailed description for the product.',
            'composition'         => 'Mineral composition.',
            'benefits'            => 'Boosts gut health.',
            'usage_instructions'  => 'Mix 200g per ton of feed.',
            'status'              => 'published',
        ], $overrides));
    }

    public function test_published_product_returns_200_and_renders_content(): void
    {
        $product = $this->makeProduct(['name' => 'Cattle Toxin Binder']);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Cattle Toxin Binder', false);
        $response->assertSee('Detailed description for the product.', false);
        $response->assertSee('Mineral composition.', false);
        $response->assertSee('Boosts gut health.', false);
        $response->assertSee('Mix 200g per ton of feed.', false);
    }

    public function test_draft_product_returns_404(): void
    {
        $product = $this->makeProduct([
            'name'   => 'Draft Product',
            'status' => 'draft',
        ]);

        $this->get('/products/' . $product->slug)->assertNotFound();
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/products/this-product-does-not-exist')->assertNotFound();
    }

    public function test_related_products_show_three_same_category_excluding_self(): void
    {
        $self = $this->makeProduct(['name' => 'Self Product']);

        // Three siblings in the same category (visible).
        $this->makeProduct(['name' => 'Sibling One']);
        $this->makeProduct(['name' => 'Sibling Two']);
        $this->makeProduct(['name' => 'Sibling Three']);

        $response = $this->get(route('products.show', $self))->assertOk();

        $response->assertSee('Related Products', false);
        $response->assertSee('Sibling One', false);
        $response->assertSee('Sibling Two', false);
        $response->assertSee('Sibling Three', false);

        // The product itself must not appear in the related strip. Scope
        // the search to the markup AFTER the "Related Products" heading so
        // page-header / JSON-LD / breadcrumb copies of $self->name (and its
        // slug-derived URLs) are not counted.
        $body = $response->getContent();
        $relatedStart = strpos($body, 'Related Products');
        $this->assertNotFalse($relatedStart, 'Related Products section should be rendered');
        $relatedHtml = substr($body, $relatedStart);
        $this->assertStringNotContainsString('Self Product', $relatedHtml);
    }

    public function test_related_products_skips_other_categories(): void
    {
        $self = $this->makeProduct(['name' => 'Self In Cat A']);

        // Sibling in a DIFFERENT category — must not be related.
        $cat = $self->animal->productCategories()->create([
            'name' => 'Different Category',
        ]);
        Product::create([
            'animal_id'           => $self->animal_id,
            'product_category_id' => $cat->id,
            'name'                => 'Foreign Category Product',
            'status'              => 'published',
        ]);

        $response = $this->get(route('products.show', $self))->assertOk();

        $response->assertDontSee('Foreign Category Product', false);
    }

    public function test_emits_product_schema_jsonld(): void
    {
        $product = $this->makeProduct(['name' => 'JSON-LD Product']);

        $response = $this->get(route('products.show', $product))->assertOk();

        $response->assertSee('"@type":"Product"', false);
        $response->assertSee('"name":"JSON-LD Product"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
    }
}
