<?php
header('Content-Type: application/json');
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
http_response_code(401);
die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);

$required = ['name', 'description', 'price', 'quantity', 'tags'];
foreach ($required as $field) {
if (empty($data[$field])) {
http_response_code(400);
die(json_encode(['success' => false, 'error' => "$field is required"]));
}
}

try {
$stmt = $conn->prepare("INSERT INTO products (name, description, price, seller_id, quantity, tags) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiis",
$data['name'],
$data['description'],
$data['price'],
$_SESSION['user_id'],
$data['quantity'],
$data['tags']
);

if ($stmt->execute()) {
echo json_encode(['success' => true]);
} else {
throw new Exception('Database error: ' . $stmt->error);
}
} catch (Exception $e) {
http_response_code(500);
echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>