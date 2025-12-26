document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.querySelector('#product-filters');
    const productArchiveContainer = document.querySelector('#product-archive-container');
    const resultCount = document.querySelector('.woocommerce-result-count');
    const orderingForm = document.querySelector('.woocommerce-ordering');

    const checkFilters = () => {
        const checkedFilters = filterForm.querySelectorAll('input[type="checkbox"]:checked');
        const resetButton = document.querySelector('#reset-filters');
        if (checkedFilters.length > 0) {
            resetButton.classList.remove('hidden');
        } else {
            resetButton.classList.add('hidden');
        }
    };

    const updateOrderingForm = (filters) => {
        if (!orderingForm) return;

        // Remove existing filter hidden inputs
        const existingInputs = orderingForm.querySelectorAll('input[type="hidden"].filter-param');
        existingInputs.forEach(input => input.remove());

        // Add new hidden inputs for current filters
        for (const [key, value] of Object.entries(filters)) {
            // Flatten array values to comma-separated string for WP compatibility
            // and use simple key name (WP_Query tax_query expects string or comma-separated string for URL vars)
            const valStr = Array.isArray(value) ? value.join(',') : value;
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key; // Ensure no [] suffix
            input.value = valStr;
            input.classList.add('filter-param');
            orderingForm.appendChild(input);
        }
    };

    const applyFilters = (page = 1) => {
        const formData = new FormData(filterForm);
        const filters = {};
        let categoryId = null;
        let currentTermId = null;
        let currentTaxonomy = null;

        for (const [name, value] of formData.entries()) {
            if (name === 'current_category_id') {
                categoryId = value;
                continue;
            }
            if (name === 'current_term_id') {
                currentTermId = value;
                continue;
            }
            if (name === 'current_taxonomy') {
                currentTaxonomy = value;
                continue;
            }
            // Handle array inputs (checkboxes)
            if (name.endsWith('[]')) {
                const cleanName = name.slice(0, -2);
                if (!filters[cleanName]) {
                    filters[cleanName] = [];
                }
                filters[cleanName].push(value);
            } else {
                 if (!filters[name]) {
                    filters[name] = [];
                }
                filters[name].push(value);
            }
        }

        // Update the ordering form with current filters as a fallback
        updateOrderingForm(filters);

        // Get base URL without query parameters or pagination to ensure clean links
        let currentUrl = window.location.origin + window.location.pathname;
        currentUrl = currentUrl.replace(/\/page\/\d+\/?/, '/');
        // Ensure trailing slash for consistency with WP pagination
        if (!currentUrl.endsWith('/')) {
            currentUrl += '/';
        }
        
        // Get sort order
        const orderby = document.querySelector('.woocommerce-ordering .orderby')?.value || new URLSearchParams(window.location.search).get('orderby');

        // Get search query
        const searchQuery = new URLSearchParams(window.location.search).get('s') || '';

        // Prepare URL params for pushState
        const url = new URL(window.location);
        const params = new URLSearchParams(url.search);
        
        // Clear existing filter params (except s and standard WP ones if we want to keep them, but here we rebuild)
        // We keep 's'
        const existingS = params.get('s');
        
        // Rebuild params
        const newParams = new URLSearchParams();
        if (existingS) newParams.set('s', existingS);
        if (orderby) newParams.set('orderby', orderby);
        
        // Add filters to params
        for (const [key, value] of Object.entries(filters)) {
             if (Array.isArray(value)) {
                // Use comma-separated values for WP compatibility
                newParams.set(key, value.join(','));
            } else {
                newParams.set(key, value);
            }
        }
        
        // Update URL
        const newUrl = `${window.location.pathname}?${newParams.toString()}`;
        window.history.pushState({path: newUrl}, '', newUrl);

        // Prepare AJAX data - we need to send the raw filters structure expected by the PHP handler
        // The PHP handler expects 'filters' to be an object where keys are taxonomy names and values are arrays of slugs.
        // My manual reconstruction above for `filters` object handles the `[]` logic, but let's double check.
        // The FormData loop above: if name is 'pa_color[]', I stored it in filters['pa_color'].
        
        const ajaxFilters = {};
        for (const [name, value] of formData.entries()) {
             if (['current_category_id', 'current_term_id', 'current_taxonomy'].includes(name)) continue;
             
             let key = name;
             if (key.endsWith('[]')) key = key.slice(0, -2);
             
             if (!ajaxFilters[key]) ajaxFilters[key] = [];
             ajaxFilters[key].push(value);
        }


        fetch(trendylux_ajax.ajax_url + '?action=filter_products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                filters: ajaxFilters,
                category_id: categoryId, 
                current_term_id: currentTermId,
                current_taxonomy: currentTaxonomy,
                search: searchQuery,
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
            event.preventDefault(); // Try to prevent default
            applyFilters(1);
        }
    });
    
    // Prevent standard form submission for sorting
    document.body.addEventListener('submit', function(event) {
        if (event.target.matches('.woocommerce-ordering')) {
            event.preventDefault();
            // If the submit happened despite our efforts, applyFilters would have run on 'change'. 
            // But if it was a manual submit, we should run it here too? 
            // Usually change triggers it.
        }
    });

    // Handle pagination clicks
    document.body.addEventListener('click', function(event) {
        // Target links inside our pagination container
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
            const url = new URL(window.location);
            const params = new URLSearchParams(url.search);
            
            const searchQuery = params.get('s'); // Get the current search query
            
            // Clear all params except 's'
            // Create new search params to avoid iteration issues
            const newParams = new URLSearchParams();
            if (searchQuery) newParams.set('s', searchQuery);
            
            // Determine redirect URL
            let redirectUrl = window.location.pathname;
            if (newParams.toString()) {
                redirectUrl += '?' + newParams.toString();
            }
             
            window.location.href = redirectUrl;
        }
    });

    // Initial check on page load
    checkFilters();
});