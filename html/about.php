<?php
session_start();
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
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link active" aria-current="page">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="services.php" class="nav-link">Services</a>
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
            </div>
        </div>
    </nav>

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
</body>
</html>