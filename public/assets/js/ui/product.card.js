/**
 * ui/product.card.js
 * Pure component to render a single product card.
 */
const ProductCard = (function() {
    function render(product, isRelated = false) {
        const classNames = isRelated
            ? "col-sm-6 col-md-4 mb-4 d-flex align-items-stretch"
            : "col-sm-6 col-md-6 col-lg-4 mb-4 d-flex align-items-stretch";

        const itemClass = isRelated ? "product__item related-product-item" : "product__item";

        // Overlaid badges on the image
        const animalLabel = (product.animal || '').toUpperCase();
        const categoryLabel = product.category || '';
        const animalBadge = animalLabel
            ? `<span class="product__item__animal-badge">${animalLabel}</span>`
            : '';
        const categoryTag = categoryLabel
            ? `<span class="product__item__category-tag">${categoryLabel}</span>`
            : '';

        return `
            <div class="${classNames}">
                <div class="${itemClass}" data-id="${product.id}" style="cursor:pointer; width:100%;">
                    <div class="product__item__img" style="aspect-ratio:4/3; overflow:hidden; background:#fff;">
                        <img src="${product.image}" alt="${product.name}" loading="lazy"
                             style="width:100%;height:100%; object-fit:cover; display:block;">
                        ${animalBadge}
                        ${categoryTag}
                    </div>
                    <div class="product__item__content-wrap">
                        <div class="product__item__content">
                            <h4 class="product__item__title">
                                <a href="#" tabindex="-1">${product.name}</a>
                            </h4>
                        </div>
                        <span class="product__item__cta" aria-hidden="true">
                            View Details <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        `;
    }

    return { render };
})();

