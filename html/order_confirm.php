<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// If someone visits this page directly without placing an order, redirect them
if (empty($_SESSION['last_order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = $_SESSION['last_order_id'];
$order_total = $_SESSION['last_order_total'];

// Clear so refreshing doesn't show stale data
unset($_SESSION['last_order_id'], $_SESSION['last_order_total']);
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
    <title>Order confirmation</title>
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
                        <a href="index.php" class="nav-link active" aria-current="page">Home</a>
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
            <div class="container text-center">
                <div class="py-5">
                    <i class="bi bi-bag-check-fill text-success" style="font-size: 5rem;"></i>
                    <h1 class="display-5 fw-bold mt-3">Order Placed!</h1>
                    <p class="lead text-muted">Thank you for your purchase. Your order has been received.</p>

                    <div class="card shadow-sm mx-auto mt-4" style="max-width: 400px;">
                        <div class="card-body">
                            <p class="mb-1"><span class="text-muted">Order ID:</span> <strong>#<?= htmlspecialchars($order_id) ?></strong></p>
                            <p class="mb-0"><span class="text-muted">Total Paid:</span> <strong>€<?= number_format($order_total, 2) ?></strong></p>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center gap-3">
                        <a href="product.php" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                        <a href="dashboard.php" class="btn btn-outline-secondary">
                            <i class="bi bi-person"></i> My Dashboard
                        </a>
                    </div>
                </div>
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
<script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
<script>localStorage.removeItem('cart');
        updateCartCount();</script>
</body>
</html>
