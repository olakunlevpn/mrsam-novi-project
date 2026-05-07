<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\User;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_non_admin_cannot_access_pages_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(PageResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_pages_index(): void
    {
        $this->actingAs($this->admin)
            ->get(PageResource::getUrl('index'))
            ->assertOk();
    }

    public function test_index_lists_seeded_pages(): void
    {
        $this->seed(PageSeeder::class);

        Livewire::actingAs($this->admin)
            ->test(ListPages::class)
            ->assertCanSeeTableRecords(Page::all());
    }

    public function test_admin_can_create_page_with_blocks(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreatePage::class)
            ->fillForm([
                'title'        => 'Promo Landing',
                'slug'         => 'promo-landing',
                'layout'       => 'custom',
                'status'       => 'published',
                'is_homepage'  => false,
                'order_column' => 0,
                'blocks'       => [
                    [
                        'type'       => 'hero',
                        'is_visible' => true,
                        'data'       => ['headline' => 'Spring Sale'],
                    ],
                    [
                        'type'       => 'cta-booking',
                        'is_visible' => false,
                        'data'       => null,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $page = Page::where('slug', 'promo-landing')->first();

        $this->assertNotNull($page);
        $this->assertSame('Promo Landing', $page->title);
        $this->assertSame('published', $page->status);
        $this->assertSame(2, $page->blocks()->count());

        $hero = $page->blocks()->where('type', 'hero')->first();
        $this->assertNotNull($hero);
        $this->assertTrue($hero->is_visible);
        $this->assertSame('Spring Sale', $hero->data['headline']);

        $cta = $page->blocks()->where('type', 'cta-booking')->first();
        $this->assertNotNull($cta);
        $this->assertFalse($cta->is_visible);
    }

    public function test_admin_can_edit_existing_page_and_reorder_blocks(): void
    {
        $page = Page::create([
            'slug'   => 'editable',
            'title'  => 'Editable Page',
            'layout' => 'custom',
            'status' => 'draft',
        ]);

        $first  = PageBlock::create(['page_id' => $page->id, 'type' => 'hero',         'order_column' => 0, 'is_visible' => true]);
        $second = PageBlock::create(['page_id' => $page->id, 'type' => 'feature-grid', 'order_column' => 1, 'is_visible' => true]);

        Livewire::actingAs($this->admin)
            ->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->assertFormSet([
                'title'  => 'Editable Page',
                'status' => 'draft',
            ])
            ->fillForm([
                'status' => 'published',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('published', $page->fresh()->status);
    }

    public function test_slug_must_be_unique(): void
    {
        Page::create([
            'slug'   => 'existing',
            'title'  => 'Existing',
            'layout' => 'custom',
            'status' => 'draft',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CreatePage::class)
            ->fillForm([
                'title'        => 'Duplicate',
                'slug'         => 'existing',
                'layout'       => 'custom',
                'status'       => 'draft',
                'is_homepage'  => false,
                'order_column' => 0,
            ])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_admin_can_delete_page_and_blocks_cascade(): void
    {
        $page = Page::create([
            'slug'   => 'doomed',
            'title'  => 'Doomed',
            'layout' => 'custom',
            'status' => 'draft',
        ]);

        PageBlock::create(['page_id' => $page->id, 'type' => 'hero']);
        PageBlock::create(['page_id' => $page->id, 'type' => 'feature-grid']);

        $this->assertSame(2, PageBlock::where('page_id', $page->id)->count());

        Livewire::actingAs($this->admin)
            ->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->callAction('delete');

        $this->assertNull(Page::find($page->id));
        $this->assertSame(0, PageBlock::where('page_id', $page->id)->count());
    }
}
