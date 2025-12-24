// Importe le CSS principal
import './style.css';

// Importe et initialise Alpine.js
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

console.log('AlpineJS opérationnel !');
console.log('Hello Trendy Lux from Vite!');

// Faire fonctionner les boutons + et - sur la page panier
function handleQuantityButtons() {
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-quantity-btn');
        if (!btn) return;

        e.preventDefault();

        const action = btn.dataset.action;
        const quantityWrapper = btn.closest('.quantity');
        if (!quantityWrapper) return;

        const input = quantityWrapper.querySelector('input[type="number"]');
        if (!input) return;

        const min = parseFloat(input.min) || 0;
        const max = parseFloat(input.max) || Infinity;
        const step = parseFloat(input.step) || 1;
        const currentValue = parseFloat(input.value) || 0;
        let newValue = currentValue;

        if (action === 'plus') {
            newValue = Math.min(max, currentValue + step);
        } else if (action === 'minus') {
            newValue = Math.max(min, currentValue - step);
        }

        if (newValue === currentValue) {
            return;
        }

        // Met à jour la valeur de l'input
        input.value = newValue;

        // --- LA MAGIE EST ICI ---

        // 1. On trouve le bouton "Mettre à jour le panier"
        const updateCartButton = document.querySelector('button[name="update_cart"]');

        // 2. On le réactive en changeant sa propriété 'disabled'
        if (updateCartButton) {
            updateCartButton.disabled = false;
        }

        // 3. On déclenche un événement "change" au cas où d'autres scripts l'écoutent
        const changeEvent = new Event('change', { bubbles: true });
        input.dispatchEvent(changeEvent);
    });
}

