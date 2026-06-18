@extends('layouts.app')

@section('body_category', 'Pigs')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('seo.breadcrumb.products'), 'url' => route('products')],
        ['name' => __('seo.breadcrumb.pigs'),     'url' => route('animals.pigs')],
    ]])
@endpush

@section('content')
    @php
        // Render each section only when its page_block is visible, so the
        // admin's "Visible on page" toggle takes effect on the frontend.
        $pageBlocks = ['page-header-pigs', 'breadcrumb-pigs', 'product-catalog'];
    @endphp
    @foreach ($pageBlocks as $blockType)
        @if ($page->shouldRenderBlock($blockType))
            @include('blocks.' . $blockType)
        @endif
    @endforeach
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
