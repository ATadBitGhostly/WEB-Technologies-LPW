<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Delete service
if (isset($_GET['delete_service_id'])) {
    $id = $_GET['delete_service_id'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?deleted=1");
    exit();
}

// Delete product
if (isset($_GET['delete_product_id'])) {
    $id = $_GET['delete_product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?deleted=1");
    exit();
}

// Update order status
if (isset($_GET['order_status'])) {
    $order_id = intval($_GET['order_id']);
    $status = $_GET['order_status'];
    $allowed = ['pending', 'delivered', 'cancelled'];

    if (in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
    }
    header("Location: admin_dashboard.php");
    exit();
}

// Delete user
if (isset($_GET['delete_user_id'])) {
    $id = $_GET['delete_user_id'];
    // Prevent deleting yourself
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: admin_dashboard.php?deleted=1");
    exit();
}

// Toggle admin
if (isset($_GET['toggle_admin_id'])) {
    $id = $_GET['toggle_admin_id'];
    // Prevent removing your own admin
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET isAdmin = IF(isAdmin = 1, 0, 1) WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: admin_dashboard.php");
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->query('SELECT * FROM services');
$services = $stmt->fetchAll();

$stmt = $conn->query('SELECT * FROM products');
$products = $stmt->fetchAll();

$stmt = $conn->query('SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC');
$orders = $stmt->fetchAll();

$stmt = $conn->query('SELECT id, username, email, isAdmin FROM users');
$users = $stmt->fetchAll();

// Fetch order items for each order (for the hover card)
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

$basePath = '../../'; // for header.php to correctly link CSS and other resources
$htmlPath = '../';  // for links in this file to point to the right place
$activePage = 'dashboard';
$pageTitle = 'Admin Dashboard - Sports Page 101';
require_once __DIR__ . '/../../includes/header.php';
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
                <h1 class="display-4 fw-normal">Admin Dashboard</h1>
                <p class="lead mt-2">Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</p>

                <?php if (isset($_COOKIE['last_visit'])): ?>
                    <div class="alert alert-info">
                        Welcome back, <strong><?= htmlspecialchars($_COOKIE['remember_user'] ?? $username) ?></strong>!
                        Last visit: <?= htmlspecialchars($_COOKIE['last_visit']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Deleted successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['added'])): ?>
                    <div class="alert alert-success">Added successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Updated successfully.</div>
                <?php endif; ?>
            </div>
        </section>
        <hr class="container my-0">

        <!-- ── SERVICES ── -->
        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>All Services</h2>
                    <a href="add_service.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Service
                    </a>
                </div>
                <?php if (empty($services)): ?>
                    <div class="alert alert-warning">No services found. Add one above!</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td><?= $service['id'] ?></td>
                                        <td><?= htmlspecialchars($service['title']) ?></td>
                                        <td><?= htmlspecialchars($service['description']) ?></td>
                                        <td>
                                            <?php if ($service['image']): ?>
                                                <img src="../../<?= htmlspecialchars($service['image']) ?>"
                                                     alt="<?= htmlspecialchars($service['title']) ?>"
                                                     style="height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="admin_dashboard.php?delete_service_id=<?= $service['id'] ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Delete this service?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <hr class="container my-0">

        <!-- ── PRODUCTS ── -->
        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>All Products</h2>
                    <a href="add_product.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Product
                    </a>
                </div>
                <?php if (empty($products)): ?>
                    <div class="alert alert-warning">No products found. Add one above!</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['id'] ?></td>
                                        <td><?= htmlspecialchars($product['title']) ?></td>
                                        <td><?= htmlspecialchars($product['description']) ?></td>
                                        <td>€<?= number_format($product['price'], 2) ?></td>
                                        <td>
                                            <?php if ($product['stock'] > 0): ?>
                                                <span class="badge bg-success">In Stock (<?= $product['stock'] ?>)</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($product['image']): ?>
                                                <img src="../../<?= htmlspecialchars($product['image']) ?>"
                                                     alt="<?= htmlspecialchars($product['title']) ?>"
                                                     style="height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="admin_dashboard.php?delete_product_id=<?= $product['id'] ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Delete this product?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <hr class="container my-0">

        <!-- ── ORDERS ── -->
        <section class="py-5">
            <div class="container">
                <h2 class="mb-4">All Orders</h2>
                <?php if (empty($orders)): ?>
                    <div class="alert alert-warning">No orders yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['username'] ?? 'Deleted user') ?></td>
                                        <td>€<?= number_format($order['total'], 2) ?></td>
                                        <td><?= $order['created_at'] ?></td>
                                        <td>
                                            <?php if ($order['status'] === 'delivered'): ?>
                                                <span class="badge bg-success">Delivered</span>
                                            <?php elseif ($order['status'] === 'cancelled'): ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 align-items-center">

                                                <!-- More hover card -->
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

                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <a href="admin_dashboard.php?order_status=delivered&order_id=<?= $order['id'] ?>"
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('Mark order #<?= $order['id'] ?> as delivered?')">
                                                        <i class="bi bi-check-circle"></i> Delivered
                                                    </a>
                                                    <a href="admin_dashboard.php?order_status=cancelled&order_id=<?= $order['id'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Cancel order #<?= $order['id'] ?>?')">
                                                        <i class="bi bi-x-circle"></i> Cancel
                                                    </a>
                                                <?php elseif ($order['status'] === 'cancelled'): ?>
                                                    <a href="admin_dashboard.php?order_status=pending&order_id=<?= $order['id'] ?>"
                                                    class="btn btn-sm btn-warning"
                                                    onclick="return confirm('Restore order #<?= $order['id'] ?> to pending?')">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>


        <!-- ── USERS ── -->
        <section class="py-5">
            <div class="container">
                <h2 class="mb-4">All Users</h2>
                <?php if (empty($users)): ?>
                    <div class="alert alert-warning">No users found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <?php if ($user['isAdmin']): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">User</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <a href="admin_dashboard.php?toggle_admin_id=<?= $user['id'] ?>"
                                                   class="btn btn-sm btn-warning me-1"
                                                   onclick="return confirm('Toggle admin for <?= htmlspecialchars($user['username']) ?>?')">
                                                    <i class="bi bi-shield"></i>
                                                    <?= $user['isAdmin'] ? 'Remove Admin' : 'Make Admin' ?>
                                                </a>
                                                <a href="admin_dashboard.php?delete_user_id=<?= $user['id'] ?>"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Delete user <?= htmlspecialchars($user['username']) ?>?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">You</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
