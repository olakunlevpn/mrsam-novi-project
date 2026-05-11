<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Resources\Tags\TagResource;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TagResourceTest extends TestCase
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
            ->get(TagResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_list_tags(): void
    {
        Tag::factory()->count(3)->create();

        Livewire::actingAs($this->admin)
            ->test(ListTags::class)
            ->assertCanSeeTableRecords(Tag::all());
    }

    public function test_admin_can_create_tag(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTag::class)
            ->fillForm([
                'name' => 'Cattle Care',
                'slug' => 'cattle-care',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tags', ['slug' => 'cattle-care']);
    }

    public function test_admin_can_edit_tag(): void
    {
        $tag = Tag::factory()->create(['name' => 'Old', 'slug' => 'old']);

        Livewire::actingAs($this->admin)
            ->test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->fillForm(['name' => 'New'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('New', $tag->fresh()->name);
    }

    public function test_admin_can_delete_tag(): void
    {
        $tag = Tag::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->callAction('delete');

        $this->assertNull(Tag::find($tag->id));
    }
}
