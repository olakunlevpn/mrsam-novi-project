@php
    $items = $page->block('testimonials', 'items');
    if (! is_array($items) || empty($items)) {
        $items = [
            [
                'name'        => 'Tymoshenko',
                'designation' => 'Chairman',
                'image'       => '/assets/images/resources/Chairman1.jpg',
                'content'     => "Novi Agro's feed additives have been a game-changer for our livestock. We've seen vastly improved health metrics and much higher yields since incorporating their solutions into our nutritional programs.",
            ],
            [
                'name'        => 'Kolawole Ishola',
                'designation' => 'C.E.O',
                'image'       => '/assets/images/resources/ishola.jpg',
                'content'     => "Partnering with Novi Agro has significantly enhanced our production efficiency. Their high-quality additives and expert guidance have been instrumental in our farm's success and sustainability across Nigeria.",
            ],
            [
                'name'        => 'Dr. Bayo',
                'designation' => 'Nutritionist',
                'image'       => '/assets/images/resources/DR Bayo.jpeg',
                'content'     => "As a nutritionist, I am impressed by the precision of Novi Agro's formulations. They provide essential bio-available nutrients that are often missing from standard feeds, ensuring optimal animal growth and vitality.",
            ],
            [
                'name'        => 'Goblin Lion',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/gardeners-1-1.png',
                'content'     => "The transformation in our poultry production since adopting Novi Agro's solutions has been remarkable. We are recording faster growth rates and much better overall health in our flocks, which has boosted our market value.",
            ],
            [
                'name'        => 'Alen Martin',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/chairman.png',
                'content'     => "Consistency is key in large-scale farming, and that's exactly what Novi Agro delivers. Their products are reliable, effective, and backed by excellent technical support which is rare in the agricultural supply industry.",
            ],
            [
                'name'        => 'Goblin Lion',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/gardeners-1-1.png',
                'content'     => "The professionalism and dedication of the Novi Agro team are unmatched. Their innovative approach to livestock nutrition has helped us overcome major production challenges effortlessly while reducing our overhead costs.",
            ],
            [
                'name'        => 'Man Hanson',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/ishola.jpg',
                'content'     => "We've tried various additives over the years, but none have matched the results we get from Novi Agro. Our livestock are healthier, grow faster, and our profit margins on the farm have seen a significant positive uptick.",
            ],
        ];
    }
@endphp
@foreach ($items as $i => $item)
                    <div class="item">
                        <div class="testimonials-card testimonials-dark-card  wow fadeInUp" data-wow-duration='1500ms'
                            data-wow-delay='{{ ($i * 100) }}ms'>
                            <div class="testimonials-card__inner">
                                <div
                                    class="testimonials-card__ratingwrap d-flex align-items-center justify-content-between">
                                    <div class="testimonials-card__rating">
                                        @php $stars = (int) ($item['rating'] ?? 5); @endphp
                                        @for ($s = 0; $s < $stars; $s++)
                                            <span class="testimonials-card__rating__start"><i class="fa fa-star"></i></span>
                                        @endfor
                                    </div><!-- /.testimonials-card__rating -->
                                    <div class="testimonials-card__icon">
                                        <img loading="lazy" src="/assets/images/shapes/funfact-icon2-1.png" alt="">
                                    </div>
                                </div>

                                <div class="testimonials-card__content">
                                    {{ $item['content'] ?? '' }}
                                </div><!-- /.testimonials-card__content -->
                                <div class="testimonials-card__top">
                                    <div class="testimonials-card__image">
                                        <img loading="lazy" src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] ?? '' }}">
                                    </div><!-- /.testimonials-card__image -->

                                    <div class="testimonials-card__top__left">
                                        <h3 class="testimonials-card__name">
                                            {{ $item['name'] ?? '' }}
                                        </h3><!-- /.testimonials-card__name -->
                                        <p class="testimonials-card__designation">{{ $item['designation'] ?? '' }}</p>
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
