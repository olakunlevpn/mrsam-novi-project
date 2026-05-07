<?php

namespace Tests\Feature\Concerns;

use App\Models\Animal;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_seo_creates_seo_meta_for_page(): void
    {
        $page = Page::create(['title' => 'SEO Page', 'status' => 'draft']);

        $page->setSeo(['title' => 'My Title', 'meta_description' => 'desc']);

        $this->assertSame('My Title', $page->fresh('seoMeta')->seoMeta->title);
    }

    public function test_set_seo_updates_rather_than_creates_duplicate(): void
    {
        $page = Page::create(['title' => 'SEO Page', 'status' => 'draft']);

        $page->setSeo(['title' => 'My Title', 'meta_description' => 'desc']);
        $page->refresh();
        $page->setSeo(['title' => 'Updated']);

        $this->assertSame(1, SeoMeta::count());
        $this->assertSame('Updated', $page->fresh('seoMeta')->seoMeta->title);
    }

    public function test_independent_seo_rows_per_model(): void
    {
        $page = Page::create(['title' => 'SEO Page', 'status' => 'draft']);

        $animal = Animal::create(['name' => 'Poultry']);
        $category = ProductCategory::create(['name' => 'Cat', 'animal_id' => $animal->id]);
        $product = Product::create([
            'name'                => 'SEO Product',
            'animal_id'           => $animal->id,
            'product_category_id' => $category->id,
        ]);

        $page->setSeo(['title' => 'Page SEO']);
        $product->setSeo(['title' => 'Product SEO']);

        $this->assertSame(2, SeoMeta::count());
    }
}
