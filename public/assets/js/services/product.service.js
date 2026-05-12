/**
 * services/product.service.js
 * Data layer for fetching, filtering, and sorting products via the
 * /api/products endpoint.
 *
 * The catalog is listing-only now: per-product detail (and "related"
 * products) are server-rendered on /products/{slug}, so this module no
 * longer caches by id or builds a related list client-side.
 */
const ProductService = (function () {
    const itemsPerPage = 12;

    function getItemsPerPage() {
        return itemsPerPage;
    }

    async function fetchProducts(filters) {
        const params = new URLSearchParams();
        if (filters.animal && filters.animal !== 'all') params.set('animal', filters.animal);
        if (filters.type && filters.type !== 'all') params.set('type', filters.type);
        if (filters.search) params.set('search', filters.search);
        if (filters.sort && filters.sort !== 'default') params.set('sort', filters.sort);
        params.set('page', filters.page || 1);

        const response = await fetch('/api/products?' + params.toString(), {
            headers: { 'Accept': 'application/json' },
        });
        if (!response.ok) {
            throw new Error('Failed to fetch products: ' + response.status);
        }
        return await response.json();
    }

    return {
        getItemsPerPage,
        fetchProducts,
    };
})();
