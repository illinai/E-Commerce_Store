<?php
session_start();
include 'config.php';

$seller_id = $_SESSION['user_id'];
$sql = "SELECT o.id, p.name, oi.quantity, o.status 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN products p ON oi.product_id = p.id 
        WHERE p.seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($orders);
?>