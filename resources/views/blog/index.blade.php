@extends('layouts.app')

@section('title', __('blog.index_title') . ' | Novi-Agro')

@push('meta')
    <meta name="description" content="{{ __('blog.index_tagline') }}" />
@endpush

@push('styles')
    <style>
        .main-menu__list>li.current>a {
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    <section class="page-header">
        <div class="page-header__bg"
            style="background-image: url('/assets/images/backgrounds/about.png');"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">@yield('blog_header_title', __('blog.index_title'))</h2>
        </div>
    </section>

    <section class="mt-4 mb-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <div class="d-flex align-items-center py-3 px-4 rounded-2"
                    style="background-color: #f8f9fa; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <ul class="grdeen-breadcrumb list-unstyled mb-0 d-flex justify-content-start align-items-center w-100">
                        <li style="color: #6c757d; font-weight: 500; font-size: 17px;">
                            <a href="{{ route('home') }}" class="d-flex align-items-center" style="color: inherit;">
                                <i class="fas fa-home me-2" style="font-size: 16px;"></i> {{ __('blog.breadcrumb_home') }}
                            </a>
                        </li>
                        <li style="color: var(--grdeen-black, #172000); font-weight: 600; font-size: 17px;">
                            <span>@yield('blog_breadcrumb', __('blog.breadcrumb_blog'))</span>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <section class="blog-listing pb-5">
        <div class="container">
            <div class="row gutter-y-30">
                <div class="col-lg-8">
                    @yield('blog_intro')

                    @if ($posts->isEmpty())
                        <div class="p-4" style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
                            <p class="mb-0" style="color:#5a6268;">{{ __('blog.no_posts') }}</p>
                        </div>
                    @else
                        <div class="row gutter-y-30">
                            @foreach ($posts as $post)
                                <div class="col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->index * 100 }}ms">
                                    @include('blog._post-card', ['post' => $post])
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $posts->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    @include('blog._sidebar', [
                        'categories'     => $categories,
                        'tags'           => $tags,
                        'activeCategory' => $activeCategory ?? null,
                        'activeTag'      => $activeTag ?? null,
                    ])
                </div>
            </div>
        </div>
    </section>
@endsection
