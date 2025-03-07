<?php
// register.php
session_start();
require 'db.php';

$registration_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm_password = trim($_POST['confirm_password'] ?? '');
  
  if (empty($username) || empty($password) || empty($confirm_password)) {
    $registration_error = "Please fill all fields.";
  } elseif ($password !== $confirm_password) {
    $registration_error = "Passwords do not match.";
  } else {
    // Check if username already exists.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
      $registration_error = "Username already exists. Please choose another.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      if ($stmt->execute([$username, $hashed_password])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
      } else {
        $registration_error = "Registration failed. Please try again.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - ContactFlow Dashboard</title>
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
      <h4 class="card-title text-center">Register</h4>
      <?php if ($registration_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($registration_error) ?></div>
      <?php endif; ?>
      <form method="post" action="register.php">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
      </form>
      <p class="mt-3 text-center">
        Already have an account? <a href="login.php">Login here</a>.
      </p>
    </div>
  </div>

  <?php include 'footer.php'; ?>

