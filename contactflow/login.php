<?php
// login.php
session_start();
$login_error = '';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  
  // Retrieve user from the database.
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();
  
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
    exit();
  } else {
    $login_error = "Invalid username or password.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - ContactFlow Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .card { max-width: 400px; margin: 50px auto; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title text-center">Login</h4>
      <?php if ($login_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>
      <form method="post" action="login.php">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
      </form>
      <p class="mt-3 text-center">
        Don't have an account? <a href="register.php">Register here</a>.
      </p>
    </div>
  </div>
  <?php include 'footer.php'; ?>

