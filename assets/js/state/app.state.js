/**
 * state/app.state.js
 * Centralized state management for the product catalog.
 */

const AppState = (function () {
    let state = {
        view: 'listing', // 'listing' | 'detail'
        filters: {
            animal: 'all',
            type: 'all',
            search: '',
            sort: 'default',
            page: 1
        },
        selectedProductId: null
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
