<?php
// $activePage is set in each page before including this file
// e.g. $activePage = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $basePath ?>css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title><?= $pageTitle ?? 'Sports Page 101' ?></title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-slightyDarkBlue">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">Sports Page 101</a>
            <button type="button" class="btn text-white" id="themeToggler">
                <i class="bi bi-moon-stars" id="dark-mode-icon"></i>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>cart.php" class="nav-link">
                            <i class="bi bi-cart"></i>
                            <span id="cart-count" class="badge border">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>index.php" class="nav-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>about.php" class="nav-link <?= ($activePage ?? '') === 'about' ? 'active' : '' ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>services.php" class="nav-link <?= ($activePage ?? '') === 'services' ? 'active' : '' ?>">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>product.php" class="nav-link <?= ($activePage ?? '') === 'products' ? 'active' : '' ?>">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $htmlPath ?>contact.php" class="nav-link <?= ($activePage ?? '') === 'contact' ? 'active' : '' ?>">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $htmlPath ?>logout.php" class="nav-link">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="<?= $htmlPath ?>register.php" class="nav-link <?= ($activePage ?? '') === 'register' ? 'active' : '' ?>">Register</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $htmlPath ?>login.php" class="nav-link <?= ($activePage ?? '') === 'login' ? 'active' : '' ?>">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>