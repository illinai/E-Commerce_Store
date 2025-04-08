// Simplified get_user_stats.php
<?php
session_start();
include '../backend/config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simple query to get registration data
$sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
        FROM users 
        GROUP BY DATE(created_at) 
        ORDER BY date DESC 
        LIMIT 7";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'date' => $row['date'],
        'count' => (int)$row['count']
    ];
}

echo json_encode([
    'success' => true,
    'data' => $data
]);
?>