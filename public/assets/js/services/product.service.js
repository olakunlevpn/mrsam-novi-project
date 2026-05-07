/**
 * services/product.service.js
 * Data layer for fetching, filtering, and sorting products.
 * Fetches from the /api/products endpoint.
 */
const ProductService = (function () {
    const itemsPerPage = 12;
    let cacheById = new Map();

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
        const data = await response.json();

        // Refresh cache from this listing — first occurrence wins (defensive merge).
        data.items.forEach(p => {
            if (!cacheById.has(p.id)) {
                cacheById.set(p.id, p);
            }
        });
        return data;
    }

    async function getProductById(id) {
        if (cacheById.has(id)) {
            return cacheById.get(id);
        }
        // Cache miss (deep-link). Pull a broad listing to populate.
        await fetchProducts({ animal: 'all', type: 'all', search: '', sort: 'default', page: 1 });
        return cacheById.get(id) || null;
    }

    async function getRelatedProducts(targetProduct, limit = 3) {
        if (!targetProduct) return [];

        const data = await fetchProducts({
            animal: 'all',
            type: targetProduct.category,
            search: '',
            sort: 'default',
            page: 1,
        });

        const seen = new Map();
        for (const p of data.items) {
            if (p.id !== targetProduct.id && !seen.has(p.name)) {
                seen.set(p.name, p);
            }
            if (seen.size >= limit) break;
        }
        return Array.from(seen.values());
    }

    return {
        getItemsPerPage,
        getProductById,
        getRelatedProducts,
        fetchProducts,
    };
})();
