        <section class="contact-one">
            <div class="container">
                <h3 class="contact-one__title">
                    {!! $page->block('contact-info-cards', 'title', 'Do you have questions? Speak<br> with us through message') !!}
                </h3>
                <p class="contact-one__text">{{ $page->block('contact-info-cards', 'subtitle', 'You can also reach out to us by phone or email for any enquiries') }}</p>
                <!-- /.contact-one__text -->
                <div class="contact-one__info-wrapper"
                    style="background-image: url({{ $page->block('contact-info-cards', 'background_image', '/assets/images/shapes/contact-bg-1.png') }});">
                    <div class="row gutter-y-30">
                        <div class="col-lg-4 col-md-6">
                            <div class="contact-one__info">
                                <div class="contact-one__info__icon">
                                    <i class="{{ $page->block('contact-info-cards', 'card_1_icon', 'icon-location') }}"></i>
                                </div><!-- /.contact-one__info__icon -->
                                <h4 class="contact-one__info__title">{{ $page->block('contact-info-cards', 'card_1_title', 'Our office') }}</h4><!-- /.contact-one__info__title -->
                                <p class="contact-one__info__text">{{ $page->block('contact-info-cards', 'card_1_text', 'KM 10, Old Lagos-Ibadan Expressway, New Garage, Ibadan') }}</p><!-- /.contact-one__info__text -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="contact-one__info">
                                <div class="contact-one__info__icon">
                                    <i class="{{ $page->block('contact-info-cards', 'card_2_icon', 'icon-telephone-call') }}"></i>
                                </div><!-- /.contact-one__info__icon -->
                                <h4 class="contact-one__info__title">{{ $page->block('contact-info-cards', 'card_2_title', 'Make a call') }}</h4><!-- /.contact-one__info__title -->
                                <p class="contact-one__info__text">
                                    @php $phone1 = $page->block('contact-info-cards', 'card_2_phone_1', '+2347041041756'); @endphp
                                    @php $phone2 = $page->block('contact-info-cards', 'card_2_phone_2', '+2349012000101'); @endphp
                                    <a href="tel:{{ $phone1 }}">{{ $phone1 }}</a>
                                    <a href="tel:{{ $phone2 }}">{{ $phone2 }}</a>
                                </p><!-- /.contact-one__info__text -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="contact-one__info">
                                <div class="contact-one__info__icon">
                                    <i class="{{ $page->block('contact-info-cards', 'card_3_icon', 'icon-help') }}"></i>
                                </div><!-- /.contact-one__info__icon -->
                                <h4 class="contact-one__info__title">{{ $page->block('contact-info-cards', 'card_3_title', 'Support') }}</h4><!-- /.contact-one__info__title -->
                                <p class="contact-one__info__text">
                                    @php $email = $page->block('contact-info-cards', 'card_3_email', 'info@novi-agro.com'); @endphp
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>

                                </p><!-- /.contact-one__info__text -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
