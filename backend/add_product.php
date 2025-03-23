<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $seller_id = $_SESSION['user_id']; // Assume seller is logged in

    // Validate image file
    if ($_FILES['image']['size'] > 5000000) { // 5MB limit
        echo json_encode(['success' => false, 'message' => 'File is too large.']);
        exit;
    }
    if (!in_array($_FILES['image']['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
        exit;
    }

    // Handle image upload
    $image = file_get_contents($_FILES['image']['tmp_name']); // Read image file as binary data

    $sql = "INSERT INTO products (seller_id, name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdsi", $seller_id, $name, $description, $price, $image, $category_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>