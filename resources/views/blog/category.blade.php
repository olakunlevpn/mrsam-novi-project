{{--
    Category archive. Re-uses the index layout via @extends so the listing
    shell stays in one place; only the page-header / breadcrumb / intro vary.
--}}
@extends('blog.index', ['activeCategory' => $category])

@section('title', __('blog.category_title', ['name' => $category->name]) . ' | Novi-Agro')

@section('blog_header_title', $category->name)
@section('blog_breadcrumb', $category->name)

@section('blog_intro')
    @if ($category->description)
        <div class="mb-4 p-4"
            style="background:#fff;border:1px solid #e9ecef;border-radius:8px;color:#5a6268;line-height:1.6;">
            {{ $category->description }}
        </div>
    @endif
@endsection
