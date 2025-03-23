<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];

$image_path = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $targetDir = "../file_uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $image_path = $targetFilePath;
    }
}

$sql = "UPDATE users SET first_name=?, last_name=?, email=?";

if ($image_path) {
    $sql .= ", image=?";
}

$sql .= " WHERE id=?";

$stmt = $conn->prepare($sql);

if ($image_path) {
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $image_path, $user_id);
} else {
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => 'Profile updated successfully']);
} else {
    echo json_encode(['error' => 'Update failed']);
}
?>