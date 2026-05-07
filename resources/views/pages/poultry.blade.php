@extends('layouts.app')

@section('title', 'Poultry | Novi-Agro | Premium Livestock Products')

@section('body_category', 'Poultry')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/poultry.html" />
    <meta name="description"
        content="Shop Novi-Agro's poultry feed additives engineered for healthier birds, better egg production, and improved broiler performance across Nigeria." />
    <meta property="og:title" content="Poultry | Novi-Agro | Premium Livestock Products" />
    <meta property="og:description" content="Shop Novi-Agro's poultry feed additives engineered for healthier birds, better egg production, and improved broiler performance across Nigeria." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_poultry.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/poultry.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Poultry | Novi-Agro | Premium Livestock Products" />
    <meta name="twitter:description" content="Shop Novi-Agro's poultry feed additives engineered for healthier birds, better egg production, and improved broiler performance across Nigeria." />
@endpush

@section('content')
    @include('blocks.page-header-poultry')
    @include('blocks.breadcrumb-poultry')
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
