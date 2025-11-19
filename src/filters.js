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

    const applyFilters = () => {
        const formData = new FormData(filterForm);
        const filters = {};
        for (const [name, value] of formData.entries()) {
            if (!filters[name]) {
                filters[name] = [];
            }
            filters[name].push(value);
        }

        fetch(trendylux_ajax.ajax_url + '?action=filter_products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                filters: filters
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    productArchiveContainer.querySelector('.products').innerHTML = data.data.products;
                    resultCount.innerHTML = data.data.result_count;
                }
            });
        
        checkFilters();
    };

    // Use event delegation for the filter form and reset button
    document.body.addEventListener('change', function(event) {
        if (event.target.closest('#product-filters')) {
            applyFilters();
        }
    });

    document.body.addEventListener('click', function(event) {
        if (event.target.matches('#reset-filters')) {
            window.location.href = window.location.pathname;
        }
    });

    // Initial check on page load
    checkFilters();
});