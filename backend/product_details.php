<?php
header('Content-Type: application/json');
require 'config.php';

try {
    $stmt = $conn->prepare("
        SELECT p.*, u.shop_name, u.first_name, u.last_name 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        ORDER BY p.created_at DESC
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'price' => (float)$row['price'],
            'image' => $row['image_url'] ?? 'imgs/default.png',
            'quantity' => (int)$row['quantity'],
            'tags' => $row['tags'],
            'seller_id' => $row['seller_id'],
            'seller_name' => htmlspecialchars($row['shop_name'] ? $row['shop_name'] : $row['first_name'] . ' ' . $row['last_name'])
        ];
    }
    
    echo json_encode(['success' => true, 'products' => $products]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>