<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_testimonial(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTestimonial::class)
            ->fillForm([
                'name'         => 'Jane Farmer',
                'designation'  => 'Owner',
                'content'      => 'Best feed additives we have used.',
                'rating'       => 4,
                'order_column' => 0,
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('testimonials', [
            'name'         => 'Jane Farmer',
            'rating'       => 4,
            'is_published' => true,
        ]);
    }

    public function test_name_and_content_are_required(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTestimonial::class)
            ->fillForm([
                'name'    => '',
                'content' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name', 'content']);
    }

    public function test_admin_can_unpublish_testimonial(): void
    {
        $testimonial = Testimonial::create([
            'name'         => 'Sample',
            'content'      => 'Sample review.',
            'rating'       => 5,
            'is_published' => true,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditTestimonial::class, ['record' => $testimonial->getRouteKey()])
            ->fillForm(['is_published' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse($testimonial->fresh()->is_published);
    }
}
