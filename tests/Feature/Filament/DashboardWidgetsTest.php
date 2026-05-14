<?php

namespace Tests\Feature\Filament;

use App\Filament\Widgets\OverviewStats;
use App\Filament\Widgets\PostsPerMonthChart;
use App\Models\Animal;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Filament\Pages\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_dashboard_has_no_filament_demo_widgets(): void
    {
        $this->actingAs($this->admin);

        $widgets = filament()->getPanel('admin')->getWidgets();

        $this->assertNotContains(\Filament\Widgets\AccountWidget::class, $widgets);
        $this->assertNotContains(\Filament\Widgets\FilamentInfoWidget::class, $widgets);
        $this->assertContains(OverviewStats::class, $widgets);
        $this->assertContains(PostsPerMonthChart::class, $widgets);
    }

    public function test_overview_stats_widget_reports_real_counts(): void
    {
        $this->seedPage('home', 'published');
        $this->seedPage('about', 'published');
        $this->seedPage('contact', 'published');
        $this->seedPage('draft-1', 'draft');

        Post::factory()->count(4)->create(['status' => 'published', 'published_at' => now()->subDay()]);
        Post::factory()->count(1)->create(['status' => 'draft', 'published_at' => null]);

        [$animal, $category] = $this->ensureCatalogScaffolding();
        for ($i = 0; $i < 5; $i++) {
            $this->seedProduct($animal->id, $category->id, "pub-{$i}", 'published');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->seedProduct($animal->id, $category->id, "drf-{$i}", 'draft');
        }

        Comment::factory()->count(2)->create(['status' => 'pending']);
        Comment::factory()->count(3)->create(['status' => 'approved']);

        $this->seedSubmission('new');
        $this->seedSubmission('read');
        $this->seedSubmission('read');

        Livewire::actingAs($this->admin)
            ->test(OverviewStats::class)
            ->assertSeeText('3') // published pages
            ->assertSeeText('4') // published posts
            ->assertSeeText('5') // published products
            ->assertSeeText('2') // pending comments
            ->assertSeeText('1'); // new submissions
    }

    public function test_dashboard_page_renders_for_admin(): void
    {
        $this->actingAs($this->admin)
            ->get(Dashboard::getUrl(panel: 'admin'))
            ->assertOk();
    }

    public function test_posts_per_month_chart_returns_six_buckets(): void
    {
        Livewire::actingAs($this->admin);

        $widget = new PostsPerMonthChart();
        $method = (new \ReflectionMethod($widget, 'getData'));
        $method->setAccessible(true);
        $data = $method->invoke($widget);

        $this->assertCount(6, $data['labels']);
        $this->assertCount(6, $data['datasets'][0]['data']);
    }

    private function seedPage(string $slug, string $status): Page
    {
        return Page::create([
            'slug'         => $slug,
            'title'        => ucfirst($slug),
            'layout'       => 'default',
            'status'       => $status,
            'published_at' => $status === 'published' ? now()->subDay() : null,
            'is_homepage'  => false,
            'order_column' => 0,
        ]);
    }

    /**
     * @return array{0: Animal, 1: ProductCategory}
     */
    private function ensureCatalogScaffolding(): array
    {
        $animal = Animal::create([
            'slug' => 'cattle-stats',
            'name' => 'Cattle Stats',
            'order_column' => 0,
        ]);
        $category = ProductCategory::create([
            'animal_id'    => $animal->id,
            'slug'         => 'premix-stats',
            'name'         => 'Premix Stats',
            'order_column' => 0,
        ]);
        return [$animal, $category];
    }

    private function seedProduct(int $animalId, int $categoryId, string $suffix, string $status): Product
    {
        return Product::create([
            'animal_id'           => $animalId,
            'product_category_id' => $categoryId,
            'slug'                => 'product-' . $suffix,
            'name'                => 'Product ' . $suffix,
            'sku'                 => 'SKU-' . strtoupper($suffix),
            'status'              => $status,
            'order_column'        => 0,
        ]);
    }

    private function seedSubmission(string $status): ContactSubmission
    {
        return ContactSubmission::create([
            'name'    => 'Tester',
            'email'   => 'tester+' . uniqid() . '@example.com',
            'message' => 'Hello',
            'status'  => $status,
        ]);
    }
}
