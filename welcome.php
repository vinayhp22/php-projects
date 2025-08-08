<?php
session_start();

// If session not set, redirect to login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$cookieUsername = $_COOKIE['username'] ?? "No cookie set";
?>

<!DOCTYPE html>
<html>
<head><title>Welcome</title></head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
  <p><strong>From Cookie:</strong> <?php echo htmlspecialchars($cookieUsername); ?></p>

  <p><a href="logout.php">Logout</a></p>
</body>
</html>
