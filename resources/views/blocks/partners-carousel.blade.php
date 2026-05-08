@php
    $partners = $page->block('partners-carousel', 'partners');
    if (! is_array($partners) || empty($partners)) {
        $partners = [
            ['logo' => '/assets/images/resources/brand-logo1-1.png', 'url' => null],
            ['logo' => '/assets/images/resources/brand-logo1-2.png', 'url' => null],
            ['logo' => '/assets/images/resources/brand-logo1-3.png', 'url' => null],
            ['logo' => '/assets/images/resources/brand-logo1-4.png', 'url' => null],
        ];
    }
@endphp
        <section class="companies-one">
            <div class="container">
                <div class="companies-one__sctwrap wow fadeInUp" data-wow-delay="100ms">
                    <div class="sec-title">


                        <h2 class="sec-title__title">{!! $page->block('partners-carousel', 'title', 'Partnering with Global Leaders in <br> Livestock &amp; Agriculture') !!}</h2>
                        <!-- /.sec-title__title -->
                    </div><!-- /.sec-title -->
                </div>

                <div class="companies-one__carousel grdeen-owl__carousel grdeen-owl__carousel--with-shadow grdeen-owl__carousel--basic-nav owl-carousel"
                    data-owl-options='{
			"items": 1,
			"margin": 0,
			"loop": true,
			"smartSpeed": 700,
			"nav": false,
			"navText": [""],
			"dots": true,
			"autoplay": false,
			"responsive": {
				"0": {
					"items": 1
				},
				"768": {
					"items": 2
				},
				"992": {
					"items": 3
				},
				"1367": {
					"items": 4
				}
			}
		}'>
                    @foreach ($partners as $partner)
                        @php
                            $logo  = is_array($partner) ? ($partner['logo'] ?? null) : $partner;
                            $url   = is_array($partner) ? ($partner['url']  ?? null) : null;
                            $alt   = is_array($partner) ? ($partner['alt']  ?? 'Partner Logo') : 'Partner Logo';
                            $href  = $url ?: route('products');
                        @endphp
                        @if ($logo)
                            <div class="item">
                                <div class="companies-one__image">
                                    <div class="companies-one__inner-img">
                                        <a href="{{ $href }}"><img loading="lazy" src="{{ $logo }}" alt="{{ $alt }}"></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section><!-- /.companies-one -->
