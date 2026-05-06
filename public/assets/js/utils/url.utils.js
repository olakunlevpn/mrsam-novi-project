/**
 * utils/url.utils.js
 * Handles reading from and writing to the URL.
 */
const UrlUtils = (function () {
    
    function getParams() {
        const params = new URLSearchParams(window.location.search);
        return {
            animal: params.get('animal'),
            type: params.get('type'),
            search: params.get('search'),
            sort: params.get('sort'),
            page: params.get('page') ? parseInt(params.get('page')) : null,
            id: params.get('id')
        };
    }

    function updateURL(state) {
        const url = new URL(window.location);
        const { view, filters, selectedProductId } = state;

        if (view === 'detail' && selectedProductId) {
            url.searchParams.set('id', selectedProductId);
        } else {
            url.searchParams.delete('id');
            
            if (filters.animal && filters.animal !== 'all') url.searchParams.set('animal', filters.animal);
            else url.searchParams.delete('animal');

            if (filters.type && filters.type !== 'all') url.searchParams.set('type', filters.type);
            else url.searchParams.delete('type');

            if (filters.search) url.searchParams.set('search', filters.search);
            else url.searchParams.delete('search');

            if (filters.sort && filters.sort !== 'default') url.searchParams.set('sort', filters.sort);
            else url.searchParams.delete('sort');

            if (filters.page && filters.page > 1) url.searchParams.set('page', filters.page);
            else url.searchParams.delete('page');
        }

        window.history.pushState(state, '', url);
    }

    return {
        getParams,
        updateURL
    };
})();
