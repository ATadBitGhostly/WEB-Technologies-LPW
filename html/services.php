<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Service.php';

$db = new Database();
$conn = $db->connect();
$serviceManager = new Service($conn);

// 5.3 Logic: Handle Search Input
$searchTerm = $_GET['search'] ?? '';
$services = [];

if (!empty($searchTerm)) {
    $services = $serviceManager->search($searchTerm);
} else {
    $services = $serviceManager->readAll();
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
    <title>Services - Sports Page 101</title>
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
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="services.php" class="nav-link active" aria-current="page">Services</a>
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
                <h1 class="display-4 fw-normal">Our Services</h1>
                <p class="lead mt-3">Here you can find information about our services and offerings at Sports Page 101.</p>
            </div>
        </section>
        
        <hr class="container my-0">

        <section class="py-4">
            <div class="container">
                <form action="services.php" method="GET" class="d-flex gap-3">
                    <input type="text" name="search" id="search-input" 
                           class="form-control w-auto flex-grow-1" 
                           placeholder="Search services..." 
                           value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if(!empty($searchTerm)): ?>
                        <a href="services.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center text-center" id="services-grid">
                    
                    <?php if (empty($services)): ?>
                        <div class="col-12">
                            <p class="text-muted">No services found matching your search.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($services as $s): ?>
                            <div class="col-lg-3 col-md-6 my-2">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <?php 
                                            // Handle the image path correctly
                                            // DB saves "images\news.png", we are in "html/services.php"
                                            // We need to go up one level to root, then into images
                                            $imgSrc = "../" . str_replace('\\', '/', $s['image']);
                                        ?>
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" 
                                             alt="<?= htmlspecialchars($s['title']) ?> Icon" 
                                             class="service-images mb-3" 
                                             style="max-height: 100px; width: auto;">
                                        
                                        <h2 class="h4"><?= htmlspecialchars($s['title']) ?></h2>
                                        <p class="card-text"><?= htmlspecialchars($s['description']) ?></p>
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
            <p class="mb-0">&copy; <p id="dynamicDate"></p> Sports Page 101. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script_service.js"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
</body>
</html>