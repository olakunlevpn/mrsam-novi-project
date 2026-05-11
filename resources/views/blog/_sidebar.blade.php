{{--
    Sidebar for blog listings. Lists categories + tags. Optional active state
    via $activeCategory / $activeTag.

    @var \Illuminate\Support\Collection $categories
    @var \Illuminate\Support\Collection $tags
    @var \App\Models\PostCategory|null  $activeCategory
    @var \App\Models\Tag|null           $activeTag
--}}
@php
    /** @var \App\Models\PostCategory|null $activeCategory */
    $activeCategory = $activeCategory ?? null;
    /** @var \App\Models\Tag|null $activeTag */
    $activeTag = $activeTag ?? null;
@endphp

<aside class="blog-sidebar">
    <div class="blog-sidebar__widget mb-4 p-4"
        style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
        <h4 class="blog-sidebar__title h5 mb-3" style="font-weight:700;color:#172000;">
            {{ __('blog.categories') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="{{ route('blog.index') }}"
                    style="color:{{ $activeCategory === null && $activeTag === null ? '#078f19' : '#5a6268' }};text-decoration:none;font-weight:{{ $activeCategory === null && $activeTag === null ? '700' : '500' }};">
                    {{ __('blog.all_posts') }}
                </a>
            </li>
            @foreach ($categories as $cat)
                <li class="mb-2">
                    <a href="{{ route('blog.category', $cat) }}"
                        style="color:{{ $activeCategory && $activeCategory->is($cat) ? '#078f19' : '#5a6268' }};text-decoration:none;font-weight:{{ $activeCategory && $activeCategory->is($cat) ? '700' : '500' }};">
                        {{ $cat->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($tags->isNotEmpty())
        <div class="blog-sidebar__widget p-4"
            style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
            <h4 class="blog-sidebar__title h5 mb-3" style="font-weight:700;color:#172000;">
                {{ __('blog.tags') }}
            </h4>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                    <a href="{{ route('blog.tag', $tag) }}"
                        style="display:inline-block;padding:4px 12px;border-radius:999px;font-size:13px;text-decoration:none;background:{{ $activeTag && $activeTag->is($tag) ? '#078f19' : '#f1f3f5' }};color:{{ $activeTag && $activeTag->is($tag) ? '#fff' : '#5a6268' }};">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</aside>
