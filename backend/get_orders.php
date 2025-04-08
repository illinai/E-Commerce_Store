<?php
header('Content-Type: application/json');
require 'config.php';
session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];

try {
    $isSeller = isset($_GET['seller']) && $_GET['seller'] === 'true';
    
    if ($isSeller) {
        // Get orders for this seller
        $stmt = $conn->prepare("
            SELECT o.*, u.first_name, u.last_name 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.seller_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
    } else {
        // Get orders for this buyer
        $stmt = $conn->prepare("
            SELECT o.*, u.first_name, u.last_name, u.shop_name
            FROM orders o
            JOIN users u ON o.seller_id = u.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // Get order items
        $itemsStmt = $conn->prepare("
            SELECT oi.*, p.name as product_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $itemsStmt->bind_param("i", $row['id']);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();
        
        $items = [];
        while ($item = $itemsResult->fetch_assoc()) {
            $items[] = [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => (float)$item['price']
            ];
        }
        
        $orders[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'seller_id' => $row['seller_id'],
            'total' => (float)$row['total'],
            'order_status' => $row['order_status'],
            'created_at' => $row['created_at'],
            'customer_name' => $isSeller ? $row['first_name'] . ' ' . $row['last_name'] : null,
            'seller_name' => !$isSeller ? ($row['shop_name'] ? $row['shop_name'] : $row['first_name'] . ' ' . $row['last_name']) : null,
            'items' => $items
        ];
    }
    
    echo json_encode(['success' => true, 'orders' => $orders]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>