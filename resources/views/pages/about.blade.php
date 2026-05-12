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
    @include('blocks.page-header-about')
    @include('blocks.breadcrumb-about')
    @include('blocks.about-detail')
    @include('blocks.feature-grid-about')
    @include('blocks.benefits-about')
    @include('blocks.journey-growth')
    @include('blocks.customer-growth')
    @include('blocks.testimonials')
@endsection
