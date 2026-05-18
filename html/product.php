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

$activePage = 'products';
$pageTitle = 'Products - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>
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


    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
