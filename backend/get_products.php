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
$sql = "SELECT * FROM products WHERE seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Convert binary data to base64 for JSON
foreach ($products as &$product) {
    if (isset($product['image']) && $product['image']) {
        $product['image'] = base64_encode($product['image']);
    }
}

echo json_encode($products);
?>