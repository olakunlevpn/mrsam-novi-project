        <section class="work-process-one">
            <div class="container">
                <div class="row gutter-y-20">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="100ms">
                        <div class="work-process-one__sctwrap">
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


                                <span class="sec-title__tagline">{{ $page->block('work-process', 'tagline', 'Our work process') }}</span><!-- /.sec-title__tagline -->

                                <h2 class="sec-title__title">{{ $page->block('work-process', 'title', 'See how we complete the work') }}</h2>
                                <!-- /.sec-title__title -->
                            </div><!-- /.sec-title -->
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="100ms">
                        <div class="work-process-one__infowrap">
                            <p class="work-process-one__infowrap__text">{{ $page->block('work-process', 'paragraph', 'At Novi Agro, we follow a systematic and efficient approach to ensure your farm gets the best nutrition, supplies, and professional guidance for maximum productivity.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row gutter-y-30">
                    <div class="col-xl-3 col-lg-6 wow fadeIn" data-wow-delay="00ms">
                        <div class="work-process-one__col">
                            <div class="work-process-one__col__circlewrap">
                                <div class="work-process-one__col__icon">
                                    <i class="{{ $page->block('work-process', 'step_1_icon', 'icon-high-quality') }}"></i>
                                </div>
                            </div>
                            <div class="work-process-one__col__info text-center">
                                <h4 class="work-process-one__col__title">{{ $page->block('work-process', 'step_1_title', 'Livestock Nutrition') }}</h4>
                                <p class="work-process-one__col__text">{{ $page->block('work-process', 'step_1_text', 'Premium additives to boost animal health and productivity.') }}</p>
                            </div>
                            <div class="work-process-one__col__shapebg"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 wow fadeIn" data-wow-delay="100ms">
                        <div class="work-process-one__col">
                            <div class="work-process-one__col__circlewrap">
                                <div class="work-process-one__col__icon">
                                    <i class="{{ $page->block('work-process', 'step_2_icon', 'icon-shop-bag') }}"></i>
                                </div>
                            </div>
                            <div class="work-process-one__col__info text-center">
                                <h4 class="work-process-one__col__title">{{ $page->block('work-process', 'step_2_title', 'Product Supply') }}</h4>
                                <p class="work-process-one__col__text">{{ $page->block('work-process', 'step_2_text', 'Providing a consistent supply of essential agro-products and supplements for your farm.') }}</p>
                            </div>
                            <div class="work-process-one__col__shapebg"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 wow fadeIn" data-wow-delay="200ms">
                        <div class="work-process-one__col">
                            <div class="work-process-one__col__circlewrap">
                                <div class="work-process-one__col__icon">
                                    <i class="{{ $page->block('work-process', 'step_3_icon', 'icon-group') }}"></i>
                                </div>
                            </div>
                            <div class="work-process-one__col__info text-center">
                                <h4 class="work-process-one__col__title">{{ $page->block('work-process', 'step_3_title', 'Consultancy') }}</h4>
                                <p class="work-process-one__col__text">{{ $page->block('work-process', 'step_3_text', 'Expert advisory services to help you scale your farming business with modern techniques.') }}</p>
                            </div>
                            <div class="work-process-one__col__shapebg"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 wow fadeIn" data-wow-delay="300ms">
                        <div class="work-process-one__col">
                            <div class="work-process-one__col__circlewrap">
                                <div class="work-process-one__col__icon">
                                    <i class="{{ $page->block('work-process', 'step_4_icon', 'icon-location') }}"></i>
                                </div>
                            </div>
                            <div class="work-process-one__col__info text-center">
                                <h4 class="work-process-one__col__title">{{ $page->block('work-process', 'step_4_title', 'Logistics') }}</h4>
                                <p class="work-process-one__col__text">{{ $page->block('work-process', 'step_4_text', 'Fast and reliable nationwide delivery, ensuring your supplies reach you when you need them.') }}</p>
                            </div>
                            <div class="work-process-one__col__shapebg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- work-process end -->
