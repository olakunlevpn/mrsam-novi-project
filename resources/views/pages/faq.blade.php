@extends('layouts.app')

@section('title', 'Your Questions Answered | Novi-Agro')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/faq.html" />
    <meta name="description"
        content="Find answers to common questions about Novi-Agro's livestock feeds, consultancy services, animal care products, and farm management solutions." />
    <meta property="og:title" content="Your Questions Answered | Novi-Agro" />
    <meta property="og:description" content="Find answers to common questions about Novi-Agro's livestock feeds, consultancy services, animal care products, and farm management solutions." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_feed.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/faq.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Your Questions Answered | Novi-Agro" />
    <meta name="twitter:description" content="Find answers to common questions about Novi-Agro's livestock feeds, consultancy services, animal care products, and farm management solutions." />

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
