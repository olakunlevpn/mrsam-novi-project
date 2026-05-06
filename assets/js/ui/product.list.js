/**
 * ui/product.list.js
 * Renders the product grid and showing-text based on current state.
 */
const ProductList = (function() {
    
    function render(data) {
        const $container = $('#product-container');
        if (!$container.length) return;

        $container.empty();

        if (data.items.length === 0) {
            $container.append('<div class="col-12 py-5 text-center"><p class="text-muted">No products found.</p></div>');
        } else {
            data.items.forEach(p => {
                $container.append(ProductCard.render(p, false));
            });
        }
        
        updateShowingText(data.startItem, data.endItem, data.totalItems);
    }

    function updateShowingText(start, end, total) {
        const $text = $('.product__showing-text');
        if ($text.length) {
            $text.text(`Showing ${start}–${end} of ${total} results`);
        }
    }

    function syncUI(filters) {
        $('#main-product-search').val(filters.search || "");
        $(`.type-radio`).prop('checked', false);
        $(`.type-radio[value="${filters.type || 'all'}"]`).prop('checked', true);
        
        $(`.animal-radio`).prop('checked', false);
        $(`.animal-radio[value="${filters.animal || 'all'}"]`).prop('checked', true);
        
        const sortVal = filters.sort || "default";
        $('.sort-option').removeClass('active');
        const $activeOption = $(`.sort-option[data-value="${sortVal}"]`);
        if ($activeOption.length) {
            $activeOption.addClass('active');
            let textToSet = $activeOption.text().trim().replace(/\s+/g, ' ');
            $('.sort-selected').text(textToSet);
        } else {
            $('.sort-selected').text("Sort by Default");
        }
    }

    return {
        render,
        syncUI
    };
})();
