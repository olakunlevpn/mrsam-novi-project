{{--
    Tag archive. Reuses the index template; only the page header copy varies.
--}}
@extends('blog.index', ['activeTag' => $tag])

@section('title', __('blog.tag_title', ['name' => $tag->name]) . ' | Novi-Agro')

@section('blog_header_title', '#' . $tag->name)
@section('blog_breadcrumb', __('blog.tag_title', ['name' => $tag->name]))
