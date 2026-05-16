<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Product.php';

$db = new Database();
$conn = $db->connect();
$productManager = new Product($conn);

// Paņem id no URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: product.php");
    exit();
}

$product = $productManager->readOne($_GET['id']);

// Ja produkts neeksistē, atpakaļ uz product.php
if (!$product) {
    header("Location: product.php");
    exit();
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
    <title>Main Page</title>
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
                        <a href="index.php" class="nav-link">Home</a>
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

                <!-- Atpakaļ poga -->
                <a href="product.php" class="btn btn-outline-secondary mb-4">
                    <i class="bi bi-arrow-left"></i> Back to Products
                </a>

                <div class="row align-items-center g-5">

                    <!-- Attēls -->
                    <div class="col-md-6 text-center">
                        <?php $imgSrc = "../" . str_replace('\\', '/', $product['image']); ?>
                        <img src="<?= htmlspecialchars($imgSrc) ?>"
                             alt="<?= htmlspecialchars($product['title']) ?>"
                             class="img-fluid rounded shadow"
                             style="max-height: 400px; width: auto;">
                    </div>

                    <!-- Info -->
                    <div class="col-md-6">
                        <h1 class="fw-normal"><?= htmlspecialchars($product['title']) ?></h1>
                        <hr>
                        <p class="lead"><?= htmlspecialchars($product['description']) ?></p>
                        <h3 class="fw-bold mt-4">€<?= htmlspecialchars($product['price']) ?></h3>

                        <?php if ($product['stock'] <= 0): ?>
                            <!-- Out of stock banner -->
                            <div class="alert alert-danger mt-3">
                                <i class="bi bi-x-circle"></i> This product is currently <strong>out of stock.</strong> Check back later!
                            </div>
                            <button class="btn btn-secondary btn-lg mt-2" disabled>
                                <i class="bi bi-cart-x"></i> Out of Stock
                            </button>
                        <?php else: ?>
                            <!-- In stock -->
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle"></i> In Stock — <strong><?= $product['stock'] ?></strong> available
                            </div>

                            <!-- Quantity selector -->
                            <div class="d-flex align-items-center gap-3 mt-4">
                                <label for="quantity" class="fw-semibold mb-0">Quantity:</label>
                                <input type="number" id="quantity"
                                    class="form-control"
                                    style="width: 90px;"
                                    value="1"
                                    min="1"
                                    max="<?= $product['stock'] ?>">
                            </div>

                            <div class="mt-4 d-flex gap-3">
                                <button id="addToCartBtn"
                                        onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['title'], ENT_QUOTES) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>, document.getElementById('quantity').value)"
                                        class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <a href="cart.php" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-cart"></i> View Cart
                                </a>
                            </div>
                        <?php endif; ?>
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

</body>
</html>