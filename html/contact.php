<?php
session_start();

$activePage = 'contact';
$pageTitle = 'Contact - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
