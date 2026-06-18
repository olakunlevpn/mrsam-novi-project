@extends('layouts.app')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),    'url' => route('home')],
        ['name' => __('seo.breadcrumb.contact'), 'url' => route('contact')],
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
        $pageBlocks = [
            'page-header-contact',
            'breadcrumb-contact',
            'contact-info-cards',
            'contact-form',
            'contact-map',
        ];
    @endphp
    @foreach ($pageBlocks as $blockType)
        @if ($page->shouldRenderBlock($blockType))
            @include('blocks.' . $blockType)
        @endif
    @endforeach
@endsection
