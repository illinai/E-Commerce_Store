<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $seller_id = $_SESSION['user_id'];

    // Handle profile image upload
    $profile_image = file_get_contents($_FILES['profile_image']['tmp_name']); // Read image file as binary data

    $sql = "UPDATE users SET first_name = ?, last_name = ?, profile_img = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $first_name, $last_name, $profile_image, $seller_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
}
?>