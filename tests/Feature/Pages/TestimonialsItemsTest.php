<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_testimonials_render_when_no_db_array(): void
    {
        $this->get('/about.html')
            ->assertOk()
            ->assertSee('Tymoshenko', false)
            ->assertSee('Kolawole Ishola', false)
            ->assertSee('Dr. Bayo', false);
    }

    public function test_db_items_replace_defaults(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'testimonials')->first();
        $block->update([
            'data' => [
                'items' => [
                    [
                        'name'        => 'Custom Reviewer A',
                        'designation' => 'Farmer',
                        'image'       => '/img/reviewer-a.png',
                        'content'     => 'Custom review one body.',
                    ],
                    [
                        'name'        => 'Custom Reviewer B',
                        'designation' => 'Vet',
                        'image'       => '/img/reviewer-b.png',
                        'content'     => 'Custom review two body.',
                        'rating'      => 4,
                    ],
                ],
            ],
        ]);

        $response = $this->get('/about.html')->assertOk();
        $response->assertSee('Custom Reviewer A', false);
        $response->assertSee('Custom Reviewer B', false);
        $response->assertSee('Custom review one body.', false);
        $response->assertSee('Custom review two body.', false);
        $response->assertSee('/img/reviewer-a.png', false);
        // Defaults must NOT appear.
        $response->assertDontSee('Tymoshenko', false);
        $response->assertDontSee('Dr. Bayo', false);
    }

    public function test_rating_count_controls_star_count(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'testimonials')->first();
        $block->update([
            'data' => [
                'items' => [
                    [
                        'name'        => 'Three Star Reviewer',
                        'designation' => 'Reviewer',
                        'image'       => '/img/r.png',
                        'content'     => '3-star review.',
                        'rating'      => 3,
                    ],
                ],
            ],
        ]);

        $response = $this->get('/about.html')->assertOk();
        $response->assertSee('Three Star Reviewer', false);
        // Three stars in the rating block.
        $count = substr_count($response->getContent(), 'fa fa-star"></i></span>');
        $this->assertGreaterThanOrEqual(3, $count);
    }
}
