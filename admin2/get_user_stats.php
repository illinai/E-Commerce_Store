<?php
session_start();
include '../backend/config.php';

// Simple error handling
try {
    // Get date range parameter
    $range = isset($_GET['range']) ? $_GET['range'] : '7days';
    
    // Prepare SQL based on range
    $sql = "SELECT DATE(created_at) as date, COUNT(*) as count FROM users WHERE 1=1 ";
    
    if ($range == '7days') {
        $sql .= "AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ";
    } elseif ($range == '30days') {
        $sql .= "AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
    }
    
    $sql .= "GROUP BY DATE(created_at) ORDER BY date";
    
    // Execute query
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    // Process results
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'date' => $row['date'],
            'count' => (int)$row['count']
        ];
    }
    
    // Return data
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>