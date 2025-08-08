<?php
session_start();

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Store in session
    $_SESSION['username'] = $username;

    // Store in cookie (valid for 1 day)
    setcookie("username", $username, time() + 86400);

    // Redirect to welcome page
    header("Location: welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Login Page</title></head>
<body>
  <h2>Login</h2>
  <form method="post" action="">
    Username: <input type="text" name="username" required>
    <input type="submit" value="Login">
  </form>
</body>
</html>
