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

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'services';
$pageTitle = 'Services - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
