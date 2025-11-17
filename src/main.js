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