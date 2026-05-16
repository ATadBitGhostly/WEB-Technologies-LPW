<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Product.php';

$db = new Database();
$conn = $db->connect();
$productManager = new Product($conn);

$searchTerm = $_GET['search'] ?? '';
$products = [];

if (!empty($searchTerm)) {
    $products = $productManager->search($searchTerm);
} else {
    $products = $productManager->readAll();
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
                        <a href="index.php" class="nav-link" aria-current="page">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="services.php" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="product.php" class="nav-link active">Products</a>
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
                <h1 class="display-4 fw-normal">Our Products</h1>
                <p class="lead mt-3">Browse our selection of sports products at Sports Page 101.</p>
            </div>
        </section>

        <hr class="container my-0">

        <section class="py-4">
            <div class="container">
                <form action="product.php" method="GET" class="d-flex gap-3">
                    <input type="text" name="search" id="search-input"
                           class="form-control w-auto flex-grow-1"
                           placeholder="Search products..."
                           value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if(!empty($searchTerm)): ?>
                        <a href="product.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center text-center" id="products-grid">

                    <?php if (empty($products)): ?>
                        <div class="col-12">
                            <p class="text-muted">No products found matching your search.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <div class="col-lg-3 col-md-6 my-2">
                                <div class="card h-100 shadow-sm">
                                    
                                    <?php if ($p['stock'] <= 0): ?>
                                        <div class="bg-danger text-white text-center py-1" style="font-size: 0.85rem;">
                                            <i class="bi"></i> Out of Stock
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-success text-white text-center py-1" style="font-size: 0.85rem;">
                                            <i class="bi"></i> In Stock (<?= $p['stock'] ?>)
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-body d-flex flex-column">
                                        <?php
                                            $imgSrc = "../" . str_replace('\\', '/', $p['image']);
                                        ?>
                                        <img src="<?= htmlspecialchars($imgSrc) ?>"
                                            alt="<?= htmlspecialchars($p['title']) ?>"
                                            class="mb-3 mx-auto"
                                            style="max-height: 150px; width: auto;">
                                        <h2 class="h4"><?= htmlspecialchars($p['title']) ?></h2>
                                        <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
                                        <p class="fw-bold">€<?= htmlspecialchars($p['price']) ?></p>

                                        <div class="mt-auto d-flex gap-2 justify-content-center">
                                            <a href="product_details.php?id=<?= $p['id'] ?>"
                                            class="btn btn-outline-primary btn-sm">
                                                View Details
                                            </a>

                                            <?php if ($p['stock'] <= 0): ?>
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="bi bi-cart-x"></i> Out of Stock
                                                </button>
                                            <?php else: ?>
                                                <button onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>', <?= $p['price'] ?>, <?= $p['stock'] ?>)"
                                                        class="btn btn-primary btn-sm">
                                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

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