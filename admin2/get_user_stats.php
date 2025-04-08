<?php
session_start();
include '../backend/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

// Get the date range parameter
$range = isset($_GET['range']) ? $_GET['range'] : '7days';

try {
    // Prepare SQL based on date range
    $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM users WHERE 1=1 ";
            
    switch ($range) {
        case '7days':
            $sql .= "AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ";
            break;
        case '30days':
            $sql .= "AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
            break;
        case 'all':
            // No date restriction
            break;
        default:
            $sql .= "AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ";
    }
    
    $sql .= "GROUP BY DATE(created_at) ORDER BY date";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Error fetching user stats: " . $conn->error);
    }
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'date' => $row['date'],
            'count' => (int)$row['count']
        ];
    }
    
    // If we have no data for certain dates, fill in with zeros
    $filledData = fillMissingDates($data, $range);
    
    echo json_encode([
        'success' => true,
        'data' => $filledData
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// Function to fill in missing dates with zero counts
function fillMissingDates($data, $range) {
    if (empty($data)) {
        return [];
    }
    
    // Determine start and end dates
    $startDate = new DateTime();
    switch ($range) {
        case '7days':
            $startDate->modify('-6 days'); // 7 days including today
            break;
        case '30days':
            $startDate->modify('-29 days'); // 30 days including today
            break;
        case 'all':
            if (!empty($data)) {
                $startDate = new DateTime($data[0]['date']);
            }
            break;
    }
    
    $endDate = new DateTime();
    
    // If range is 'all' and we have data, use the first date as start
    if ($range === 'all' && !empty($data)) {
        // Find earliest and latest dates in the data
        $dates = array_column($data, 'date');
        $earliestDate = min($dates);
        $startDate = new DateTime($earliestDate);
    }
    
    // Create an associative array with all dates
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($startDate, $interval, $endDate->modify('+1 day')); // Include end date
    
    $filledData = [];
    $dataByDate = [];
    
    // Convert data array to associative array with date as key
    foreach ($data as $item) {
        $dataByDate[$item['date']] = $item['count'];
    }
    
    // Fill in all dates
    foreach ($dateRange as $date) {
        $dateString = $date->format('Y-m-d');
        $filledData[] = [
            'date' => $dateString,
            'count' => isset($dataByDate[$dateString]) ? $dataByDate[$dateString] : 0
        ];
    }
    
    return $filledData;
}
?>