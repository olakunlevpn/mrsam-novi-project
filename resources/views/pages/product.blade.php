@extends('layouts.app')

@php
    /** @var \App\Models\Product $product */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Product> $related */

    $seo = $product->seoMeta;
    $metaTitle = $seo?->title ?? $product->name;
    $metaDescription = $seo?->meta_description
        ?? \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 160);

    $heroImageUrl = $product->hero_image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->hero_image)
        : '/assets/images/backgrounds/photorealistic-view-cow-grazing-outdoors.jpg';

    $animalLabel = $product->animal?->name;
    $categoryLabel = $product->productCategory?->name;
@endphp

@section('title', $metaTitle)

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('products.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('products.breadcrumb.products'), 'url' => route('products')],
        ['name' => $product->name,                     'url' => route('products.show', $product)],
    ]])

    @php
        $productJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $metaDescription,
            'image' => url($heroImageUrl),
            'sku' => $product->sku ?: $product->slug,
            'category' => $categoryLabel,
            'url' => route('products.show', $product),
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name'),
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($productJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <section class="page-header">
        <div class="page-header__bg"
            style="background-image: url('/assets/images/backgrounds/photorealistic-view-cow-grazing-outdoors.jpg'); background-position: center; background-size: cover;">
        </div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $product->name }}</h2>
        </div>
    </section>

    <section class="mt-4 mb-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <div class="d-flex align-items-center py-3 px-4 rounded-2"
                    style="background-color: #f8f9fa; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <ul class="grdeen-breadcrumb list-unstyled mb-0 d-flex justify-content-start align-items-center w-100 flex-wrap">
                        <li style="color: #6c757d; font-weight: 500; font-size: 17px;">
                            <a href="{{ route('home') }}" class="d-flex align-items-center" style="color: inherit;">
                                <i class="fas fa-home me-2" style="font-size: 16px;"></i>
                                {{ __('products.breadcrumb.home') }}
                            </a>
                        </li>
                        <li style="color: #6c757d; font-weight: 500; font-size: 17px;">
                            <a href="{{ route('products') }}" style="color: inherit;">{{ __('products.breadcrumb.products') }}</a>
                        </li>
                        <li style="color: var(--grdeen-black, #172000); font-weight: 600; font-size: 17px;">
                            <span>{{ \Illuminate\Support\Str::limit($product->name, 60) }}</span>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <section class="product-one pb-5">
        <div class="container">
            <div class="mb-5 d-flex justify-content-between align-items-center">
                <a href="{{ route('products') }}" class="grdeen-btn">
                    <span><i class="fas fa-arrow-left me-2"></i>{{ __('products.detail.back_to_catalog') }}</span>
                </a>
                <div class="d-flex align-items-center gap-2">
                    @if ($animalLabel)
                        <span class="badge bg-success-soft text-success px-3 py-2">{{ \Illuminate\Support\Str::upper($animalLabel) }}</span>
                    @endif
                    @if ($categoryLabel)
                        <span class="badge bg-light text-muted px-3 py-2">{{ $categoryLabel }}</span>
                    @endif
                </div>
            </div>

            <div class="row gutter-y-40">
                <div class="col-lg-5">
                    <div class="product-detail-img-wrap rounded-3 bg-light p-4 text-center">
                        <img loading="lazy" src="{{ $heroImageUrl }}" alt="{{ $product->name }}" class="img-fluid"
                            style="max-height: 500px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="product-detail-content">
                        <h2 class="mb-4"
                            style="color: var(--grdeen-black); font-weight: 700; font-size: 42px;">{{ $product->name }}</h2>

                        @if (filled($product->description))
                            <div class="mb-4">
                                <h5 class="text-success fw-bold text-uppercase small mb-2"
                                    style="letter-spacing: 1px;">{{ __('products.detail.description') }}</h5>
                                <p class="text-muted" style="line-height: 1.8; font-size: 16px;">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if (filled($product->composition))
                            <div class="mb-4">
                                <h5 class="text-success fw-bold text-uppercase small mb-2"
                                    style="letter-spacing: 1px;">{{ __('products.detail.composition') }}</h5>
                                <p class="bg-light p-4 rounded text-muted mb-0"
                                    style="font-size: 15px; font-style: italic;">{{ $product->composition }}</p>
                            </div>
                        @endif

                        @if (filled($product->benefits))
                            <div class="mb-4">
                                <h5 class="text-success fw-bold text-uppercase small mb-2"
                                    style="letter-spacing: 1px;">{{ __('products.detail.benefits') }}</h5>
                                <p class="text-muted mb-0" style="font-size: 16px;">{{ $product->benefits }}</p>
                            </div>
                        @endif

                        @if (filled($product->usage_instructions))
                            <div class="mb-5">
                                <h5 class="text-success fw-bold text-uppercase small mb-2"
                                    style="letter-spacing: 1px;">{!! __('products.detail.usage') !!}</h5>
                                <p class="text-muted mb-0" style="font-size: 16px;">{{ $product->usage_instructions }}</p>
                            </div>
                        @endif

                        <div class="d-flex align-items-center gap-3 mt-4">
                            <a href="{{ route('contact') }}" class="grdeen-btn">
                                <span>{{ __('products.detail.enquire') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <div class="mt-5 pt-5 border-top">
                    <h4 class="fw-bold mb-4">{{ __('products.detail.related') }}</h4>
                    <div class="row g-4">
                        @foreach ($related as $sibling)
                            @include('products._card', ['product' => $sibling])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
