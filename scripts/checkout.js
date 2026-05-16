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

        const minimal = cart.map(i => ({ id: i.id, quantity: i.quantity }));
        document.getElementById('cart_json').value = JSON.stringify(minimal);
        document.getElementById('cart_total_client').value = total.toFixed(2);
    }
}

function validateCardNumber(input) {
    const digits = input.value.replace(/\s+/g, '');
    if (digits.length < 13 || digits.length > 19) {
        input.setCustomValidity('Invalid length.');
    } else {
        input.setCustomValidity('');
    }
}

function validateExpiry(input) {
    const val = input.value.trim();
    if (!/^\d{2}\/\d{2}$/.test(val)) {
        input.setCustomValidity('Format must be MM/YY.');
        return;
    }
    const [mm, yy] = val.split('/').map(n => parseInt(n, 10));
    const now = new Date();
    const curYY = now.getFullYear() % 100;
    const curMM = now.getMonth() + 1;

    if (mm < 1 || mm > 12) {
        input.setCustomValidity('Invalid month.');
    } else if (yy < curYY || (yy === curYY && mm < curMM)) {
        input.setCustomValidity('Card has expired.');
    } else {
        input.setCustomValidity('');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    renderCheckout();

    const cardInput = document.getElementById('card_number');
    const expiryInput = document.getElementById('card_expiry');

    // Auto-format card number as XXXX XXXX XXXX XXXX
    cardInput.addEventListener('input', function () {
        const digits = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = digits.replace(/(.{4})/g, '$1 ').trim();
        validateCardNumber(this);
    });

    // Auto-format expiry as MM/YY
    expiryInput.addEventListener('input', function (e) {
        let digits = this.value.replace(/\D/g, '').slice(0, 4);
        if (digits.length >= 3) {
            digits = digits.slice(0, 2) + '/' + digits.slice(2);
        }
        this.value = digits;
        validateExpiry(this);
    });
});

document.getElementById('checkout-form').addEventListener('submit', function (e) {
    const form = this;
    const cart = getCart();
    const errorDiv = document.getElementById('checkout-error');

    if (cart.length === 0) {
        e.preventDefault();
        errorDiv.textContent = 'Your cart is empty.';
        errorDiv.style.display = 'block';
        return;
    }
    errorDiv.style.display = 'none';

    const cardInput = document.getElementById('card_number');
    const expiryInput = document.getElementById('card_expiry');

    // Run validation once more on submit to catch untouched fields
    validateCardNumber(cardInput);
    validateExpiry(expiryInput);

    form.classList.add('was-validated');

    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        return;
    }

    renderCheckout();
});