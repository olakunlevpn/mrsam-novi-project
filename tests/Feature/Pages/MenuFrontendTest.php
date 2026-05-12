<?php

namespace Tests\Feature\Pages;

use App\Models\Menu;
use App\Models\MenuItem;
use App\View\Composers\SiteComposer;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuFrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteComposer::clearCache();
    }

    public function test_default_menu_renders_when_db_is_empty(): void
    {
        // No MenuSeeder. Primary nav falls back to the hardcoded header
        // defaults; footer Categories widget falls back to the static
        // "All Products" / "FAQ" tail (no Animals + no footer menu).
        $response = $this->get('/')->assertOk();
        $response->assertSee('>Home</a>', false);
        $response->assertSee('>Products</a>', false);
        $response->assertSee('>Cattle</a>', false);
        $response->assertSee('>All Products</a>', false);
        $response->assertSee('>FAQ</a>', false);
    }

    public function test_seeded_primary_menu_renders_with_children(): void
    {
        $this->seed(MenuSeeder::class);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('>Home</a>', false);
        $response->assertSee('>Products</a>', false);
        $response->assertSee('>Cattle</a>', false);
        $response->assertSee('>Pigs</a>', false);
        $response->assertSee('>Poultry</a>', false);
        $response->assertSee('>Services</a>', false);
        $response->assertSee('>About</a>', false);
    }

    public function test_custom_menu_replaces_defaults(): void
    {
        $menu = Menu::create(['location' => 'primary', 'name' => 'Primary']);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Custom Home Label',
            'route_name'   => 'home',
            'order_column' => 0,
        ]);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'External Site',
            'url'          => 'https://example.com/external',
            'target'       => '_blank',
            'order_column' => 1,
        ]);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('Custom Home Label', false);
        $response->assertSee('External Site', false);
        $response->assertSee('https://example.com/external', false);
        $response->assertSee('target="_blank"', false);
        // Default labels must NOT appear in nav.
        $response->assertDontSee('class="dropdown ', false);
    }

    public function test_custom_footer_menu_replaces_defaults(): void
    {
        $menu = Menu::create(['location' => 'footer', 'name' => 'Footer']);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Privacy Policy',
            'url'          => '/privacy',
            'order_column' => 0,
        ]);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Terms of Service',
            'url'          => '/terms',
            'order_column' => 1,
        ]);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('Privacy Policy', false);
        $response->assertSee('Terms of Service', false);
        $response->assertSee('href="/privacy"', false);
        $response->assertSee('href="/terms"', false);
        $response->assertDontSee('Cattle Solutions', false);
    }

    public function test_current_route_marked_with_current_class(): void
    {
        $this->seed(MenuSeeder::class);
        SiteComposer::clearCache();

        $response = $this->get('/about')->assertOk();
        // The About item should be marked current.
        $response->assertSee('<li class="current">', false);
    }

    public function test_unregistered_route_falls_back_to_hash(): void
    {
        $menu = Menu::create(['location' => 'footer', 'name' => 'Footer']);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Broken Link',
            'route_name'   => 'this.route.does.not.exist',
            'order_column' => 0,
        ]);
        SiteComposer::clearCache();

        $response = $this->get('/')->assertOk();
        $response->assertSee('Broken Link', false);
        $response->assertSee('href="#"', false);
    }
}
