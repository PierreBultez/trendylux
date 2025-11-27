document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.querySelector('#product-filters');
    const productArchiveContainer = document.querySelector('#product-archive-container');
    const resultCount = document.querySelector('.woocommerce-result-count');

    const checkFilters = () => {
        const checkedFilters = filterForm.querySelectorAll('input[type="checkbox"]:checked');
        const resetButton = document.querySelector('#reset-filters');
        if (checkedFilters.length > 0) {
            resetButton.classList.remove('hidden');
        } else {
            resetButton.classList.add('hidden');
        }
    };

    const applyFilters = (page = 1) => {
        const formData = new FormData(filterForm);
        const filters = {};
        let categoryId = null;

        for (const [name, value] of formData.entries()) {
            if (name === 'current_category_id') {
                categoryId = value;
                continue;
            }
            if (!filters[name]) {
                filters[name] = [];
            }
            filters[name].push(value);
        }

        // Get base URL without query parameters or pagination to ensure clean links
        let currentUrl = window.location.origin + window.location.pathname;
        currentUrl = currentUrl.replace(/\/page\/\d+\/?/, '/');
        // Ensure trailing slash for consistency with WP pagination
        if (!currentUrl.endsWith('/')) {
            currentUrl += '/';
        }
        
        // Get sort order
        const orderby = document.querySelector('.woocommerce-ordering .orderby')?.value || new URLSearchParams(window.location.search).get('orderby');

        fetch(trendylux_ajax.ajax_url + '?action=filter_products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                filters: filters,
                category_id: categoryId,
                page: page,
                page_url: currentUrl,
                orderby: orderby
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    productArchiveContainer.querySelector('.products').innerHTML = data.data.products;
                    resultCount.innerHTML = data.data.result_count;
                    // Scroll to top of products
                    productArchiveContainer.scrollIntoView({ behavior: 'smooth' });
                    
                    // Update URL with new state (optional but good for UX)
                     const url = new URL(window.location);
                     url.searchParams.set('orderby', orderby);
                     // We might want to update page in URL too, but let's stick to basics first
                     // history.pushState({}, '', url);
                }
            });
        
        checkFilters();
    };

    // Use event delegation for the filter form and reset button
    document.body.addEventListener('change', function(event) {
        if (event.target.closest('#product-filters')) {
            applyFilters(1); // Reset to page 1 on filter change
        }
        
        if (event.target.matches('.woocommerce-ordering .orderby')) {
            applyFilters(1);
        }
    });
    
    // Prevent standard form submission for sorting
    document.body.addEventListener('submit', function(event) {
        if (event.target.matches('.woocommerce-ordering')) {
            event.preventDefault();
        }
    });

    // Handle pagination clicks
    document.body.addEventListener('click', function(event) {
        // Target links inside our pagination container (assuming it's inside product-archive-container or we can target the class)
        // Our pagination uses .join-item.btn or standard WP classes if fallback.
        // We should target the <a> tag specifically.
        const paginationLink = event.target.closest('.woocommerce-pagination a, .join a, .page-numbers');
        
        if (paginationLink) {
            event.preventDefault();
            const href = paginationLink.getAttribute('href');
            if (href) {
                // Extract page number from URL. Supports /page/2/ and ?paged=2
                let page = 1;
                const matchPath = href.match(/\/page\/(\d+)/);
                const matchQuery = href.match(/paged=(\d+)/);

                if (matchPath) {
                    page = parseInt(matchPath[1]);
                } else if (matchQuery) {
                    page = parseInt(matchQuery[1]);
                }
                
                applyFilters(page);
            }
        }

        if (event.target.matches('#reset-filters')) {
            window.location.href = window.location.pathname;
        }
    });

    // Initial check on page load
    checkFilters();
});