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
        SELECT id, name, description, price, quantity, tags
        FROM products 
        WHERE name LIKE ? OR description LIKE ? OR tags LIKE ?
        ORDER BY name ASC
    ");
    
    $searchParam = "%{$searchTerm}%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Convert any binary data to base64 if needed
        /*if (!empty($row['image_blob'])) {
            $row['image_blob_base64'] = base64_encode($row['image_blob']);
        }*/
        $products[] = $row;
    }
    
    echo json_encode(['success' => true, 'products' => $products]);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>