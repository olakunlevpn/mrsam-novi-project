@extends('layouts.app')

@section('title', 'Products | Novi-Agro | Premium Livestock Products')

@section('body_category', 'All')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/products.html" />
    <meta name="description"
        content="Browse Novi-Agro's full range of premium livestock feed additives for cattle, pigs, and poultry — trusted by farmers across Nigeria." />
    <meta property="og:title" content="Products | Novi-Agro | Premium Livestock Products" />
    <meta property="og:description" content="Browse Novi-Agro's full range of premium livestock feed additives for cattle, pigs, and poultry — trusted by farmers across Nigeria." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_feed.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/products.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Products | Novi-Agro | Premium Livestock Products" />
    <meta name="twitter:description" content="Browse Novi-Agro's full range of premium livestock feed additives for cattle, pigs, and poultry — trusted by farmers across Nigeria." />

    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('seo.breadcrumb.products'), 'url' => route('products')],
    ]])
@endpush

@section('content')
    @include('blocks.page-header-products')
    @include('blocks.breadcrumb-products')
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
