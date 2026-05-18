<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email)) {
        $errors[] = 'Please fill in both fields.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND email = ?");
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['reset_user_id'] = $user['id'];
            header('Location: reset_password.php');
            exit;
        } else {
            $errors[] = 'No account found with that username and email.';
        }
    }
}

$basePath = '../';   // ← for css, js, includes
$htmlPath = '';     // ← for links in this file to point to the right place
$activePage = 'forgot_password';
$pageTitle = 'Forgot Password - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

    <main>
        <section class="py-5">
            <div class="container text-center">
                <h1 class="display-4 fw-normal">Forgot Password</h1>
                <p class="lead mt-3">Enter your username and email to reset your password.</p>
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

                            <form method="POST" action="forgot_password.php">
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
                                <button type="submit" class="btn btn-primary w-100">Continue</button>
                            </form>
                            <p class="mt-3 text-center mb-0">
                                Remembered it? <a href="login.php">Back to Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
