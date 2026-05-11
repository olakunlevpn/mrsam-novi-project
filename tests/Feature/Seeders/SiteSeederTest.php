<?php

namespace Tests\Feature\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;
use Database\Seeders\FaqSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_seeder_creates_brand_and_contact_keys(): void
    {
        $this->seed(SettingSeeder::class);
        $this->assertSame('Novi Agro', Setting::get('brand.name'));
        $this->assertSame('info@novi-agro.com', Setting::get('contact.email'));
        $this->assertSame('+2347041041756', Setting::get('contact.phone'));
        $this->assertNotEmpty(Setting::get('social.facebook'));
        $this->assertNotEmpty(Setting::get('social.instagram'));
    }

    public function test_menu_seeder_creates_primary_with_dropdown(): void
    {
        $this->seed(MenuSeeder::class);
        $primary = Menu::where('location', 'primary')->first();
        $this->assertNotNull($primary);
        $topLevel = $primary->items()->get();
        $this->assertSame(5, $topLevel->count());     // Home, Products, Services, About, Blog
        $this->assertContains('Blog', $topLevel->pluck('label')->all());

        $products = MenuItem::where('label', 'Products')->where('menu_id', $primary->id)->first();
        $this->assertNotNull($products);
        $children = $products->children()->get();
        $this->assertSame(3, $children->count());     // Cattle, Pigs, Poultry
        $childLabels = $children->pluck('label')->all();
        $this->assertSame(['Cattle', 'Pigs', 'Poultry'], $childLabels);
    }

    public function test_menu_seeder_creates_footer_menu(): void
    {
        $this->seed(MenuSeeder::class);
        $footer = Menu::where('location', 'footer')->first();
        $this->assertNotNull($footer);
        $items = $footer->items()->get();
        $this->assertSame(5, $items->count());
    }

    public function test_faq_seeder_creates_general_category_and_at_least_5_faqs(): void
    {
        $this->seed(FaqSeeder::class);
        $this->assertTrue(FaqCategory::where('slug', 'general')->exists());
        $this->assertGreaterThanOrEqual(5, Faq::count());
        $this->assertSame(0, Faq::where('is_published', false)->count());
    }

    public function test_seeders_are_idempotent(): void
    {
        $this->seed([SettingSeeder::class, MenuSeeder::class, FaqSeeder::class]);
        $settings = Setting::count();
        $items = MenuItem::count();
        $faqs = Faq::count();

        $this->seed([SettingSeeder::class, MenuSeeder::class, FaqSeeder::class]);
        $this->assertSame($settings, Setting::count());
        // MenuSeeder reseeds children — count should match exactly
        $this->assertSame($items, MenuItem::count());
        $this->assertSame($faqs, Faq::count());
    }
}
