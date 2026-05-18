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


$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'product_details';
$pageTitle = 'Product Details - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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


    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
