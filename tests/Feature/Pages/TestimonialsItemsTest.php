<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use App\Models\Testimonial;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_testimonials_render_on_about(): void
    {
        // The create_testimonials_table migration seeds the original reviews.
        $this->get('/about')
            ->assertOk()
            ->assertSee('Tymoshenko', false)
            ->assertSee('Kolawole Ishola', false)
            ->assertSee('Dr. Bayo', false);
    }

    public function test_only_published_testimonials_render(): void
    {
        Testimonial::query()->update(['is_published' => false]);

        Testimonial::create([
            'name'         => 'Visible Reviewer',
            'designation'  => 'Farmer',
            'content'      => 'Published review body.',
            'rating'       => 5,
            'order_column' => 0,
            'is_published' => true,
        ]);

        $response = $this->get('/about')->assertOk();
        $response->assertSee('Visible Reviewer', false);
        $response->assertSee('Published review body.', false);
        // Unpublished seeded reviews must not appear.
        $response->assertDontSee('Tymoshenko', false);
        $response->assertDontSee('Dr. Bayo', false);
    }

    public function test_testimonials_render_in_order_column_sequence(): void
    {
        Testimonial::query()->delete();

        Testimonial::create([
            'name' => 'Zeta Reviewer', 'content' => 'B body', 'rating' => 5,
            'order_column' => 2, 'is_published' => true,
        ]);
        Testimonial::create([
            'name' => 'Alpha Reviewer', 'content' => 'A body', 'rating' => 5,
            'order_column' => 1, 'is_published' => true,
        ]);

        $html = $this->get('/about')->assertOk()->getContent();

        $this->assertLessThan(
            strpos($html, 'Zeta Reviewer'),
            strpos($html, 'Alpha Reviewer'),
            'Lower order_column must render first.',
        );
    }

    public function test_rating_controls_star_count(): void
    {
        Testimonial::query()->delete();

        Testimonial::create([
            'name'         => 'Three Star Reviewer',
            'designation'  => 'Reviewer',
            'content'      => '3-star review.',
            'rating'       => 3,
            'order_column' => 0,
            'is_published' => true,
        ]);

        $response = $this->get('/about')->assertOk();
        $response->assertSee('Three Star Reviewer', false);
        // Scope to the testimonials carousel section so star markup from other
        // about-page sections (e.g. benefits-about) does not skew the count.
        $html    = $response->getContent();
        $section = substr($html, (int) strpos($html, 'testimonials-one--two'));
        $count   = substr_count($section, 'testimonials-card__rating__start');
        $this->assertSame(3, $count);
    }

    public function test_section_hidden_when_block_toggled_off(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'testimonials')->first();
        $block->update(['is_visible' => false]);

        $this->get('/about')
            ->assertOk()
            ->assertDontSee('testimonials-one--two', false);
    }
}
