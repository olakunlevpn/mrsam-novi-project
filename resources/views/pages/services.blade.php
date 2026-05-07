@extends('layouts.app')

@section('title', 'Services | Novi-Agro | Quality Livestock Solutions')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/services.html" />
    <meta name="description"
        content="Novi-Agro provides high-quality livestock feeds and expert consultancy services to empower farmers and enhance productivity." />
    <meta property="og:title" content="Services | Novi-Agro | Quality Livestock Solutions" />
    <meta property="og:description" content="Novi-Agro provides high-quality livestock feeds and expert consultancy services to empower farmers and enhance productivity." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_support.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/services.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Services | Novi-Agro | Quality Livestock Solutions" />
    <meta name="twitter:description" content="Novi-Agro provides high-quality livestock feeds and expert consultancy services to empower farmers and enhance productivity." />
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
