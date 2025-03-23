<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $profile_image = $data['profile_image'];
    $seller_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET first_name = ?, last_name = ?, profile_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $first_name, $last_name, $profile_image, $seller_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>