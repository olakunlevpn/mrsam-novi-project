@extends('layouts.app')

@section('title', 'NOVI AGRO LTD')

@push('meta')
    <!-- Schema.org Structured Data -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Novi Agro Ltd",
        "description": "Leading provider of premium livestock feed additives and agricultural consultancy services in Nigeria.",
        "url": "https://novi-agro.com",
        "logo": "https://novi-agro.com/assets/images/images-removebg-preview.png",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+2347041041756",
            "contactType": "customer service",
            "email": "info@novi-agro.com"
        },
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "NG"
        },
        "sameAs": [
            "https://www.facebook.com/profile.php?id=100077163775495",
            "https://www.instagram.com/novi_agroltd/"
        ]
    }
    </script>
    @endverbatim
@endpush

@push('styles')
    <!-- Video Hero Custom Styles -->
    <style>
        .video-hero-one {
            background: linear-gradient(135deg, #1a3c1a 0%, #2d5a1b 50%, #0d2b0d 100%);
        }

        .video-hero-one__content {
            text-align: left;
            position: relative;
            z-index: 2;
            padding-left: 40px;
        }

        /* Decorative Vertical Accent */
        .video-hero-one__content::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 6px;
            background: var(--grdeen-base, #1a9120);
            border-radius: 10px;
            animation: heroVerticalLine 1s ease both;
        }

        /* Side Gradient for legibility */
        .video-hero-one__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);
            z-index: 1;
        }

        @media (max-width: 767px) {
            .video-hero-one__content {
                padding-left: 25px;
            }

            .video-hero-one__content::before {
                width: 4px;
            }

            .video-hero-one__overlay {
                background: rgba(53, 194, 10, 0.6);
                /* More uniform overlay on mobile */
            }
        }

        .video-hero-one__sub-title {
            display: inline-block;
            background: var(--grdeen-base, #1a9120);
            /* Brand Green Background */
            color: #ffffff !important;
            /* White Text */
            opacity: 1 !important;
            font-size: 14px;
            /* Slightly smaller for the badge look */
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 25px;
            padding: 10px 24px;
            border: 2px solid #ffffff;
            /* Contrast border */
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            animation: heroFadeLeft 0.8s ease both;
        }

        .video-hero-one__title {
            color: #ffffff !important;
            opacity: 1 !important;
            font-size: clamp(48px, 8vw, 95px);
            font-weight: 900;
            line-height: 1.0;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin-bottom: 45px;
            animation: heroFadeLeft 1s ease 0.2s both;
        }

        .video-hero-one__btn {
            animation: heroFadeLeft 1.2s ease 0.4s both;
        }

        @keyframes heroVerticalLine {
            from {
                transform: scaleY(0);
                opacity: 0;
            }

            to {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        @keyframes heroFadeLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Navbar Home Link Distinguished Style */
        .main-menu__list>li.current>a {
            color: #ffffff !important;
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Product detail responsive */
        @media (max-width: 767px) {
            #slotProductTitle {
                font-size: 28px !important;
            }
            .product-detail-img-wrap {
                padding: 15px !important;
            }
            #product-details .d-flex.justify-content-between {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Species section - card label span color (supplement to novi-responsive.css) */
        .species-card__label span {
            color: #fff;
        }
    </style>
@endpush

@section('content')
    @include('blocks.hero')
    @include('blocks.feature-grid')
    @include('blocks.about-intro')
    @include('blocks.species-cards')
    @include('blocks.services-summary')
    @include('blocks.work-process')
    @include('blocks.benefits')
    @include('blocks.stats-bar')
    @include('blocks.cta-booking')
    @include('blocks.partners-carousel')
@endsection
