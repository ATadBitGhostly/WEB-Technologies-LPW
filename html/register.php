<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Username = trim($_POST['username']);
    $Email = trim($_POST['email']);
    $Password = trim($_POST['password']);
    $Confirmpassword = trim($_POST['confirm_password']);

    if (empty($Username) || strlen($Username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    if(empty($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email must be a valid email address";
    }
    if (empty($Password) || strlen($Password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    if ($Password !== $Confirmpassword) {
        $errors[] = "Passwords do not match";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$Username, $Email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists";
        } else{
            $Hashed = password_hash($Password, PASSWORD_DEFAULT);
            $Insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $Insert->execute([$Username, $Email, $Hashed]);
            setcookie("remember_user", $Username, time() + (86000 * 30), "/");

            $success = true;
        }
    }
}
//Create register.php to allow users to register.
//• Validate form data using PHP.
//• Passwords must be hashed (e.g., password_hash).
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
            <div class="container text-center">
                <h1 class="display-4 fw-normal">Create an Account</h1>
                <p class="lead mt-3">Join Sports Page 101 to access exclusive features and content.</p>
            </div>
        </section>
        <hr class="container my-0">

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card shadow-sm p-4">

                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    Registration successful! <a href="login.php">Log in here</a>.
                                </div>
                            <?php else: ?>

                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach ($errors as $e): ?>
                                                <li><?= htmlspecialchars($e) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="register.php">
                                    <div class="mb-3">
                                        <input type="text" name="username" class="form-control"
                                               placeholder="Username"
                                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control"
                                               placeholder="Email address"
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Password (min. 6 characters)" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="confirm_password" class="form-control"
                                               placeholder="Confirm password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Register</button>
                                </form>
                                <p class="mt-3 text-center mb-0">Already have an account? <a href="login.php">Log in</a></p>

                            <?php endif; ?>

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
    <script src="../scripts/script-dm-head-foot.js"></script>
    <script src="../scripts/cart.js"></script>
    <script>document.addEventListener('DOMContentLoaded', updateCartCount);</script>
</body>
</html>

