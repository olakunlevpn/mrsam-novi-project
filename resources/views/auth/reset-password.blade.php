@extends('layouts.app')

@section('title', __('auth.reset_title'))

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(/assets/images/backgrounds/contact.jpg);"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ __('auth.reset_title') }}</h2>
        </div>
    </section>

    <section class="contact-one">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="contact-one__wrapper p-4 p-md-5 bg-white shadow-sm rounded">
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $request->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autofocus
                                    autocomplete="username">
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('auth.password_label') }}</label>
                                <input id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    autocomplete="new-password">
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
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

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">{{ __('auth.reset_password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
