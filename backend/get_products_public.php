<?php
header('Content-Type: application/json');
require 'config.php';
try {
    // Get ALL products (public view)
    $stmt = $conn->prepare("SELECT p.id, p.name, p.description, p.price, p.image_url, p.tags, u.shop_name
    FROM products p
    JOIN users u ON p.seller_id = u.id
    ORDER BY p.created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Handle the image blob data
        $imageData = null;
        if (!empty($row['image_url'])) {
            // Convert binary data to data URL
            $imageData = 'data:image/jpeg;base64,' . base64_encode($row['image_url']);
        }
        
        $products[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'price' => (float)$row['price'],
            'image' => $imageData ?? 'imgs/default.png',
            'tags' => $row['tags'],
            'shop_name' => htmlspecialchars($row['shop_name'] ?? 'Unknown Seller')
        ];
    }
    echo json_encode(['success' => true, 'products' => $products]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}