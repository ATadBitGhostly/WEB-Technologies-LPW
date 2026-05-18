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

$activePage = 'reset_password';
$pageTitle = 'Reset Password - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

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

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
