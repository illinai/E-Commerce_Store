<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

try {
    $sql = "SELECT id, name, description, price, image_url FROM products WHERE seller_id = ?";
    $stmt = $conn->prepare($sql);
    $seller_id = $_SESSION['user_id']; // Ensure the seller ID is set in the session
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>