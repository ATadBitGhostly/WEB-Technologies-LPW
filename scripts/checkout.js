function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

function renderCheckout() {
    const cart = getCart();
    const content = document.getElementById('checkout-content');
    const empty = document.getElementById('checkout-empty');
    const tbody = document.getElementById('checkout-items');
    const totalEl = document.getElementById('checkout-total');
    const placeBtn = document.getElementById('place-order-btn');

    if (cart.length === 0) {
        if (content) content.style.display = 'none';
        if (empty) empty.style.display = 'block';
        if (placeBtn) placeBtn.disabled = true;
        return;
    }

    if (content) content.style.display = 'block';
    if (empty) empty.style.display = 'none';
    if (placeBtn) placeBtn.disabled = false;

    if (tbody) {
        tbody.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            tbody.innerHTML += `
                <tr>
                    <td>${item.title}</td>
                    <td>${item.quantity}</td>
                    <td>€${subtotal.toFixed(2)}</td>
                </tr>
            `;
        });
        if (totalEl) totalEl.textContent = '€' + total.toFixed(2);

        // Sync these for the PHP side
        const minimal = cart.map(i => ({ id: i.id, quantity: i.quantity }));
        document.getElementById('cart_json').value = JSON.stringify(minimal);
        document.getElementById('cart_total_client').value = total.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', renderCheckout);

document.getElementById('checkout-form').addEventListener('submit', function (e) {
    const form = this;
    const cart = getCart();
    const errorDiv = document.getElementById('checkout-error');

    // 1. Check if cart is empty
    if (cart.length === 0) {
        e.preventDefault();
        errorDiv.textContent = 'Your cart is empty.';
        errorDiv.style.display = 'block';
        return;
    }
    errorDiv.style.display = 'none';

    // 2. Clear all previous custom errors
    const cardInput = document.getElementById('card_number');
    const expiryInput = document.getElementById('card_expiry');
    cardInput.setCustomValidity('');
    expiryInput.setCustomValidity('');

    // 3. Validate Card Number (Ignore spaces, count digits)
    const cardDigits = cardInput.value.replace(/\s+/g, '');
    if (cardDigits.length < 13 || cardDigits.length > 19) {
        cardInput.setCustomValidity('Invalid length.');
    }

    // 4. Validate Expiry Date (MM/YY)
    const expiryVal = expiryInput.value.trim();
    if (/^\d{2}\/\d{2}$/.test(expiryVal)) {
        const parts = expiryVal.split('/');
        const mm = parseInt(parts[0], 10);
        const yy = parseInt(parts[1], 10);

        const now = new Date();
        const curYY = now.getFullYear() % 100; // 26
        const curMM = now.getMonth() + 1;      // 5

        if (mm < 1 || mm > 12) {
            expiryInput.setCustomValidity('Invalid month.');
        } else if (yy < curYY || (yy === curYY && mm < curMM)) {
            expiryInput.setCustomValidity('Card has expired.');
        }
    } else {
        expiryInput.setCustomValidity('Format must be MM/YY.');
    }

    // 5. Trigger Bootstrap visuals
    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        return;
    }

    // Ensure hidden inputs are updated one last time
    renderCheckout();
});