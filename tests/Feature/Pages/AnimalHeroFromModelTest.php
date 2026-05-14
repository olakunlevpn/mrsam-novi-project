<?php

namespace Tests\Feature\Pages;

use App\Models\Animal;
use App\Models\Page;
use Database\Seeders\AnimalSeeder;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnimalHeroFromModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_default_hero_image_renders_when_no_animal_record(): void
    {
        // No AnimalSeeder. Hardcoded fallback applies.
        $this->get('/cattle')
            ->assertOk()
            ->assertSee('/assets/images/backgrounds/cows-green-field-sunny-day.jpg', false)
            ->assertSee('page-header__title">Cattle', false);
    }

    public function test_animal_record_drives_hero_image_and_title(): void
    {
        $this->seed(AnimalSeeder::class);

        // Update only hero_image — the HasSlug trait would auto-regenerate the
        // slug from a name change and break the controller's slug lookup.
        $cattle = Animal::where('slug', 'cattle')->first();
        $cattle->update(['hero_image' => '/assets/images/custom-cattle-hero.jpg']);

        $this->get('/cattle')
            ->assertOk()
            ->assertSee('/assets/images/custom-cattle-hero.jpg', false)
            // The default hero must NOT appear.
            ->assertDontSee('cows-green-field-sunny-day.jpg', false);
    }

    public function test_block_data_override_wins_over_animal_record(): void
    {
        $this->seed(AnimalSeeder::class);
        $this->seed(PageSeeder::class);

        $animal = Animal::where('slug', 'pigs')->first();
        $animal->update([
            'name'       => 'Animal-driven name',
            'hero_image' => '/animal-driven.jpg',
        ]);

        $page  = Page::where('slug', 'pigs')->first();
        $block = $page->blocks()->where('type', 'page-header-pigs')->first();
        $block->update([
            'data' => [
                'title'            => 'Block-driven title',
                'background_image' => '/block-driven.jpg',
            ],
        ]);

        // Block override is the highest precedence. Scope the "must not see"
        // checks to the page-header section: the renamed Animal record now
        // also surfaces in the footer Categories widget (animals collection)
        // and breadcrumb labels, which is the intended new behavior and
        // unrelated to the block-override precedence under test here.
        $response = $this->get('/pigs')
            ->assertOk()
            ->assertSee('/block-driven.jpg', false)
            ->assertSee('page-header__title">Block-driven title', false);

        $body         = $response->getContent();
        $headerStart  = strpos($body, '<section class="page-header"');
        $headerEnd    = strpos($body, '</section>', $headerStart);
        $this->assertNotFalse($headerStart, 'page-header section should render');
        $this->assertNotFalse($headerEnd,   'page-header section should close');
        $headerHtml   = substr($body, $headerStart, $headerEnd - $headerStart);
        $this->assertStringNotContainsString('Animal-driven name', $headerHtml);
        $this->assertStringNotContainsString('/animal-driven.jpg',  $headerHtml);
    }

    public function test_relative_hero_image_resolves_to_storage_url(): void
    {
        $this->seed(AnimalSeeder::class);

        $cattle = Animal::where('slug', 'cattle')->first();
        // FileUpload writes relative paths like "animals/hero/foo.jpg".
        $cattle->update(['hero_image' => 'animals/hero/relative-path.jpg']);

        $this->get('/cattle')
            ->assertOk()
            ->assertSee('/storage/animals/hero/relative-path.jpg', false);
    }

    public function test_seeded_animal_overrides_default_hero_image(): void
    {
        $this->seed(AnimalSeeder::class);

        // The seeder copies the source onto the public disk and stores the
        // relative path; the partial resolves that into a /storage URL.
        $this->get('/poultry')
            ->assertOk()
            ->assertSee('/storage/animals/hero/poultry-hens-factory-chicken-cages.jpg', false)
            ->assertDontSee('/assets/images/backgrounds/hens-factory-chicken-cages.jpg', false);
    }

    public function test_non_animal_pages_do_not_break_when_animal_var_missing(): void
    {
        // Home / about / etc never inject $animal but their blocks do not
        // reference it, so they must keep working unchanged.
        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get('/services')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/faq')->assertOk();
    }
}
