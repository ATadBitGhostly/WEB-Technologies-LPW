<?php
session_start();

$activePage = 'cart';
$pageTitle = 'Cart - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

    <main>
        <section class="py-5">
            <div class="container">
                <h1 class="display-4 fw-normal">Your Cart</h1>
                <hr>

                <!-- Tukšs grozs -->
                <div id="cart-empty" class="text-center py-5" style="display:none;">
                    <i class="bi bi-cart-x" style="font-size: 4rem; color: gray;"></i>
                    <p class="lead mt-3 text-muted">Your cart is empty.</p>
                    <a href="product.php" class="btn btn-primary mt-2">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>

                <!-- Grozs ar produktiem -->
                <div id="cart-content">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- JS aizpilda šeit -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Kopsumma -->
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Order Summary</h5>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Total:</span>
                                        <span class="fw-bold" id="cart-total">€0.00</span>
                                    </div>
                                    <div class="d-grid gap-2 mt-3">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="checkout.php" class="btn btn-primary">
                                                Proceed to Checkout
                                            </a>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-primary">
                                                Login to Checkout
                                            </a>
                                        <?php endif; ?>
                                        <button onclick="clearCart()" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i> Clear Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
    <script src="../scripts/cart.js"></script>
    <script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
    <script>
        // Lapa ielādējas → parāda grozu
        document.addEventListener('DOMContentLoaded', function () {
            renderCart();

            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                document.getElementById('cart-content').style.display = 'none';
                document.getElementById('cart-empty').style.display = 'block';
            }
        });
    </script>