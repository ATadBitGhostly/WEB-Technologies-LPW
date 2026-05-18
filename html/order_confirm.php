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

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'order_confirm';
$pageTitle = 'Order Confirmation - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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

    <script>localStorage.removeItem('cart');
    updateCartCount();</script>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
