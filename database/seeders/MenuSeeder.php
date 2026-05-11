<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPrimary();
        $this->seedFooter();
    }

    private function seedPrimary(): void
    {
        $menu = Menu::updateOrCreate(
            ['location' => 'primary'],
            ['name' => 'Primary Navigation'],
        );

        // Drop existing items and recreate for idempotency
        MenuItem::where('menu_id', $menu->id)->delete();

        $home = MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Home',
            'route_name'   => 'home',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 0,
        ]);

        $products = MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Products',
            'route_name'   => 'products',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 1,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'parent_id'    => $products->id,
            'label'        => 'Cattle',
            'route_name'   => 'animals.cattle',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 0,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'parent_id'    => $products->id,
            'label'        => 'Pigs',
            'route_name'   => 'animals.pigs',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 1,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'parent_id'    => $products->id,
            'label'        => 'Poultry',
            'route_name'   => 'animals.poultry',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 2,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Services',
            'route_name'   => 'services',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 2,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'About',
            'route_name'   => 'about',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 3,
        ]);

        MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Blog',
            'route_name'   => 'blog.index',
            'url'          => null,
            'target'       => '_self',
            'order_column' => 4,
        ]);
    }

    private function seedFooter(): void
    {
        $menu = Menu::updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Categories'],
        );

        MenuItem::where('menu_id', $menu->id)->delete();

        $items = [
            ['Cattle Solutions', 'animals.cattle', 0],
            ['Swine Care',       'animals.pigs',   1],
            ['Poultry Products', 'animals.poultry', 2],
            ['All Products',     'products',        3],
            ['FAQ',              'faq',             4],
        ];

        foreach ($items as [$label, $routeName, $order]) {
            MenuItem::create([
                'menu_id'      => $menu->id,
                'label'        => $label,
                'route_name'   => $routeName,
                'url'          => null,
                'target'       => '_self',
                'order_column' => $order,
            ]);
        }
    }
}
