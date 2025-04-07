<?php
header('Content-Type: application/json');
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['order_id']) || !isset($data['status'])) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Missing required fields']));
    }
    
    $orderId = $data['order_id'];
    $status = $data['status'];
    $sellerId = $_SESSION['user_id'];
    
    // Verify valid status
    $validStatuses = ['pending', 'shipped', 'returned'];
    if (!in_array($status, $validStatuses)) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Invalid status']));
    }
    
    // Update order status (only allow seller to update their own orders)
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("sii", $status, $orderId, $sellerId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Order not found or not authorized']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>