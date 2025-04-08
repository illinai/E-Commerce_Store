<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

session_set_cookie_params([
    'lifetime' => 86400, // 1 day
    'path' => '/smann06/E-Commerce_Store/backup2/', // Exact deployment path
    'domain' => '.ok.ubc.ca', // Top-level domain
    'secure' => true, // REQUIRED for HTTPS
    'httponly' => true,
    'samesite' => 'Lax' // Balance security and functionality
]);

session_start();

header('Content-Type: application/json');

include 'config.php';

try {
    if (isset($_GET['action']) && $_GET['action'] === 'debug') {
        die(json_encode([
            'session' => [
                'id' => session_id(),
                'data' => $_SESSION,
                'status' => session_status()
            ],
            'cookies' => $_COOKIE,
            'server' => [
                'session_path' => session_save_path(),
                'https' => isset($_SERVER['HTTPS'])
            ]
        ]));
    }

    // GET PROFILE ENDPOINT
    if (isset($_GET['action']) && $_GET['action'] === 'get') {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('Not logged in', 401);
        }

        // Update SQL to include profile_img
        $stmt = $conn->prepare("SELECT first_name, last_name, email, shop_name, shop_description, profile_img 
                               FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception('Profile not found', 404);
        }

        // Fetch the result as an associative array
        $user = $result->fetch_assoc();

        // Convert the BLOB (profile_img) to base64 encoding
        if ($user['profile_img']) {
            $user['profile_img'] = base64_encode($user['profile_img']);
        }

        echo json_encode($user);
    }

    else {
        throw new Exception('Invalid request', 400);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'error' => $e->getMessage(),
        'debug' => [
            'session_id' => session_id(),
            'user_id_set' => isset($_SESSION['user_id'])
        ]
    ]);
} finally {
    // Cleanup
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    ob_end_flush();
}
?>