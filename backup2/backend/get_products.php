<?php
header('Content-Type: application/json');
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

try {
    $sellerId = $_SESSION['user_id'];
    
    // Get products for this seller
    $stmt = $conn->prepare("SELECT p.*, u.shop_name FROM products p 
                           JOIN users u ON p.seller_id = u.id 
                           WHERE p.seller_id = ? 
                           ORDER BY p.created_at DESC");
    $stmt->bind_param("i", $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'price' => (float)$row['price'],
            'created_at' => $row['created_at'],
            'shop_name' => htmlspecialchars($row['shop_name'] ?? 'Unknown Shop'),
            'quantity' => $row['quantity'],
            'tags' => $row['tags']
        ];
    }
    
    // Get orders for this seller
    $orderStmt = $conn->prepare("SELECT o.* FROM orders o 
                                WHERE o.seller_id = ? 
                                ORDER BY o.created_at DESC");
    $orderStmt->bind_param("i", $sellerId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    
    $orders = [];
    while ($row = $orderResult->fetch_assoc()) {
        $orders[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'total' => (float)$row['total'],
            'order_status' => $row['order_status'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode(['success' => true, 'products' => $products, 'orders' => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>