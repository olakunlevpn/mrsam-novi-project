@extends('layouts.app')

@section('title', 'Pigs | Novi-Agro | Premium Livestock Products')

@section('body_category', 'Pigs')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/pigs.html" />
    <meta name="description"
        content="Discover Novi-Agro's specialized pig feed additives formulated for optimal swine growth, health, and productivity on Nigerian farms." />
    <meta property="og:title" content="Pigs | Novi-Agro | Premium Livestock Products" />
    <meta property="og:description" content="Discover Novi-Agro's specialized pig feed additives formulated for optimal swine growth, health, and productivity on Nigerian farms." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_pigs.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/pigs.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Pigs | Novi-Agro | Premium Livestock Products" />
    <meta name="twitter:description" content="Discover Novi-Agro's specialized pig feed additives formulated for optimal swine growth, health, and productivity on Nigerian farms." />

    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('seo.breadcrumb.products'), 'url' => route('products')],
        ['name' => __('seo.breadcrumb.pigs'),     'url' => route('animals.pigs')],
    ]])
@endpush

@section('content')
    @include('blocks.page-header-pigs')
    @include('blocks.breadcrumb-pigs')
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
