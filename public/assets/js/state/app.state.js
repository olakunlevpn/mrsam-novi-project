/**
 * state/app.state.js
 * Centralized state management for the product catalog listing.
 *
 * The catalog SPA only handles filter/sort/pagination state; per-product
 * detail pages have moved to their own server-rendered route, so there is
 * no `view` switch or `selectedProductId` here anymore.
 */

const AppState = (function () {
    let state = {
        filters: {
            animal: 'all',
            type: 'all',
            search: '',
            sort: 'default',
            page: 1
        }
    };

    const listeners = [];

    function getState() {
        return { ...state, filters: { ...state.filters } };
    }

    function setState(newStateFragment, trigger = true) {
        state = {
            ...state,
            ...newStateFragment,
            filters: { ...state.filters, ...(newStateFragment.filters || {}) }
        };

        if (trigger) {
            notifyListeners();
        }
    }

    function subscribe(listener) {
        listeners.push(listener);
    }

    function notifyListeners() {
        listeners.forEach(listener => listener(getState()));
    }

    return {
        getState,
        setState,
        subscribe
    };
})();
