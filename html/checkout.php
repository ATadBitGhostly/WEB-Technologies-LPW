<?php
session_start();
// Only logged-in users can access checkout
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Checkout Page</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-slightyDarkBlue">

        <div class="container-fluid">

            <a href="#" class="navbar-brand">Sports Page 101</a>
            <button type="button" class="btn text-white" id="themeToggler"><i class="bi bi-moon-stars" id="dark-mode-icon"></i></button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link">
                            <i class="bi bi-cart"></i>
                            <span id="cart-count" class="badge border">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="services.php" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="product.php" class="nav-link">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link">Register</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
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
                                            <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
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

    <footer class="bg-slightyDarkBlue text-light py-4">
        <div class="container text-center">
            <div class="mb-2">
                <a href="#" class="link-light text-decoration-none me-3 link-opacity-75-hover">Facebook</a>
                <a href="#" class="link-light text-decoration-none me-3 link-opacity-75-hover">Twitter</a>
                <a href="#" class="link-light text-decoration-none me-3 link-opacity-75-hover">Instagram</a>
            </div>
            <p class="mb-0">&copy; <span id="dynamicDate"></span> Sports Page 101. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
    <script src="../scripts/cart.js"></script>
    <script src="../scripts/checkout.js"></script>
    <script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
</body>
</html>
