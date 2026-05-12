@extends('layouts.app')

@section('body_category', 'Cattle')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('seo.breadcrumb.products'), 'url' => route('products')],
        ['name' => __('seo.breadcrumb.cattle'),   'url' => route('animals.cattle')],
    ]])
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
    <script src="/assets/js/ui/pagination.js"></script>
    <script src="/assets/js/main.js"></script>
@endpush
