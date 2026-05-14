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
    <title>Contact - Sports Page 101</title>
</head>
<body>

    <?php
    
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../classes/Message.php';

    $messageObj = new Message($conn);

    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $message = trim($_POST["message"]);

        $validationError = $messageObj->validate($name, $email, $message);

        if ($validationError) {
            $error = $validationError;
        } else {
            if ($messageObj->create($name, $email, $message)) {
                $success = "Message Sent!";
            } else {
                $error = "Failed to send message.";
            }
        }

    }
    
    ?>

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
                        <a href="services.php" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link active" aria-current="page">Contact</a>
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
            <div class="container text-center">
                <h1 class="display-4 fw-normal">Contact Us</h1>
            </div>
        </section>
        <hr class="container my-0">
        <section class="py-5">
            <div class="container text-center">
                <div class="row">
                    <div class="col-lg-6 col-md-6 my-1">
                        <div class="card shadow-sm p-4 text-center">
                            <!-- Contact Form -->
                            
                            <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                            </div>
                            <?php endif; ?>

                            <form id="contactForm" method="POST" action="contact.php" novalidate>
                                <h2 class="mb-4">Send us a message</h2>
                                
                                <div class="mb-3 w-100">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                                </div>

                                <div class="mb-3 w-100">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>

                                <div class="mb-3 w-100">
                                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Write your message here"></textarea>
                                </div>
                                
                                <button class="btn btn-primary" type="submit">Send message</button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="col-lg-6 col-md-6 my-1">
                        <div class="card shadow-sm p-4 text-center">
                            <h2>Get in Touch</h2>
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <strong>Email:</strong>
                                    <a href="mailto:sportspage101@example.com" class="ms-2">
                                        sportspage101@example.com
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <strong>Phone:</strong>
                                    <a href="tel:+37121123434" class="ms-2">
                                        +371 21123434
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <strong>Address:</strong>
                                    <span class="ms-2">
                                        123 Sports Street, Athletic City, AC 2211
                                    </span>
                                </li>

                                <li class="list-group-item">
                                    <strong>Hours:</strong>
                                    <span class="ms-2">
                                        Monday – Friday, 10:00 AM – 5:00 PM
                                    </span>
                                </li>

                            </ul>
                        </div>
                    </div>
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

    <script src="../scripts/contact.js"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
</body>
</html>