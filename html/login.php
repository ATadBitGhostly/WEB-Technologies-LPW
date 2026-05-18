<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Dashboard needed
    exit;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (empty($username) || empty($password)) {
        $errors[] = "Username or Password is empty";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($username && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['isAdmin'] = $user['isAdmin'];

            setcookie("remember_user", $user['username'], time() + (86000 * 30), "/");
            setcookie("last_login", date("d/m/y H:i"), time() + (86000 * 30), "/");

            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = "Username or Password is invalid";
        }
    }
}


$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'home';
$pageTitle = 'Home - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>
    <main>
        <section class="py-5">
            <div class="container text-center">
                <h1 class="display-4 fw-normal">Welcome back!</h1>
                <p class="lead mt-3">Log in to your Sports Page 101 account.</p>
            </div>
        </section>
        <hr class="container my-0">

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card shadow-sm p-4">

                            <?php if (isset($_COOKIE['remember_user']) && isset($_COOKIE['last_visit'])): ?>
                                <div class="alert alert-info">
                                    Welcome back, <strong><?= htmlspecialchars($_COOKIE['remember_user']) ?></strong>!
                                    Last visit: <?= htmlspecialchars($_COOKIE['last_visit']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $e): ?>
                                            <li><?= htmlspecialchars($e) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="login.php">
                                <div class="mb-3">
                                    <?php if (isset($_GET['reset'])): ?>
                                        <div class="alert alert-success">Password reset successfully. You can now log in.</div>
                                    <?php endif; ?>
                                    <input type="text" name="username" class="form-control"
                                        placeholder="Username"
                                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log In</button>
                            </form>
                            <p class="mt-3 text-center mb-0">Don't have an account? <a href="register.php">Register</a></p>
                            <p class="mt-2 text-center mb-0"> <a href="forgot_password.php">Forgot your password?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

