<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Animals\AnimalResource;
use App\Filament\Resources\Comments\CommentResource;
use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\PostCategories\PostCategoryResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\ProductCategories\ProductCategoryResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Tags\TagResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Smoke test every Filament resource: its index page must load for an admin
 * and forbid a non-admin. Catches broken nav callbacks, missing imports,
 * and bad relationship() definitions.
 */
class AdminResourcesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public static function resourceProvider(): array
    {
        return [
            'pages'              => [PageResource::class],
            'menus'              => [MenuResource::class],
            'faqs'               => [FaqResource::class],
            'animals'            => [AnimalResource::class],
            'product-categories' => [ProductCategoryResource::class],
            'products'           => [ProductResource::class],
            'contact-submissions' => [ContactSubmissionResource::class],
            'posts'              => [PostResource::class],
            'post-categories'    => [PostCategoryResource::class],
            'tags'               => [TagResource::class],
            'comments'           => [CommentResource::class],
        ];
    }

    #[DataProvider('resourceProvider')]
    public function test_admin_can_open_index(string $resource): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get($resource::getUrl('index'))
            ->assertOk();
    }

    #[DataProvider('resourceProvider')]
    public function test_non_admin_forbidden(string $resource): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get($resource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_navigation_groups_use_translation_keys(): void
    {
        // Non-empty, human-readable nav groups are required to keep nav usable.
        $this->assertSame('Content', PageResource::getNavigationGroup());
        $this->assertSame('Content', MenuResource::getNavigationGroup());
        $this->assertSame('Content', FaqResource::getNavigationGroup());
        $this->assertSame('Catalog', AnimalResource::getNavigationGroup());
        $this->assertSame('Catalog', ProductCategoryResource::getNavigationGroup());
        $this->assertSame('Catalog', ProductResource::getNavigationGroup());
        $this->assertSame('Inbox',   ContactSubmissionResource::getNavigationGroup());
        $this->assertSame('Blog',    PostResource::getNavigationGroup());
        $this->assertSame('Blog',    PostCategoryResource::getNavigationGroup());
        $this->assertSame('Blog',    TagResource::getNavigationGroup());
        $this->assertSame('Blog',    CommentResource::getNavigationGroup());
    }
}
