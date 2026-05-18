<?php
session_start();

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'about';
$pageTitle = 'About - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

    <section class="py-5">
        <div class="container text-center">
            <div>
                <h1 class="display-4 fw-normal">About Us</h1>
                <p class="lead mt-3">This is the About page for Sports Page 101. Here you can find information about our company, mission, and values.</p>
            </div>
            <hr>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <h1 class="display-4 fw-normal">Meet the team behind Sports Page 101</h1>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-md-4 my-2">
                    <!-- These cards are also placeholders -->
                    <div class="card h-100 shadow-sm p-4 text-center">
                        <h2>Reinis Sausiņš</h2>
                        <img src="../images/ReinisPhoto.jpeg" class="about-picture mx-auto d-block">
                        <p>Founder and CEO of Sports Page 101. Passionate about sports journalism and bringing the latest news to fans.</p>
                    </div>
                </div>
                <div class="col-md-4 my-2">
                    <!-- These cards are also placeholders -->
                    <div class="card h-100 shadow-sm p-4 text-center">
                        <h2>Ričards Ābols</h2>
                        <img src="../images/RicardsPhoto.JPG" class="about-picture mx-auto d-block">
                        <p>Lead Developer. Responsible for building and maintaining the website's technical infrastructure.</p>
                    </div>
                </div>
                <div class="col-md-4 my-2">
                    <!-- These cards are also placeholders -->
                    <div class="card h-100 shadow-sm p-4 text-center">
                        <h2>Eduards Vijups</h2>
                        <img src="../images/EduardsPhoto.jpg" class="about-picture mx-auto d-block">
                        <p>Content Manager. Responsible for curating and managing the content on Sports Page 101.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="container my-0">
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <h1 class="display-4"><strong>Our goal</strong></h1>
            </div>
            <div class="card shadow-sm p-4 text-center">
                <p>Our goal at Sports Page 101 is to deliver only the best and latest news about all of sports. We break down games, strategies, and player performances to give you a deeper understanding of the sports you love.</p>
            </div>
        </div>
    </section>
    <hr class="container my-0">
    <section class="py-5">
        <div class="container">
            <h1 class="display-4 fw-normal">The timeline of Sports Page 101</h1>
            <div class="timeline">
                <ul class="list-group list-group-flush">
                    <!-- These cards are placeholders for the timeline -->
                    <li class="list-group-item bg-transparent">
                    <div class="card p-2">
                        <span class="timeline-date">September 2023</span>
                        <h3 class="text-primary-emphasis">The Draft Phase</h3>
                        <p>Marcus, Elena, and Jax meet at a sports bar in Brooklyn. Realizing they all want a news site that cuts through the noise, the blueprint for the page is born.</p>
                    </div>
                    </li>

                    <li class="list-group-item bg-transparent">
                    <div class="card p-2">
                        <span class="timeline-date">January 2024</span>
                        <h3 class="text-primary-emphasis">First Kick-Off</h3>
                        <p>The beta site goes live. We start with a focus on NBA trade deadlines and NFL playoff coverage, gaining our first 10,000 loyal readers.</p>
                    </div>
                    </li>

                    <li class="list-group-item bg-transparent">
                    <div class="card p-2">
                        <span class="timeline-date">June 2025</span>
                        <h3 class="text-primary-emphasis">Expansion Team</h3>
                        <p>We launch the "Culture & Sneakers" vertical led by Jax, bridging the gap between performance stats and street style.</p>
                    </div>
                    </li>

                    <li class="list-group-item bg-transparent">
                    <div class="card p-2">
                        <span class="timeline-date">Today</span>
                        <h3 class="text-primary-emphasis">The Big Leagues</h3>
                        <p>Now a leading voice in independent sports media, we continue to provide the best on everything from global soccer to local high school phenoms.</p>
                    </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
