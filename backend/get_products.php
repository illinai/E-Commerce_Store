<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Check connection after include
if (!$conn || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error: ' . ($conn ? $conn->connect_error : 'Could not establish connection')]);
    exit;
}

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

// Convert binary image data to base64 for JSON
foreach ($products as &$product) {
    if (isset($product['image']) && $product['image']) {
        $product['image'] = base64_encode($product['image']);
    }
}

echo json_encode($products);
?>