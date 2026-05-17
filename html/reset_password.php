<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Must have come through forgot_password.php
if (!isset($_SESSION['reset_user_id'])) {
    header('Location: forgot_password.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($password) || empty($confirm)) {
        $errors[] = 'Please fill in both fields.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $_SESSION['reset_user_id']]);

        // Clean up reset session key
        unset($_SESSION['reset_user_id']);

        header('Location: login.php?reset=1');
        exit;
    }
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
    <title>Reset Password - Sports Page 101</title>
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
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="product.php" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="py-5">
            <div class="container text-center">
                <h1 class="display-4 fw-normal">Reset Password</h1>
                <p class="lead mt-3">Choose a new password for your account.</p>
            </div>
        </section>
        <hr class="container my-0">

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card shadow-sm p-4">

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $e): ?>
                                            <li><?= htmlspecialchars($e) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="reset_password.php">
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control"
                                           placeholder="New password (min. 6 characters)" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="confirm_password" class="form-control"
                                           placeholder="Confirm new password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-lock"></i> Save New Password
                                </button>
                            </form>

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
            <p class="mb-0">&copy; <span id="dynamicDate"></span> Sports Page 101. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/script-dm-head-foot.js"></script>
    <script src="../scripts/cart.js"></script>
    <script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
</body>
</html>