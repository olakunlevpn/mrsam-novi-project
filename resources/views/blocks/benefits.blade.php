        <section class="our-benefits-one">
            <div class="container-fluid">
                <div class="row">
                    <div class="our-benefits-one__left wow fadeInRight" data-wow-delay="100ms">
                        <div class="our-benefits-one__content">
                            <div class="our-benefits-one__content__sctwap">
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


                                    <span class="sec-title__tagline">{{ $page->block('benefits', 'tagline', 'Your BENEFITS') }}</span><!-- /.sec-title__tagline -->

                                    <h2 class="sec-title__title">{{ $page->block('benefits', 'title', 'Why is mine different from others?') }}</h2>
                                    <!-- /.sec-title__title -->
                                </div><!-- /.sec-title -->
                            </div>

                            <p class="our-benefits-one__content__text">{{ $page->block('benefits', 'paragraph_1', "At Novi-Agro, we stand out through our unwavering commitment to quality, sustainability, and farmer empowerment. We don't just supply products, we build lasting partnerships that help your farm thrive.") }}</p>

                            <div class="our-benefits-one__content__qualitwrap d-flex flex-wrap">
                                <div class="our-benefits-one__content__qualitwrap__col d-flex flex-wrap">
                                    <div class="our-benefits-one__content__qualitwrap__icon">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <h4 class="our-benefits-one__content__qualitwrap__title">{!! $page->block('benefits', 'card_1_title', 'Quality <br> services') !!}</h4>
                                </div>

                                <div class="our-benefits-one__content__qualitwrap__col d-flex flex-wrap">
                                    <div
                                        class="our-benefits-one__content__qualitwrap__icon our-benefits-one__content__qualitwrap__icon--groupicon">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <h4 class="our-benefits-one__content__qualitwrap__title">{!! $page->block('benefits', 'card_2_title', 'Skilled <br> Team') !!}
                                    </h4>
                                </div>
                            </div>

                            <p class="our-benefits-one__content__text">{{ $page->block('benefits', 'paragraph_2', 'We provide end-to-end support, from consultation to providing feeds additive suitable for your livestock, ensuring you get the most out of every season.') }}</p>

                            <div class="our-benefits-one__content__listwrap d-flex justify-content-between">
                                <ul class="list-unstyled our-benefits-one__content__list">
                                    <li><i class="icon-check-solid"></i>{{ $page->block('benefits', 'bullet_1', 'Expert team members') }}</li>
                                    <li><i class="icon-check-solid"></i>{{ $page->block('benefits', 'bullet_2', 'Affordable quality services') }}</li>
                                    <li><i class="icon-check-solid"></i>{{ $page->block('benefits', 'bullet_3', 'Professional Farming Services') }} </li>
                                </ul>
                                <a href="{{ route('about') }}" class="grdeen-btn our-benefits-one__btn">
                                    <span>{{ $page->block('benefits', 'cta_label', 'Find out more') }}</span>
                                </a>
                            </div>
                        </div>
                    </div><!-- /.col-lg-6 -->

                    <div class="our-benefits-one__right">
                        <div class="our-benefits-one__image wow fadeInLeft" data-wow-delay="100ms">
                            <div class="our-benefits-one__shapetop"></div>
                            <img loading="lazy" class="our-benefits-one__bigimage" src="{{ $page->block('benefits', 'image_main', '/assets/images/generated/benefits_hero.png') }}"
                                alt="Success in Farming">
                            <div class="our-benefits-one__smimage">
                                <img loading="lazy" src="{{ $page->block('benefits', 'image_thumb', '/assets/images/generated/benefits_thumb.png') }}" alt="Modern Infrastructure">
                            </div>
                        </div>
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.our-benefits-one -->
