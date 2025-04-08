<?php
header('Content-Type: application/json');
require 'config.php';
session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['order_id']) || !isset($data['status'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Missing required fields']));
}

$order_id = $data['order_id'];
$status = $data['status'];
$seller_id = $_SESSION['user_id'];

// Make sure the status is one of the allowed values
if (!in_array($status, ['pending', 'shipped', 'completed', 'returned'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Invalid status value']));
}

try {
    // Make sure the order belongs to this seller
    $checkStmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND seller_id = ?");
    $checkStmt->bind_param("ii", $order_id, $seller_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(403);
        die(json_encode(['success' => false, 'error' => 'You do not have permission to update this order']));
    }

    // Update the order status
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Database error: ' . $stmt->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>