{{--
    Generic shell used by PageController::show() for admin-created Pages
    that don't have a dedicated pages.{slug} Blade. Title and SEO come from
    the Page record; meta tags come from $page->seoMeta via head.blade.php.
    Body content is driven entirely by the page's blocks via the existing
    <x-cms.page-blocks /> component.
--}}
@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <x-cms.page-blocks :page="$page" />
@endsection
