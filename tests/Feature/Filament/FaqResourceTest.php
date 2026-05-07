<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FaqResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private FaqCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->category = FaqCategory::create(['name' => 'General']);
    }

    public function test_admin_can_create_faq(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreateFaq::class)
            ->fillForm([
                'faq_category_id' => $this->category->id,
                'question'        => 'How do I reset my password?',
                'answer'          => 'Use the password reset link on the login page.',
                'order_column'    => 0,
                'is_published'    => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('faqs', [
            'question' => 'How do I reset my password?',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_unpublish_faq(): void
    {
        $faq = Faq::create([
            'faq_category_id' => $this->category->id,
            'question'        => 'Sample',
            'answer'          => 'Sample answer.',
            'is_published'    => true,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditFaq::class, ['record' => $faq->getRouteKey()])
            ->fillForm(['is_published' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse($faq->fresh()->is_published);
    }
}
