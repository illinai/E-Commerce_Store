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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['order_id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $order_id = $data['order_id'];
    $status = $data['status'];
    
    // Check if orders table has a status column
    $checkSql = "SHOW COLUMNS FROM orders LIKE 'status'";
    $checkResult = $conn->query($checkSql);
    if (!$checkResult || $checkResult->num_rows == 0) {
        // Status column doesn't exist - need to add it
        $alterSql = "ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'Pending'";
        if (!$conn->query($alterSql)) {
            echo json_encode(['success' => false, 'message' => 'Could not add status column: ' . $conn->error]);
            exit;
        }
    }
    
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>