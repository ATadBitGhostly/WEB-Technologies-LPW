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


$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'register';
$pageTitle = 'Register - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>


