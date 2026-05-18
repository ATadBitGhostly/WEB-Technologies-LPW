<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../classes/User.php';

// Redirect if not logged in or is admin
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] == 1) {
    header('Location: login.php');
    exit;
}

$userManager = new User($conn);
$user_id = $_SESSION['user_id'];

$profileSuccess = '';
$profileError = '';
$passwordSuccess = '';
$passwordError = '';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($username)) {
        $profileError = "Username is required.";
    } elseif (empty($email)) {
        $profileError = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $profileError = "Invalid email format.";
    } else {
        try {
            $userManager->updateProfile($user_id, $username, $email);
            $_SESSION['username'] = $username;
            $profileSuccess = "Profile updated successfully!";
        } catch (Exception $e) {
            $profileError = $e->getMessage();
        }
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $passwordError = "All password fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordError = "New passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $passwordError = "New password must be at least 6 characters.";
    } else {
        try {
            $userManager->updatePassword($user_id, $currentPassword, $newPassword);
            $passwordSuccess = "Password changed successfully!";
        } catch (Exception $e) {
            $passwordError = $e->getMessage();
        }
    }
}

// Fetch current user data
$currentUser = $userManager->readOne($user_id);

// Fetch order history
$stmt = $conn->prepare("
    SELECT orders.id, orders.total, orders.status, orders.created_at 
    FROM orders 
    WHERE orders.user_id = ? 
    ORDER BY orders.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Fetch order items for each order
$orderItems = [];
foreach ($orders as $order) {
    $s = $conn->prepare("
        SELECT order_items.quantity, order_items.price, products.title
        FROM order_items
        LEFT JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?
    ");
    $s->execute([$order['id']]);
    $orderItems[$order['id']] = $s->fetchAll();
}


$activePage = 'dashboard';
$pageTitle = 'Dashboard - Sports Page 101';
require_once __DIR__ . '/../includes/header.php';
?>

    <style>
        .order-details-card {
            display: none;
            position: absolute;
            z-index: 1000;
            min-width: 280px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            padding: 1rem;
        }
        .order-details-wrapper {
            position: relative;
        }
        .order-details-wrapper:hover .order-details-card,
        .order-details-card:hover {
            display: block;
        }
    </style>

    <main>
        <section class="py-5">
            <div class="container">
                <h1 class="display-4 fw-normal">My Dashboard</h1>
                <p class="lead mt-2">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
            </div>
        </section>
        <hr class="container my-0">

        <section class="py-5">
            <div class="container">
                <div class="row g-5">

                    <!-- ── LEFT COLUMN — Profile + Password ── -->
                    <div class="col-lg-5">

                        <!-- Cart Link -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-1"><i class="bi bi-cart"></i> Your Cart</h5>
                                    <p class="text-muted mb-0">View and manage your cart items.</p>
                                </div>
                                <a href="cart.php" class="btn btn-primary">
                                    Go to Cart <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Edit Profile -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-person"></i> Edit Profile</h5>

                                <?php if ($profileError): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($profileError) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($profileSuccess): ?>
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle"></i> <?= htmlspecialchars($profileSuccess) ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-semibold">Username</label>
                                        <input type="text" name="username" id="username"
                                               class="form-control"
                                               value="<?= htmlspecialchars($currentUser['username']) ?>"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control"
                                               value="<?= htmlspecialchars($currentUser['email']) ?>"
                                               required>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" name="update_profile" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-lock"></i> Change Password</h5>

                                <?php if ($passwordError): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($passwordError) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($passwordSuccess): ?>
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle"></i> <?= htmlspecialchars($passwordSuccess) ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                        <input type="password" name="current_password" id="current_password"
                                               class="form-control"
                                               placeholder="Enter current password"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label fw-semibold">New Password</label>
                                        <input type="password" name="new_password" id="new_password"
                                               class="form-control"
                                               placeholder="Min. 6 characters"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label fw-semibold">Confirm New Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                               class="form-control"
                                               placeholder="Repeat new password"
                                               required>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" name="change_password" class="btn btn-primary">
                                            <i class="bi bi-lock"></i> Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- ── RIGHT COLUMN — Order History ── -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-bag"></i> Order History</h5>

                                <?php if (empty($orders)): ?>
                                    <div class="alert alert-warning mb-0">
                                        You haven't placed any orders yet.
                                        <a href="product.php" class="alert-link">Browse products</a>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td><?= $order['id'] ?></td>
                                                        <td>€<?= number_format($order['total'], 2) ?></td>
                                                        <td>
                                                            <?php if ($order['status'] === 'delivered'): ?>
                                                                <span class="badge bg-success">Delivered</span>
                                                            <?php elseif ($order['status'] === 'cancelled'): ?>
                                                                <span class="badge bg-danger">Cancelled</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $order['created_at'] ?></td>
                                                        <td>
                                                            <div class="order-details-wrapper">
                                                                <button class="btn btn-sm btn-info text-white">
                                                                    <i class="bi bi-eye"></i> More
                                                                </button>
                                                                <div class="order-details-card">
                                                                    <h6 class="fw-bold mb-2">Order #<?= $order['id'] ?></h6>
                                                                    <hr class="my-1">
                                                                    <?php if (!empty($orderItems[$order['id']])): ?>
                                                                        <?php foreach ($orderItems[$order['id']] as $item): ?>
                                                                            <div class="d-flex justify-content-between">
                                                                                <span><?= htmlspecialchars($item['title']) ?> x<?= $item['quantity'] ?></span>
                                                                                <span>€<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                        <hr class="my-1">
                                                                        <div class="d-flex justify-content-between fw-bold">
                                                                            <span>Total:</span>
                                                                            <span>€<?= number_format($order['total'], 2) ?></span>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <p class="text-muted mb-0">No items found.</p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
