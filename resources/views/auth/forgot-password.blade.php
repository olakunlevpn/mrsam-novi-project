@extends('layouts.app')

@section('title', __('auth.forgot_title'))

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(/assets/images/backgrounds/contact.jpg);"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ __('auth.forgot_title') }}</h2>
        </div>
    </section>

    <section class="contact-one">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <x-auth-session-status :status="session('status')" />

                    <div class="contact-one__wrapper p-4 p-md-5 bg-white shadow-sm rounded">
                        <p class="text-muted mb-4">{{ __('auth.forgot_help') }}</p>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">{{ __('auth.send_reset_link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
