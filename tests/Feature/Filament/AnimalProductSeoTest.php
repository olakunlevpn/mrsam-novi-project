<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Animals\Pages\CreateAnimal;
use App\Filament\Resources\Animals\Pages\EditAnimal;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Models\Animal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SeoMeta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnimalProductSeoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_creating_animal_persists_seo_meta(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateAnimal::class)
            ->fillForm([
                'name'         => 'Goats',
                'slug'         => 'goats',
                'order_column' => 0,
                'seoMeta'      => [
                    'title'            => 'Goats | Premium Livestock',
                    'meta_description' => 'Specialized feed for goats.',
                    'canonical_url'    => 'https://novi-agro.com/goats',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $animal = Animal::where('slug', 'goats')->first();
        $this->assertNotNull($animal);

        $seo = $animal->seoMeta;
        $this->assertInstanceOf(SeoMeta::class, $seo);
        $this->assertSame('Goats | Premium Livestock', $seo->title);
        $this->assertSame('Specialized feed for goats.', $seo->meta_description);
        $this->assertSame('https://novi-agro.com/goats', $seo->canonical_url);
    }

    public function test_editing_animal_updates_existing_seo_row(): void
    {
        $animal = Animal::create(['name' => 'Cattle', 'slug' => 'cattle']);
        $animal->setSeo([
            'title'            => 'Old Title',
            'meta_description' => 'Old desc',
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditAnimal::class, ['record' => $animal->getRouteKey()])
            ->fillForm([
                'seoMeta' => [
                    'title'            => 'New Title',
                    'meta_description' => 'New desc',
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(1, SeoMeta::where('seoable_type', Animal::class)->where('seoable_id', $animal->id)->count());
        $this->assertSame('New Title', $animal->fresh()->seoMeta->title);
    }

    public function test_creating_product_persists_seo_meta(): void
    {
        $animal   = Animal::create(['name' => 'Cattle', 'slug' => 'cattle']);
        $category = ProductCategory::create([
            'animal_id' => $animal->id,
            'name'      => 'Feed Additives',
            'slug'      => 'feed-additives',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CreateProduct::class)
            ->fillForm([
                'animal_id'           => $animal->id,
                'product_category_id' => $category->id,
                'name'                => 'BroilerMix Pro',
                'slug'                => 'broilermix-pro',
                'sku'                 => 'BMP-001',
                'status'              => 'published',
                'order_column'        => 0,
                'seoMeta'             => [
                    'title'            => 'BroilerMix Pro | Premium Cattle Feed',
                    'meta_description' => 'Advanced cattle feed additive for growth.',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::where('slug', 'broilermix-pro')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->seoMeta);
        $this->assertSame('BroilerMix Pro | Premium Cattle Feed', $product->seoMeta->title);
        $this->assertSame('Advanced cattle feed additive for growth.', $product->seoMeta->meta_description);
    }

    public function test_editing_product_updates_existing_seo_row(): void
    {
        $animal   = Animal::create(['name' => 'Pigs', 'slug' => 'pigs']);
        $category = ProductCategory::create([
            'animal_id' => $animal->id,
            'name'      => 'Concentrates',
            'slug'      => 'concentrates',
        ]);
        $product  = Product::create([
            'animal_id'           => $animal->id,
            'product_category_id' => $category->id,
            'name'                => 'Sample Product',
            'slug'                => 'sample-product',
            'status'              => 'draft',
        ]);
        $product->setSeo([
            'og_title'       => 'Old OG Title',
            'og_description' => 'Old',
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'seoMeta' => [
                    'og_title'       => 'New OG Title',
                    'og_description' => 'New',
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(1, SeoMeta::where('seoable_type', Product::class)->where('seoable_id', $product->id)->count());
        $this->assertSame('New OG Title', $product->fresh()->seoMeta->og_title);
    }
}
