<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../main/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];
$total = floatval(preg_replace('/[^0-9.]/', '', $_POST['total']));

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Assume cart is sent from frontend as JSON string in POST
$cart = json_decode(file_get_contents("php://input"), true); // If using fetch()

// Simulate adding items (replace with actual loop from frontend if needed)
foreach ($_SESSION['cart'] ?? [] as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear session cart
unset($_SESSION['cart']);

header("Location: ../main/confirmation.html");
exit();
?>