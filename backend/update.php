<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

// Session configuration
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/smann06/E-Commerce_Store/backup2/',
    'domain' => '.ok.ubc.ca', 
    'secure' => true, 
    'httponly' => true,
    'samesite' => 'Lax' 
]);
session_start();

header('Content-Type: application/json');

include 'config.php';

try {
    // Only handle POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed', 405);
    }

    // Verify required data exists
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if (!isset($input['action']) || $input['action'] !== 'update') {
        throw new Exception('Invalid action', 400);
    }

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Not logged in', 401);
    }

    // Validate required fields
    $required = ['first_name', 'last_name', 'email'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("$field is required", 400);
        }
    }

    // Prepare and execute update query
    $sql = "UPDATE users SET 
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                shop_name = ?, 
                shop_description = ?";
    
    // Check if a new profile image is uploaded
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file details
        $fileTmpPath = $_FILES['profile_img']['tmp_name'];
        $fileName = $_FILES['profile_img']['name'];
        $fileSize = $_FILES['profile_img']['size'];
        $fileType = $_FILES['profile_img']['type'];

        // Read the file content
        $profile_img = file_get_contents($fileTmpPath); // Store the image as BLOB in the DB

        // Add profile_img field to the query
        $sql .= ", profile_img = ?";
    } else {
        // If no image uploaded, set profile_img to NULL
        $profile_img = null;
    }

    // Finish the query with the WHERE clause
    $sql .= " WHERE id = ?";

    // Clean data
    $firstName = trim($input['first_name']);
    $lastName = trim($input['last_name']);
    $email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
    $shopName = !empty($input['shop_name']) ? trim($input['shop_name']) : null;
    $shopDesc = !empty($input['shop_description']) ? trim($input['shop_description']) : null;

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (include profile_img if it's uploaded)
    if ($profile_img) {
        $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $shopName, $shopDesc, $profile_img, $_SESSION['user_id']);
        $stmt->send_long_data(5, $profile_img); // Send the BLOB data to column 5 (profile_img)
    } else {
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $shopName, $shopDesc, $_SESSION['user_id']);
    }

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception('Database update failed: ' . $stmt->error, 500);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'error' => $e->getMessage(),
        'debug' => [
            'session_id' => session_id(),
            'user_id_set' => isset($_SESSION['user_id']),
            'input_data' => $input ?? null
        ]
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    ob_end_flush();
}
?>