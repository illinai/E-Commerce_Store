<?php
    // search.php

    include 'config.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the search term from the request
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

    $conn->close();
?>
