        <!-- video-hero-start -->
        <section class="video-hero-one"
            style="position:relative; width:100%; height:100vh; min-height:500px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
            <!-- Background Video: Nigerian cattle grazing on a fallowed bush -->
            <!-- Place your video file at: assets/videos/hero-cattle.mp4 -->
            <video class="video-hero-one__video" autoplay muted loop playsinline
                style="position:absolute; top:50%; left:50%; width:100%; height:100%; object-fit:cover; transform:translate(-50%,-50%); z-index:0;">
                <source src="{{ $page->block('hero', 'video_src', '/assets/videos/Nigerian_Breed_Cow_Video_Generated.mp4') }}" type="video/mp4">
                <!-- Fallback image if video is not supported or not found -->
            </video>
            <!-- Dark overlay for text readability -->
            <div class="video-hero-one__overlay"></div>
            <!-- Hero Content -->
            <div class="container" style="position:relative; z-index:2;">
                <div class="row">
                    <div class="col-xl-7 col-lg-9 col-md-12">
                        <div class="video-hero-one__content">
                            <h5 class="video-hero-one__sub-title">
                                {{ $page->block('hero', 'subtitle', 'WELCOME TO NOVI-AGRO') }}
                            </h5>
                            <h2 class="video-hero-one__title">
                                {{ $page->block('hero', 'headline', 'Advanced Animal Care Solutions') }}
                            </h2>
                            <div class="video-hero-one__btn">
                                <a href="{{ $page->block('hero', 'cta_url', '/products') }}" class="grdeen-btn grdeen-btn--base"><span>{{ $page->block('hero', 'cta_label', 'Browse Products') }}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- video-hero-end -->
