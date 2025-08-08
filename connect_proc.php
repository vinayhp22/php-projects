<?php
$host = "localhost";
$user = "root";
$password = "root";
$database = "demo_db";

// Connect to MySQL
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

// Close connection
mysqli_close($conn);
?>
