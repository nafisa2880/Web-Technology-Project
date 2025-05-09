<?php
// Database configuration
$host = 'localhost'; // Database host (usually localhost)
$username = 'root'; // Database username (default for XAMPP)
$password = ''; // Database password (default for XAMPP is empty)
$dbname = 'healthinfo'; // Database name

// Create a new connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
