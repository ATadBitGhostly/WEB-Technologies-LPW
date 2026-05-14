<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
header('Location: login.php');
exit;
}


// delete function
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: dashboard.php?deleted=1");
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->query('SELECT * FROM services');
$services = $stmt->fetchAll();
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
    <title>Register - Sports Page 101</title>
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
                <h1 class="display-4 fw-normal">Dashboard</h1>
                <p class="lead mt-2">Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</p>

                <?php if (isset($_COOKIE['last_visit'])): ?>
                    <div class="alert alert-info">
                        Welcome back, <strong><?= htmlspecialchars($_COOKIE['remember_user'] ?? $username) ?></strong>!
                        Last visit: <?= htmlspecialchars($_COOKIE['last_visit']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Service deleted successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['added'])): ?>
                    <div class="alert alert-success">Service added successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Service updated successfully.</div>
                <?php endif; ?>
            </div>
        </section>
        <hr class="container my-0">

        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>All Services</h2>
                    <a href="./admin/add_service.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Service
                    </a>
                </div>

                <?php if (empty($services)): ?>
                    <div class="alert alert-warning">No services found. Add one above!</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= $service['id'] ?></td>
                                    <td><?= htmlspecialchars($service['title']) ?></td>
                                    <td><?= htmlspecialchars($service['description']) ?></td>
                                    <td>
                                        <?php if ($service['image']): ?>
                                            <img src="../<?= htmlspecialchars($service['image']) ?>"
                                                alt="<?= htmlspecialchars($service['title']) ?>"
                                                style="height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="./admin/edit_service.php?id=<?= $service['id'] ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="dashboard.php?delete_id=<?= $service['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this service?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
            <p class="mb-0">&copy; <p id="dynamicDate"></p> Sports Page 101. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
    <script src="../scripts/cart.js"></script>
    <script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
</body>
</html>
