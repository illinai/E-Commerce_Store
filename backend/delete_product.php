<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// For testing purposes - simulate logged in user if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a user ID that exists in your database
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID is required']);
        exit;
    }

    $product_id = $data['product_id'];
    $seller_id = $_SESSION['user_id'];

    // Delete the product
    $sql = "DELETE FROM products WHERE id = ? AND seller_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("ii", $product_id, $seller_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database execute error: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>