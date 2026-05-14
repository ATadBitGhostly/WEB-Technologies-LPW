function addToCart(id, title, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(p => p.id === id);
    if (item) {
        item.quantity++;
    } else {
        cart.push({ id, title, price, quantity: 1 });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount(); // ← pievieno
    alert(title + ' added to cart!');
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
    if (!tbody) return;

    tbody.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
        tbody.innerHTML += `
            <tr>
                <td>${item.title}</td>
                <td>€${item.price}</td>
                <td>
                    <input type="number" min="1" value="${item.quantity}"
                           onchange="updateQuantity(${item.id}, this.value)"
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

function updateQuantity(id, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(p => p.id === id);
    if (item) item.quantity = parseInt(quantity);
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