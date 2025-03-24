<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Log POST data
    error_log("Received POST data: " . print_r($_POST, true));
    error_log("Received FILES data: " . print_r($_FILES, true));

    // Check if required fields are set
    if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval(trim($_POST['price']));
    $seller_id = intval($_SESSION['user_id']); // Ensure seller_id is an integer

    // Check if image was uploaded
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Image upload error: ' . $_FILES['image']['error']]);
        exit;
    }

    // Validate image file
    if ($_FILES['image']['size'] > 5000000) { // 5MB limit
        echo json_encode(['success' => false, 'message' => 'File is too large.']);
        exit;
    }

    if (!in_array($_FILES['image']['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type: ' . $_FILES['image']['type']]);
        exit;
    }

    try {
        // Handle image upload
        $image_name = basename($_FILES['image']['name']);
        $image_path = __DIR__ . '/../uploads/' . $image_name; // Save images in /uploads directory

        // Ensure the /uploads directory exists
        if (!is_dir(__DIR__ . '/../uploads')) {
            mkdir(__DIR__ . '/../uploads', 0755, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
            exit;
        }

        // Insert product into the database using prepared statement
        $sql = "INSERT INTO products (name, description, price, image_url, seller_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
            exit;
        }

        // Bind parameters
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_path, $seller_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'product_id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database execute error: ' . $stmt->error]);
        }

        $stmt->close();  // Close the prepared statement
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}