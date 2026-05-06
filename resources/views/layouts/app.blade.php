<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')
<body class="custom-cursor" data-category="@yield('body_category', 'All')">
    @include('layouts.partials.preloader')
    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')
    @include('layouts.partials.scripts')
</body>
</html>
