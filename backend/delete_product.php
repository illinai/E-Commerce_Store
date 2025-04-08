<?php
header('Content-Type: application/json');
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Invalid product ID']));
}

try {
    $productId = $_GET['id'];
    
    // First check if the product is in any orders
    $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM order_items WHERE product_id = ?");
    $checkStmt->bind_param("i", $productId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        // Product is in use in orders
        echo json_encode([
            'success' => false, 
            'error' => 'Cannot delete this product because it is included in one or more orders.'
        ]);
        exit;
    }
    
    // If product is not in any orders, delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $_SESSION['user_id']);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Product not found or unauthorized']);
        }
    } else {
        throw new Exception('Database error: ' . $stmt->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>