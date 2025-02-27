<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$dbname = 'blogsphere_db';
$port = 3307;  // Use your configured port (or change to 3306 if needed)

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session for user authentication
session_start();
?>
