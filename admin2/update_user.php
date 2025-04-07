<?php
session_start();
include '../backend/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Get the user ID and action
$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (empty($userId) || empty($action)) {
    echo json_encode(['error' => 'User ID and action are required']);
    exit();
}

try {
    switch ($action) {
        case 'enable':
            $stmt = $conn->prepare("UPDATE users SET ability = 'enabled' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'User has been enabled']);
            break;
            
        case 'disable':
            $stmt = $conn->prepare("UPDATE users SET ability = 'disabled' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'User has been disabled']);
            break;
            
        case 'delete':
            // First check if user has any orders
            $stmt = $conn->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderCount = $result->fetch_assoc()['order_count'];
            
            if ($orderCount > 0) {
                echo json_encode(['error' => 'Cannot delete user with existing orders']);
                exit();
            }
            
            // Delete the user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'User has been deleted']);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>