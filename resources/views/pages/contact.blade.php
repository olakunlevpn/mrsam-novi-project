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
    @include('blocks.page-header-contact')
    @include('blocks.breadcrumb-contact')
    @include('blocks.contact-info-cards')
    @include('blocks.contact-form')
    @include('blocks.contact-map')
@endsection
