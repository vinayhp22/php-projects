<?php
session_start();
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
include '../includes/header.php';
?>
<h2>Login</h2>
<?php if(isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required class="form-control" placeholder="Enter your username">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required class="form-control" placeholder="Enter your password">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
<?php include '../includes/footer.php'; ?>
