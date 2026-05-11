    @php
        $brandLogo  = $settings['brand.logo']      ?? '/assets/images/images-removebg-preview.png';
        $brandTag   = $settings['brand.tagline']   ?? 'Quality Feed - Healthy Life';
        $contactAdr = $settings['contact.address'] ?? 'New Garage, Ibadan.';
        $contactEml = $settings['contact.email']   ?? 'info@novi-agro.com';
        $contactPhn = $settings['contact.phone']   ?? '+2347041041756';
        $socialFb   = $settings['social.facebook'] ?? 'https://www.facebook.com/profile.php?id=100077163775495';
        $socialIg   = $settings['social.instagram'] ?? 'https://www.instagram.com/novi_agroltd/';
    @endphp
    <div class="page-wrapper">
        <div class="topbar-one topbar-one--one_only">
            <div class="container-fluid">
                <div class="topbar-one__inner">
                    <div class="topbar-one__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ $brandLogo }}" alt="Novi Agro Logo">
                        </a>
                    </div><!-- /.main-header__logo -->

                    <ul class="list-unstyled topbar-one__info">
                        <li class="topbar-one__info__item">
                            <div class="topbar-one__info__iconwrap">
                                <i class="icon-pin"></i>
                            </div>
                            <div class="topbar-one__info__address d-flex flex-wrap">
                                <small class="topbar-one__info__address__text">Company address</small>
                                <a href="{{ route('contact') }}">{{ $contactAdr }}</a>
                            </div>
                        </li>
                        <li class="topbar-one__info__item">
                            <div class="topbar-one__info__iconwrap">
                                <i class="icon-email"></i>
                            </div>
                            <div class="topbar-one__info__address">
                                <small class="topbar-one__info__address__text">Send an email</small>
                                <a href="mailto:{{ $contactEml }}">{{ $contactEml }}</a>
                            </div>
                        </li>
                        <li class="topbar-one__info__item">
                            <div class="topbar-one__info__iconwrap">
                                <i class="icon-phone-receiver-silhouette"></i>
                            </div>
                            <div class="topbar-one__info__address">
                                <small class="topbar-one__info__address__text">Helpline and support</small>
                                <a href="tel:{{ $contactPhn }}">{{ $contactPhn }} </a>
                            </div>
                        </li>
                    </ul><!-- /.list-unstyled topbar-one__info -->

                    <div class="topbar-one__right">
                        <!-- <p class="topbar-one__text">Mon to Sat: 9:00am – 6:00pm Sun: Closed</p> -->
                        <div class="topbar-one__social">
                            <a href="{{ $socialFb }}" target="_blank"
                                rel="noopener noreferrer">
                                <i class="fab fa-facebook-f"></i>
                                <span class="sr-only">Facebook</span>
                            </a>
                            <a href="{{ $socialIg }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram"></i>
                                <span class="sr-only">Instagram</span>
                            </a>
                        </div><!-- /.topbar-one__social -->
                    </div><!-- /.topbar-one__right -->
                </div><!-- /.topbar-one__inner -->
            </div><!-- /.container-fluid -->
        </div><!-- /.topbar-one -->

        <header class="main-header main-header--only-one main-header--one_only sticky-header sticky-header--normal">
            <div class="container-fluid">
                <div class="main-header__inner">
                    <div class="main-header__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ $brandLogo }}" alt="Novi Agro Logo">
                        </a>
                    </div><!-- /.main-header__logo -->
                    <div class="main-header__wellcome d-flex align-items-center">
                        <p class="main-header__wellcome__tagline">@yield('header_tagline', $brandTag)</p>
                        <a href="{{ route('contact') }}" class="grdeen-btn main-header__wellcome__btn">
                            <span>Contact</span>
                        </a>
                    </div><!-- /.main-header__logo -->

                    <nav class="main-header__nav main-menu">
                        <ul class="main-menu__list">
                            @php $primaryMenu = $menus['primary'] ?? null; @endphp
                            @if ($primaryMenu && $primaryMenu->items->isNotEmpty())
                                @foreach ($primaryMenu->items as $item)
                                    @php $hasChildren = $item->children->isNotEmpty(); @endphp
                                    <li class="{{ $hasChildren ? 'dropdown ' : '' }}{{ $item->isCurrent() ? 'current' : '' }}">
                                        <a href="{{ $item->resolved_url }}"@if ($item->target && $item->target !== '_self') target="{{ $item->target }}"@endif>{{ $item->label }}</a>
                                        @if ($hasChildren)
                                            <ul>
                                                @foreach ($item->children as $child)
                                                    <li><a href="{{ $child->resolved_url }}"@if ($child->target && $child->target !== '_self') target="{{ $child->target }}"@endif>{{ $child->label }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @else
                                <li class="{{ request()->routeIs('home') ? 'current' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                                <li class="dropdown {{ request()->routeIs('products', 'animals.*') ? 'current' : '' }}">
                                    <a href="{{ route('products') }}">Products</a>
                                    <ul>
                                        <li><a href="{{ route('animals.cattle') }}">Cattle</a></li>
                                        <li><a href="{{ route('animals.pigs') }}">Pigs</a></li>
                                        <li><a href="{{ route('animals.poultry') }}">Poultry</a></li>
                                    </ul>
                                </li>
                                <li class="{{ request()->routeIs('services') ? 'current' : '' }}"><a href="{{ route('services') }}">Services</a></li>
                                <li class="{{ request()->routeIs('about') ? 'current' : '' }}"><a href="{{ route('about') }}">About</a></li>
                                <li class="{{ request()->routeIs('blog.*') ? 'current' : '' }}"><a href="{{ route('blog.index') }}">{{ __('blog.nav_label') }}</a></li>
                            @endif
                        </ul>
                    </nav><!-- /.main-header__nav -->
                    <div class="main-header__right">
                        <div class="mobile-nav__btn mobile-nav__toggler">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div><!-- /.mobile-nav__toggler -->
                        <a href="{{ route('faq') }}" class="grdeen-btn main-header__btn">
                            <span>Insights</span>
                        </a><!-- /.thm-btn main-header__btn -->
                    </div><!-- /.main-header__right -->
                </div><!-- /.main-header__inner -->
            </div><!-- /.container-fluid -->
        </header><!-- /.main-header -->
