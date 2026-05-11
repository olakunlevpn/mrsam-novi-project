@extends('layouts.app')

@php
    /** @var \App\Models\Post $post */
    $seo            = $post->seoMeta;
    $metaTitle      = $seo?->title ?? $post->title;
    $metaDescription = $seo?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->body), 160);
@endphp

@section('title', $metaTitle . ' | Novi-Agro')

@push('meta')
    {{-- Per-post SEO. Mirrors the pattern in layouts/partials/head.blade.php for Pages. --}}
    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}" />
    @endif
    @if ($seo?->canonical_url)
        <link rel="canonical" href="{{ $seo->canonical_url }}" />
    @endif
    @if ($seo?->noindex)
        <meta name="robots" content="noindex, nofollow" />
    @elseif ($seo?->robots)
        <meta name="robots" content="{{ $seo->robots }}" />
    @endif

    <meta property="og:title" content="{{ $seo?->og_title ?? $post->title }}" />
    <meta property="og:description" content="{{ $seo?->og_description ?? $metaDescription }}" />
    @if ($seo?->og_image ?? $post->cover_image)
        <meta property="og:image" content="{{ $seo?->og_image ?? $post->cover_image }}" />
    @endif
    <meta property="og:type" content="article" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $seo?->og_title ?? $post->title }}" />
    <meta name="twitter:description" content="{{ $seo?->og_description ?? $metaDescription }}" />
@endpush

@push('styles')
    <style>
        .main-menu__list>li.current>a {
            color: #ffffff !important;
        }

        .blog-post__body {
            color: #3a3a3a;
            line-height: 1.8;
            font-size: 16px;
        }

        .blog-post__body p,
        .blog-post__body ul,
        .blog-post__body ol,
        .blog-post__body blockquote {
            margin-bottom: 1.25rem;
        }

        .blog-post__body h2,
        .blog-post__body h3,
        .blog-post__body h4 {
            color: #172000;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .blog-post__body img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 1rem 0;
        }

        .blog-post__body a {
            color: #078f19;
        }
    </style>
@endpush

@section('content')
    <section class="page-header">
        <div class="page-header__bg"
            style="background-image: url('{{ $post->cover_image ?? '/assets/images/backgrounds/about.png' }}');"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $post->title }}</h2>
        </div>
    </section>

    <section class="mt-4 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <div class="d-flex align-items-center py-3 px-4 rounded-2"
                    style="background-color: #f8f9fa; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <ul class="grdeen-breadcrumb list-unstyled mb-0 d-flex justify-content-start align-items-center w-100 flex-wrap">
                        <li style="color: #6c757d; font-weight: 500; font-size: 17px;">
                            <a href="{{ route('home') }}" class="d-flex align-items-center" style="color: inherit;">
                                <i class="fas fa-home me-2" style="font-size: 16px;"></i> {{ __('blog.breadcrumb_home') }}
                            </a>
                        </li>
                        <li style="color: #6c757d; font-weight: 500; font-size: 17px;">
                            <a href="{{ route('blog.index') }}" style="color: inherit;">{{ __('blog.breadcrumb_blog') }}</a>
                        </li>
                        <li style="color: var(--grdeen-black, #172000); font-weight: 600; font-size: 17px;">
                            <span>{{ \Illuminate\Support\Str::limit($post->title, 60) }}</span>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <section class="blog-detail pb-5">
        <div class="container">
            <div class="row gutter-y-30">
                <div class="col-lg-8">
                    <article class="blog-post p-4 p-lg-5"
                        style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
                        <div class="blog-post__meta d-flex flex-wrap align-items-center mb-3"
                            style="font-size:14px;color:#6c757d;gap:12px;">
                            @if ($post->author)
                                <span>
                                    <i class="fas fa-user me-1" aria-hidden="true"></i>
                                    {{ __('blog.by_author', ['author' => $post->author->name ?? __('blog.unknown_author')]) }}
                                </span>
                            @endif
                            @if ($post->published_at)
                                <span>
                                    <i class="far fa-calendar me-1" aria-hidden="true"></i>
                                    {{ __('blog.posted_on', ['date' => $post->published_at->format('F j, Y')]) }}
                                </span>
                            @endif
                            @if ($post->category)
                                <span>
                                    <i class="fas fa-folder me-1" aria-hidden="true"></i>
                                    <a href="{{ route('blog.category', $post->category) }}"
                                        style="color:#078f19;text-decoration:none;font-weight:600;">
                                        {{ __('blog.in_category', ['category' => $post->category->name]) }}
                                    </a>
                                </span>
                            @endif
                        </div>

                        {{--
                            Body is HTML produced by Filament's rich-text editor and is
                            saved by trusted admin users only. Safe to render raw.
                            Task 5.4 will revisit if non-admin content ever appears here.
                        --}}
                        <div class="blog-post__body">
                            {!! $post->body !!}
                        </div>

                        @if ($post->tags->isNotEmpty())
                            <div class="blog-post__tags mt-4 pt-4"
                                style="border-top:1px solid #e9ecef;">
                                <span class="me-2" style="font-weight:700;color:#172000;">{{ __('blog.tags') }}:</span>
                                @foreach ($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag) }}"
                                        style="display:inline-block;padding:4px 12px;border-radius:999px;font-size:13px;text-decoration:none;background:#f1f3f5;color:#5a6268;margin-right:6px;margin-bottom:6px;">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>

                    {{-- Comments placeholder. Task 5.4 will render the full comment thread + post form here. --}}
                    <div id="comments" class="mt-4 p-4"
                        style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
                        <h3 class="h5 mb-2" style="font-weight:700;color:#172000;">{{ __('blog.comments_heading') }}</h3>
                        <p class="mb-0" style="color:#6c757d;">{{ __('blog.comments_placeholder') }}</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    @include('blog._sidebar')
                </div>
            </div>

            @if ($relatedPosts->isNotEmpty())
                <div class="related-posts mt-5">
                    <h3 class="h4 mb-4" style="font-weight:700;color:#172000;">{{ __('blog.related_posts') }}</h3>
                    <div class="row gutter-y-30">
                        @foreach ($relatedPosts as $related)
                            <div class="col-md-4 wow fadeInUp" data-wow-delay="{{ $loop->index * 100 }}ms">
                                @include('blog._post-card', ['post' => $related])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
