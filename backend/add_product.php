<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'config.php';
session_start();

function errorResponse($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    errorResponse('Unauthorized', 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$required = ['name', 'description', 'price', 'quantity', 'tags'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        errorResponse("$field is required");
    }
}

$name = $_POST['name'];
$description = $_POST['description'];
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);
$tags = $_POST['tags'];
$seller_id = $_SESSION['user_id'];

// Read image as binary
$image_blob = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_blob = file_get_contents($_FILES['image']['tmp_name']);
}

try {
    $stmt = $conn->prepare("
        INSERT INTO products (name, description, price, seller_id, quantity, tags, image_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssdissb", $name, $description, $price, $seller_id, $quantity, $tags, $image_blob_ref);

    // Send the image blob via reference
    $image_blob_ref = null;
    if ($image_blob !== null) {
        $image_blob_ref = ''; // send an empty string first
        $stmt->send_long_data(6, $image_blob);
    }

    if (!$stmt->execute()) {
        errorResponse("Database error: " . $stmt->error, 500);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    errorResponse("Exception: " . $e->getMessage(), 500);
}
?>