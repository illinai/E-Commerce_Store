<?php
$servername = "cosc360.ok.ubc.ca";
$username = "vpanagsa";
$password = "vpanagsa";
$dbname = "vpanagsa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Confirm connection
echo "Connected successfully!";
?>