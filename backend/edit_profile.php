<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// For testing purposes - simulate logged in user if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a user ID that exists in your database
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['first_name']) || !isset($_POST['last_name'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    
    // Check which column exists for profile image
    $checkSql = "SHOW COLUMNS FROM users LIKE 'profile_img'";
    $checkResult = $conn->query($checkSql);
    $imageColumnName = ($checkResult && $checkResult->num_rows > 0) ? 'profile_img' : 'image';
    
    // Check if profile image was uploaded
    $image_updated = false;
    $image = null;
    
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        // Validate image file
        if ($_FILES['profile_img']['size'] > 5000000) { // 5MB limit
            echo json_encode(['success' => false, 'message' => 'File is too large.']);
            exit;
        }
        
        if (!in_array($_FILES['profile_img']['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
            exit;
        }
        
        $image = file_get_contents($_FILES['profile_img']['tmp_name']);
        $image_updated = true;
    }
    
    try {
        if ($image_updated) {
            $sql = "UPDATE users SET first_name = ?, last_name = ?, $imageColumnName = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $first_name, $last_name, $image, $user_id);
        } else {
            $sql = "UPDATE users SET first_name = ?, last_name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $first_name, $last_name, $user_id);
        }
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>