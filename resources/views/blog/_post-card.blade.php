{{--
    Single post card used by the blog index, category, and tag listings.
    Wrapped in a Bootstrap column by the caller.

    @var \App\Models\Post $post
--}}
<article class="blog-card h-100 d-flex flex-column"
    style="background:#fff;border:1px solid #e9ecef;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
    @if ($post->cover_image)
        <a href="{{ route('blog.show', $post) }}" class="blog-card__image d-block">
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}"
                style="width:100%;height:220px;object-fit:cover;display:block;">
        </a>
    @endif
    <div class="blog-card__body p-4 d-flex flex-column flex-grow-1">
        <div class="blog-card__meta mb-2" style="font-size:13px;color:#6c757d;">
            @if ($post->category)
                <a href="{{ route('blog.category', $post->category) }}"
                    style="color:#078f19;font-weight:600;text-decoration:none;">
                    {{ $post->category->name }}
                </a>
                <span class="mx-1">|</span>
            @endif
            @if ($post->published_at)
                <time datetime="{{ $post->published_at->toIso8601String() }}">
                    {{ $post->published_at->format('M j, Y') }}
                </time>
            @endif
        </div>

        <h3 class="blog-card__title h5 mb-2" style="font-weight:700;">
            <a href="{{ route('blog.show', $post) }}" style="color:#172000;text-decoration:none;">
                {{ $post->title }}
            </a>
        </h3>

        @if ($post->excerpt)
            <p class="blog-card__excerpt mb-3" style="color:#5a6268;font-size:15px;line-height:1.6;">
                {{ \Illuminate\Support\Str::limit($post->excerpt, 160) }}
            </p>
        @endif

        <div class="blog-card__footer mt-auto d-flex flex-wrap align-items-center justify-content-between"
            style="font-size:13px;">
            <span style="color:#6c757d;">
                {{ __('blog.by_author', ['author' => $post->author?->name ?? __('blog.unknown_author')]) }}
            </span>
            <a href="{{ route('blog.show', $post) }}" class="grdeen-btn" style="padding:6px 16px;font-size:13px;">
                <span>{{ __('blog.read_more') }}</span>
            </a>
        </div>
    </div>
</article>
