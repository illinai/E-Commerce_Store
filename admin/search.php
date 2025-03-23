<?php
    include 'config.php'; // Your database connection file

    $search = isset($_GET['query']) ? trim($_GET['query']) : '';

    if ($search === '') {
        echo json_encode(['users' => [], 'products' => []]);
        exit;
    }

    $searchTerm = "%$search%";

    // Search for users
    $userQuery = "SELECT first_name, last_name, email FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    $users = [];
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }

    // Search for products
    $productQuery = "SELECT product_name, price FROM products WHERE product_name LIKE ?";
    $productStmt = $conn->prepare($productQuery);
    $productStmt->bind_param("s", $searchTerm);
    $productStmt->execute();
    $productResult = $productStmt->get_result();

    $products = [];
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
    }

    // Return JSON response
    echo json_encode(['users' => $users, 'products' => $products]);

    // Close connections
    $userStmt->close();
    $productStmt->close();
    $conn->close();
?>
