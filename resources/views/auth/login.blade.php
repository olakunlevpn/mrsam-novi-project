@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(/assets/images/backgrounds/contact.jpg);"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ __('auth.login_title') }}</h2>
        </div>
    </section>

    <section class="contact-one">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <x-auth-session-status :status="session('status')" />

                    <div class="contact-one__wrapper p-4 p-md-5 bg-white shadow-sm rounded">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autofocus
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
                                    autocomplete="current-password">
                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                                <label for="remember_me" class="form-check-label">{{ __('auth.remember_me') }}</label>
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-underline text-muted" href="{{ route('password.request') }}">
                                        {{ __('auth.forgot_prompt') }}
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-success px-4">{{ __('auth.log_in') }}</button>
                            </div>

                            <p class="mt-3 mb-0 text-muted">
                                {{ __('auth.already_registered') }}
                                <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
