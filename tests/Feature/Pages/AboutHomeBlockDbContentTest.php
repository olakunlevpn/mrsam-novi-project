<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verify that content-rich blocks (about-intro, about-detail, cta-booking,
 * stats-bar, partners-carousel) read their visible copy from the DB block
 * and fall back to original defaults when the row has no `data` payload.
 */
class AboutHomeBlockDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_intro_renders_default_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Brings your farming business to life', false)
            ->assertSee('Feed Additives at its best', false);
    }

    public function test_about_intro_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'about-intro')->first();
        $block->update([
            'data' => [
                'title'      => 'Custom About Intro Title',
                'paragraph_1' => 'Custom paragraph one content.',
                'bullet_1'   => 'Custom bullet one',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom About Intro Title', false)
            ->assertSee('Custom paragraph one content.', false)
            ->assertSee('Custom bullet one', false)
            ->assertDontSee('Brings your farming business to life', false)
            ->assertDontSee('Feed Additives at its best', false);
    }

    public function test_about_detail_renders_default_content(): void
    {
        $this->get('/about.html')
            ->assertOk()
            ->assertSee('We offer expert livestock solutions', false)
            ->assertSee('Premium feed additives and nutritional supplements', false);
    }

    public function test_about_detail_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'about')->first();
        $block = $page->blocks()->where('type', 'about-detail')->first();
        $block->update([
            'data' => [
                'title'  => 'Custom About Title',
                'cta_label' => 'Get In Touch',
            ],
        ]);

        $this->get('/about.html')
            ->assertOk()
            ->assertSee('Custom About Title', false)
            ->assertSee('Get In Touch', false)
            ->assertDontSee('We offer expert livestock solutions', false);
    }

    public function test_cta_booking_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'cta-booking')->first();
        $block->update([
            'data' => [
                'tagline'      => 'Talk To An Expert',
                'submit_label' => 'Send Now',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Talk To An Expert', false)
            ->assertSee('Send Now', false)
            ->assertDontSee('Free Book Now', false)
            ->assertDontSee('>Submit Message<', false);
    }

    public function test_stats_bar_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'stats-bar')->first();
        $block->update([
            'data' => [
                'stat_1_title' => 'Custom Stat Title',
                'stat_1_value' => '99',
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Stat Title', false)
            ->assertSee('data-stop="99"', false)
            ->assertDontSee('Projects Completed', false);
    }

    public function test_partners_carousel_uses_db_data(): void
    {
        $this->seed(PageSeeder::class);

        $page  = Page::where('slug', 'home')->first();
        $block = $page->blocks()->where('type', 'partners-carousel')->first();
        $block->update([
            'data' => ['title' => 'Our Trusted Brand Partners'],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Our Trusted Brand Partners', false)
            ->assertDontSee('Partnering with Global Leaders', false);
    }
}
