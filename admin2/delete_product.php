<?php
// search.php

include 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $deleteQuery = "DELETE FROM products WHERE id = $productId";

    if ($conn->query($deleteQuery) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
    }
    exit; 
}

// Handle search
if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    // Search for products
    $productQuery = "SELECT * FROM products WHERE name LIKE '%$searchTerm%'";
    $productResult = $conn->query($productQuery);

    $products = [];
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
    }

    // Search for users
    $userQuery = "SELECT * FROM users WHERE username LIKE '%$searchTerm%'";
    $userResult = $conn->query($userQuery);

    $users = [];
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }

    // Combine results
    $results = [
        'products' => $products,
        'users' => $users
    ];

    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($results);
}

$conn->close();
?>