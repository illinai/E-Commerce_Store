<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// For testing purposes - simulate logged in user if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a user ID that exists in your database
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$seller_id = $_SESSION['user_id'];

// Check which column name exists in the users table
$checkSql = "SHOW COLUMNS FROM users LIKE 'profile_img'";
$checkResult = $conn->query($checkSql);
if ($checkResult && $checkResult->num_rows > 0) {
    $sql = "SELECT first_name, last_name, profile_img FROM users WHERE id = ?";
    $useProfileImg = true;
} else {
    $sql = "SELECT first_name, last_name, image as profile_img FROM users WHERE id = ?";
    $useProfileImg = false;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Convert binary data to base64 for JSON
if ($profile && isset($profile['profile_img']) && $profile['profile_img']) {
    $profile['profile_img'] = base64_encode($profile['profile_img']);
}

echo json_encode($profile);
?>