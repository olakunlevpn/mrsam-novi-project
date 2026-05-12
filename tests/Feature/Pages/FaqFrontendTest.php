<?php

namespace Tests\Feature\Pages;

use App\Models\Faq;
use App\Models\FaqCategory;
use Database\Seeders\FaqSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_faqs_render_when_table_is_empty(): void
    {
        // No FaqSeeder. The accordion falls back to its hardcoded items.
        $this->get('/faq')
            ->assertOk()
            ->assertSee("What's wrong with my Livestock farm?", false)
            ->assertSee('What is your return policy?', false);
    }

    public function test_seeded_faqs_render_dynamically(): void
    {
        $this->seed(FaqSeeder::class);

        // Adjust one row to a unique sentinel so we can prove DB-driven rendering.
        $faq = Faq::orderBy('order_column')->first();
        $faq->update(['question' => 'CUSTOM SENTINEL QUESTION']);

        $this->get('/faq')
            ->assertOk()
            ->assertSee('CUSTOM SENTINEL QUESTION', false);
    }

    public function test_unpublished_faqs_are_excluded(): void
    {
        $this->seed(FaqSeeder::class);

        $hidden = Faq::orderBy('order_column')->first();
        $hidden->update([
            'question'     => 'HIDDEN FROM PUBLIC',
            'is_published' => false,
        ]);

        $this->get('/faq')
            ->assertOk()
            ->assertDontSee('HIDDEN FROM PUBLIC', false);
    }

    public function test_admin_added_faq_appears_on_public_page(): void
    {
        $cat = FaqCategory::create(['name' => 'General']);
        Faq::create([
            'faq_category_id' => $cat->id,
            'question'        => 'Brand-new admin question?',
            'answer'          => '<p>Brand-new admin answer body.</p>',
            'order_column'    => 0,
            'is_published'    => true,
        ]);

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Brand-new admin question?', false)
            ->assertSee('Brand-new admin answer body.', false);
    }

    public function test_first_faq_has_active_class(): void
    {
        $cat = FaqCategory::create(['name' => 'General']);
        Faq::create([
            'faq_category_id' => $cat->id,
            'question'        => 'First Q',
            'answer'          => '<p>A1</p>',
            'order_column'    => 0,
            'is_published'    => true,
        ]);
        Faq::create([
            'faq_category_id' => $cat->id,
            'question'        => 'Second Q',
            'answer'          => '<p>A2</p>',
            'order_column'    => 1,
            'is_published'    => true,
        ]);

        $response = $this->get('/faq')->assertOk();
        // First item has active class.
        $response->assertSee('class="accrodion active"', false);
        $response->assertSee('First Q', false);
        $response->assertSee('Second Q', false);
    }
}
