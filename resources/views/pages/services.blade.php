@extends('layouts.app')

@push('meta')
    @include('partials.breadcrumb-jsonld', ['crumbs' => [
        ['name' => __('seo.breadcrumb.home'),     'url' => route('home')],
        ['name' => __('seo.breadcrumb.services'), 'url' => route('services')],
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
    @include('blocks.page-header-services')
    @include('blocks.breadcrumb-services')
    @include('blocks.service-cards-grid')
@endsection
