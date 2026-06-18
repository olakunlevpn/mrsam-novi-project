@extends('layouts.app')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),  'url' => route('home')],
        ['name' => __('seo.breadcrumb.about'), 'url' => route('about')],
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
        // Each section is backed by a page_block of the same type; render only
        // when visible so the admin's "Visible on page" toggle takes effect.
        $pageBlocks = [
            'page-header-about',
            'breadcrumb-about',
            'about-detail',
            'feature-grid-about',
            'benefits-about',
            'journey-growth',
            'customer-growth',
            'testimonials',
        ];
    @endphp
    @foreach ($pageBlocks as $blockType)
        @if ($page->shouldRenderBlock($blockType))
            @include('blocks.' . $blockType)
        @endif
    @endforeach
@endsection
