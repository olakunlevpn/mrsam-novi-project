@extends('layouts.app')

@section('title', 'Cattle | Novi-Agro | Premium Livestock Products')

@section('body_category', 'Cattle')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/cattle.html" />
    <meta name="description"
        content="Explore Novi-Agro's premium cattle feed additives designed to boost growth, milk production, and overall herd health for Nigerian farmers." />
    <meta property="og:title" content="Cattle | Novi-Agro | Premium Livestock Products" />
    <meta property="og:description" content="Explore Novi-Agro's premium cattle feed additives designed to boost growth, milk production, and overall herd health for Nigerian farmers." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_cattle.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/cattle.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Cattle | Novi-Agro | Premium Livestock Products" />
    <meta name="twitter:description" content="Explore Novi-Agro's premium cattle feed additives designed to boost growth, milk production, and overall herd health for Nigerian farmers." />
@endpush

@section('content')
    @include('blocks.page-header-cattle')
    @include('blocks.breadcrumb-cattle')
    @include('blocks.product-catalog')
@endsection

@push('scripts')
    <script src="/assets/js/state/app.state.js"></script>
    <script src="/assets/js/utils/url.utils.js"></script>
    <script src="/assets/js/services/product.service.js"></script>
    <script src="/assets/js/ui/product.card.js"></script>
    <script src="/assets/js/ui/product.list.js"></script>
    <script src="/assets/js/ui/product.detail.js"></script>
    <script src="/assets/js/ui/pagination.js"></script>
    <script src="/assets/js/main.js"></script>
@endpush
