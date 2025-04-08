<?php
header('Content-Type: application/json');
require 'config.php';

try {
    // Prepare the SQL query
    $sql = "SELECT id, name, description, price, quantity, tags, category, image_path FROM products";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['price'] = (float)$row['price'];
            $row['quantity'] = (int)$row['quantity'];
            $row['tags'] = array_map('trim', explode(',', $row['tags']));
            $row['image'] = $row['image_path'] ?: 'imgs/default.png';
            unset($row['image_path']);
            $products[] = $row;
        }
    }

    echo json_encode(['success' => true, 'products' => $products]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>
