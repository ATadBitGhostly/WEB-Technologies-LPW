function addToCart(id, title, price, stock, qty = 1) {
    console.log('--- addToCart called ---');
    console.log('id:', id);
    console.log('title:', title);
    console.log('price:', price);
    console.log('stock:', stock);
    console.log('qty received:', qty);
    qty = parseInt(qty);
    console.log('qty after parseInt:', qty);

    if (qty < 1 || qty > stock) {
        alert('Please select a valid quantity (1 - ' + stock + ').');
        return;
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(p => p.id === id);
    let currentQty = item ? item.quantity : 0;

    if (currentQty + qty > stock) {
        alert('Sorry! Only ' + (stock - currentQty) + ' more available for ' + title + '.');
        return;
    }

    if (item) {
        item.quantity += qty;
    } else {
        cart.push({ id, title, price, quantity: qty, stock });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    alert(qty + 'x ' + title + ' added to cart!');
}


function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(p => p.id !== id);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

function renderCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const tbody = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');
    const cartContent = document.getElementById('cart-content');
    const cartEmpty = document.getElementById('cart-empty');

    if (!tbody) return;

    // Show/hide empty cart message
    if (cart.length === 0) {
        if (cartContent) cartContent.style.display = 'none';
        if (cartEmpty) cartEmpty.style.display = 'block';
        return;
    } else {
        if (cartContent) cartContent.style.display = 'block';
        if (cartEmpty) cartEmpty.style.display = 'none';
    }

    tbody.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
        tbody.innerHTML += `
            <tr>
                <td>${item.title}</td>
                <td>€${parseFloat(item.price).toFixed(2)}</td>
                <td>
                    <input type="number" 
                           min="1" 
                           max="${item.stock}"
                           value="${item.quantity}"
                           onchange="updateQuantity(${item.id}, this.value, ${item.stock})"
                           class="form-control form-control-sm" style="width: 70px;">
                </td>
                <td>€${(item.price * item.quantity).toFixed(2)}</td>
                <td>
                    <button onclick="removeFromCart(${item.id})"
                            class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
    });

    if (totalEl) totalEl.innerText = '€' + total.toFixed(2);
}

function updateQuantity(id, quantity, stock) {
    let qty = parseInt(quantity);

    // Stock limit pārbaude
    if (qty < 1) qty = 1;
    if (qty > stock) {
        alert('Sorry! Only ' + stock + ' available in stock.');
        qty = stock;
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(p => p.id === id);
    if (item) item.quantity = qty;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const badge = document.getElementById('cart-count');
    if (badge) badge.innerText = count;
}

function clearCart() {
    localStorage.removeItem('cart');
    updateCartCount();
    renderCart();
}

document.addEventListener('DOMContentLoaded', updateCartCount);