@extends('layouts.app')

@section('title', __('auth.register_title'))

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(/assets/images/backgrounds/contact.jpg);"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ __('auth.register_title') }}</h2>
        </div>
    </section>

    <section class="contact-one">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="contact-one__wrapper p-4 p-md-5 bg-white shadow-sm rounded">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('auth.name') }}</label>
                                <input id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    required
                                    autofocus
                                    autocomplete="name">
                                @error('name')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autocomplete="username">
                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('auth.password_label') }}</label>
                                <input id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    autocomplete="new-password">
                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('auth.password_confirmation') }}</label>
                                <input id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                    autocomplete="new-password">
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <a class="text-decoration-underline text-muted" href="{{ route('login') }}">
                                    {{ __('auth.already_registered') }}
                                </a>
                                <button type="submit" class="btn btn-success px-4">{{ __('auth.register') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
