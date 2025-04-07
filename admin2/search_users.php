<?php
session_start();
include '../backend/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

// Get the search term
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

if (empty($searchTerm)) {
    echo json_encode(['error' => 'Search term is required']);
    exit();
}

try {
    // Prepare search query - look for matches in first_name, last_name, or email
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, email, ability 
        FROM users 
        WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ?) 
        AND role != 'admin' 
        ORDER BY first_name ASC
    ");
    
    $searchParam = "%{$searchTerm}%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>