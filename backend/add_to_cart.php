<?php
session_start();
header('Content-Type: application/json');

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$productId = $data['product_id'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'No product ID received']);
    exit;
}

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Optionally prevent duplicates
if (!in_array($productId, $_SESSION['cart'])) {
    $_SESSION['cart'][] = $productId;
}

echo json_encode(['success' => true]);
?>