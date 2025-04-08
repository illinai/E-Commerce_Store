<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary form fields are set and not empty
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {
        
        // Sanitize and validate input data
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.<br>";
            exit();
        }

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle the profile image upload
        $profile_img = null;
        if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
            // Get file details
            $fileTmpPath = $_FILES['profile_img']['tmp_name'];
            // Read the file content
            $profile_img = file_get_contents($fileTmpPath); // Store the image as BLOB in the DB
        }

        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: Email already exists.<br>";
            header("refresh:3;url=../main/index.html"); // Redirect to the login page
            exit();
        } else {
            // Insert user into the database using prepared statement
            $sql = "INSERT INTO users (email, password, first_name, last_name, role, profile_img) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $role = 'normal';  // Default role for a new user
            
            // Bind parameters (profile_img is a BLOB, so it will be bound as a string)
            $stmt->bind_param("sssssb", $email, $hashed_password, $first_name, $last_name, $role, $null);

            // Bind the BLOB for profile_img
            if ($profile_img) {
                $stmt->send_long_data(5, $profile_img); // Send the BLOB data to column 5 (profile_img)
            } else {
                $null = null;  // If no image is uploaded, set profile_img as NULL
            }

            // Execute the query and check for success or failure
            if ($stmt->execute()) {
                // Display a confirmation message
                echo "Account created successfully!<br>";
                echo "You will be redirected to the login page shortly.<br>";

                header("refresh:3;url=../main/index.html"); // Redirect to the login page
                exit();
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
        }
        
        $stmt->close();  // Close the prepared statement
    } else {
        echo "Please fill out all required fields.<br>";
    }
}
?>