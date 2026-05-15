<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Must be a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

require_once '../includes/db.php';

// Get and validate cart JSON
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

// Re-price every item from the DB (never trust client price)
foreach ($cart as $item) {
    $id = intval($item['id'] ?? 0);
    $qty = intval($item['quantity'] ?? 0);

    if ($id <= 0 || $qty <= 0) continue;

    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) continue; // skip if product doesn't exist

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

// Insert into orders table
try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([$user_id, $total]);
    $order_id = $conn->lastInsertId();

    // Insert each order item
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($valid_items as $item) {
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    $conn->commit();

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['order_error'] = 'Something went wrong. Please try again.';
    header('Location: checkout.php');
    exit;
}

// Pass order info to confirm page
$_SESSION['last_order_id'] = $order_id;
$_SESSION['last_order_total'] = $total;

header('Location: order_confirm.php');
exit;
?>
