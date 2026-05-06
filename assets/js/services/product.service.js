/**
 * services/product.service.js
 * Data layer for fetching, filtering, and sorting products.
 * Relies on the global `productsData` variable from products-data.js.
 */
const ProductService = (function () {
    const itemsPerPage = 12;

    function getItemsPerPage() {
        return itemsPerPage;
    }

    function getProductById(id) {
        return productsData.find(p => p.id === id) || null;
    }

    function getRelatedProducts(targetProduct, limit = 3) {
        if (!targetProduct) return [];
        
        let related = productsData
            .filter(p => p.category === targetProduct.category && p.id !== targetProduct.id && p.name !== targetProduct.name);
            
        // De-duplicate by product name to prevent repetition
        const uniqueProductsMap = new Map();
        for (const p of related) {
            if (!uniqueProductsMap.has(p.name)) {
                uniqueProductsMap.set(p.name, p);
            }
        }
        
        return Array.from(uniqueProductsMap.values()).slice(0, limit);
    }

    function fetchProducts(filters) {
        let temp = [...productsData];

        if (filters.animal && filters.animal !== "all") {
            temp = temp.filter(p => p.animal === filters.animal);
        }
        if (filters.type && filters.type !== "all") {
            temp = temp.filter(p => p.category === filters.type);
        }
        
        if (filters.search) {
            const query = filters.search.toLowerCase();
            temp = temp.filter(p => 
                p.name.toLowerCase().includes(query) || 
                p.description.toLowerCase().includes(query)
            );
        }

        // De-duplicate by product name to prevent repetition
        const uniqueProductsMap = new Map();
        for (const p of temp) {
            if (!uniqueProductsMap.has(p.name)) {
                uniqueProductsMap.set(p.name, p);
            }
        }
        temp = Array.from(uniqueProductsMap.values());

        switch (filters.sort) {
            case "z-a": 
                temp.sort((a, b) => b.name.localeCompare(a.name)); 
                break;
            case "newest": 
                temp.sort((a, b) => b.id.localeCompare(a.id)); 
                break;
            case "popular": 
                temp.sort((a, b) => b.description.length - a.description.length); 
                break;
            default: 
                temp.sort((a, b) => a.name.localeCompare(b.name));
        }

        const totalItems = temp.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        let currentPage = filters.page || 1;
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        return {
            items: temp.slice(start, end),
            totalItems,
            totalPages,
            currentPage,
            itemsPerPage,
            startItem: totalItems === 0 ? 0 : start + 1,
            endItem: Math.min(currentPage * itemsPerPage, totalItems)
        };
    }

    return {
        getItemsPerPage,
        getProductById,
        getRelatedProducts,
        fetchProducts
    };
})();
