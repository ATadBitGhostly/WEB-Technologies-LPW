<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Only logged-in users can access checkout
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'checkout';
$pageTitle = 'Checkout - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>  
    <main>
        <section class="py-5">
            <div class="container">
                <h1 class="display-4 fw-normal">Checkout</h1>
                <hr>

                <div id="checkout-empty" class="text-center py-5" style="display:none;">
                    <i class="bi bi-cart-x" style="font-size: 4rem; color: gray;"></i>
                    <p class="lead mt-3 text-muted">Your cart is empty.</p>
                    <a href="product.php" class="btn btn-primary mt-2">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>

                <form id="checkout-form" method="POST" action="process_order.php">
                    <input type="hidden" name="cart_json" id="cart_json" value="">
                    <input type="hidden" name="cart_total_client" id="cart_total_client" value="0">

                    <div id="checkout-content" style="display:none;">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4">Billing Details</h5>
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="John Doe" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="john@example.com" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="123 Main St" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="Riga" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="zip" class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="LV-1000" pattern="[A-Za-z0-9\-]{3,10}" required>
                                                <div class="invalid-feedback">Enter a valid ZIP (e.g. LV-1000).</div>
                                            </div>
                                        </div>
                                        <h5 class="mt-4 mb-3">Payment</h5>
                                        <div class="mb-3">
                                            <label for="card_number" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" pattern="[\d\s]{13,19}" required>
                                            <div class="invalid-feedback">Enter a valid card number.</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="card_expiry" class="form-label">Expiry Date</label>
                                                <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" pattern="\d{2}/\d{2}" required>
                                                <div class="invalid-feedback">Enter expiry as MM/YY (e.g. 05/28).</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="card_cvv" class="form-label">CVV</label>
                                                <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" maxlength="3" pattern="\d{3}" required>
                                                <div class="invalid-feedback">CVV must be 3 digits.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4">Order Summary</h5>
                                        <table class="table table-sm">
                                            <thead class="table-dark">
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                            </thead>
                                            <tbody id="checkout-items"></tbody>
                                        </table>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total:</span>
                                            <span id="checkout-total">€0.00</span>
                                        </div>

                                        <div id="checkout-error" class="alert alert-danger mt-3" style="display:none;"></div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" id="place-order-btn" class="btn btn-primary btn-lg">
                                                <i class="bi bi-bag-check"></i> Place Order
                                            </button>
                                        </div>
                                        <div class="text-center mt-2">
                                            <a href="cart.php" class="text-muted small">
                                                <i class="bi bi-arrow-left"></i> Back to Cart
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="../scripts/checkout.js"></script>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
