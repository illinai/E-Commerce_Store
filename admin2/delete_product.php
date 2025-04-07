<?php
session_start();
include '../backend/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Get the product ID
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if (empty($productId)) {
    echo json_encode(['error' => 'Product ID is required']);
    exit();
}

try {
    // First check if product exists in any orders
    $stmt = $conn->prepare("
        SELECT COUNT(*) as order_count 
        FROM order_items 
        WHERE product_id = ?
    ");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderCount = $result->fetch_assoc()['order_count'];
    
    if ($orderCount > 0) {
        echo json_encode([
            'error' => 'Cannot delete product - it exists in customer orders. Consider disabling it instead.'
        ]);
        exit();
    }
    
    // Delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Product has been deleted successfully']);
    } else {
        echo json_encode(['error' => 'Product not found or already deleted']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>