<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeHeroDbContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_renders_fallback_defaults_when_block_data_is_null(): void
    {
        // Seed the page structure but clear the hero block's data payload.
        // This proves the accessor defaults are in effect when no data is stored.
        $this->seed(PageSeeder::class);

        $page = Page::where('slug', 'home')->first();
        $page->blocks()->where('type', 'hero')->update(['data' => null]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('WELCOME TO NOVI-AGRO', false);
        $response->assertSee('Advanced Animal Care Solutions', false);
    }

    public function test_hero_renders_db_data_when_seeded(): void
    {
        $this->seed(PageSeeder::class);

        // Override one data payload with a unique sentinel.
        $page = Page::where('slug', 'home')->first();
        $hero = $page->blocks()->where('type', 'hero')->first();
        $hero->update(['data' => array_merge($hero->data ?? [], ['headline' => 'PROMPT INJECTION SENTINEL'])]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('PROMPT INJECTION SENTINEL', false);
        // Ensure the original default is NOT rendered (proves DB wins over default).
        $response->assertDontSee('Advanced Animal Care Solutions', false);
    }
}
