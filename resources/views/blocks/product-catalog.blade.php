        <section class="product-one">
            <div class="container">
                <div id="catalog-listing">
                    <!-- Sticky search & sort controls bar -->
                    <div class="catalog-controls-sticky">
                        <div class="product__showing-wrap">
                            <input type="text" class="product__search-input flex-grow-1" id="main-product-search"
                                placeholder="{{ $page->block('product-catalog', 'search_placeholder', 'Search for available products') }}">
                            <div class="product__showing-sort">
                                <div class="sort-dropdown">
                                    <button type="button" class="sort-toggle" aria-haspopup="listbox"
                                        aria-expanded="false">
                                        <span class="sort-selected">{{ $page->block('product-catalog', 'sort_default_label', 'Sort by Default') }}</span>
                                        <i class="fas fa-chevron-down sort-chevron"></i>
                                    </button>
                                    <ul class="sort-menu" role="listbox">
                                        <li class="sort-option active" data-value="default" role="option">{{ $page->block('product-catalog', 'sort_default_label', 'Sort by Default') }}</li>
                                        <li class="sort-option" data-value="newest" role="option">{{ $page->block('product-catalog', 'sort_newest_label', 'Sort by Newest') }}</li>
                                        <li class="sort-option" data-value="z-a" role="option">{{ $page->block('product-catalog', 'sort_alpha_label', 'Sort by Alphabetical (Z-A)') }}</li>
                                        <li class="sort-option" data-value="popular" role="option">{{ $page->block('product-catalog', 'sort_popular_label', 'Sort by Popularity') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gutter-y-60 mt-3">
                        <div class="col-lg-3">
                            <div class="product__sidebar sticky-lg-top independent-scroll-col">
                                <h3 class="filter-title d-flex justify-content-between align-items-center"
                                    data-toggle="collapse" data-bs-toggle="collapse" data-target="#product-type-filters"
                                    data-bs-target="#product-type-filters" role="button" aria-expanded="true"
                                    aria-controls="product-type-filters" style="cursor: pointer;">{{ $page->block('product-catalog', 'filter_type_title', 'Product Type') }} <i
                                        class="fas fa-minus toggle-icon"></i></h3>
                                <div class="product__sidebar">
                                    <div class="filter-options collapse show" id="product-type-filters">
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="all" id="type-all" checked><label
                                                class="form-check-label" for="type-all">All Types</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Toxin Binder"
                                                id="type-toxin"><label class="form-check-label" for="type-toxin">Toxin
                                                Binder</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Enzymes" id="type-enzyme"><label
                                                class="form-check-label" for="type-enzyme">Enzymes</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Acidifier"
                                                id="type-acidifier"><label class="form-check-label"
                                                for="type-acidifier">Acidifier</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Emulsifier"
                                                id="type-emulsifier"><label class="form-check-label"
                                                for="type-emulsifier">Emulsifier</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Hepatoprotector"
                                                id="type-hepato"><label class="form-check-label"
                                                for="type-hepato">Hepatoprotector</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Mould Inhibitor"
                                                id="type-mould"><label class="form-check-label" for="type-mould">Mould
                                                Inhibitor</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Pellet Binder"
                                                id="type-pellet"><label class="form-check-label"
                                                for="type-pellet">Pellet
                                                Binder</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Premixes" id="type-premix"><label
                                                class="form-check-label" for="type-premix">Premixes</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Concentrates"
                                                id="type-conc"><label class="form-check-label"
                                                for="type-conc">Concentrates</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Amino Acids"
                                                id="type-amino"><label class="form-check-label" for="type-amino">Amino
                                                Acids</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Ca &amp; P Sources"
                                                id="type-cap"><label class="form-check-label" for="type-cap">Ca &amp; P
                                                Sources</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Anti-Stress"
                                                id="type-antistress"><label class="form-check-label"
                                                for="type-antistress">Anti-Stress</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Choline" id="type-choline"><label
                                                class="form-check-label" for="type-choline">Choline</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Coccidiostat"
                                                id="type-cocci"><label class="form-check-label"
                                                for="type-cocci">Coccidiostat</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Growth Promoter"
                                                id="type-growth"><label class="form-check-label"
                                                for="type-growth">Growth Promoter</label>
                                        </div>
                                        <div class="form-check mb-2"><input class="form-check-input type-radio"
                                                type="radio" name="productType" value="Pigments/ Yolk Colourants"
                                                id="type-pigment"><label class="form-check-label"
                                                for="type-pigment">Yolk
                                                Colourants</label>
                                        </div>
                                    </div>
                                </div>

                                <hr class="filter-divider">
                                <h3 class="filter-title mt-4 d-flex justify-content-between align-items-center"
                                    data-toggle="collapse" data-bs-toggle="collapse"
                                    data-target="#collapse-animal-category" data-bs-target="#collapse-animal-category"
                                    role="button" aria-expanded="true" aria-controls="collapse-animal-category"
                                    style="cursor: pointer;">{{ $page->block('product-catalog', 'filter_animal_title', 'Animal Category') }} <i class="fas fa-minus toggle-icon"></i></h3>
                                <div class="filter-options collapse show mb-4" id="collapse-animal-category">
                                    @php
                                        $activeAnimal = match (true) {
                                            request()->routeIs('animals.cattle') => 'cattle',
                                            request()->routeIs('animals.pigs') => 'pigs',
                                            request()->routeIs('animals.poultry') => 'poultry',
                                            default => 'all',
                                        };
                                    @endphp
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-all" value="all"
                                            @checked($activeAnimal === 'all')
                                            @if($activeAnimal !== 'all') data-href="{{ route('products') }}" @endif>
                                        <label class="form-check-label" for="cat-all"><a href="{{ route('products') }}">{{ $page->block('product-catalog', 'cat_all_label', 'All Products') }}</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-cattle" value="cattle"
                                            @checked($activeAnimal === 'cattle')
                                            @if($activeAnimal !== 'cattle') data-href="{{ route('animals.cattle') }}" @endif>
                                        <label class="form-check-label" for="cat-cattle"><a
                                                href="{{ route('animals.cattle') }}">{{ $page->block('product-catalog', 'cat_cattle_label', 'Cattle') }}</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-pigs" value="pigs"
                                            @checked($activeAnimal === 'pigs')
                                            @if($activeAnimal !== 'pigs') data-href="{{ route('animals.pigs') }}" @endif>
                                        <label class="form-check-label" for="cat-pigs"><a
                                                href="{{ route('animals.pigs') }}">{{ $page->block('product-catalog', 'cat_pigs_label', 'Swine/Pigs') }}</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-poultry" value="poultry"
                                            @checked($activeAnimal === 'poultry')
                                            @if($activeAnimal !== 'poultry') data-href="{{ route('animals.poultry') }}" @endif>
                                        <label class="form-check-label" for="cat-poultry"><a
                                                href="{{ route('animals.poultry') }}">{{ $page->block('product-catalog', 'cat_poultry_label', 'Poultry') }}</a></label>
                                    </div>
                                </div>
                            </div><!-- /.product__sidebar -->

                        </div><!-- /.col-lg-3 -->

                        <div class="col-lg-9 sticky-lg-top independent-scroll-col">

                            <div class="row" id="product-container">
                                <!-- Products will be rendered here by product-manager.js -->
                            </div>

                            <div class="product-pagination">
                            </div><!-- /.product-pagination -->
                        </div><!-- /.row -->
                    </div>
                </div>
            </div><!-- /.container -->
        </section><!-- /.product-one -->
