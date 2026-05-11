<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\PostCategories\Pages\CreatePostCategory;
use App\Filament\Resources\PostCategories\Pages\EditPostCategory;
use App\Filament\Resources\PostCategories\Pages\ListPostCategories;
use App\Filament\Resources\PostCategories\PostCategoryResource;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostCategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(PostCategoryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_list_categories(): void
    {
        PostCategory::factory()->count(2)->create();

        Livewire::actingAs($this->admin)
            ->test(ListPostCategories::class)
            ->assertCanSeeTableRecords(PostCategory::all());
    }

    public function test_admin_can_create_category(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreatePostCategory::class)
            ->fillForm([
                'name'         => 'Industry News',
                'slug'         => 'industry-news',
                'description'  => 'Briefings.',
                'order_column' => 0,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('post_categories', [
            'slug' => 'industry-news',
            'name' => 'Industry News',
        ]);
    }

    public function test_admin_can_edit_category(): void
    {
        $category = PostCategory::factory()->create([
            'name' => 'Old',
            'slug' => 'old',
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditPostCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm(['name' => 'New'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('New', $category->fresh()->name);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = PostCategory::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(EditPostCategory::class, ['record' => $category->getRouteKey()])
            ->callAction('delete');

        $this->assertNull(PostCategory::find($category->id));
    }
}
