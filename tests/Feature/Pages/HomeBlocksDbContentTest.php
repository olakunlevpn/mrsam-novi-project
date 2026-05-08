<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * DB-content overrides for the remaining home-page blocks: feature-grid,
 * services-summary, benefits, species-cards, work-process.
 */
class HomeBlocksDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_grid_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'feature-grid')->first();
        $block->update([
            'data' => [
                'card_3_title' => 'Custom Feature Award',
                'card_3_text'  => 'Custom feature description.',
                'card_3_icon'  => 'fas fa-medal',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Feature Award', false)
            ->assertSee('Custom feature description.', false)
            ->assertSee('fas fa-medal', false)
            ->assertDontSee('Livestock Feeding Award', false);
    }

    public function test_services_summary_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'services-summary')->first();
        $block->update([
            'data' => [
                'card_1_title' => 'Custom Service',
                'card_1_text'  => 'Custom blurb for the first service card.',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Service', false)
            ->assertSee('Custom blurb for the first service card.', false)
            ->assertDontSee('Livestock Feeds', false)
            ->assertDontSee('Nutrient-rich additives for optimal livestock health.', false);
    }

    public function test_benefits_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'benefits')->first();
        $block->update([
            'data' => [
                'title'      => 'Custom Benefits Headline',
                'cta_label'  => 'Read More',
                'bullet_1'   => 'Custom bullet point one',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Benefits Headline', false)
            ->assertSee('Read More', false)
            ->assertSee('Custom bullet point one', false)
            ->assertDontSee('Why is mine different from others?', false)
            ->assertDontSee('Find out more', false);
    }

    public function test_species_cards_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'species-cards')->first();
        $block->update([
            'data' => [
                'heading'      => 'OUR LIVESTOCK',
                'card_1_label' => 'Birds',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('OUR LIVESTOCK', false)
            ->assertSee('>Birds<', false)
            ->assertDontSee('>SPECIES<', false);
    }

    public function test_work_process_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'work-process')->first();
        $block->update([
            'data' => [
                'title'        => 'Our Custom Process',
                'step_1_title' => 'Custom Step One',
                'step_4_title' => 'Custom Last Step',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Our Custom Process', false)
            ->assertSee('Custom Step One', false)
            ->assertSee('Custom Last Step', false)
            ->assertDontSee('See how we complete the work', false)
            ->assertDontSee('>Livestock Nutrition<', false)
            ->assertDontSee('>Logistics<', false);
    }
}
