<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $image_url = $data['image_url'];
    $quantity = $data['quantity'];
    $seller_id = $_SESSION['user_id']; // Assume seller is logged in
    $category_id = 1; // Default category ID (you can change this)

    $sql = "INSERT INTO products (seller_id, name, description, price, image_url, category_id, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdsii", $seller_id, $name, $description, $price, $image_url, $category_id, $quantity);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>