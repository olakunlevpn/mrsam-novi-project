        <section class="product-one">
            <div class="container">
                <div id="catalog-listing">
                    <!-- Sticky search & sort controls bar -->
                    <div class="catalog-controls-sticky">
                        <div class="product__showing-wrap">
                            <input type="text" class="product__search-input flex-grow-1" id="main-product-search"
                                placeholder="Search for available products">
                            <div class="product__showing-sort">
                                <div class="sort-dropdown">
                                    <button type="button" class="sort-toggle" aria-haspopup="listbox"
                                        aria-expanded="false">
                                        <span class="sort-selected">Sort by Default</span>
                                        <i class="fas fa-chevron-down sort-chevron"></i>
                                    </button>
                                    <ul class="sort-menu" role="listbox">
                                        <li class="sort-option active" data-value="default" role="option">Sort by Default</li>
                                        <li class="sort-option" data-value="newest" role="option">Sort by Newest</li>
                                        <li class="sort-option" data-value="z-a" role="option">Sort by Alphabetical (Z-A)</li>
                                        <li class="sort-option" data-value="popular" role="option">Sort by Popularity</li>
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
                                    aria-controls="product-type-filters" style="cursor: pointer;">Product Type <i
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
                                    style="cursor: pointer;">Animal
                                    Category <i class="fas fa-minus toggle-icon"></i></h3>
                                <div class="filter-options collapse show mb-4" id="collapse-animal-category">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-all" value="all" checked>
                                        <label class="form-check-label" for="cat-all"><a href="{{ route('products') }}">All
                                                Products</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-cattle" value="cattle">
                                        <label class="form-check-label" for="cat-cattle"><a
                                                href="{{ route('animals.cattle') }}">Cattle</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-pigs" value="pigs">
                                        <label class="form-check-label" for="cat-pigs"><a
                                                href="{{ route('animals.pigs') }}">Swine/Pigs</a></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input animal-radio" type="radio" name="animalFilter"
                                            id="cat-poultry" value="poultry">
                                        <label class="form-check-label" for="cat-poultry"><a
                                                href="{{ route('animals.poultry') }}">Poultry</a></label>
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

                <!-- Integrated Product Detail View -->
                <div id="product-details" style="display: none;">
                    <div class="mb-5 d-flex justify-content-between align-items-center">
                        <a href="#" id="back-to-listing" class="grdeen-btn"><span><i
                                    class="fas fa-arrow-left me-2"></i>Back to Catalog</span></a>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-soft text-success px-3 py-2" id="slotAnimalBadge"></span>
                            <span class="badge bg-light text-muted px-3 py-2" id="slotCategoryBadge"></span>
                        </div>
                    </div>

                    <div class="row gutter-y-40">
                        <div class="col-lg-5">
                            <div class="product-detail-img-wrap rounded-3 bg-light p-4 text-center">
                                <img loading="lazy" id="slotProductImage" src="" alt="Product" class="img-fluid"
                                    style="max-height: 500px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="product-detail-content">
                                <h2 id="slotProductTitle" class="mb-4"
                                    style="color: var(--grdeen-black); font-weight: 700; font-size: 42px;"></h2>

                                <div id="slotDescriptionContainer" class="mb-4">
                                    <h5 class="text-success fw-bold text-uppercase small mb-2"
                                        style="letter-spacing: 1px;">Description</h5>
                                    <p id="slotProductDescription" class="text-muted"
                                        style="line-height: 1.8; font-size: 16px;"></p>
                                </div>

                                <div id="slotCompositionContainer" class="mb-4">
                                    <h5 class="text-success fw-bold text-uppercase small mb-2"
                                        style="letter-spacing: 1px;">Composition</h5>
                                    <p id="slotProductComposition" class="bg-light p-4 rounded text-muted mb-0"
                                        style="font-size: 15px; font-style: italic;"></p>
                                </div>

                                <div id="slotBenefitsContainer" class="mb-4">
                                    <h5 class="text-success fw-bold text-uppercase small mb-2"
                                        style="letter-spacing: 1px;">Typical Benefits</h5>
                                    <p id="slotProductBenefits" class="text-muted mb-0" style="font-size: 16px;"></p>
                                </div>

                                <div id="slotUsageContainer" class="mb-5">
                                    <h5 class="text-success fw-bold text-uppercase small mb-2"
                                        style="letter-spacing: 1px;">Usage &amp; Directions</h5>
                                    <p id="slotProductUsage" class="text-muted mb-0" style="font-size: 16px;"></p>
                                </div>

                                <div class="d-flex align-items-center gap-3 mt-4">
                                    <a href="{{ route('contact') }}" class="grdeen-btn"><span>Enquire Now</span></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="relatedProductsSection" class="mt-5 pt-5 border-top">
                        <h4 class="fw-bold mb-4">Related Products</h4>
                        <div class="row g-4" id="slotRelatedProducts">
                            <!-- Related products injected here -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
        </section><!-- /.product-one -->
