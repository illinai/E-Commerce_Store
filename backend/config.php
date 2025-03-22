<?php
// filepath: 
$servername = "cosc360.ok.ubc.ca";
$username = "vpanagsa";
$password = "vpanagsa";
$dbname = "vpanagsa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

// Debugging information
echo "<br>Server: $servername";
echo "<br>Username: $username";
echo "<br>Password: $password";
echo "<br>Database: $dbname";
?>