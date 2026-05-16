<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$cart_json = $_POST['cart_json'] ?? '';
$cart = json_decode($cart_json, true);

if (empty($cart) || !is_array($cart)) {
    $_SESSION['order_error'] = 'Your cart was empty. Please try again.';
    header('Location: checkout.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;
$valid_items = [];

// Re-price every item from DB and check stock
foreach ($cart as $item) {
    $id = intval($item['id'] ?? 0);
    $qty = intval($item['quantity'] ?? 0);

    if ($id <= 0 || $qty <= 0) continue;

    $stmt = $conn->prepare("SELECT id, title, price, stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) continue;

    // Check if enough stock
    if ($product['stock'] < $qty) {
        $_SESSION['order_error'] = 'Sorry! Only ' . $product['stock'] . ' of "' . $product['title'] . '" available in stock.';
        header('Location: checkout.php');
        exit;
    }

    // Check if out of stock
    if ($product['stock'] <= 0) {
        $_SESSION['order_error'] = '"' . $product['title'] . '" is out of stock.';
        header('Location: checkout.php');
        exit;
    }

    $total += $product['price'] * $qty;
    $valid_items[] = [
        'product_id' => $product['id'],
        'quantity'   => $qty,
        'price'      => $product['price'],
    ];
}

if (empty($valid_items)) {
    $_SESSION['order_error'] = 'No valid products found. Please try again.';
    header('Location: checkout.php');
    exit;
}

// Insert order and deduct stock
try {
    $conn->beginTransaction();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([$user_id, $total]);
    $order_id = $conn->lastInsertId();

    // Insert order items + deduct stock
    $insertStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stockStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($valid_items as $item) {
        $insertStmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        $stockStmt->execute([$item['quantity'], $item['product_id']]);
    }

    $conn->commit();

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['order_error'] = 'Something went wrong. Please try again.';
    header('Location: checkout.php');
    exit;
}

$_SESSION['last_order_id'] = $order_id;
$_SESSION['last_order_total'] = $total;

header('Location: order_confirm.php');
exit;
?>