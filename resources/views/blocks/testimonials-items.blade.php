@php
    $items = \App\Models\Testimonial::published()->ordered()->get();
@endphp
@foreach ($items as $i => $item)
                    <div class="item">
                        <div class="testimonials-card testimonials-dark-card  wow fadeInUp" data-wow-duration='1500ms'
                            data-wow-delay='{{ ($i * 100) }}ms'>
                            <div class="testimonials-card__inner">
                                <div
                                    class="testimonials-card__ratingwrap d-flex align-items-center justify-content-between">
                                    <div class="testimonials-card__rating">
                                        @php $stars = (int) ($item->rating ?? 5); @endphp
                                        @for ($s = 0; $s < $stars; $s++)
                                            <span class="testimonials-card__rating__start"><i class="fa fa-star"></i></span>
                                        @endfor
                                    </div><!-- /.testimonials-card__rating -->
                                    <div class="testimonials-card__icon">
                                        <img loading="lazy" src="/assets/images/shapes/funfact-icon2-1.png" alt="">
                                    </div>
                                </div>

                                <div class="testimonials-card__content">
                                    {{ $item->content ?? '' }}
                                </div><!-- /.testimonials-card__content -->
                                <div class="testimonials-card__top">
                                    <div class="testimonials-card__image">
                                        <img loading="lazy" src="{{ \App\Support\AssetUrl::resolve($item->image ?? null, '') ?? '' }}" alt="{{ $item->name ?? '' }}">
                                    </div><!-- /.testimonials-card__image -->

                                    <div class="testimonials-card__top__left">
                                        <h3 class="testimonials-card__name">
                                            {{ $item->name ?? '' }}
                                        </h3><!-- /.testimonials-card__name -->
                                        <p class="testimonials-card__designation">{{ $item->designation ?? '' }}</p>
                                        <!-- /.testimonials-card__designation -->

                                        <div class="testimonials-card__quote">
                                            <i class="icon-quote"></i>
                                        </div>
                                    </div><!-- /.testimonials-card__top__left -->
                                </div><!-- /.testimonials-card__top -->
                                <div class="testimonials-card__shape2">
                                    <img loading="lazy" src="/assets/images/shapes/testimonial-shape2-1.png" alt="">
                                </div>
                            </div><!-- /.testimonials-card__inner -->
                        </div><!-- /.testimonials-card -->
                    </div><!-- /.item -->
@endforeach
