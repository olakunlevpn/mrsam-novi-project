@extends('layouts.app')

@section('title', __('auth.verify_title'))

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(/assets/images/backgrounds/contact.jpg);"></div>
        <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
        <div class="page-header__overlay"></div>
        <div class="container">
            <h2 class="page-header__title">{{ __('auth.verify_title') }}</h2>
        </div>
    </section>

    <section class="contact-one">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="contact-one__wrapper p-4 p-md-5 bg-white shadow-sm rounded">
                        <p class="text-muted mb-4">{{ __('auth.verify_help') }}</p>

                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success" role="status">{{ __('auth.verify_sent') }}</div>
                        @endif

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <form method="POST" action="{{ route('verification.send') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-success px-4">{{ __('auth.resend_verification') }}</button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted text-decoration-underline p-0">
                                    {{ __('auth.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
