<?php
session_start();
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Product.php';

// Tikai admin var piekļūt
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '0');

    // PHP validācija
    if (empty($title)) {
        $error = "Title is required.";
    } elseif (empty($description)) {
        $error = "Description is required.";
    } elseif (empty($price)) {
        $error = "Price is required.";
    } elseif (empty($stock)) {
        $error = "Stock is required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number.";
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error = "Stock must be a non-negative integer.";
    } else {
        try {
            $db = new Database();
            $productManager = new Product($db->connect());
            $productManager->create($title, $description, $image, $price, $stock);
            $success = "Product added successfully!";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Add Product - Sports Page 101</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-slightyDarkBlue">
        <div class="container-fluid">
            <a href="../index.php" class="navbar-brand">Sports Page 101</a>
            <button type="button" class="btn text-white" id="themeToggler">
                <i class="bi bi-moon-stars" id="dark-mode-icon"></i>
            </button>
        </div>
    </nav>

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <a href="../dashboard.php" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <h1 class="fw-normal mb-0">Add New Product</h1>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <form method="POST" id="addProductForm">
                                    <div class="mb-3">
                                        <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title"
                                               class="form-control"
                                               placeholder="Product title"
                                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description"
                                                  class="form-control"
                                                  placeholder="Product description"
                                                  rows="4"
                                                  required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                        <input type="number" name="price" id="price"
                                               class="form-control"
                                               placeholder="Product price"
                                               step="0.01"
                                               min="0"
                                               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock" class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" id="stock"
                                            class="form-control"
                                            placeholder="Available stock"
                                            min="0"
                                            value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="form-label fw-semibold">Image Path</label>
                                        <input type="text" name="image" id="image"
                                               class="form-control"
                                               placeholder="e.g. images/product.png"
                                               value="<?= htmlspecialchars($_POST['image'] ?? '') ?>">
                                        <div class="form-text">Leave empty if no image.</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Add Product
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

