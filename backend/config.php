<?php
// filepath: 
$servername = "cosc360.ok.ubc.ca";
$username = "vpanagsa";
$password = "vpanagsa";
$dbname = "vpanagsa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Debugging information
echo "<br>Server: $servername";
echo "<br>Username: $username";
echo "<br>Password: $password";
echo "<br>Database: $dbname";
?>