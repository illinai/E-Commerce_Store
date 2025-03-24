<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// For testing purposes - simulate logged in user if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a user ID that exists in your database
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$seller_id = $_SESSION['user_id'];

// Check if the orders table has a status column
$checkSql = "SHOW COLUMNS FROM orders LIKE 'status'";
$checkResult = $conn->query($checkSql);
$hasStatusColumn = ($checkResult && $checkResult->num_rows > 0);

if ($hasStatusColumn) {
    $sql = "SELECT o.id, p.name, oi.quantity, o.status
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE p.seller_id = ?";
} else {
    $sql = "SELECT o.id, p.name, oi.quantity, 'Pending' as status
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE p.seller_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($orders);
?>