/**
 * main.js
 * Wire up the Product Catalog application.
 */
$(window).on('load', function () {
    const bodyCategory = $('body').attr('data-category') || "All";

    // 1. Initial State Resolution
    function resolveInitialState() {
        const urlParams = UrlUtils.getParams();
        
        const defaultAnimal = urlParams.animal 
            || (bodyCategory !== "All" ? bodyCategory.toLowerCase() : "all");

        const initialState = {
            view: urlParams.id ? 'detail' : 'listing',
            selectedProductId: urlParams.id || null,
            filters: {
                animal: defaultAnimal,
                type: urlParams.type || 'all',
                search: urlParams.search || '',
                sort: urlParams.sort || 'default',
                page: urlParams.page || 1
            }
        };

        AppState.setState(initialState, false);
    }

    // 2. React to State Changes
    AppState.subscribe((state) => {
        // Sync URL
        UrlUtils.updateURL(state);

        // Switch View
        ProductDetailUI.showView(state.view);

        if (state.view === 'detail') {
            const product = ProductService.getProductById(state.selectedProductId);
            if (product) {
                const related = ProductService.getRelatedProducts(product);
                ProductDetailUI.renderDetail(product, related);
            } else {
                // Not found, go back
                AppState.setState({ view: 'listing', selectedProductId: null });
            }
        } else {
            // Render Listing
            ProductList.syncUI(state.filters);
            const data = ProductService.fetchProducts(state.filters);
            ProductList.render(data);
            PaginationUI.render(data);
        }
    });

    // 3. Event Listeners
    
    // Custom sort dropdown
    $(document).on('click', '.sort-toggle', function (e) {
        e.stopPropagation();
        const $toggle = $(this);
        const $menu = $toggle.siblings('.sort-menu');
        
        $toggle.toggleClass('open');
        $menu.toggleClass('open');
        $toggle.attr('aria-expanded', $toggle.hasClass('open'));
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.sort-dropdown').length) {
            $('.sort-toggle').removeClass('open').attr('aria-expanded', 'false');
            $('.sort-menu').removeClass('open');
        }
    });

    // Listing Clicks
    $(document).on('click', '.product__item, .related-product-item', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        AppState.setState({ view: 'detail', selectedProductId: id });
    });

    // Back to listing
    $(document).on('click', '#back-to-listing', function (e) {
        e.preventDefault();
        AppState.setState({ view: 'listing', selectedProductId: null });
    });

    // Filters
    $(document).on('change', '.type-radio', function () {
        AppState.setState({ 
            filters: { type: $(this).val(), page: 1 } 
        });
    });

    $(document).on('change', '.animal-radio', function () {
        var href = $(this).data('href');
        if (href) {
            window.location.href = href;
            return;
        }
        AppState.setState({
            filters: { animal: $(this).val(), page: 1 }
        });
    });

    $(document).on('click', '.sort-option', function () {
        $('.sort-toggle').removeClass('open').attr('aria-expanded', 'false');
        $('.sort-menu').removeClass('open');
        AppState.setState({ 
            filters: { sort: $(this).data('value'), page: 1 } 
        });
    });

    let searchTimeout;
    $(document).on('input', '#main-product-search', function () {
        const val = $(this).val();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            AppState.setState({ 
                filters: { search: val, page: 1 } 
            });
        }, 300);
    });

    // Pagination
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            AppState.setState({ filters: { page: page } });
        }
    });

    window.onpopstate = (e) => {
        if (e.state) {
            AppState.setState(e.state, true);
        } else {
            resolveInitialState();
            AppState.setState(AppState.getState(), true);
        }
    };

    // 4. Initialize
    // Product Manager Initialized
    resolveInitialState();
    AppState.setState(AppState.getState(), true); // trigger initial render

    if ($('.preloader').length) {
        $('.preloader').fadeOut(500);
    }
});
