document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.querySelector('#product-filters');
    const productGrid = document.querySelector('.products');
    const resetButton = document.querySelector('#reset-filters');

    const checkFilters = () => {
        const checkedFilters = filterForm.querySelectorAll('input[type="checkbox"]:checked');
        if (checkedFilters.length > 0) {
            resetButton.classList.remove('hidden');
        } else {
            resetButton.classList.add('hidden');
        }
    };

    const applyFilters = () => {
        const formData = new FormData(filterForm);
        formData.append('action', 'filter_products');

        fetch(trendylux_ajax.ajax_url, {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.text())
            .then((data) => {
                productGrid.innerHTML = data;
            });
        
        checkFilters();
    };

    if (filterForm) {
        filterForm.addEventListener('change', applyFilters);
    }

    if (resetButton) {
        resetButton.addEventListener('click', () => {
            filterForm.reset(); // Reset all form fields
            applyFilters(); // Apply filters to reload products
        });
    }

    // Initial check on page load
    checkFilters();
});
