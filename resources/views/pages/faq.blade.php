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
    @include('blocks.page-header-faq')
    @include('blocks.breadcrumb-faq')
    @include('blocks.faq-accordion')
@endsection
