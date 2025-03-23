<?php
session_start();
include 'config.php';

$seller_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

echo json_encode($profile);
?>