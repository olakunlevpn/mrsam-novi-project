@extends('layouts.app')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'), 'url' => route('home')],
        ['name' => __('seo.breadcrumb.faq'),  'url' => route('faq')],
    ]])
@endpush

@push('styles')
    <style>
        .main-menu__list>li.current>a {
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    @php
        // Render each section only when its page_block is visible, so the
        // admin's "Visible on page" toggle takes effect on the frontend.
        $pageBlocks = ['page-header-faq', 'breadcrumb-faq', 'faq-accordion'];
    @endphp
    @foreach ($pageBlocks as $blockType)
        @if ($page->shouldRenderBlock($blockType))
            @include('blocks.' . $blockType)
        @endif
    @endforeach
@endsection
