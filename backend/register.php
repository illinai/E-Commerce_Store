<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $name = $firstName . ' ' . $lastName;

    // Insert user into database
    $sql = "INSERT INTO users (email, password, name) VALUES ('$email', '$password', '$name')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>