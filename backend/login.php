<?php
session_start();
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            
            // if admin
            if($user['role'] == 'admin'){
                header("Location: ../admin2/adminDash.php"); // Redirect to admin dashboard
                exit();
            //if account disabled    
            } elseif($user["ability"] == 'disabled'){
                header("Location: ../main/disabled.html"); // Redirect to disabled dashboard
                exit();
            } else {
                header("Location: ../main/homePage.php"); // Redirect to dashboard
                exit();
            }
        } else {
            // Incorrect password
            header("Location: ../main/index.html?error=Invalid email or password");
            exit();
        }
    } else {
        // User not found
        header("Location: ../main/index.html?error=User does not exist. Please register.");
        exit();
    }
}
