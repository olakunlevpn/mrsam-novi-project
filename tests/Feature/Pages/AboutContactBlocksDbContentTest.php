<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * DB-content overrides for the remaining about-page, contact-page,
 * services-page, and faq-page blocks.
 */
class AboutContactBlocksDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_grid_about_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'feature-grid-about')->first();
        $block->update([
            'data' => [
                'card_4_title' => 'Trusted Globally',
                'card_4_text'  => 'Used by partners in 12 countries worldwide.',
            ],
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Trusted Globally', false)
            ->assertSee('Used by partners in 12 countries worldwide.', false)
            ->assertDontSee('Happy customers', false)
            ->assertDontSee('Trusted by over 2,000 farmers across Nigeria', false);
    }

    public function test_benefits_about_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'benefits-about')->first();
        $block->update([
            'data' => [
                'title'    => 'Custom About Benefits Heading',
                'bullet_1' => 'Custom bullet item one',
                'stat_value' => '5000+',
            ],
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Custom About Benefits Heading', false)
            ->assertSee('Custom bullet item one', false)
            ->assertSee('5000+', false)
            ->assertDontSee('Our expertise in', false)
            ->assertDontSee('Expert team members', false);
    }

    public function test_journey_growth_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'journey-growth')->first();
        $block->update([
            'data' => [
                'title'        => 'Our Story',
                'card_1_value' => '20',
                'card_5_value' => '500',
            ],
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Our Story', false)
            ->assertSee('>20</h2>', false)
            ->assertSee('>500</h2>', false)
            // The visible <h2> heading should not show the original number.
            ->assertDontSee('>15</h2>', false);
    }

    public function test_customer_growth_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'customer-growth')->first();
        $block->update([
            'data' => [
                'eyebrow'         => 'PROGRESS',
                'title'           => 'Customer Reach',
                'summary_1_label' => 'Happy Clients',
            ],
        ]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('PROGRESS', false)
            ->assertSee('Customer Reach', false)
            ->assertSee('Happy Clients', false)
            ->assertDontSee('Growth Metrics', false)
            ->assertDontSee('Customer Growth', false)
            ->assertDontSee('Total Customers', false);
    }

    public function test_contact_info_cards_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'contact')->first();
        $block = $page->blocks()->where('type', 'contact-info-cards')->first();
        $block->update([
            'data' => [
                'card_1_text'   => 'New office address goes here.',
                'card_3_email'  => 'hello@novi-agro.com',
            ],
        ]);

        $this->get('/contact')
            ->assertOk()
            ->assertSee('New office address goes here.', false)
            ->assertSee('hello@novi-agro.com', false)
            ->assertSee('mailto:hello@novi-agro.com', false)
            ->assertDontSee('KM 10, Old Lagos-Ibadan Expressway, New Garage, Ibadan', false);
    }

    public function test_contact_form_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'contact')->first();
        $block = $page->blocks()->where('type', 'contact-form')->first();
        $block->update([
            'data' => [
                'submit_label' => 'Submit Inquiry',
                'action_url'   => 'https://example.com/submit',
            ],
        ]);

        $this->get('/contact')
            ->assertOk()
            ->assertSee('Submit Inquiry', false)
            ->assertSee('https://example.com/submit', false)
            ->assertDontSee('Send a message', false);
    }

    public function test_service_cards_grid_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'services')->first();
        $block = $page->blocks()->where('type', 'service-cards-grid')->first();
        $block->update([
            'data' => [
                'card_1_title' => 'Premium Additives',
                'card_6_title' => 'Global Trade',
            ],
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('Premium Additives', false)
            ->assertSee('Global Trade', false)
            ->assertDontSee('>Livestock Additives</a>', false)
            ->assertDontSee('>Export Services', false);
    }

    public function test_faq_accordion_header_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'faq')->first();
        $block = $page->blocks()->where('type', 'faq-accordion')->first();
        $block->update([
            'data' => [
                'eyebrow'  => 'Got questions?',
                'title'    => 'Custom FAQ heading line',
                'subtitle' => 'Custom guidance text',
            ],
        ]);

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Got questions?', false)
            ->assertSee('Custom FAQ heading line', false)
            ->assertSee('Custom guidance text', false)
            ->assertDontSee('Need help?', false)
            ->assertDontSee('Everything you need to know about our products and services', false);
    }
}
