        @php
            $brandLogo  = $settings['brand.logo']       ?? '/assets/images/images-removebg-preview.png';
            $contactEml = $settings['contact.email']    ?? 'info@novi-agro.com';
            $contactPhn = $settings['contact.phone']    ?? '+2347041041756';
            $socialFb   = $settings['social.facebook']  ?? 'https://www.facebook.com/profile.php?id=100077163775495';
            $socialIg   = $settings['social.instagram'] ?? 'https://www.instagram.com/novi_agroltd/';

            $footerCategoriesTitle = $settings['footer.categories_title'] ?? __('site.footer.categories');
            $footerGalleryTitle    = $settings['footer.gallery_title']    ?? __('site.footer.gallery');
            $footerProductsTitle   = $settings['footer.products_title']   ?? __('site.footer.products');

            // Admin-curated gallery (Settings page: Footer > Gallery images).
            // Only render the widget if the admin has supplied at least one
            // entry — keeps the slot empty rather than falling back to images
            // the editor never picked.
            $footerGalleryItems = collect($settings['footer.gallery_images'] ?? [])
                ->filter(fn ($entry) => is_array($entry) && ! empty($entry['src']))
                ->values();

            // SiteComposer injects $footerCategories / $footerProducts as
            // collections. Coalesce defensively so any view that bypasses the
            // composer still renders cleanly.
            $footerCategoryItems = collect($footerCategories ?? []);
            $footerProductItems  = collect($footerProducts ?? []);
        @endphp
        <footer class="main-footer main-footer--two">
            <div class="main-footer__bg" style="background: linear-gradient(135deg, #07c543 0%, #078f19 100%);">
            </div>
            <div class="main-footer__overlay"></div>
            <!-- /.main-footer__bg -->
            <div class="main-footer__top">
                <div class="container">
                    <div class="row">
                        <div class="footer-widget__col footer-widget__col__col1">
                            <div class="footer-widget footer-widget--about">
                                <a href="{{ route('home') }}" class="footer-widget__logo">
                                    <span class="footer-widget__logo-text">Novi <span>Agro</span></span>
                                </a>
                                <p class="footer-widget__experience-text">Dedicated to providing premium products,
                                    reliable feed additives, and comprehensive animal care supplies for professionals
                                    and farm owners worldwide.
                                </p>
                            </div><!-- /.footer-widget -->
                        </div><!-- /.col-md-6 -->
                        <div class="footer-widget__col  footer-widget__col__col2">
                            <div class="footer-widget footer-widget--links">
                                <h6 class="footer-widget__title">{{ $footerCategoriesTitle }}</h6>
                                <ul class="list-unstyled footer-widget__links">
                                    @php $footerMenu = $menus['footer'] ?? null; @endphp
                                    @if ($footerMenu && $footerMenu->items->isNotEmpty())
                                        @foreach ($footerMenu->items as $item)
                                            <li><a href="{{ $item->resolved_url }}"@if ($item->target && $item->target !== '_self') target="{{ $item->target }}"@endif>{{ $item->label }}</a></li>
                                        @endforeach
                                    @elseif ($footerCategoryItems->isNotEmpty())
                                        @foreach ($footerCategoryItems as $cat)
                                            <li><a href="{{ $cat['url'] }}">{{ $cat['label'] }}</a></li>
                                        @endforeach
                                    @else
                                        {{-- DB is empty (no Animals seeded) and no admin footer menu. --}}
                                        <li><a href="{{ route('products') }}">{{ __('site.footer.all_products') }}</a></li>
                                        <li><a href="{{ route('faq') }}">{{ __('site.footer.faq') }}</a></li>
                                    @endif
                                </ul><!-- /.list-unstyled footer-widget__links -->
                            </div><!-- /.footer-widget -->
                        </div><!-- /.col-md-6 -->
                        @if ($footerGalleryItems->isNotEmpty())
                            <div class="footer-widget__col  footer-widget__col__col3">
                                <div class="footer-widget footer-widget--gallery">
                                    <h6 class="footer-widget__title">{{ $footerGalleryTitle }}</h6><!-- /.footer-widget__title -->
                                    <div class="footer-widget__gallerywrap d-flex flex-wrap">
                                        @foreach ($footerGalleryItems as $galleryItem)
                                            <div class="footer-widget__gallerywrap__img">
                                                <img loading="lazy" src="{{ $galleryItem['src'] }}" alt="{{ $galleryItem['alt'] ?? '' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div><!-- /.footer-widget -->
                            </div><!-- /.col-md-6 -->
                        @endif
                        @if ($footerProductItems->isNotEmpty())
                            <div class="footer-widget__col  footer-widget__col__col4">
                                <div class="footer-widget footer-widget--blog">
                                    <h6 class="footer-widget__title">{{ $footerProductsTitle }}</h6><!-- /.footer-widget__title -->

                                    <div class="footer-widget__post-wrap">
                                        @foreach ($footerProductItems as $product)
                                            @php
                                                $thumb = $product->hero_image
                                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->hero_image)
                                                    : '/assets/images/generated/product_thumb_feed.png';
                                            @endphp
                                            <div class="footer-widget__post-col">
                                                <div class="footer-widget__post-img">
                                                    <img loading="lazy" src="{{ $thumb }}" alt="{{ $product->name }}">
                                                </div>
                                                <div class="footer-widget__post-info">
                                                    <span class="footer-widget__post-date"><i class="far fa-calendar"></i>
                                                        <span> {{ optional($product->updated_at)->format('d M Y') }}</span>
                                                    </span>
                                                    <h6 class="footer-widget__post-heading"><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div><!-- /.footer-widget -->
                            </div><!-- /.col-md-6 -->
                        @endif
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.main-footer__top -->

            <div class="main-footer__bottom">
                <div class="container">
                    <div class="main-footer__bottom__inner">
                        <p class="main-footer__copyright"> &copy; <span class="dynamic-year"></span> Novi Agro All
                            Rights Reserved</p>
                        <div class="main-footer__social-row">
                            <p class="main-footer__social-row-text">Social</p>
                            <ul class="main-footer__social-list">
                                <li><a href="{{ $socialFb }}" target="_blank"
                                        rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="{{ $socialIg }}" target="_blank"
                                        rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- /.main-footer__inner -->
                </div><!-- /.container -->
            </div><!-- /.main-footer__bottom -->
        </footer><!-- /.main-footer --><!-- /.main-footer -->

    </div><!-- /.page-wrapper -->

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

            <div class="logo-box">
                <a href="{{ route('home') }}" aria-label="logo image"><img src="{{ $brandLogo }}"
                        alt="Novi-Agro Logo" /></a>
            </div>
            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:{{ $contactEml }}">{{ $contactEml }}</a>
                </li>
                <li>
                    <i class="fa fa-phone-alt"></i>
                    <a href="tel:{{ $contactPhn }}">{{ $contactPhn }}</a>
                </li>
            </ul><!-- /.mobile-nav__contact -->
            <div class="mobile-nav__social">
                <a href="{{ $socialFb }}" target="_blank"
                    rel="noopener noreferrer">
                    <i class="fab fa-facebook-f"></i>
                    <span class="sr-only">Facebook</span>
                </a>
                <a href="{{ $socialIg }}" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-instagram"></i>
                    <span class="sr-only">Instagram</span>
                </a>
            </div><!-- /.mobile-nav__social -->
        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->
    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <!-- /.search-popup__overlay -->
        <div class="search-popup__content">
            <form role="search" method="get" class="search-popup__form" action="#">
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="grdeen-btn">
                    <span><i class="icon-search"></i></span>
                </button>
            </form>
        </div>
        <!-- /.search-popup__content -->
    </div>
    <!-- /.search-popup -->

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top">
        <span class="scroll-to-top__text">back top</span>
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
    </a>
