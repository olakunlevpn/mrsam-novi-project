<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Animals\Pages\CreateAnimal;
use App\Filament\Resources\Animals\Pages\EditAnimal;
use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Models\Animal;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SlugFieldTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_create_form_auto_fills_slug_from_source_field(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateAnimal::class)
            ->fillForm(['name' => 'Sheep & Goats'])
            ->assertSchemaStateSet([
                'name' => 'Sheep & Goats',
                'slug' => 'sheep-goats',
            ]);
    }

    public function test_slug_field_is_readonly_in_create_form(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateAnimal::class)
            ->assertFormFieldIsReadOnly('slug');
    }

    public function test_slug_field_is_readonly_in_edit_form(): void
    {
        $animal = Animal::create([
            'name'         => 'Cattle Test',
            'slug'         => 'cattle-test-locked',
            'order_column' => 0,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditAnimal::class, ['record' => $animal->getRouteKey()])
            ->assertFormFieldIsReadOnly('slug');
    }

    public function test_edit_form_does_not_rewrite_slug_when_name_changes(): void
    {
        $animal = Animal::create([
            'name'         => 'Cattle Stable',
            'slug'         => 'cattle-stable-locked',
            'order_column' => 0,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditAnimal::class, ['record' => $animal->getRouteKey()])
            ->fillForm(['name' => 'Cattle Renamed'])
            ->assertSchemaStateSet([
                'name' => 'Cattle Renamed',
                'slug' => 'cattle-stable-locked',
            ]);
    }

    public function test_slug_auto_generates_for_tag_form_too(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTag::class)
            ->fillForm(['name' => 'Animal Nutrition'])
            ->assertSchemaStateSet([
                'name' => 'Animal Nutrition',
                'slug' => 'animal-nutrition',
            ]);

        $tag = Tag::create(['name' => 'Existing Tag', 'slug' => 'existing-tag-locked']);

        Livewire::actingAs($this->admin)
            ->test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->assertFormFieldIsReadOnly('slug');
    }
}
