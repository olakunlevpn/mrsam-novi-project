{{--
    BreadcrumbList JSON-LD partial. Include from a `@push('meta')` block.

    Usage:
        @push('meta')
            @include('partials.breadcrumb-jsonld', ['crumbs' => [
                ['name' => __('seo.breadcrumb.home'),  'url' => route('home')],
                ['name' => __('seo.breadcrumb.about'), 'url' => route('about')],
            ]])
        @endpush

    Each crumb is an associative array with `name` and `url` (absolute URL,
    so prefer route() over hand-written paths).
--}}
@php
    /** @var array<int, array{name: string, url: string}> $crumbs */
    $crumbs = $crumbs ?? [];
    $itemListElement = [];
    foreach ($crumbs as $i => $crumb) {
        $itemListElement[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['name'],
            'item'     => $crumb['url'],
        ];
    }
    $payload = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $itemListElement,
    ];
@endphp
@if (! empty($itemListElement))
    <script type="application/ld+json">
        {!! json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif
