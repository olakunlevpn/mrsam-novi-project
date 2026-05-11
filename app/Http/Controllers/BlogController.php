<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * Eager-load relations used by every listing card / detail view, so the
     * blade templates don't hit N+1 queries on category, tags, author, seo.
     *
     * @var array<int, string>
     */
    private const POST_LIST_RELATIONS = ['category', 'tags', 'author', 'seoMeta'];

    public function index()
    {
        $posts = Post::published()
            ->with(self::POST_LIST_RELATIONS)
            ->latest('published_at')
            ->paginate(10);

        return view('blog.index', [
            'posts' => $posts,
            ...$this->sidebarData(),
        ]);
    }

    public function show(Post $post)
    {
        // 404 for drafts or future-scheduled posts. Reuse scopePublished to
        // keep the published gate in one place.
        if (! Post::published()->whereKey($post->id)->exists()) {
            throw new NotFoundHttpException('Post not found.');
        }

        $post->load([...self::POST_LIST_RELATIONS, 'comments']);

        // 3 other published posts in the same category, fallback to latest 3
        // elsewhere if the category has no siblings.
        $related = collect();
        if ($post->post_category_id) {
            $related = Post::published()
                ->where('post_category_id', $post->post_category_id)
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->with(self::POST_LIST_RELATIONS)
                ->limit(3)
                ->get();
        }
        if ($related->isEmpty()) {
            $related = Post::published()
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->with(self::POST_LIST_RELATIONS)
                ->limit(3)
                ->get();
        }

        return view('blog.show', [
            'post'          => $post,
            'relatedPosts'  => $related,
            'activeCategory' => $post->category,
            'activeTag'     => null,
            ...$this->sidebarData(),
        ]);
    }

    public function category(PostCategory $category)
    {
        $posts = $category->posts()
            ->published()
            ->with(self::POST_LIST_RELATIONS)
            ->latest('published_at')
            ->paginate(10);

        return view('blog.category', [
            'category' => $category,
            'posts'    => $posts,
            ...$this->sidebarData(),
        ]);
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()
            ->published()
            ->with(self::POST_LIST_RELATIONS)
            ->latest('published_at')
            ->paginate(10);

        return view('blog.tag', [
            'tag'   => $tag,
            'posts' => $posts,
            ...$this->sidebarData(),
        ]);
    }

    /**
     * Shared sidebar payload. Single source of ordering so categories/tags
     * don't drift between actions or @include consumers.
     *
     * @return array{categories: \Illuminate\Database\Eloquent\Collection<int, PostCategory>, tags: \Illuminate\Database\Eloquent\Collection<int, Tag>}
     */
    private function sidebarData(): array
    {
        return [
            'categories' => PostCategory::query()->orderBy('name')->get(),
            'tags'       => Tag::query()->orderBy('name')->get(),
        ];
    }
}
