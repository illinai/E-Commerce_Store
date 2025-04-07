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
    
    // Get product count
    $productStmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ?");
    $productStmt->bind_param("i", $sellerId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();
    $productCount = $productResult->fetch_row()[0];
    
    // Get order count
    $orderStmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE seller_id = ?");
    $orderStmt->bind_param("i", $sellerId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    $orderCount = $orderResult->fetch_row()[0];
    
    // Get total sales
    $salesStmt = $conn->prepare("SELECT SUM(total) FROM orders WHERE seller_id = ?");
    $salesStmt->bind_param("i", $sellerId);
    $salesStmt->execute();
    $salesResult = $salesStmt->get_result();
    $totalSales = $salesResult->fetch_row()[0] ?? 0;
    
    echo json_encode([
        'success' => true, 
        'product_count' => (int)$productCount, 
        'order_count' => (int)$orderCount, 
        'total_sales' => (float)$totalSales
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>