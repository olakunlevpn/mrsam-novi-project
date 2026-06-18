@php
    /** @var \App\Models\Page|null $page */
    $page = $page ?? null;
    $seo  = $page?->seoMeta;
    $defaultTitle = 'NOVI AGRO LTD';
    // Admin-editable (Settings > Site Identity). Stored value carries its own
    // separator (e.g. "| Quality Feeds…"); prepend one space so it reads cleanly.
    $rawSuffix    = trim((string) ($settings['site.title_suffix'] ?? '| Quality Feeds - Healthy Life'));
    $titleSuffix  = $rawSuffix === '' ? '' : ' ' . $rawSuffix;

    // Resolve <title>: SEO override > page title > yielded section > default
    $resolvedTitle = $seo?->title
        ?? trim((string) ($__env->yieldContent('title') ?: ''))
        ?: $defaultTitle;
@endphp
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $resolvedTitle }}{{ str_ends_with($resolvedTitle, $titleSuffix) ? '' : $titleSuffix }}</title>
    <!-- favicons Icons -->
    @php
        $brandFavicon = $settings['brand.favicon'] ?? null;
    @endphp
    @if ($brandFavicon)
        <link rel="icon" href="{{ $brandFavicon }}" />
        <link rel="shortcut icon" href="{{ $brandFavicon }}" />
        <link rel="apple-touch-icon" href="{{ $brandFavicon }}" />
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicons/favicon_io/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicons/favicon_io/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicons/favicon_io/favicon-16x16.png" />
        <link rel="manifest" href="/assets/images/favicons/favicon_io/site.webmanifest" />
    @endif

    @if ($seo)
        @if ($seo->canonical_url)
            <link rel="canonical" href="{{ $seo->canonical_url }}" />
        @endif
        @if ($seo->meta_description)
            <meta name="description" content="{{ $seo->meta_description }}" />
        @endif
        @if ($seo->noindex)
            <meta name="robots" content="noindex, nofollow" />
        @elseif ($seo->robots)
            <meta name="robots" content="{{ $seo->robots }}" />
        @endif

        <!-- Open Graph Tags -->
        @if ($seo->og_title)
            <meta property="og:title" content="{{ $seo->og_title }}" />
        @endif
        @if ($seo->og_description)
            <meta property="og:description" content="{{ $seo->og_description }}" />
        @endif
        @if ($seo->og_image_url)
            <meta property="og:image" content="{{ $seo->og_image_url }}" />
        @endif
        <meta property="og:type" content="website" />
        @if ($seo->canonical_url)
            <meta property="og:url" content="{{ $seo->canonical_url }}" />
        @endif
        <meta name="twitter:card" content="summary_large_image" />
        @if ($seo->og_title)
            <meta name="twitter:title" content="{{ $seo->og_title }}" />
        @endif
        @if ($seo->og_description)
            <meta name="twitter:description" content="{{ $seo->og_description }}" />
        @endif
    @else
        <link rel="canonical" href="https://novi-agro.com/" />
        <meta name="description"
            content="Novi-Agro is a leading provider of premium livestock solutions, including high-quality feeds, expert consultancy, animal care products, and agricultural training." />

        <!-- Open Graph Tags -->
        <meta property="og:title" content="NOVI AGRO LTD | Quality Feeds - Healthy Life" />
        <meta property="og:description" content="Novi-Agro is a leading provider of premium livestock solutions, including high-quality feeds, expert consultancy, animal care products, and agricultural training." />
        <meta property="og:image" content="https://novi-agro.com/assets/images/generated/about_main.png" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://novi-agro.com/" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="NOVI AGRO LTD | Quality Feeds - Healthy Life" />
        <meta name="twitter:description" content="Novi-Agro is a leading provider of premium livestock solutions, including high-quality feeds, expert consultancy, animal care products, and agricultural training." />
    @endif
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100;200;300;400;500;600;700;800;900;1000&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/assets/vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/vendors/bootstrap-select/bootstrap-select.min.css" />
    <link rel="stylesheet" href="/assets/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="/assets/vendors/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="/assets/vendors/jarallax/jarallax.css" />
    <link rel="stylesheet" href="/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="/assets/vendors/nouislider/nouislider.min.css" />
    <link rel="stylesheet" href="/assets/vendors/nouislider/nouislider.pips.css" />
    <link rel="stylesheet" href="/assets/vendors/tiny-slider/tiny-slider.css" />
    <link rel="stylesheet" href="/assets/vendors/grdeen-icons/style.css" />
    <link rel="stylesheet" href="/assets/vendors/owl-carousel/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="/assets/vendors/owl-carousel/css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="/assets/vendors/grdeen-two-icons/style.css">

    <!-- template styles -->
    <link rel="stylesheet" href="/assets/css/grdeen.css" />
    <link rel="stylesheet" href="/assets/css/novi-responsive.css" />

    @stack('styles')
    @stack('meta')
</head>
