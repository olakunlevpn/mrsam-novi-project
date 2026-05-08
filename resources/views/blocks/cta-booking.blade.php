        <section class="free-booking-one">
            <div class="free-booking-one__bg" style="background-image: url('{{ $page->block('cta-booking', 'background_image', '/assets/images/shapes/booking-bg1-1.jpg') }}');">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="free-booking-one__content">
                            <div class="free-booking-one__sctwrap wow fadeInUp" data-wow-delay="100ms">
                                <div class="sec-title">

                                    <div class="sec-title__img">
                                        <svg aria-hidden="true" role="presentation" version="1.0" xmlns="http://www.w3.org/2000/svg" direction="rtl"
                                            width="12.000000pt" height="10.000000pt" viewBox="0 0 12.000000 10.000000"
                                            preserveAspectRatio="xMidYMid meet">
                                            <g transform="translate(0.000000,10.000000) scale(0.100000,-0.100000)"
                                                stroke="none">
                                                <path d="M58 84 c12 -8 22 -24 22 -34 0 -10 -10 -26 -22 -34 l-22 -16 27 0
			c17 0 32 9 43 25 15 23 15 27 0 50 -11 16 -26 25 -43 25 l-27 0 22 -16z" />
                                            </g>
                                        </svg>
                                    </div>


                                    <span class="sec-title__tagline">{{ $page->block('cta-booking', 'tagline', 'Free Book Now') }}</span><!-- /.sec-title__tagline -->

                                    <h2 class="sec-title__title">{!! $page->block('cta-booking', 'title', 'Book a FREE <br> Professional Farming <br> Consultation!') !!}</h2><!-- /.sec-title__title -->
                                </div><!-- /.sec-title -->
                            </div>
                            <form class="free-booking-one__form contact-form-validated form-one wow fadeInUp"
                                data-wow-duration="1500ms" action="{{ $page->block('cta-booking', 'form_action', 'https://formsubmit.co/info@novi-agro.com') }}"
                                method="POST">
                                <input type="text" name="_honey" style="display:none" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="_subject" value="{{ $page->block('cta-booking', 'form_subject', 'New Booking Request - Novi Agro') }}">
                                <div class="form-one__group">
                                    <div class="form-one__control">
                                        <input type="text" name="name" placeholder="Your Name" aria-label="Your Name" required>
                                    </div><!-- /.form-one__control -->
                                    <div class="form-one__control">
                                        <input type="email" name="email" placeholder="Your Email" aria-label="Your Email" required>
                                    </div><!-- /.form-one__control -->
                                    <div class="form-one__control">
                                        <input type="tel" name="tel" placeholder="Your Phone" aria-label="Your Phone">
                                    </div><!-- /.form-one__control -->
                                    <div class="form-one__control">
                                        <div class="form-one__control__select">
                                            <label class="sr-only" for="booking-service-select">Choose Services</label>
                                            <select class="selectpicker" id="booking-service-select" name="service">
                                                <option value="">Choose Services</option>
                                                <option value="Consultation">Consultation</option>
                                                <option value="Training">Training</option>
                                            </select>
                                        </div><!-- /.main-menu__language -->
                                    </div><!-- /.form-one__control -->
                                    <div class="form-one__control form-one__control--full d-none">
                                        <input class="grdeen-datepicker" type="text" name="date"
                                            placeholder="Select date" aria-label="Select date">
                                        <i class="fa fa-calendar-alt form-one__control__icon"></i>
                                    </div><!-- /.form-one__control form-one__control--full -->
                                    <div class="form-one__control form-one__control--full">
                                        <textarea name="message" placeholder="Message" aria-label="Your Message"></textarea><!-- /# -->
                                    </div><!-- /.form-one__control -->
                                    <div class="form-one__control form-one__control--full">
                                        <button type="submit" class="grdeen-btn free-booking-one__submit"><span>{{ $page->block('cta-booking', 'submit_label', 'Submit Message') }}</span></button>
                                    </div><!-- /.form-one__control -->
                                </div><!-- /.form-one__group -->
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="grdeen-stretch-element-inside-column">
                            <div class="free-booking-one__image">
                                <div class="free-booking-one__image__shape">
                                    <img loading="lazy" src="{{ $page->block('cta-booking', 'image_bg', '/assets/images/generated/booking_bg.png') }}" alt="Novi">
                                </div>
                                <div class="free-booking-one__image__maskingimg  wow fadeInRight"
                                    data-wow-delay="300ms">
                                    <img loading="lazy" src="{{ $page->block('cta-booking', 'image_vet', '/assets/images/generated/booking_vet1.png') }}" alt="Consultation">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
            <div class="free-booking-one__shadow">
                <h1 class="free-booking-one__shadow__title">{{ $page->block('cta-booking', 'shadow_title', 'Farming') }}</h1>
            </div>
        </section><!-- /.free-booking-one -->
