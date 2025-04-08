<?php
header('Content-Type: application/json');
require 'config.php';

try {
    // Select all necessary product info + seller info
    $sql = "SELECT 
                p.id, 
                p.name, 
                p.description, 
                p.price, 
                p.quantity, 
                p.tags, 
                p.image_url,
                u.shop_name, 
                u.shop_description
            FROM products p
            JOIN users u ON p.seller_id = u.id";
    
    // Add the WHERE clause only if the ability column exists
    $checkColumn = $conn->query("SHOW COLUMNS FROM users LIKE 'ability'");
    if ($checkColumn && $checkColumn->num_rows > 0) {
        $sql .= " WHERE u.ability = 'enabled'";
    }

    $result = $conn->query($sql);
    $products = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convert image BLOB to base64
            $image = $row['image_url'];
            $base64Image = null;
            
            if ($image) {
                $base64Image = 'data:image/jpeg;base64,' . base64_encode($image);
            } else {
                $base64Image = 'imgs/default.png'; // fallback if no image
            }

            $products[] = [
                'id' => $row['id'],
                'name' => htmlspecialchars($row['name']),
                'description' => htmlspecialchars($row['description']),
                'price' => (float) $row['price'],
                'quantity' => (int) ($row['quantity'] ?? 0),
                'tags' => array_map('trim', explode(',', $row['tags'] ?? '')),
                'image_url' => $base64Image, 
                'image' => $base64Image,
                'shop_name' => htmlspecialchars($row['shop_name'] ?? ''),
                'shop_description' => htmlspecialchars($row['shop_description'] ?? '')
            ];
        }
    }

    echo json_encode(['success' => true, 'products' => $products]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>