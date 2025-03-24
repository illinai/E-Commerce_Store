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

        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: Email already exists.<br>";
            header("refresh:3;url=../main/index.html"); // Redirect after 3 seconds to the login page
            exit();
        } else {
            // Insert user into the database using prepared statement
            $sql = "INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $role = 'normal';  // Default role for a new user
            $stmt->bind_param("sssss", $email, $hashed_password, $first_name, $last_name, $role);
            
            if ($stmt->execute()) {
                // Display a confirmation message
                echo "Account created successfully!<br>";
                echo "You will be redirected to the login page shortly.<br>";

                // Add a short delay before redirecting
                header("refresh:3;url=../main/index.html"); // Redirect after 3 seconds to the login page
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