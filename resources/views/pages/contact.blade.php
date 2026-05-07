@extends('layouts.app')

@section('title', 'Contact | Novi-Agro | Quality Livestock Solutions')

@push('meta')
    <link rel="canonical" href="https://novi-agro.com/contact.html" />
    <meta name="description"
        content="Contact Novi-Agro for premium livestock feeds, consultancy, and animal care solutions. We are here to support your farming business." />
    <meta property="og:title" content="Contact | Novi-Agro | Quality Livestock Solutions" />
    <meta property="og:description" content="Contact Novi-Agro for premium livestock feeds, consultancy, and animal care solutions. We are here to support your farming business." />
    <meta property="og:image" content="https://novi-agro.com/assets/images/generated/gallery_support.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://novi-agro.com/contact.html" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Contact | Novi-Agro | Quality Livestock Solutions" />
    <meta name="twitter:description" content="Contact Novi-Agro for premium livestock feeds, consultancy, and animal care solutions. We are here to support your farming business." />
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
