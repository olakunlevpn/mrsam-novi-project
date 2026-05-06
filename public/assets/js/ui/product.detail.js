/**
 * ui/product.detail.js
 * Handles product detail rendering and view switching.
 */
const ProductDetailUI = (function() {

    function toggleSlot(containerId, slotId, content) {
        if (content && content.trim() !== "") {
            $(containerId).show();
            $(slotId).text(content);
        } else {
            $(containerId).hide();
        }
    }

    function renderDetail(product, relatedProducts) {
        $('#slotProductTitle').text(product.name);
        $('#slotProductImage').attr('src', product.image).attr('alt', product.name);
        
        toggleSlot('#slotDescriptionContainer', '#slotProductDescription', product.description);
        toggleSlot('#slotCompositionContainer', '#slotProductComposition', product.composition);
        toggleSlot('#slotBenefitsContainer', '#slotProductBenefits', product.benefits);
        toggleSlot('#slotUsageContainer', '#slotProductUsage', product.usage);

        $('#slotAnimalBadge').text((product.animal || '').toUpperCase());
        $('#slotCategoryBadge').text(product.category || '');

        renderRelated(relatedProducts);
    }

    function renderRelated(relatedProducts) {
        const $container = $('#slotRelatedProducts');
        $container.empty();

        if (relatedProducts.length === 0) {
            $('#relatedProductsSection').hide();
            return;
        }

        $('#relatedProductsSection').show();

        relatedProducts.forEach(p => {
            $container.append(ProductCard.render(p, true));
        });
    }

    function showView(viewType) {
        if (viewType === 'detail') {
            $('#catalog-listing').hide();
            // Re-trigger animation by briefly removing it
            const $details = $('#product-details');
            $details.css('animation', 'none');
            // Force reflow so animation re-fires
            $details[0] && $details[0].offsetHeight;
            $details.css('animation', '');
            $details.fadeIn(250);
            // Scroll to top of the product section (not window, preserves header context)
            const $section = $details.closest('section');
            const targetTop = ($section.length ? $section.offset().top : 0) - 110;
            $('html, body').animate({ scrollTop: Math.max(0, targetTop) }, 350, 'swing');
        } else {
            $('#product-details').hide();
            $('#catalog-listing').fadeIn(280);
        }
    }

    return {
        renderDetail,
        showView
    };
})();
