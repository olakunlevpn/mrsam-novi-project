<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MenuResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_menu_with_nested_items(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateMenu::class)
            ->fillForm([
                'name'     => 'Primary',
                'location' => 'primary',
                'items'    => [
                    [
                        'label'      => 'Home',
                        'route_name' => 'home',
                        'target'     => '_self',
                        'children'   => [],
                    ],
                    [
                        'label'      => 'Products',
                        'route_name' => 'products',
                        'target'     => '_self',
                        'children'   => [
                            ['label' => 'Cattle',  'route_name' => 'animals.cattle',  'target' => '_self'],
                            ['label' => 'Pigs',    'route_name' => 'animals.pigs',    'target' => '_self'],
                            ['label' => 'Poultry', 'route_name' => 'animals.poultry', 'target' => '_self'],
                        ],
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $menu = Menu::where('location', 'primary')->first();
        $this->assertNotNull($menu);

        $topLevel = MenuItem::where('menu_id', $menu->id)->whereNull('parent_id')->get();
        $this->assertCount(2, $topLevel);

        $products = $topLevel->firstWhere('label', 'Products');
        $this->assertNotNull($products);

        $children = MenuItem::where('parent_id', $products->id)->orderBy('order_column')->get();
        $this->assertCount(3, $children);
        $this->assertSame(['Cattle', 'Pigs', 'Poultry'], $children->pluck('label')->all());

        // Children must inherit the parent's menu_id.
        foreach ($children as $child) {
            $this->assertSame($menu->id, $child->menu_id);
        }
    }

    public function test_admin_can_edit_existing_menu_and_modify_children(): void
    {
        $menu = Menu::create(['location' => 'primary', 'name' => 'Primary']);
        $products = MenuItem::create([
            'menu_id'      => $menu->id,
            'label'        => 'Products',
            'route_name'   => 'products',
            'target'       => '_self',
            'order_column' => 0,
        ]);
        MenuItem::create([
            'menu_id'      => $menu->id,
            'parent_id'    => $products->id,
            'label'        => 'Cattle',
            'route_name'   => 'animals.cattle',
            'target'       => '_self',
            'order_column' => 0,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditMenu::class, ['record' => $menu->getRouteKey()])
            ->fillForm([
                'name' => 'Primary Navigation',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Primary Navigation', $menu->fresh()->name);
        $this->assertSame(1, MenuItem::where('parent_id', $products->id)->count());
    }

    public function test_location_must_be_unique(): void
    {
        Menu::create(['location' => 'primary', 'name' => 'Existing']);

        Livewire::actingAs($this->admin)
            ->test(CreateMenu::class)
            ->fillForm([
                'name'     => 'Duplicate',
                'location' => 'primary',
            ])
            ->call('create')
            ->assertHasFormErrors(['location']);
    }
}
