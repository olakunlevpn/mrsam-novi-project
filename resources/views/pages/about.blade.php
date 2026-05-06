@extends('layouts.app')

@section('title', 'About | Novi-Agro | Quality Livestock Solutions')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/about.html" />
    <meta name="description"
        content="Learn about Novi-Agro's mission to empower Nigerian farmers with premium livestock feed additives, expert consultancy, and innovative agricultural solutions." />
    <meta property="og:title" content="About | Novi-Agro | Quality Livestock Solutions" />
    <meta property="og:description" content="Learn about Novi-Agro's mission to empower Nigerian farmers with premium livestock feed additives, expert consultancy, and innovative agricultural solutions." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/about_main.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/about.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="About | Novi-Agro | Quality Livestock Solutions" />
    <meta name="twitter:description" content="Learn about Novi-Agro's mission to empower Nigerian farmers with premium livestock feed additives, expert consultancy, and innovative agricultural solutions." />
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
