/**
 * main.js
 * Wire up the Product Catalog application.
 *
 * Catalog is now a listing-only SPA (filter, sort, search, paginate). Each
 * product card is a real <a href="/products/{slug}"> so the browser handles
 * navigation to the server-rendered detail page natively — no in-page
 * detail view, no `view` state, no ProductDetailUI module.
 */
$(window).on('load', function () {
    const bodyCategory = $('body').attr('data-category') || "All";

    // 1. Initial State Resolution
    function resolveInitialState() {
        const urlParams = UrlUtils.getParams();

        const defaultAnimal = urlParams.animal
            || (bodyCategory !== "All" ? bodyCategory.toLowerCase() : "all");

        const initialState = {
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
    AppState.subscribe(async (state) => {
        // Sync URL
        UrlUtils.updateURL(state);

        // Render Listing
        ProductList.syncUI(state.filters);
        try {
            const data = await ProductService.fetchProducts(state.filters);
            ProductList.render(data);
            PaginationUI.render(data);
        } catch (err) {
            console.error('Catalog fetch failed', err);
            // Render an empty state to avoid blank UI
            ProductList.render({ items: [], totalItems: 0, totalPages: 0, currentPage: 1, itemsPerPage: 12, startItem: 0, endItem: 0 });
            PaginationUI.render({ items: [], totalItems: 0, totalPages: 0, currentPage: 1, itemsPerPage: 12, startItem: 0, endItem: 0 });
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

    // Card clicks are handled natively via <a href> on .product__item — no
    // JS click handler needed for catalog -> detail navigation.

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
    resolveInitialState();
    AppState.setState(AppState.getState(), true); // trigger initial render

    if ($('.preloader').length) {
        $('.preloader').fadeOut(500);
    }
});
