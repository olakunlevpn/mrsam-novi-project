<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_non_admin_cannot_access_posts_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(PostResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_list_posts(): void
    {
        Post::factory()->count(3)->create(['author_id' => $this->admin->id]);

        Livewire::actingAs($this->admin)
            ->test(ListPosts::class)
            ->assertCanSeeTableRecords(Post::all());
    }

    public function test_admin_can_create_draft_post(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CreatePost::class)
            ->fillForm([
                'title'          => 'Hello World',
                'slug'           => 'hello-world',
                'excerpt'        => 'Quick intro.',
                'body'           => '<p>Body text.</p>',
                'status'         => 'draft',
                'author_id'      => $this->admin->id,
                'allow_comments' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $post = Post::where('slug', 'hello-world')->first();
        $this->assertNotNull($post);
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at, 'Draft posts should not auto-stamp published_at.');
    }

    public function test_publishing_a_post_auto_fills_published_at(): void
    {
        $post = Post::factory()->create([
            'author_id'    => $this->admin->id,
            'status'       => 'draft',
            'published_at' => null,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditPost::class, ['record' => $post->getRouteKey()])
            ->fillForm([
                'status'       => 'published',
                'published_at' => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $fresh = $post->fresh();
        $this->assertSame('published', $fresh->status);
        $this->assertNotNull(
            $fresh->published_at,
            'Saving status=published with null published_at must auto-stamp it.'
        );
    }

    public function test_admin_can_bulk_publish_draft_posts(): void
    {
        $drafts = Post::factory()
            ->count(3)
            ->create([
                'author_id'    => $this->admin->id,
                'status'       => 'draft',
                'published_at' => null,
            ]);

        Livewire::actingAs($this->admin)
            ->test(ListPosts::class)
            ->callTableBulkAction('publish', $drafts->pluck('id')->all());

        $drafts->each(function ($post) {
            $fresh = $post->fresh();
            $this->assertSame('published', $fresh->status);
            $this->assertNotNull(
                $fresh->published_at,
                'Bulk publish must trigger the saving hook so published_at is stamped.'
            );
        });
    }

    public function test_post_slug_must_be_unique(): void
    {
        Post::factory()->create([
            'author_id' => $this->admin->id,
            'slug'      => 'taken',
            'title'     => 'Taken',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CreatePost::class)
            ->fillForm([
                'title'     => 'Duplicate',
                'slug'      => 'taken',
                'body'      => '<p>x</p>',
                'status'    => 'draft',
                'author_id' => $this->admin->id,
            ])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }
}
