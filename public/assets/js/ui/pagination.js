/**
 * ui/pagination.js
 * Renders pagination controls.
 */
const PaginationUI = (function() {

    function render(data) {
        const $cont = $('.product-pagination');
        if (!$cont.length) return;
        $cont.empty();
        
        const { totalPages, currentPage } = data;
        
        if (totalPages <= 1) return;

        if (currentPage > 1) {
            $cont.append(`<a href="#" class="page-link page-numbers prev" data-page="${currentPage - 1}"><i class="fas fa-chevron-left"></i></a>`);
        }

        let pages = new Set();
        pages.add(1);
        pages.add(totalPages);
        pages.add(currentPage);
        if (currentPage > 1) pages.add(currentPage - 1);
        if (currentPage < totalPages) pages.add(currentPage + 1);

        let pagesArr = Array.from(pages).sort((a, b) => a - b);
        let lastPage = 0;
        
        for (const p of pagesArr) {
            if (lastPage !== 0 && p - lastPage > 1) {
                // Not clickable separator
                $cont.append(`<span class="page-numbers border-0 text-muted" style="background:transparent; pointer-events:none;">...</span>`);
            }
            $cont.append(`<a href="#" class="page-link page-numbers ${p === currentPage ? 'active current' : ''}" data-page="${p}">${p}</a>`);
            lastPage = p;
        }

        if (currentPage < totalPages) {
            $cont.append(`<a href="#" class="page-link page-numbers next" data-page="${currentPage + 1}"><i class="fas fa-chevron-right"></i></a>`);
        }
    }

    return {
        render
    };
})();
