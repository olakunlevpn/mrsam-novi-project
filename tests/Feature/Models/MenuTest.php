<?php

namespace Tests\Feature\Models;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_menu_with_top_level_items(): void
    {
        $menu = Menu::create(['location' => 'primary', 'name' => 'Main Menu']);

        $item1 = MenuItem::create(['menu_id' => $menu->id, 'label' => 'Home',    'order_column' => 0]);
        $item2 = MenuItem::create(['menu_id' => $menu->id, 'label' => 'About',   'order_column' => 1]);
        $item3 = MenuItem::create(['menu_id' => $menu->id, 'label' => 'Contact', 'order_column' => 2]);

        // Child of item2
        MenuItem::create(['menu_id' => $menu->id, 'parent_id' => $item2->id, 'label' => 'Team', 'order_column' => 0]);

        $this->assertSame(3, $menu->items()->count());
        $this->assertSame(1, $item2->children()->count());
    }

    public function test_cascade_delete_drops_items(): void
    {
        $menu = Menu::create(['location' => 'footer', 'name' => 'Footer Menu']);

        MenuItem::create(['menu_id' => $menu->id, 'label' => 'Privacy', 'order_column' => 0]);
        MenuItem::create(['menu_id' => $menu->id, 'label' => 'Terms',   'order_column' => 1]);

        $this->assertSame(2, MenuItem::where('menu_id', $menu->id)->count());

        $menu->delete();

        $this->assertSame(0, MenuItem::where('menu_id', $menu->id)->count());
    }
}
