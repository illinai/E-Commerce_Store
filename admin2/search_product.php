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
    // Prepare search query - look for matches in name, description, or tags
    $stmt = $conn->prepare("
        SELECT id, name, description, quantity
        FROM products 
        WHERE name LIKE ? OR description LIKE ?
        ORDER BY name ASC
    ");
    
    $searchParam = "%{$searchTerm}%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    echo json_encode(['success' => true, 'products' => $products]);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>