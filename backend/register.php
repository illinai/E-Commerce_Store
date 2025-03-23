<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['first_name']) && 
        isset($_POST['last_name']) && 
        isset($_POST['email']) && 
        isset($_POST['password']) &&
        isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
    ) {
        // Sanitize inputs
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.<br>";
            exit();
        }

        // Handle image upload
        $uploadDir = "../uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = basename($_FILES["profile_image"]["name"]);
        $targetFile = $uploadDir . time() . "_" . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow only images
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            echo "Error uploading the profile image.";
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: Email already exists.<br>";
            header("refresh:3;url=../main/index.html");
            exit();
        } else {
            // Insert user data with profile image path
            $sql = "INSERT INTO users (email, password, first_name, last_name, role, profile_image) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $role = 'normal';
            $stmt->bind_param("ssssss", $email, $hashed_password, $first_name, $last_name, $role, $targetFile);

            if ($stmt->execute()) {
                echo "Account created successfully!<br>";
                echo "You will be redirected to the login page shortly.<br>";
                header("refresh:3;url=../main/index.html");
                exit();
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
        }

        $stmt->close();
    } else {
        echo "Please fill out all required fields and upload an image.<br>";
    }
}
?>