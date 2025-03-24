<?php
session_start();
include 'config.php';

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// For testing purposes - simulate logged in user if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a user ID that exists in your database
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['price'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $seller_id = $_SESSION['user_id'];
    
    // Check if image was uploaded
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Image upload error: ' . $_FILES['image']['error']]);
        exit;
    }
    
    // Validate image file
    if ($_FILES['image']['size'] > 5000000) { // 5MB limit
        echo json_encode(['success' => false, 'message' => 'File is too large.']);
        exit;
    }
    
    if (!in_array($_FILES['image']['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type: ' . $_FILES['image']['type']]);
        exit;
    }
    
    try {
        // Handle image upload
        $image = file_get_contents($_FILES['image']['tmp_name']); // Read image file as binary data
        
        $sql = "INSERT INTO products (seller_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Check if prepare succeeded
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
            exit;
        }
        
        $stmt->bind_param("issds", $seller_id, $name, $description, $price, $image);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'product_id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database execute error: ' . $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>