// Styliser l'input quantité sur la page panier
document.addEventListener('DOMContentLoaded', () => {
    // Le code pour les inputs (qui retire les flèches par défaut)
    const quantityInputs = document.querySelectorAll('.woocommerce-cart-form .quantity .qty');
    quantityInputs.forEach(input => {
        input.classList.add('input', 'input-bordered', 'w-16', 'text-center', 'join-item');
        input.classList.remove('input-text');
        // Cache les flèches par défaut du navigateur
        input.style.appearance = 'textfield';
    });

    // Gère les boutons + et -
    handleQuantityButtons();
    
    // Gestion du formulaire Newsletter (Event Delegation pour support AJAX/Multi-instance)
    document.body.addEventListener('submit', function(e) {
        if (!e.target.classList.contains('js-newsletter-form')) return;

        e.preventDefault();
        const form = e.target;
        
        const emailInput = form.querySelector('input[name="email"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // On cherche le conteneur de message associé (frère ou dans le parent)
        const msgContainer = form.parentElement.querySelector('.js-newsletter-message');
        
        if (!msgContainer) return;

        const originalBtnText = submitBtn.innerHTML;

        // Reset UI
        msgContainer.innerHTML = '';
        msgContainer.classList.remove('text-success', 'text-error');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span>';

        const formData = new FormData();
        formData.append('action', 'subscribe_newsletter');
        formData.append('email', emailInput.value);
        formData.append('nonce', trendylux_ajax.nonce);

        fetch(trendylux_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                msgContainer.classList.add('text-success');
                msgContainer.textContent = data.data;
                emailInput.value = ''; // Clear input
            } else {
                msgContainer.classList.add('text-error');
                msgContainer.textContent = data.data;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            msgContainer.classList.add('text-error');
            msgContainer.textContent = 'Une erreur technique est survenue.';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});

// Ajout pour masquer les flèches via CSS
const style = document.createElement('style');
style.innerHTML = `
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
`;
document.head.appendChild(style);


// AJAX Live Search Logic
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('trendylux-search-input');
    const resultsContainer = document.getElementById('trendylux-search-results');
    let debounceTimer;

    // Fonction pour mettre à jour la preview
    const updatePreview = (product) => {
        const previewContainer = document.getElementById('trendylux-search-preview');
        if (!previewContainer) return;
        
        previewContainer.innerHTML = `
            <div class="h-full flex flex-col animate-fade-in">
                <div class="relative w-full aspect-square rounded-box overflow-hidden mb-4 bg-gray-50">
                    <img src="${product.image_lg || 'https://placehold.co/400x400?text=No+Image'}" class="w-full h-full object-cover" alt="${product.title}">
                    <div class="absolute top-2 right-2 badge badge-primary font-bold shadow-sm">${product.price}</div>
                </div>
                <h3 class="font-bold text-lg leading-tight mb-2">${product.title}</h3>
                <div class="text-xs text-gray-500 mb-4 line-clamp-3">${product.excerpt}</div>
                <div class="mt-auto grid grid-cols-2 gap-2">
                    <a href="${product.url}" class="btn btn-secondary btn-sm btn-block">Voir</a>
                    <a href="${product.add_to_cart_url}" class="btn btn-primary btn-sm btn-block">Ajouter</a>
                </div>
            </div>
        `;
    };

    if (searchInput && resultsContainer) {
        searchInput.addEventListener('input', function() {
            const term = this.value.trim();

            clearTimeout(debounceTimer);

            if (term.length < 3) {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(function() {
                // Loading state
                resultsContainer.innerHTML = '<div class="p-8 text-center text-gray-500 text-sm"><span class="loading loading-spinner loading-md mb-2 block mx-auto"></span>Recherche en cours...</div>';
                resultsContainer.classList.remove('hidden');

                const url = new URL(trendylux_ajax.ajax_url);
                url.searchParams.append('action', 'trendylux_ajax_search');
                url.searchParams.append('term', term);

                fetch(url)
                    .then(response => response.json())
                    .then(response => {
                        if (response.success && response.data.length > 0) {
                            const products = response.data;
                            
                            // Construction du Layout 2 Colonnes
                            let html = `
                                <div class="flex h-[450px]">
                                    <!-- Colonne Gauche : Liste -->
                                    <div class="w-5/12 border-r border-base-200 overflow-y-auto custom-scrollbar flex flex-col">
                                        <ul class="menu p-2 w-full gap-1">
                            `;

                            products.forEach((product, index) => {
                                // On stocke les données produit en JSON dans un attribut data pour y accéder facilement au survol
                                const productData = JSON.stringify(product).replace(/"/g, '&quot;');
                                html += `
                                    <li>
                                        <a href="${product.url}" class="js-search-item flex items-center gap-3 py-3 hover:bg-base-200 rounded-box transition-colors ${index === 0 ? 'active bg-base-200' : ''}" data-product="${productData}">
                                            <div class="avatar">
                                                <div class="w-10 h-10 rounded-btn border border-base-200">
                                                    <img src="${product.image_sm || 'https://placehold.co/100x100?text=No+Image'}" alt="${product.title}" />
                                                </div>
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="font-bold text-xs truncate">${product.title}</div>
                                                <div class="text-primary text-[10px] font-bold">${product.price}</div>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    </li>
                                `;
                            });

                            html += `
                                        </ul>
                                        <div class="mt-auto p-2 border-t border-base-200 bg-base-50 sticky bottom-0">
                                            <button type="submit" class="btn btn-ghost btn-xs w-full text-primary font-bold">
                                                Voir tous les résultats
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Colonne Droite : Preview -->
                                    <div class="w-7/12 p-6 bg-base-100" id="trendylux-search-preview">
                                        <!-- Preview will be injected here -->
                                    </div>
                                </div>
                            `;

                            resultsContainer.innerHTML = html;

                            // Initialiser la preview avec le premier produit
                            if (products.length > 0) {
                                updatePreview(products[0]);
                            }

                            // Ajouter les événements de survol
                            const items = resultsContainer.querySelectorAll('.js-search-item');
                            items.forEach(item => {
                                item.addEventListener('mouseenter', function() {
                                    // Retirer la classe active des autres
                                    items.forEach(i => i.classList.remove('active', 'bg-base-200'));
                                    // Ajouter à l'actuel
                                    this.classList.add('active', 'bg-base-200');

                                    // Mettre à jour la preview
                                    const data = JSON.parse(this.getAttribute('data-product'));
                                    updatePreview(data);
                                });
                            });

                        } else {
                            resultsContainer.innerHTML = `
                                <div class="p-8 text-center flex flex-col items-center justify-center h-48">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    <div class="text-gray-500 font-bold">Aucun résultat</div>
                                    <p class="text-xs text-gray-400 mt-1">Essayez un autre mot-clé (ex: "robe", "sac", "nike")</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        resultsContainer.innerHTML = '';
                        resultsContainer.classList.add('hidden');
                    });
            }, 300);
        });

        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });

        // Re-open results if input has value and is focused
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 3 && resultsContainer.innerHTML !== '') {
                resultsContainer.classList.remove('hidden');
            }
        });
    }
});