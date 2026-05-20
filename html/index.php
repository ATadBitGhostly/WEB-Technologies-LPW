<?php
session_start();

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'home';
$pageTitle = 'Home - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>
    
    <section class="py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-normal">Welcome to Sports Page 101</h1>
            <!-- This is a placeholder, someday This will change to introduce our page better -->
            <!-- Btw use word wrap (Alt+Z) to make one line long text to short multiple line text -->
            <p class="lead mt-3">Welcome to the Sports Page 101! The Best Sports News Platform in the World (100%). </p>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="alert alert-success mt-3">
                    Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Last visit: <strong><?= htmlspecialchars($_COOKIE['last_login']) ?></strong>.
                </div>
            <?php endif; ?>
        </div>
    </section>
    <hr class="container my-0">
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">    
                    <div class="card shadow-sm p-4 text-center">
                        <h2 class="mb-3">What is Sports Page 101?</h2>
                        <p>Sports Page 101 is a comprehensive platform dedicated to providing the latest news, insights, and analysis on various sports. Whether you're a fan of football, basketball, baseball, or any other sport, we've got you covered with in-depth articles, expert opinions, and up-to-date scores. Our mission is to keep you informed and engaged with the world of sports.</p>
                        <a href="about.php" class="btn btn-primary mt-3">To About Page</a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title">Latest News</h2>
                            <p class="card-text">Stay updated with the latest news in the sports world. From game results to player transfers, we bring you the most recent developments in your favorite sports.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title">Expert Analysis</h2>
                            <p class="card-text">Get insights and analysis from our team of sports experts. We break down games, strategies, and player performances to give you a deeper understanding of the sports you love.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title">Upcoming Events</h2>
                            <p class="card-text">Never miss out on upcoming sports events. We provide schedules, previews, and coverage of major tournaments and matches around the world.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>