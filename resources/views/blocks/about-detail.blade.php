        <section class="about-one about-one--two about-one--two--about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-one__image wow fadeInLeft" data-wow-delay="100ms">
                            <div class="about-one__shapetop"></div>
                            <img loading="lazy" class="about-one__bigimage" src="{{ $page->block('about-detail', 'image_main', '/assets/images/about12_1.jpg') }}" alt="Novi">
                            <div class="about-one__smimage">
                                <img loading="lazy" src="{{ $page->block('about-detail', 'image_thumb', '/assets/images/About_2.jpg') }}" alt="Novi">
                            </div>

                            <div class="about-one__shapebottom"></div>
                        </div>
                    </div><!-- /.col-lg-6 -->

                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="100ms">
                        <div class="about-one__content">
                            <div class="about-one__content__sctwap">
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


                                    <span class="sec-title__tagline">{{ $page->block('about-detail', 'tagline', 'Novi-Agro Limited') }}
                                    </span><!-- /.sec-title__tagline -->

                                    <h2 class="sec-title__title">{{ $page->block('about-detail', 'title', 'We offer expert livestock solutions') }}</h2>
                                    <!-- /.sec-title__title -->
                                </div><!-- /.sec-title -->
                            </div>
                            <p class="about-one__content__text">
                                {{ $page->block('about-detail', 'paragraph', 'At Novi Agro, we are dedicated to improving livestock health and farm productivity. We provide high-quality feed additives and professional veterinary consultancy to ensure your animals thrive and your farm operations run smoothly.') }}
                            </p>
                            <div class="about-one__content__qualitwrap">
                                <div class="about-one__content__qualitwrap__col">
                                    <div class="about-one__content__qualitwrap__icon">
                                        <i class="icon-high-quality"></i>
                                    </div>
                                    <h4 class="about-one__content__qualitwrap__title">{!! $page->block('about-detail', 'quality_card_1', 'Quality <br> livestock services') !!}
                                    </h4>
                                </div>
                                <div class="about-one__content__qualitwrap__col mb-0">
                                    <div class="about-one__content__qualitwrap__icon">
                                        <i class="icon-plant"></i>
                                    </div>
                                    <h4 class="about-one__content__qualitwrap__title">{!! $page->block('about-detail', 'quality_card_2', "We're experienced <br> specialists") !!}
                                    </h4>
                                </div>
                                <div class="about-one__content__qualitwrap__pricesbox text-center">
                                    <div class="about-one__content__qualitwrap__tpright">
                                        <i class="icon-pot"></i>
                                    </div>
                                    <div class="about-one__content__qualitwrap__btmleft">
                                        <i class="icon-pot"></i>
                                    </div>
                                    <strong class="about-one__content__qualitwrap__price">{{ $page->block('about-detail', 'stat_value', '5+') }}</strong>
                                    <span class="about-one__content__qualitwrap__text">{{ $page->block('about-detail', 'stat_label', 'Years Of Experience') }}</span>
                                </div>
                            </div>
                            <ul class="list-unstyled about-one__content__list">
                                <li><i class="icon-check-solid"></i>{{ $page->block('about-detail', 'bullet_1', 'Premium feed additives and nutritional supplements') }}
                                </li>
                                <li><i class="icon-check-solid"></i>{{ $page->block('about-detail', 'bullet_2', 'Expert veterinary and farming consultancy') }}</li>
                                <li><i class="icon-check-solid"></i>{{ $page->block('about-detail', 'bullet_3', 'Commitment to maximizing farm productivity') }}</li>
                            </ul>
                            <a href="{{ route('contact') }}" class="grdeen-btn">
                                <span>{{ $page->block('about-detail', 'cta_label', 'Learn More') }}</span>
                            </a>
                        </div>
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.about-one -->
